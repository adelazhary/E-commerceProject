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
    protected $casts = [
        'modified_at' => 'datetime',
        'price' => 'integer',
        'is_available' => 'boolean',
        'is_in_stock' => 'boolean',
    ];
    protected $appends = ['excerpt'];
    protected $with = ['country', 'categories', 'discount'];
    protected $dates = ['modified_at'];
    protected $hidden = ['pivot'];
    protected $touches = ['categories'];
    // protected $dispatchesEvents = [
    //     'created' => \App\Events\ProductCreated::class,
    //     'updated' => \App\Events\ProductUpdated::class,
    //     'deleted' => \App\Events\ProductDeleted::class,
    // ];
    // attribute

    public function setModifiedAtAttribute($value)
    {
        $this->attributes['modified_at'] = $value ? $value : now();
    }
    protected function description(): Attribute{
        return Attribute::make(
            get: fn($value) => strip_tags($value),
            set: fn($value) => strip_tags($value),

        );
    }
    protected function name(): Attribute{
        return Attribute::make(
            get: fn($value) => ucwords($value),
            set: fn($value) => ucwords($value),
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
