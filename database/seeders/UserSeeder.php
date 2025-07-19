<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = 'Admin';
        $email = 'admin@ecommerce-base.test';
        $password = 'admin123';

        $admin = User::where('email', $email)->first();

        if (!$admin) {
            $admin = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
            ]);

            $admin->assignRole('admin');
        }
    }
}
