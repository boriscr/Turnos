<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Log;

class VerificarStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Configuración para producción - alta escalabilidad
     */
    public $timeout = 3600; // 1 hora máximo de ejecución
    public $tries = 3; // 3 reintentos en caso de error
    public $backoff = [60, 300, 600]; // Espera progresiva entre reintentos

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('🚀 [PROD] Iniciando VerificarStatusJob - Procesamiento optimizado');
        
        try {
            $controller = new ReservationController();
            $result = $controller->checkStatusAutomatically();
            
            Log::info("🚀 [PROD] Job completado: {$result['reservas_actualizadas']} reservas actualizadas");
            
        } catch (\Exception $e) {
            Log::error("❌ [PROD] Error en VerificarStatusJob: " . $e->getMessage());
            $this->fail($e); // Marcar job como fallido
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("💥 [PROD] VerificarStatusJob FALLÓ definitivamente: " . $exception->getMessage());
        
        // 📧 Aquí puedes agregar notificaciones a administradores
        // Mail::to('admin@tu-app.com')->send(new JobFailedNotification($exception));
    }
}