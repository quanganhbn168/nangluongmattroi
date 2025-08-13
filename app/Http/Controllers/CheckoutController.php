<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order; // Nhúng Order model

class CheckoutController extends Controller
{
    protected $orderService;

    // "Tiêm" OrderService vào controller để sử dụng
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Hiển thị trang thanh toán.
     */
    public function index()
    {
        $cartItems = collect([]);
        if (Auth::guard('web')->check()) {
            $cartItems = Auth::guard('web')->user()->cartItems()->with('product')->get();
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.page')->with('error', 'Giỏ hàng của bạn đang trống!');
            }
        }

        $bankConfig = [
            'bankId'      => '970415',
            'accountNo'   => '105867163975',
            'accountName' => 'TRAN QUANG ANH',
                'bankName'    => 'VietinBank'
            ];

            return view('checkout.index', [
                'cartItems'  => $cartItems,
                'bankConfig' => $bankConfig,
            ]);
        }

    /**
     * Xử lý việc đặt hàng.
     */
    public function placeOrder(Request $request)
    {
        // Validate dữ liệu khách hàng gửi lên
        $customerData = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:15',
            'customer_address' => 'required|string|max:255',
            'payment_method'   => 'required|in:cod,bank_transfer',
            'note'             => 'nullable|string',
            'cart_data'        => 'nullable|json', // Dữ liệu giỏ hàng của khách
        ]);

        try {
            // Lấy dữ liệu giỏ hàng tùy theo loại người dùng
            $user = Auth::guard('web')->user();
            $cartItems = $user ? $user->cartItems()->with('product')->get() : collect([]);
            $guestCart = !$user && $request->has('cart_data') ? json_decode($request->input('cart_data'), true) : [];

            // Gọi OrderService để tạo đơn hàng
            $order = $this->orderService->createFromCheckout($customerData, $cartItems, $guestCart);
            
            // Chuyển hướng đến trang thành công
            return redirect()->route('checkout.success', ['order' => $order->id]);

        } catch (\Exception $e) {
            // Xử lý nếu có lỗi (ví dụ: giỏ hàng trống)
            return back()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Hiển thị trang đặt hàng thành công.
     */
    public function success(Order $order)
    {
        return view('checkout.success', ['order' => $order]);
    }

}