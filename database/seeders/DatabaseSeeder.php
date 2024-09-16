<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'manager',
            'email' => 'manager@manager.com',
            'password'=>Hash::make('password',[1212121212]),
            
        ]);
        \App\Models\User::factory()->create([
            'name' => 'developer',
            'email' => 'developer@developer.com',
            'password'=>Hash::make('password',[1212121212]),
            
        ]);
        \App\Models\User::factory()->create([
            'name' => 'tester',
            'email' => 'tester@tester.com',
            'password'=>Hash::make('password',[1212121212]),
            
        ]);
    }
}
