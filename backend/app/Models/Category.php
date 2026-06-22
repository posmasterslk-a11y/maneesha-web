<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'image', 'is_active', 'is_featured', 'sort_order', 'size_chart_image'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean'
    ];

    protected $appends = ['size_chart_url'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getSizeChartUrlAttribute()
    {
        return $this->size_chart_image ? url('storage/' . $this->size_chart_image) : null;
    }
}
