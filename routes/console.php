<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('queue:work')->everyTwoSeconds();
Schedule::command('app:convert-steps-to-coin')->dailyAt('23:00');
