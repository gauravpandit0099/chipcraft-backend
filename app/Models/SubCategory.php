<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subcategory extends Model
{
    /* mass assignable subcategory fields */
    protected $fillable = ['category_id', 'name', 'slug', 'status'];

    /* subcategory belongs to category */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /* subcategory has many products */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}