<?php

namespace Database\Seeders;

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
    }
}
