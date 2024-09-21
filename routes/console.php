<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('queue:work --stop-when-empty')->everySecond()->runInBackground()->withoutOverlapping()->sendOutputTo(getcwd()."/queue.log");
Schedule::command('app:convert-steps-to-coin --stop-when-empty')->dailyAt('23:00')->runInBackground()->withoutOverlapping()->sendOutputTo(getcwd()."/converter.log");
