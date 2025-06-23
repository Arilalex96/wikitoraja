<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //for demo: 
        $admin = User::factory()->create([
            'name'=>'admin', 
            'email'=> 'admin@wikitoraja.com', 
            'password'=> Hash::make('password'), 
            'active' => true
        ]);
        /** 
        *$validator = User::factory()->create([
        *    'name'=> 'Rudi Harmawan', 
        *   'email'=> 'validator@wikitoraja.com', 
        *    'password'=> Hash::make('password'), 
        *    'active' => true
        *]);

        *$contributor = User::factory()->create([
        *    'name'=>'Aini Latifah', 
        *    'email'=> 'contributor@wikitoraja.com', 
        *    'password'=> Hash::make('password'), 
        *    'active' => true
        *]);
        */
        

        $admin->assignRole('admin');
        /** 
        * $contributor->assignRole('contributor');
        * $validator->assignRole('validator');

        * User::factory(100)->withRole('contributor')->create();
        * User::factory(100)->withRole('validator')->create();
        */
        
    }
}