<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'create article']);
        Permission::create(['name' => 'index article']);
        Permission::create(['name' => 'edit article']);
        Permission::create(['name' => 'delete article']);
        Permission::create(['name' => 'approval article']);
        Permission::create(['name' => 'edit article rating']);
        Permission::create(['name' => 'get article preview']);

        Role::where('name', 'validator')->first()->givePermissionTo([
            'index article',
            'approval article',
            'get article preview',
        ]);

        Role::where('name', 'contributor')->first()->givePermissionTo([
            'index article',
            'create article',
            'edit article',
            'delete article',
            'edit article rating'
        ]);
    }
}
