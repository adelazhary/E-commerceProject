<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventory extends Model
{
    use HasFactory;
    protected $fillable=['qantity','modified_at'];
    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
