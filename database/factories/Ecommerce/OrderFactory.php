<?php

namespace Database\Factories\Ecommerce;

use App\Models\Ecommerce\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number' => $this->faker->unique()->numberBetween(1, 100),
            'user_id' => $this->faker->numberBetween($min = 1, $max = 100),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'decline']),
            'payment_method' => $this->faker->randomElement(['cash_on_delivery', 'paypal', 'stripe', 'card']),
            'shipping_firstname' => '',
            'shipping_lastname' => '',
            'shipping_email' => '',
            'shipping_phone' => '',
            'shipping_country' => '',
            'shipping_address' => '',
            'shipping_city' => '',
            'shipping_state' => '',
            'shipping_zipcode' => '',
            'billing_firstname' => '',
            'billing_lastname' => '',
            'billing_email' => '',
            'billing_phone' => '',
            'billing_country' => '',
            'billing_address' => '',
            'billing_city' => '',
            'billing_state' => '',
            'billing_zipcode' => '',
        ];

        /*
        return [
            'order_id' => '1',
            'product_id' => $this->faker->numberBetween($min = 1, $max = 100),
            'name' => $this->faker->conpany,
            'price' => $this->faker->numberBetween($min = 16, $max = 30),
            'quantity' => $this->faker->numberBetween($min = 16, $max = 30),
            'delivered_at' => now(),
        ];
        */
    }
}
