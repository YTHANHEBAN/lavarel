<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'province',
        'district',
        'ward',
    ];

    // Mỗi địa chỉ thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
