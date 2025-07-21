<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage-panel',
            'manage-permissions',
            'manage-roles',
            'manage-users',

            // Permissions
            'view-permissions',
            'create-permissions',
            'edit-permissions',
            'delete-permissions',

            // Roles
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
