<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('queue:work')->everyTwoSeconds();
Schedule::command('app:convert-steps-to-coin --stop-when-empty')->dailyAt('23:00');
