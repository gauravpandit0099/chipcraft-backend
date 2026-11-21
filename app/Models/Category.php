<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /* mass assignable category fields */
    protected $fillable = ['name', 'slug', 'status'];

    /* category has many products */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}