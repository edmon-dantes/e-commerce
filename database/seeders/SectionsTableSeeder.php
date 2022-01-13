<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionsTableSeeder extends Seeder
{
    public function run()
    {
        $section_0 = Section::create(['name' => 'Men', 'status' => 1]);
        $section_1 = Section::create(['name' => 'Women', 'status' => 1]);
        $section_2 = Section::create(['name' => 'Kids', 'status' => 1]);

        // $sections = [
        //     ['name' => 'Men', 'status' => 1],
        //     ['name' => 'Women', 'status' => 1],
        //    ['name' => 'Kids', 'status' => 1],
        // ];

        // Section::insert($sections);
    }
}
