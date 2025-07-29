<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

     protected $fillable = [
        'name',
        'sku',
        'price',
        'stock',
        'category',
        'image',
        'description',
        'is_new',
        'is_on_sale',
        'average_rating',
        'review_count',
    ];

    protected $casts = [
        'is_new' => 'boolean',
        'is_on_sale' => 'boolean',
        'average_rating' => 'float',
        'review_count' => 'integer',
    ];
}