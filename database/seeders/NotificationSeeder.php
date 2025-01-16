<?php

namespace Database\Seeders;

use App\Enums\NotificationEnum;
use App\Enums\RewardClaimEnum;
use App\Models\Reward;
use App\Models\RewardClaim;
use App\Models\StepActivity;
use App\Models\User;
use App\Notifications\ExchangeRewardProcess;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::find(3)->update([
            'coin' => 100000
        ]);
        StepActivity::factory()->count(10)->for(User::find(3))->create();
        RewardClaim::factory()->count(5)->for(User::find(3))->for(Reward::find(1))->create();
        RewardClaim::factory()->count(5)->for(User::find(3))->for(Reward::find(1))->create([
            "status" => RewardClaimEnum::WAITING_CONFIRMATION,
            "created_at" => now()->subDay(2)
        ]);
        RewardClaim::factory()->count(5)->for(User::find(3))->for(Reward::find(1))->create([
            "status" => RewardClaimEnum::READY_TO_PICKUP,
        ]);
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
        for ($i = 0 ; $i < 5;$i++) {
            User::find(3)->notify(
                new ExchangeRewardProcess(
                    NotificationEnum::getValue('TARGET_NOT_ACHIEVED'),
                    'Target langkah hari ini belum tercapai, ikuti rekomendasi kami untuk kebugaran kamu (dari sistem)',
                    route('recommendation')
                )
            );
        }
    }
}
