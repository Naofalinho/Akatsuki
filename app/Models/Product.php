<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'price',
        'old_price',
        'image',
        'stock',
        'is_popular',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Génère automatiquement le slug.
     */
    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * Pourcentage de réduction.
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if (
            !$this->old_price ||
            $this->old_price <= $this->price
        ) {
            return null;
        }

        return (int) round(
            (($this->old_price - $this->price) / $this->old_price) * 100
        );
    }

    /**
     * Vérifie si le produit est disponible.
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->is_active && $this->stock > 0;
    }
}