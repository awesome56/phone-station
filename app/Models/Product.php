<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'brand',
        'tagline',
        'description',
        'price',
        'compare_at_price',
        'image',
        'specs',
        'badge',
        'featured',
        'in_stock',
        'stock',
    ];

    protected $casts = [
        'specs' => 'array',
        'featured' => 'boolean',
        'in_stock' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    /**
     * Additional gallery images (the primary image lives on the products table).
     */
    public function gallery(): Collection
    {
        return $this->images()->get();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->compare_at_price || $this->compare_at_price <= $this->price) {
            return 0;
        }

        return (int) round((1 - $this->price / $this->compare_at_price) * 100);
    }
}
