<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'short_description',
        'base_price', 'stock', 'main_image', 'gallery_images',
        'fabric', 'care_instructions', 'is_active', 'is_featured', 'in_hero_slider', 'sort_order',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_active'      => 'boolean',
        'is_featured'    => 'boolean',
        'base_price'     => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
