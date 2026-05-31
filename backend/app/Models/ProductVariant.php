<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'size', 'price', 'stock', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'price' => 'float'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
