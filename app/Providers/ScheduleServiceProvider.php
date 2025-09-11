<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\ArchiveOldAppointments;

class ScheduleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(Schedule $schedule): void
    {
        $schedule->command('assists:verificar')->everyTenMinutes();// Verificacion cada 10 minutos
        //$schedule->command('assists:verificar')->dailyAt('00:00'); // Cambié a diario para menor carga
        $schedule->job(new ArchiveOldAppointments())->monthlyOn(1, '00:00'); // Archivado mensual
    }
}

