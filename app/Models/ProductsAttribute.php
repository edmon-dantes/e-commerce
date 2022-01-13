<?php

namespace App\Models;

use App\Traits\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsAttribute extends Model
{
    use HasFactory, BaseModel;

    protected $fillable = [
        'size',
        'sku',
        'price',
        'stock',
        'status',
    ];

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = (int) $value;
    }

    public function product()
    {
        return $this->belongsTo(Product::class); // ->select('id', 'name'); // ->where('status', 1);
    }
}
