<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*Artisan::command('inspire', function () {*/
/*    $this->comment(Inspiring::quote());*/
/*})->purpose('Display an inspiring quote')->hourly();*/

Schedule::command('queue:work --stop-when-empty')->everyTwoSeconds();
Schedule::command('app:convert-steps-to-coin')->dailyAt('23:00');
