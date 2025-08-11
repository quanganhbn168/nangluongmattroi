<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem; // Giả sử bạn dùng model này cho giỏ hàng DB

class CheckoutController extends Controller
{
    /**
     * Hiển thị form thanh toán.
     */
    public function index()
    {
        // Lấy giỏ hàng từ database nếu đã đăng nhập
        $cartItems = Auth::check() ? Auth::user()->cartItems()->with('product')->get() : collect([]);

        // Nếu đã đăng nhập mà giỏ hàng trống, không cho checkout
        if (Auth::check() && $cartItems->isEmpty()) {
            return redirect()->route('cart.page')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        return view('checkout.index', ['cartItems' => $cartItems]);
    }

    /**
     * Xử lý đặt hàng.
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:15',
            'customer_address' => 'required|string|max:255',
            'payment_method' => 'required|in:cod,bank_transfer',
            'note' => 'nullable|string',
        ]);

        // Sử dụng transaction để đảm bảo toàn vẹn dữ liệu
        return DB::transaction(function () use ($request) {
            $user = Auth::user();
            $cartData = [];

            // Xử lý giỏ hàng và tổng tiền
            if ($user) { // Người dùng đã đăng nhập
                $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
                if ($cartItems->isEmpty()) {
                    throw new \Exception('Giỏ hàng trống.');
                }
                $totalPrice = $cartItems->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                });
                $cartData = $cartItems;
            } else { // Khách vãng lai
                $cart = json_decode($request->input('cart_data'), true);
                if (empty($cart)) {
                    throw new \Exception('Giỏ hàng trống.');
                }
                // TODO: Cần có logic lấy thông tin sản phẩm từ DB dựa trên product_id trong $cart
                // Tạm thời tính tổng tiền từ dữ liệu gửi lên (Cần cải thiện bảo mật sau)
                $totalPrice = array_reduce($cart, function ($carry, $item) {
                    return $carry + $item['price'] * $item['quantity'];
                }, 0);
                $cartData = $cart;

                // Tạo một user "placeholder" cho khách vãng lai
                $user = User::firstOrCreate(
                    ['phone' => $request->customer_phone],
                    [
                        'name' => $request->customer_name,
                        'address' => $request->customer_address,
                        'password' => null, // Không có mật khẩu, không thể đăng nhập
                    ]
                );
            }

            // 1. Tạo đơn hàng (Order)
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending', // Trạng thái chờ xử lý
                'note' => $request->note,
                'total_price' => $totalPrice,
                'payment_method' => $request->payment_method, // Thêm cột này vào migration `orders`
            ]);

            // 2. Tạo các mục chi tiết đơn hàng (Order Items)
            foreach ($cartData as $item) {
                $product = $user ? $item->product : (object)$item;
                $quantity = $user ? $item->quantity : $item['quantity'];
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id ?? $product->productId,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => ($product->price * $quantity),
                ]);
            }
            
            // 3. Xóa giỏ hàng sau khi đã đặt hàng
            if (Auth::check()) {
                CartItem::where('user_id', $user->id)->delete();
            }

            // 4. Chuyển hướng đến trang thành công
            return redirect()->route('checkout.success', ['order' => $order->id]);
        });
    }

    /**
     * Hiển thị trang đặt hàng thành công.
     */
    public function success(Order $order)
    {
        // Dùng Route Model Binding để tự động tìm đơn hàng
        return view('checkout.success', ['order' => $order]);
    }
}