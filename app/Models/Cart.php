<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'token',
        'type',
        'user_id',
        'cart_data',
        'status',
    ];

    public function setCartDataAttribute($value)
    {
        $this->attributes['cart_data'] = serialize($value);
    }
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = (int) $value;
    }

    public function getCartDataAttribute($value)
    {
        return unserialize($value);
    }
}
