<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Product;
use Gloudemans\Shoppingcart\Cart as ShoppingcartCart;

class CartController extends Controller
{
    // Hiển thị danh sách sản phẩm trong giỏ hàng
    public function index()
{
    $userId = Auth::id();

    // Lấy tất cả đơn hàng trong giỏ của user hiện tại
    $cartItems = Cart::where('user_id', $userId)->with('product')->get();

    // Tính tổng tiền
    $total = $cartItems->sum(function ($item) {
        return $item->price * $item->quantity;
    });

    return view('carts.list', compact('cartItems', 'total'));
}

public function store(Request $request)
{
    // Bước 1: Validate đầu vào
    $data = $request->validate([
        'user_id' => 'required|integer',
        'product_id' => 'required|integer',
        'quantity' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
    ]);

    // Bước 2: Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $existingItem = Cart::where('user_id', $data['user_id'])
        ->where('product_id', $data['product_id'])
        ->first();

    if ($existingItem) {
        // Nếu đã có, tăng số lượng
        $existingItem->quantity += $data['quantity'];
        $existingItem->total_price = $existingItem->quantity * $data['price'];
        $existingItem->save();
    } else {
        // Nếu chưa có, tạo mới
        $data['total_price'] = $data['quantity'] * $data['price'];
        Cart::create($data);
    }

    // Bước 3: Thông báo và chuyển hướng
    Session::flash('message', 'Thêm vào giỏ hàng thành công!');
    return redirect()->route('cart.index');
}

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required|numeric|min:1',
        ]);

        $cartItem = Cart::findOrFail($id);

        // Kiểm tra user sở hữu cart
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->quantity = $data['quantity'];
        $cartItem->total_price = $data['quantity'] * $cartItem->price;
        $cartItem->save();

        Session::flash('message', 'Cập nhật giỏ hàng thành công!');
        return redirect()->route('cart.index');
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function destroy($id)
    {
        $cartItem = Cart::findOrFail($id);

        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        Session::flash('message', 'Xóa sản phẩm khỏi giỏ hàng thành công!');
        return redirect()->route('cart.index');
    }

    // Xóa toàn bộ giỏ hàng (tuỳ chọn)
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        Session::flash('message', 'Đã xóa toàn bộ giỏ hàng.');
        return redirect()->route('cart.index');
    }

    public function checkout()
    {
        $userId = Auth::id();

        $carts = Cart::where('user_id', $userId)->with('product')->get();

        $total = $carts->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('carts.checkout', compact('carts', 'total'));
    }

}
