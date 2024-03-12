<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
