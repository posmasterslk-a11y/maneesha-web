<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'order_index',
        'is_active',
        'subtitle',
        'title_top',
        'title_bottom',
        'desc',
        'btn_text',
        'btn_link',
        'show_text'
    ];
}
