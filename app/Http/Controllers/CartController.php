<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cartItems = $this->cartService->getItems();
        $total     = $this->cartService->getTotalPrice();

        return view('frontend.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $this->cartService->add($request->product_id, $request->quantity ?? 1);

        return response()->json([
            'message' => 'Thêm vào giỏ hàng thành công',
            'cart_total' => $this->cartService->getTotalQuantity(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        $this->cartService->update($request->product_id, $request->quantity);

        return response()->json([
            'message' => 'Cập nhật giỏ hàng thành công',
            'cart_total' => $this->cartService->getTotalQuantity(),
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $this->cartService->remove($request->product_id);

        return response()->json([
            'message' => 'Đã xoá khỏi giỏ hàng',
            'cart_total' => $this->cartService->getTotalQuantity(),
        ]);
    }
}
