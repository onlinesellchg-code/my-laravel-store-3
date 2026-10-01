<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id','name','slug','sku','description','short_description',
        'price','old_price','stock','image_url','emoji','is_active','is_featured'
    ];

    protected $casts = [
        'price' => 'integer',
        'old_price' => 'integer',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (! $this->old_price || $this->old_price <= $this->price) {
            return null;
        }
        return (int) round((1 - ($this->price / $this->old_price)) * 100);
    }
}
