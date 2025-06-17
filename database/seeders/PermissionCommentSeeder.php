<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'create comment']);
        Permission::create(['name' => 'edit comment']);
        Permission::create(['name' => 'delete comment']);

        Role::where('name', 'contributor')->first()->givePermissionTo([
            'create comment',
            'edit comment',
            'delete comment',
        ]);
    }
}
