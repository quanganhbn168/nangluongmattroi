<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\CartItem; // Giả sử bạn đã tạo model này

class CartController extends Controller
{
    // Lấy tất cả item trong giỏ hàng
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        return response()->json($cartItems);
    }

    // Thêm sản phẩm vào giỏ
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $cartItem = CartItem::where('user_id', $user->id)
                            ->where('product_id', $request->product_id)
                            ->first();

        if ($cartItem) {
            // Nếu đã có, cập nhật số lượng
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Nếu chưa có, tạo mới
            $cartItem = CartItem::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }
        
        return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ!', 'cart' => $this->getCartData()]);
    }

    // Cập nhật số lượng
    public function update(Request $request, $cartItemId)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);
        
        $cartItem = CartItem::where('id', $cartItemId)->where('user_id', Auth::id())->firstOrFail();

        if ($request->quantity == 0) {
            $cartItem->delete();
            $message = 'Sản phẩm đã được xóa khỏi giỏ!';
        } else {
            $cartItem->update(['quantity' => $request->quantity]);
            $message = 'Giỏ hàng đã được cập nhật!';
        }
        
        return response()->json(['message' => $message, 'cart' => $this->getCartData()]);
    }

    // Xóa sản phẩm
    public function remove($cartItemId)
    {
        CartItem::where('id', $cartItemId)->where('user_id', Auth::id())->firstOrFail()->delete();
        return response()->json(['message' => 'Sản phẩm đã được xóa khỏi giỏ!', 'cart' => $this->getCartData()]);
    }

    // Lấy dữ liệu giỏ hàng để trả về cho front-end
    private function getCartData()
    {
        // Viết hàm để lấy và tính toán tổng tiền, số lượng...
        // Tạm thời trả về số lượng item
        return Auth::user()->cartItems()->count();
    }

    // Thêm vào trong class CartController
    public function showCartPage()
    {
    // Lấy các mục trong giỏ hàng CỦA NGƯỜI DÙNG ĐÃ ĐĂNG NHẬP
    // Với khách vãng lai, giỏ hàng sẽ được render bằng JavaScript
        $cartItems = [];
        if (Auth::check()) {
        // Nhớ thêm quan hệ cartItems() trong Model User nhé
            $cartItems = Auth::user()->cartItems()->with('product')->get();
        }

        return view('cart.index', ['cartItems' => $cartItems]);
    }
}