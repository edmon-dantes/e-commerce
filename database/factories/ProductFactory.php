<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->name;

        return [
            'name' => $name,
            'code' => $this->faker->unique()->numberBetween(30000000000,80000000000),
            'description' => $this->faker->optional()->text,
            'properties' => $this->faker->optional()->randomElement([
                [
                    'color' => $this->faker->randomElement(['BLUE', 'RED', 'GREEN', 'BLACK', 'WHITE', 'YELLOW']),
                    'size' => $this->faker->randomElement(['L', 'XS', 'M']),
                ]
            ]),
            'quantity' => $this->faker->numberBetween($min = 0, $max = 20),
            'purchase_price' => $this->faker->numberBetween($min = 1, $max = 15),
            'sale_price' => $this->faker->numberBetween($min = 16, $max = 30),
            'status' => $this->faker->numberBetween($min = 0, $max = 1),
            'shop_id' => $this->faker->numberBetween(1, 97)
        ];
    }
}
