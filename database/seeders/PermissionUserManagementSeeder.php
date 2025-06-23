<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionUserManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'index contributor']);
        Permission::create(['name' => 'index validator']);
        Permission::create(['name' => 'get validator']);
        Permission::create(['name' => 'create validator']);
        Permission::create(['name' => 'delete validator']);
        Permission::create(['name' => 'edit non admin user']);

        //profile
        Permission::create(['name' => 'view own profile']);
        Permission::create(['name' => 'edit own password']);



        Role::findByName('admin')->givePermissionTo([
            'index contributor',
            'index validator',
            'get validator',
            'create validator',
            'delete validator',
            'edit non admin user',
            'view own profile',
            'edit own password',
        ]);

        Role::findByName('contributor')->givePermissionTo([
            'view own profile',
            'edit own password',
        ]);

        Role::findByName('validator')->givePermissionTo([
            'view own profile',
            'edit own password',
        ]);
    }
}
