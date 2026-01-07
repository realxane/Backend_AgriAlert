protected function schedule(Schedule $schedule)
{
    // On vérifie toutes les heures
    $schedule->command('weather:check')->hourly();
}