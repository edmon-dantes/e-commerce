<?php

namespace Database\Factories\Ecommerce;

use App\Models\Ecommerce\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'code' => Str::random(5),
            'type' => 'discount',
            'value' => '-12.5%',
        ];
    }
}
