<?php

namespace Database\Seeders;

use App\Models\Permission\PermissionSpatie;
use App\Models\Permission\RoleSpatie;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        PermissionSpatie::create(['name' => 'users.index', 'status' => 1]);
        PermissionSpatie::create(['name' => 'users.create', 'status' => 1]);
        PermissionSpatie::create(['name' => 'users.show', 'status' => 1]);
        PermissionSpatie::create(['name' => 'users.edit', 'status' => 1]);
        PermissionSpatie::create(['name' => 'users.destroy', 'status' => 1]);
        PermissionSpatie::create(['name' => 'users.store.multiple', 'status' => 1]);
        PermissionSpatie::create(['name' => 'users.update.multiple', 'status' => 1]);
        PermissionSpatie::create(['name' => 'users.destroy.multiple', 'status' => 1]);

        PermissionSpatie::create(['name' => 'roles.index', 'status' => 1]);
        PermissionSpatie::create(['name' => 'roles.create', 'status' => 1]);
        PermissionSpatie::create(['name' => 'roles.show', 'status' => 1]);
        PermissionSpatie::create(['name' => 'roles.edit', 'status' => 1]);
        PermissionSpatie::create(['name' => 'roles.destroy', 'status' => 1]);

        PermissionSpatie::create(['name' => 'permissions.index', 'status' => 1]);
        PermissionSpatie::create(['name' => 'permissions.create', 'status' => 1]);
        PermissionSpatie::create(['name' => 'permissions.show', 'status' => 1]);
        PermissionSpatie::create(['name' => 'permissions.edit', 'status' => 1]);
        PermissionSpatie::create(['name' => 'permissions.destroy', 'status' => 1]);

        PermissionSpatie::create(['name' => 'sections.index', 'status' => 1]);
        PermissionSpatie::create(['name' => 'sections.create', 'status' => 1]);
        PermissionSpatie::create(['name' => 'sections.show', 'status' => 1]);
        PermissionSpatie::create(['name' => 'sections.edit', 'status' => 1]);
        PermissionSpatie::create(['name' => 'sections.destroy', 'status' => 1]);
        PermissionSpatie::create(['name' => 'sections.destroy.multiple', 'status' => 1]);

        PermissionSpatie::create(['name' => 'categories.index', 'status' => 1]);
        PermissionSpatie::create(['name' => 'categories.create', 'status' => 1]);
        PermissionSpatie::create(['name' => 'categories.show', 'status' => 1]);
        PermissionSpatie::create(['name' => 'categories.edit', 'status' => 1]);
        PermissionSpatie::create(['name' => 'categories.destroy', 'status' => 1]);
        PermissionSpatie::create(['name' => 'categories.destroy.multiple', 'status' => 1]);

        PermissionSpatie::create(['name' => 'brands.index', 'status' => 1]);
        PermissionSpatie::create(['name' => 'brands.create', 'status' => 1]);
        PermissionSpatie::create(['name' => 'brands.show', 'status' => 1]);
        PermissionSpatie::create(['name' => 'brands.edit', 'status' => 1]);
        PermissionSpatie::create(['name' => 'brands.destroy', 'status' => 1]);
        PermissionSpatie::create(['name' => 'brands.destroy.multiple', 'status' => 1]);

        PermissionSpatie::create(['name' => 'products.index', 'status' => 1]);
        PermissionSpatie::create(['name' => 'products.create', 'status' => 1]);
        PermissionSpatie::create(['name' => 'products.show', 'status' => 1]);
        PermissionSpatie::create(['name' => 'products.edit', 'status' => 1]);
        PermissionSpatie::create(['name' => 'products.destroy', 'status' => 1]);
        PermissionSpatie::create(['name' => 'products.destroy.multiple', 'status' => 1]);

        PermissionSpatie::create(['name' => 'banners.index', 'status' => 1]);
        PermissionSpatie::create(['name' => 'banners.create', 'status' => 1]);
        PermissionSpatie::create(['name' => 'banners.show', 'status' => 1]);
        PermissionSpatie::create(['name' => 'banners.edit', 'status' => 1]);
        PermissionSpatie::create(['name' => 'banners.destroy', 'status' => 1]);
        PermissionSpatie::create(['name' => 'banners.destroy.multiple', 'status' => 1]);

        PermissionSpatie::create(['name' => 'cart.index', 'status' => 1]);
        PermissionSpatie::create(['name' => 'cart.create', 'status' => 1]);
        PermissionSpatie::create(['name' => 'cart.edit', 'status' => 1]);
        PermissionSpatie::create(['name' => 'cart.destroy', 'status' => 1]);

        // PermissionSpatie::create(['name' => 'shops.index', 'status' => 1]);
        // PermissionSpatie::create(['name' => 'shops.create', 'status' => 1]);
        // PermissionSpatie::create(['name' => 'shops.show', 'status' => 1]);
        // PermissionSpatie::create(['name' => 'shops.edit', 'status' => 1]);
        // PermissionSpatie::create(['name' => 'shops.destroy', 'status' => 1]);

        $role = RoleSpatie::create(['name' => 'super-admin', 'status' => 1]);
        $role->givePermissionTo(PermissionSpatie::all());

        $role = RoleSpatie::create(['name' => 'seller', 'status' => 1]);
        $role->givePermissionTo('products.index');
        $role->givePermissionTo('products.create');
        $role->givePermissionTo('products.show');
        $role->givePermissionTo('products.edit');
        $role->givePermissionTo('products.destroy');

        // $role->givePermissionTo('shops.index');
        // $role->givePermissionTo('shops.create');
        // $role->givePermissionTo('shops.show');
        // $role->givePermissionTo('shops.edit');
        // $role->givePermissionTo('shops.destroy');

        $role = RoleSpatie::create(['name' => 'client', 'status' => 1]);
        $role->givePermissionTo('sections.index');
        $role->givePermissionTo('categories.index');
        $role->givePermissionTo('brands.index');
        $role->givePermissionTo('products.index');
        $role->givePermissionTo('products.show');
        $role->givePermissionTo('users.show');
        $role->givePermissionTo('users.edit');
        $role->givePermissionTo('cart.index');
        $role->givePermissionTo('cart.create');
        $role->givePermissionTo('cart.edit');
        $role->givePermissionTo('cart.destroy');


        // $role = RoleSpatie::create(['name' => 'invited', 'status' => 1]);
        // $role->givePermissionTo('sections.index');
        // $role->givePermissionTo('categories.index');
        // $role->givePermissionTo('brands.index');
        // $role->givePermissionTo('products.index');
        // $role->givePermissionTo('products.show');
        // $role->givePermissionTo('cart.index');
        // $role->givePermissionTo('cart.create');
        // $role->givePermissionTo('cart.edit');
        // $role->givePermissionTo('cart.destroy');
    }
}
