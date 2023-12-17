<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_address extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'postal_code',
        'country',
        'address_line1',
        'address_line2',
        'phone_number',
        'user_id'
    ];
}
