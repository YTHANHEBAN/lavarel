<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Comment extends Model
{
    // Cho phép gán các trường này từ request
    protected $fillable = [
        'user_id',
        'product_id',
        'content',
        'rating',
    ];

    // Quan hệ: mỗi comment thuộc về một user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ: mỗi comment thuộc về một product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
