<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

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
    // attribute
    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
    // mutator
    public function setImageAttribute($value)
    {
        $this->attributes['image'] = $value->store('products', 'public');
    }

    public function setModifiedAtAttribute($value)
    {
        $this->attributes['modified_at'] = $value ? $value : now();
    }
    public function getModifiedAtAttribute($value)
    {
        return $value ? $value : now();
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
    public function discount(): BelongsTo
    {
        return $this->belongsTo(discount::class);
    }

    public function getDescriptionAttribute($value)
    {
        return ucwords($value);
    }
    public function getExcerptAttribute()
    {
        return Str::limit($this->description, 200);
    }
}
