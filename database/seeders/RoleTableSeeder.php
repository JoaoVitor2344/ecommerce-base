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

        foreach ($roles as $name) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin', 'web');
        $permissions = \Spatie\Permission\Models\Permission::all();
        foreach ($permissions as $permission) {
            if (!$adminRole->hasPermissionTo($permission)) {
                $adminRole->givePermissionTo($permission);
            }
        }
    }
}
