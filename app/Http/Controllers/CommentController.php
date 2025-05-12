<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // public function store(Request $request)
    // {
    //     // Xác thực dữ liệu đầu vào
    //     $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //         'content'    => 'required|string',
    //         'rating'     => 'required|integer|min:1|max:5',
    //     ]);

    //     // Lưu bình luận vào database
    //     Comment::create([
    //         'user_id' => Auth::id(),
    //         'product_id' => $request->product_id,
    //         'content'    => $request->content,
    //         'rating'     => $request->rating,
    //     ]);

    //     return back()->with('success', 'Đã gửi đánh giá của bạn.');
    // }
    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'content'    => 'required|string',
            'rating'     => 'required|integer|min:1|max:5',
        ]);

        // Lưu bình luận vào database
        Comment::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'content'    => $request->content,
            'rating'     => $request->rating,
        ]);

        return back()->with('success', 'Đã gửi đánh giá của bạn.');
    }

}
