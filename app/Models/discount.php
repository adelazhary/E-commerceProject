<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Extension\Attributes\Node\Attributes;

class discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'discription',
        'active',
        'modified_at',
        'discount_percent'
    ];

    public function scopeActive($query)
    {
        $query->where('active', 1);
    }
    public function scopeInactive($query)
    {
        $query->where('active', 0);
    }
    public function start_date(): Attribute
    {
        return Attribute::make(
            set: fn($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }
    public function end_date(): Attribute
    {
        return Attribute::make(
            set: fn($value) => Carbon::parse($value)->format('Y-m-d'),
        );
    }
}
