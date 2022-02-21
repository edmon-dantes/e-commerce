<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'users.index', 'status' => 1]);
        Permission::create(['name' => 'users.create', 'status' => 1]);
        Permission::create(['name' => 'users.show', 'status' => 1]);
        Permission::create(['name' => 'users.edit', 'status' => 1]);
        Permission::create(['name' => 'users.destroy', 'status' => 1]);

        Permission::create(['name' => 'roles.index', 'status' => 1]);
        Permission::create(['name' => 'roles.create', 'status' => 1]);
        Permission::create(['name' => 'roles.show', 'status' => 1]);
        Permission::create(['name' => 'roles.edit', 'status' => 1]);
        Permission::create(['name' => 'roles.destroy', 'status' => 1]);

        Permission::create(['name' => 'permissions.index', 'status' => 1]);
        Permission::create(['name' => 'permissions.create', 'status' => 1]);
        Permission::create(['name' => 'permissions.show', 'status' => 1]);
        Permission::create(['name' => 'permissions.edit', 'status' => 1]);
        Permission::create(['name' => 'permissions.destroy', 'status' => 1]);

        Permission::create(['name' => 'sections.index', 'status' => 1]);
        Permission::create(['name' => 'sections.create', 'status' => 1]);
        Permission::create(['name' => 'sections.show', 'status' => 1]);
        Permission::create(['name' => 'sections.edit', 'status' => 1]);
        Permission::create(['name' => 'sections.destroy', 'status' => 1]);

        Permission::create(['name' => 'categories.index', 'status' => 1]);
        Permission::create(['name' => 'categories.create', 'status' => 1]);
        Permission::create(['name' => 'categories.show', 'status' => 1]);
        Permission::create(['name' => 'categories.edit', 'status' => 1]);
        Permission::create(['name' => 'categories.destroy', 'status' => 1]);

        Permission::create(['name' => 'brands.index', 'status' => 1]);
        Permission::create(['name' => 'brands.create', 'status' => 1]);
        Permission::create(['name' => 'brands.show', 'status' => 1]);
        Permission::create(['name' => 'brands.edit', 'status' => 1]);
        Permission::create(['name' => 'brands.destroy', 'status' => 1]);

        Permission::create(['name' => 'products.index', 'status' => 1]);
        Permission::create(['name' => 'products.create', 'status' => 1]);
        Permission::create(['name' => 'products.show', 'status' => 1]);
        Permission::create(['name' => 'products.edit', 'status' => 1]);
        Permission::create(['name' => 'products.destroy', 'status' => 1]);

        Permission::create(['name' => 'banners.index', 'status' => 1]);
        Permission::create(['name' => 'banners.create', 'status' => 1]);
        Permission::create(['name' => 'banners.show', 'status' => 1]);
        Permission::create(['name' => 'banners.edit', 'status' => 1]);
        Permission::create(['name' => 'banners.destroy', 'status' => 1]);

        Permission::create(['name' => 'cart_items.index', 'status' => 1]);
        Permission::create(['name' => 'cart_items.create', 'status' => 1]);
        Permission::create(['name' => 'cart_items.edit', 'status' => 1]);
        Permission::create(['name' => 'cart_items.destroy', 'status' => 1]);

        $role = Role::create(['name' => 'super-admin', 'status' => 1]);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'seller', 'status' => 1]);
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

        $role = Role::create(['name' => 'client', 'status' => 1]);
        $role->givePermissionTo('sections.index');
        $role->givePermissionTo('categories.index');
        $role->givePermissionTo('brands.index');
        $role->givePermissionTo('products.index');
        $role->givePermissionTo('products.show');
        $role->givePermissionTo('cart_items.index');
        $role->givePermissionTo('cart_items.create');
        $role->givePermissionTo('cart_items.edit');
        $role->givePermissionTo('cart_items.destroy');
    }
}
