<?php
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());

})->purpose('Display an inspiring quote');

//Schedule::command('weather:check')->hourly();
Schedule::command('weather:check')->everyMinute();

Schedule::command('weather:capture')->everyMinute(); //pour tester lancer la commande(dans un autre terminal,le server doit tourner) php artisan schedule:work