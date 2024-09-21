<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('queue:work --stop-when-empty --queue=high,default')->everyMinute()->withoutOverlapping()->sendOutputTo(getcwd()."/queue.log");
Schedule::command('queue:work --stop-when-empty --queue=stepsCount')->everyFiveMinutes()->withoutOverlapping()->sendOutputTo(getcwd()."/queue.log");
Schedule::command('app:convert-steps-to-coin --stop-when-empty')->dailyAt('23:00')->withoutOverlapping()->sendOutputTo(getcwd()."/converter.log");
