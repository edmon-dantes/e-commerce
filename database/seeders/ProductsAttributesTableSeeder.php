<?php

namespace Database\Seeders;

use App\Models\ProductsAttribute;
use Illuminate\Database\Seeder;

class ProductsAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item_0 = ProductsAttribute::create(['product_id' => 1, 'size' => 'Small', 'price' => 1200, 'stock' => 10, 'sku' => 'BT001-S', 'status' => 1]);
        $item_1 = ProductsAttribute::create(['product_id' => 1, 'size' => 'Medium', 'price' => 1300, 'stock' => 20, 'sku' => 'BT001-M', 'status' => 1]);
        $item_2 = ProductsAttribute::create(['product_id' => 1, 'size' => 'Large', 'price' => 1400, 'stock' => 30, 'sku' => 'BT001-L', 'status' => 1]);
    }
}
