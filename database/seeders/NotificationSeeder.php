<?php

namespace Database\Seeders;

use App\Enums\NotificationEnum;
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
        for ($i = 0 ; $i < 5;$i++) {
            User::find(3)->notify(
                new ExchangeRewardProcess(
                    NotificationEnum::getValue('COIN_CONVERT'),
                    'Body Notifikasi'
                )
            );
        }
        for ($i = 0 ; $i < 5;$i++) {
            User::find(3)->notify(
                new ExchangeRewardProcess(
                    NotificationEnum::getValue('NEW_EXCHANGE'),
                    'Body Notifikasi'
                )
            );
        }
        for ($i = 0 ; $i < 5;$i++) {
            User::find(3)->notify(
                new ExchangeRewardProcess(
                    NotificationEnum::getValue('EXCHANGE_ON_PROGRESS'),
                    'Body Notifikasi'
                )
            );
        }
        for ($i = 0 ; $i < 5;$i++) {
            User::find(3)->notify(
                new ExchangeRewardProcess(
                    NotificationEnum::getValue('EXCHANGE_READY_TO_PICKUP'),
                    'Body Notifikasi'
                )
            );
        }
        for ($i = 0 ; $i < 5;$i++) {
            User::find(3)->notify(
                new ExchangeRewardProcess(
                    NotificationEnum::getValue('EXCHANGE_CANCELED'),
                    'Body Notifikasi'
                )
            );
        }
        for ($i = 0 ; $i < 5;$i++) {
            User::find(3)->notify(
                new ExchangeRewardProcess(
                    NotificationEnum::getValue('EXCHANGE_DONE'),
                    'Body Notifikasi'
                )
            );
        }
    }
}
