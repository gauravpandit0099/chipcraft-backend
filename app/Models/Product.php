<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /* mass assignable product fields */
    protected $fillable = ['category_id', 'subcategory_id', 'name', 'slug', 'description', 'price', 'in_stock', 'image'];

    /* product belongs to category */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}