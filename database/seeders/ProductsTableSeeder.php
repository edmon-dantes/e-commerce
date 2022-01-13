<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $model_0 = Product::create(['category_id' => 2, 'section_id' => 1, 'brand_id' => 1, 'name' => 'Blue T-Shirt', 'code' => 'BT001', 'color' => 'Blue', 'price' => 1500, 'discount' => 10, 'weight' => 200, 'video' => '', 'main_image' => '', 'description' => 'Test Product', 'wash_care' => '', 'fabric' => '', 'pattern' => '', 'sleeve' => '', 'fit' => '', 'occassion' => '', 'meta_title' => '', 'meta_description' => '', 'meta_keywords' => '', 'is_featured' => 1, 'status' => 1]);
        // $model_1 = Product::create(['category_id' => 2, 'section_id' => 1, 'brand_id' => 2, 'name' => 'Blue T-Shirt 2', 'code' => 'BT002', 'color' => 'Blue', 'price' => 1500, 'discount' => 10, 'weight' => 200, 'video' => '', 'main_image' => '', 'description' => 'Test Product', 'wash_care' => '', 'fabric' => '', 'pattern' => '', 'sleeve' => '', 'fit' => '', 'occassion' => '', 'meta_title' => '', 'meta_description' => '', 'meta_keywords' => '', 'is_featured' => 1, 'status' => 1]);

        $model_0 = Product::create(['category_id' => 2, 'section_id' => 1, 'brand_id' => 1, 'name' => 'Blue T-Shirt', 'code' => 'BT001', 'color' => 'Blue', 'price' => 1200, 'discount' => 10, 'weight' => 200, 'description' => 'Test Product', 'details' => '', 'wash_care' => 'Machine Wash', 'fabric' => 'Polyster', 'pattern' => 'Printed', 'sleeve' => 'Short Sleeve', 'fit' => 'Regular', 'occassion' => 'Casual', 'meta_title' => 'Demo', 'meta_description' => 'demo', 'meta_keywords' => 'demo', 'is_featured' => 1, 'status' => 1]);
        $model_1 = Product::create(['category_id' => 2, 'section_id' => 1, 'brand_id' => 2, 'name' => 'Blue T-Shirt 2', 'code' => 'BT002', 'color' => 'Blue', 'price' => 1500, 'discount' => 10, 'weight' => 200, 'description' => 'Test Product', 'details' => '', 'wash_care' => 'Machine Wash', 'fabric' => 'Polyster', 'pattern' => 'Printed', 'sleeve' => 'Short Sleeve', 'fit' => 'Regular', 'occassion' => 'Casual', 'meta_title' => 'Demo', 'meta_description' => 'demo', 'meta_keywords' => 'demo', 'is_featured' => 1, 'status' => 1]);

        // Product::factory()->times(50)->hastPosts(1)->create();
        // Product::factory()->times(100)->create();
    }
}
