<?php

namespace Database\Seeders;

use App\Models\CoinRate;
use App\Models\Notification;
use App\Models\Partner;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->has(Partner::factory()->hasRewards(100, ['status' => 'publish']))->create([
            'name' => 'Mitra',
            'email' => 'partner@example.com',
        ]);
        CoinRate::create([
            'coin' => 3,
            'rupiah' => 30,
            'step' => 1000,
            'coin_balance' => 100000000
        ]);
    }
}
