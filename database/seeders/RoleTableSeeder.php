<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin',
            'user',
        ];

        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role]);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $permissions = \Spatie\Permission\Models\Permission::all();
        $adminRole->syncPermissions($permissions);
    }
}
