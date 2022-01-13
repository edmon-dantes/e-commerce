<?php

namespace Database\Seeders;

use App\Models\Ecommerce\Order;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Order::factory()->count(1)->hasItems(1)->create();

        // $orders = Order::factory()->times(3)->create();

        // $orders->each(function (Order $o) {
        //     $ids = \App\Models\Product::inRandomOrder()->take(rand(1, 3))->pluck('id');
        //     foreach ($ids as $id) {
        //         $o->items()->attach($id, ['name' => '', 'price' => '12.6', 'quantity' => 15, 'delivered_at' => now()]);
        //     }
        // });
    }
}
