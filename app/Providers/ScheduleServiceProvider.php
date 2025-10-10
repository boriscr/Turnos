<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\ArchiveOldAppointments;
use App\Jobs\VerificarStatusJob;
use Illuminate\Support\Facades\Log;

class ScheduleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(Schedule $schedule): void
    {
        // ==================== 🎯 CONFIGURACIÓN DESARROLLO ====================
        if (app()->environment('local', 'development')) {
            Log::info('Configurando scheduler para DESARROLLO');
            
            // ✅ DESARROLLO: Call directo cada minuto (rápido para testing)
            $schedule->call(function () {
                Log::info('🔧 [DEV] Ejecutando verificación automática');
                $controller = new \App\Http\Controllers\ReservationController();
                $result = $controller->checkStatusAutomatically();
                Log::info("🔧 [DEV] Verificación completada: {$result['reservas_actualizadas']} reservas actualizadas");
            })->everyMinute()->name('verificar-status-dev');
        }
        
        // ==================== 🚀 CONFIGURACIÓN PRODUCCIÓN ====================
        else {
            Log::info('Configurando scheduler para PRODUCCIÓN');
            
            // ✅ PRODUCCIÓN: Job optimizado una vez al día
            $schedule->job(new VerificarStatusJob())
                ->dailyAt('02:00') // 2:00 AM - horario de baja demanda
                ->name('verificar-status-prod')
                ->withoutOverlapping(30); // Evitar superposición (30 minutos)
        }

        // ==================== 📦 ARCHIVADO (AMBOS ENTORNOS) ====================
        $schedule->job(new ArchiveOldAppointments())
            ->monthlyOn(1, '03:00') // 3:00 AM del día 1 de cada mes
            ->name('archive-appointments-job');
    }
}