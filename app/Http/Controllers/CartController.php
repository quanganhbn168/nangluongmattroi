<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
class CartController extends Controller
{
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        return response()->json($cartItems);
    }
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
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }
        $cartData = $this->getCartDataForAPI();

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
            'cart'    => $cartData
        ]);
    }
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
    public function remove($cartItemId)
    {
    
        CartItem::where('id', $cartItemId)->where('user_id', Auth::id())->firstOrFail()->delete();

    
    
        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được xóa khỏi giỏ!',
        'cart' => $this->getCartDataForAPI() 
    ]);
    }
    private function getCartData()
    {
        return Auth::user()->cartItems()->count();
    }
    public function showCartPage()
    {
    // Dù có check Auth hay không, chúng ta luôn cần biến $cartItems trong view.
        $cartItems = []; 
        
    // Nếu người dùng đã đăng nhập, lấy dữ liệu từ database.
        if (Auth::check()) {
        // Dùng lại hàm getCartDataForAPI() để có cấu trúc dữ liệu đồng nhất
        // và đảm bảo có đủ thông tin (product, total_price, etc.)
        // Lấy mảng 'items' từ kết quả trả về.
            $cartData = $this->getCartDataForAPI();
            $cartItems = $cartData['items']; 
        }
        
    // Truyền biến $cartItems vào view.
        return view('cart.index', ['cartItems' => $cartItems]);
    }
    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => ['required','integer'],
            'variant_id' => ['nullable','integer'],
            'quantity'   => ['required','integer','min:1'],
        ]);
        $productId = (int) ($request->input('variant_id') ?: $request->input('product_id'));
        $qty       = (int) $request->input('quantity');
        if (Auth::check()) {
            $user = Auth::user();
            $user->cartItems()->updateOrCreate(
                ['product_id' => $productId],
                ['quantity'   => DB::raw('GREATEST(quantity,0) + '.$qty)]
            );
        } else {
            $cart = session()->get('guest_cart', []);
            $found = false;
            foreach ($cart as &$item) {
                if ((int)$item['id'] === $productId) {
                    $item['quantity'] = (int)$item['quantity'] + $qty;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $cart[] = ['id' => $productId, 'quantity' => $qty];
            }
            session(['guest_cart' => $cart]);
        }
        return redirect()->route('checkout.index');
    }

    private function getCartDataForAPI()
    {
        $user = auth()->user();
        $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
        $total_price = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });
        $total_quantity = $cartItems->sum('quantity');

        return [
            'items'          => $cartItems,
            'total_price'    => $total_price,
            'total_quantity' => $total_quantity,
        ];
    }

    public function merge(Request $request)
    {
        $guestCart = $request->input('guest_cart', []);
        $user = auth()->user();

        if ($user && !empty($guestCart)) {
            foreach ($guestCart as $guestItem) {
                $existingItem = CartItem::where('user_id', $user->id)
                ->where('product_id', $guestItem['id'])
                ->first();

                if ($existingItem) {
                    $existingItem->quantity += $guestItem['quantity'];
                    $existingItem->save();
                } else {
                    CartItem::create([
                        'user_id' => $user->id,
                        'product_id' => $guestItem['id'],
                        'quantity' => $guestItem['quantity'],
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Giỏ hàng đã được gộp.']);
    }
}