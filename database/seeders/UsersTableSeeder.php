<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'super-admin',
            'username' => 'super-admin',
            'email' => 'superadmin@gmail.com',
            'phone_number' => '5661617',
            'address' => 'Demo',
            'password' => 'abc123ABC',
            'status' => 1,
            'email_verified_at' => Carbon::now(),
        ]);
        $admin->assignRole('super-admin');

        $seller = User::create([
            'name' => 'seller demo',
            'username' => 'sellerdemó',
            'email' => 'sellerdemo@gmail.com',
            'phone_number' => '5661617',
            'address' => 'Demo',
            'password' => 'abc123ABC',
            'status' => 1,
            'email_verified_at' => Carbon::now(),
        ]);
        $seller->assignRole('seller');

        $client = User::create([
            'name' => 'client demo',
            'username' => 'cliéntdemo',
            'email' => 'clientdemo@gmail.com',
            'phone_number' => '5661617',
            'address' => 'Demo',
            'password' => 'abc123ABC',
            'status' => 1,
            'email_verified_at' => Carbon::now(),
        ]);
        $client->assignRole('client');

        User::factory()->times(97)->create();
    }
}
