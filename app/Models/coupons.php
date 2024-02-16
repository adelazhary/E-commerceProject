<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class coupons extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'value', 'type', 'percentage_off'];

}
