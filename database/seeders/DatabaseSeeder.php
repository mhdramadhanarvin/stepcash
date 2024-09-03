<?php

namespace Database\Seeders;

use App\Models\CoinRate;
use App\Models\Partner;
use App\Models\Reward;
use App\Models\User;
use App\Policies\RewardPolicy;
use Google\Service\Dfareporting\UserRolePermission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);
        $user = User::factory()->has(Partner::factory()->hasRewards(100, ['status' => 'publish']))->create([
            'name' => 'Mitra',
            'email' => 'partner@example.com',
        ]);
        CoinRate::create([
            'coin' => 3,
            'rupiah' => 30,
            'step' => 1000,
            'coin_balance' => 100000000
        ]);
        Artisan::call('shield:generate', ['--all' => true]);
        Artisan::call('shield:super-admin', ['--user' => 1]);
        $role = Role::create([
            'guard_name' => 'web',
            'name' => 'partner'
        ]);
        $role->givePermissionTo(Permission::where('name', 'like', '%reward')->pluck('name'));
        $role->givePermissionTo(Permission::where('name', 'like', '%partner')->pluck('name'));
        $role->givePermissionTo(Permission::where('name', 'like', '%reward::claim')->pluck('name'));
        $user->assignRole('partner');
    }
}
