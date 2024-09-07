<?php

namespace Database\Seeders;

use App\Enums\NotificationEnum;
use App\Models\Notification;
use App\Models\Reward;
use App\Models\RewardClaim;
use App\Models\StepActivity;
use App\Models\User;
use App\Notifications\ExchangeRewardProcess;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StepActivity::factory()->count(10)->for(User::find(3))->create();
        RewardClaim::factory()->count(10)->for(User::find(3))->for(Reward::find(1))->create();
        for ($i = 0 < 50;$i++;) {
            User::find(3)->notify(
                new ExchangeRewardProcess(
                    fake()->randomElement(NotificationEnum::class),
                    'Body Notifikasi'
                )
            );
        }
    }
}
