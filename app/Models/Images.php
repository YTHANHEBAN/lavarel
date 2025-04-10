<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'productImages';
    protected $fillable = [ 'imagePath','product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
