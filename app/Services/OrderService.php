<?php
namespace App\Services;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrderService
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function createOrder(array $data): Order
    {
        $cart = $this->cartService->getItems();

        if (empty($cart)) {
            abort(400, 'Giỏ hàng rỗng');
        }

        // Nếu chưa đăng nhập, tự tạo user nếu email chưa có
        if (auth()->guest()) {
            $user = User::where('email', $data['customer_email'])->first();
            if (!$user) {
                $user = User::create([
                    'name'     => $data['customer_name'],
                    'email'    => $data['customer_email'],
                    'password' => Hash::make(Str::random(10)),
                    'phone'    => $data['customer_phone'] ?? null,
                ]);

                // Gửi email đặt mật khẩu (nếu muốn)
            }
            $data['user_id'] = $user->id;
        } else {
            $data['user_id'] = auth()->id();
        }

        $data['total_price'] = $this->cartService->getTotalPrice();
        $order = Order::create($data);

        // Lưu từng sản phẩm
        foreach ($cart as $productId => $item) {
            OrderDetail::create([
                'order_id'      => $order->id,
                'product_id'    => $productId,
                'product_name'  => $item['name'],
                'product_price' => $item['price'],
                'quantity'      => $item['quantity'],
                'subtotal'      => $item['price'] * $item['quantity'],
            ]);
        }

        $this->cartService->clear();

        return $order;
    }
}
