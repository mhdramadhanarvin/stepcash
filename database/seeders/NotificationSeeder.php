<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\StepActivity;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notification::factory()->count(10)->for(User::find(3))->create();
        sleep(10);
        Notification::factory()->count(10)->for(User::find(3))->create();
        sleep(10);
        Notification::factory()->count(10)->for(User::find(3))->create();
        sleep(10);
        Notification::factory()->count(10)->for(User::find(3))->create();
        sleep(10);
        Notification::factory()->count(10)->for(User::find(3))->create();
        StepActivity::factory()->count(10)->for(User::find(3))->create();
    }
}
