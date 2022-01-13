<?php

namespace Database\Factories\Ecommerce;

use App\Models\Ecommerce\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shop::class;

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
            'description' => $this->faker->text,
            'rating' => null,
            'active' => $this->faker->boolean(),
            'slug' => Str::slug($name, '-'),
            'user_id' => $this->faker->unique()->numberBetween(4, 100) //User::all()->random()->id
        ];
    }
}
