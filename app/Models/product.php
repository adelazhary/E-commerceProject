<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'modified_at',
        'price',
        'discount_id',
        'inventory_id',
        'is_available',
        'is_in_stock',
        'amount_in_stock',
        'country_id',
    ];

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
    /**
     * The categories that belong to the product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(category::class);
    }
    /**
     * Get the country that owns the product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(country::class);
    }

    public function orders(): belongsToMany
    {
        return $this->belongsToMany(Order::class);
    }
}
