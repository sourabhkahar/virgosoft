<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(1);
        $user->assets()->createMany([
            ['symbol' => 'BTC', 'amount' => 1.5],
            ['symbol' => 'ETH', 'amount' => 10],
            ['symbol' => 'USDT', 'amount' => 1000],
        ]);

        $user = User::find(2);
        $user->assets()->createMany([
            ['symbol' => 'BTC', 'amount' => 3],
            ['symbol' => 'ETH', 'amount' => 5],
            ['symbol' => 'USDT', 'amount' => 700],
        ]);
    }
}
