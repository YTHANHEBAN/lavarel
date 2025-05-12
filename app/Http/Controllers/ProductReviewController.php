<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function store(Request $request, $productId)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    $userId = Auth::id();

    // ✅ Kiểm tra xem user đã đánh giá sản phẩm này chưa
    $alreadyReviewed = ProductReview::where('user_id', $userId)
        ->where('product_id', $productId)
        ->exists();

    if ($alreadyReviewed) {
        return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
    }

    // ✅ Tạo đánh giá nếu chưa có
    ProductReview::create([
        'user_id' => $userId,
        'product_id' => $productId,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return back()->with('success', 'Đánh giá đã được gửi!');
}

}
