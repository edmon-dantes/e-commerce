<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(SectionsTableSeeder::class);
        // $this->call(CategoriesTableSeeder::class);
        // $this->call(BrandsTableSeeder::class);
        // $this->call(ProductsTableSeeder::class);
        // $this->call(ProductsAttributesTableSeeder::class);

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UsersTableSeeder::class);
        // $this->call(ShopsTableSeeder::class);

        // Category::factory()->hasProducts(100)->create();

        // Category::each(function ($category) {
        //     $category->products()->save(Product::factory()->make());
        // });

        // Category::factory()->times(1)->create()->each(function($category){
        //     $category->products()->save(Product::factory()->make());
        // });

        // $this->call(OrdersTableSeeder::class);

        // $this->call(CouponsTableSeeder::class);

        // \App\Models\User::factory(10)->create();
    }
}
