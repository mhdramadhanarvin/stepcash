<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('queue:work --stop-when-empty --queue=high,default')->everyMinute()->withoutOverlapping()->sendOutputTo(getcwd()."/queue.log");
Schedule::command('queue:work --stop-when-empty --queue=stepsCount')->everyFiveMinutes()->withoutOverlapping()->sendOutputTo(getcwd()."/queue.log");
Schedule::command('app:convert-steps-to-coin')->dailyAt('00:00')->withoutOverlapping()->sendOutputTo(getcwd()."/converter.log");
Schedule::command('app:cancel-exchange-expired')->hourly()->withoutOverlapping()->sendOutputTo(getcwd()."/converter.log");
Schedule::command('app:send-recommendation')->dailyAt('21:00')->withoutOverlapping()->sendOutputTo(getcwd()."/converter.log");
