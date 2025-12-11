<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Bruce Wayne',
            'email' => 'bruce.wayne@example.com',
            'balance' => 100000,
        ]);
        
        User::factory()->create([
            'name' => 'Clark Kent',
            'email' => 'clark.kent@example.com',
            'balance' => 50000,
        ]);
    }
}
