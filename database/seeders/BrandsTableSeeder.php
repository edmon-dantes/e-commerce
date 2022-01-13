<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandsTableSeeder extends Seeder
{
    public function run()
    {
        $model_0 = Brand::create(['name' => 'Arrow', 'status' => 1]);
        $model_1 = Brand::create(['name' => 'Gap', 'status' => 1]);
        $model_2 = Brand::create(['name' => 'Lee', 'status' => 1]);
        $model_3 = Brand::create(['name' => 'Monte Carlo', 'status' => 1]);
        $model_4 = Brand::create(['name' => 'Peter England', 'status' => 1]);
    }
}
