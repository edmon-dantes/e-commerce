<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item_0 = Category::create(['name' => 'T-Shirts', 'discount' => 0, 'meta_title' => '', 'meta_description' => '', 'meta_keywords' => '', 'section_id' => 1, 'status' => 1]);
        $item_1 = Category::create(['name' => 'Casual T-Shirts', 'discount' => 0, 'meta_title' => '', 'meta_description' => '', 'meta_keywords' => '', 'section_id' => 1, 'status' => 1]);

        // Category::factory()->times(100)->create();
    }
}
