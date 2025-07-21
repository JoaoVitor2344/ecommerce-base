<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = 'Admin';
        $email = 'admin@ecommerce-base.test';
        $password = 'admin123';
        $admin = \App\Models\User::where('email', $email)->first();

        if (!$admin) {
            \App\Models\User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
            ]);

            $admin = \App\Models\User::where('email', $email)->first();
            if ($admin) {
                $admin->assignRole('admin');
            }
        }
    }
}
