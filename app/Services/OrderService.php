<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
class OrderService
{
    /**
     * ADMIN: tạo đơn hàng thủ công từ form admin.
     * $data tối thiểu cần: user_id (nullable), customer_name, customer_phone, customer_address,
     * payment_method, note (nullable), items: [ ['product_id'=>..., 'quantity'=>..., 'price'=>optional] ... ]
     */
    public function createForAdmin(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            // Nếu chọn user có sẵn thì dùng, không thì tạo/hoặc để null và ghi thông tin customer_* vào đơn
            $userId = $data['user_id'] ?? null;
            $user = null;

            if ($userId) {
                $user = User::find($userId);
            }

            $order = Order::create([
                'user_id'          => $user?->id,
                'technician_id'     => $data['technician_id'] ?? null,
                'status'           => $data['status'] ?? 'pending',
                'note'             => $data['note'] ?? null,
                'payment_method'   => $data['payment_method'] ?? 'cod',
                'customer_name'    => $data['customer_name'],
                'customer_phone'   => $data['customer_phone'],
                'customer_address' => $data['customer_address'],
                'total_price'      => 0, // sẽ cập nhật sau
            ]);

            $items = $this->normalizeItemsFromAdmin(($data['items'] ?? []), null);
            if (empty($items)) {
                throw new \Exception('Đơn hàng phải có ít nhất 1 sản phẩm.');
            }

            $total = $this->syncItems($order, $items);
            $order->update(['total_price' => $total]);

            return $order;
        });
    }

    /**
     * ADMIN: cập nhật đơn hàng thủ công từ form admin.
     * $data cấu trúc tương tự createForAdmin.
     */
    public function updateForAdmin(Order $order, array $data): Order
    {
        return DB::transaction(function () use ($order, $data) {
            $userId = $data['user_id'] ?? null;
            $user = null;

            if ($userId) {
                $user = User::find($userId);
            }

            $order->update([
                'user_id'          => $user?->id,
                'technician_id'    => $data['technician_id'] ?? $order->technician_id,
                'status'           => $data['status'] ?? $order->status,
                'note'             => $data['note'] ?? $order->note,
                'payment_method'   => $data['payment_method'] ?? $order->payment_method,
                'customer_name'    => $data['customer_name'] ?? $order->customer_name,
                'customer_phone'   => $data['customer_phone'] ?? $order->customer_phone,
                'customer_address' => $data['customer_address'] ?? $order->customer_address,
            ]);

            // Đồng bộ lại items
            $items = $this->normalizeItemsFromAdmin(($data['items'] ?? []), $order);
            if (empty($items)) {
                // Cho phép đơn trống hay không? Thường KHÔNG → ném lỗi
                throw new \Exception('Đơn hàng phải có ít nhất 1 sản phẩm.');
            }

            $total = $this->syncItems($order, $items);
            $order->update(['total_price' => $total]);

            return $order;
        });
    }

    /**
     * FRONT: tạo đơn từ trang checkout (đã có sẵn).
     */
    public function createFromCheckout(array $customerData, Collection $cartItems, array $guestCart): Order
    {
        return DB::transaction(function () use ($customerData, $cartItems, $guestCart) {
            $user = Auth::guard('web')->user();

            if (!$user) {
                // Nếu bảng users bắt buộc password NOT NULL → gán chuỗi ngẫu nhiên / hoặc dùng bcrypt('')
                $user = User::firstOrCreate(
                    ['phone' => $customerData['customer_phone']],
                    [
                        'name'     => $customerData['customer_name'],
                        'address'  => $customerData['customer_address'] ?? null,
                        'password' => bcrypt(str()->random(16)),
                    ]
                );
            }

            $order = Order::create([
                'user_id'          => $user->id,
                'status'           => 'pending',
                'note'             => $customerData['note'] ?? null,
                'payment_method'   => $customerData['payment_method'],
                'customer_name'    => $customerData['customer_name'],
                'customer_phone'   => $customerData['customer_phone'],
                'customer_address' => $customerData['customer_address'],
                'total_price'      => 0,
            ]);

            $itemsToProcess = [];

            if ($user && !$cartItems->isEmpty()) {
                foreach ($cartItems as $item) {
                    $itemsToProcess[] = [
                        'product_id'   => $item->product_id,
                        'product_name' => $item->product->name,
                        // Bạn có thể ưu tiên price_discount nếu có
                        'price'        => $item->product->price_discount ?: $item->product->price,
                        'quantity'     => $item->quantity,
                    ];
                }
                $user->cartItems()->delete();
            } elseif (!empty($guestCart)) {
                $productIds = array_column($guestCart, 'id');
                $productsFromDB = Product::findMany($productIds)->keyBy('id');

                foreach ($guestCart as $gi) {
                    if (isset($productsFromDB[$gi['id']])) {
                        $p = $productsFromDB[$gi['id']];
                        $itemsToProcess[] = [
                            'product_id'   => $p->id,
                            'product_name' => $p->name,
                            'price'        => $p->price_discount ?: $p->price,
                            'quantity'     => $gi['quantity'],
                        ];
                    }
                }
            }

            if (empty($itemsToProcess)) {
                throw new \Exception('Giỏ hàng của bạn đang trống.');
            }

            $total = $this->syncItems($order, $itemsToProcess);
            $order->update(['total_price' => $total]);

            return $order;
        });
    }

    /**
     * Đồng bộ items: xóa toàn bộ cũ, tạo lại theo $items, trả về tổng tiền.
     * $items: mảng phần tử gồm product_id, product_name, price, quantity
     */
    private function syncItems(Order $order, array $items): float
    {
        $order->orderItems()->delete();

        $total = 0;
        foreach ($items as $it) {
            $price    = (float) $it['price'];
            $qty      = (int)   $it['quantity'];
            $subtotal = $price * $qty;
            $total   += $subtotal;

            $order->orderItems()->create([
                'product_id'           => $it['product_id'],
                'product_name'         => $it['product_name'],
                'product_price'        => $price,
                'quantity'             => $qty,
                'subtotal'             => $subtotal,
                'warranty_months'      => $it['warranty_months'] ?? 0,
                'warranty_expires_at'  => $it['warranty_expires_at'] ?? null,
            ]);
        }

        return $total;
    }

    /**
     * Chuẩn hóa dữ liệu items từ form admin:
     * - Nếu thiếu price → lấy từ DB (ưu tiên price_discount nếu có).
     * - Luôn gán product_name để cố định tên ở thời điểm đặt hàng.
     */
    private function normalizeItemsFromAdmin(array $items, ?Order $order = null): array
    {
        $result = [];

        // Lấy tất cả product_id xuất hiện
        $ids = collect($items)->pluck('product_id')->filter()->unique()->values();
        $products = Product::whereIn('id', $ids)->get()->keyBy('id');
        $baseDate = $order ? $this->baseWarrantyDate($order) : Carbon::now();
        foreach ($items as $row) {
            if (empty($row['product_id']) || empty($row['quantity'])) {
                continue;
            }

            $p = $products[$row['product_id']] ?? null;
            if (!$p) {
                continue;
            }

            $price = isset($row['price']) && $row['price'] !== ''
                ? (float) $row['price']
                : (float) ($p->price_discount ?: $p->price);

            $qty = (int) $row['quantity'];
            if ($qty <= 0) {
                continue;
            }
            $wMonths = isset($row['warranty_months']) && $row['warranty_months'] !== ''
            ? max(0, (int) $row['warranty_months'])
            : 0;

            $wExpires = $wMonths > 0
            ? $baseDate->copy()->addMonthsNoOverflow($wMonths)->toDateString()
            : null;

            $result[] = [
                'product_id'           => $p->id,
                'product_name'         => $p->name,
                'price'                => $price,
                'quantity'             => $qty,
                'warranty_months'      => $wMonths,
                'warranty_expires_at'  => $wExpires,
            ];
        }

        return $result;
    }

    private function baseWarrantyDate(Order $order): Carbon
    {
    // Nếu bạn có cột delivered_at, ưu tiên nó, không thì dùng created_at
        $base = $order->delivered_at ?? $order->created_at;
        return Carbon::parse($base);
    }
}
