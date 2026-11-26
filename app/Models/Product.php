<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /* mass assignable product fields */
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'name',
        'slug',
        'description',
        'price',
        'in_stock',
        'image',
        'gallery_images',
        'specifications'
    ];

    /* cast stock and gallery json */
    protected function casts(): array
    {
        return [
            'in_stock' => 'boolean',
            'gallery_images' => 'array',
            'specifications' => 'array',
        ];
    }

    /* product belongs to category */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /* product belongs to subcategory */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }
}