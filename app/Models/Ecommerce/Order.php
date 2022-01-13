<?php

namespace App\Models\Ecommerce;

use App\Models\Product;
use App\Models\Ecommerce\SubOrder;
use App\Models\User;
use App\Traits\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasFactory, Notifiable, SoftDeletes, BaseModel;

    protected $guard_name = 'api';

    protected $fillable = [
        'number',
        'user_id',
        // 'status',
        'grand_total',
        'item_count',
        // 'is_paid',
        // 'payment_method',
        'notes',
        'shipping_firstname',
        'shipping_lastname',
        'shipping_email',
        'shipping_phone',
        'shipping_country',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zipcode',
        'billing_firstname',
        'billing_lastname',
        'billing_email',
        'billing_phone',
        'billing_country',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zipcode',
    ];

    protected $attributes = [
        'status' => 'pending',
        'is_paid' => false,
        'payment_method' => 'cash_on_delivery'
    ];

    public function getRouteKeyName()
    {
        return 'number';
    }

    public function items()
    {
        return $this->belongsToMany(Product::class, 'order_items', 'order_id', 'product_id')->withTimestamps()->withPivot('name', 'price', 'quantity');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function subOrders()
    {
        return $this->hasMany(SubOrder::class);
    }

    public function generateSubOrders()
    {
        $orderItems = $this->items;

        foreach ($orderItems->grouBy('shop_id') as $shopId => $products) {
            $shop = Shop::find($shopId);

            $suborder =  $this->subOrders()->create([
                'order_id' => $this->id,
                'seller_id' => $shop->user_id,
                'grand_total' => $products->sum('pivot.price'),
                'item_count' => $products->count()
            ]);

            foreach ($products as $product) {
                $suborder->items()->attach($product->id, ['price' => $product->pivot->price, 'quantity' => $product->pivot->quantity]);
            }
        }
    }

    public function getShippingFullAdddressAttribute()
    {
        return $this->shipping_fullname . "<br>" . $this->shipping_address . ', ' . $this->shipping_city . ', ' . $this->shipping_city;
    }

    public function scopeSearchSeller($query, $value)
    {
        return $query->orwhere("user_id", "like", "%$value%");
    }
}
