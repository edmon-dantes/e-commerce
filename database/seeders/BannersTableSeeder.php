<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannersTableSeeder extends Seeder
{
    public function run()
    {
        $banner_0 = Banner::create(['name' => 'Summer', 'status' => 1]);
        $banner_1 = Banner::create(['name' => 'Autumn', 'status' => 1]);
        $banner_2 = Banner::create(['name' => 'Winter', 'status' => 1]);
    }
}
