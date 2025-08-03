<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ReservationController;

class VerificarAsistencias extends Command
{
    protected $signature = 'asistencias:verificar';
    protected $description = 'Verifica y actualiza las asistencias de los appointments pasados';

    public function handle()
    {
        app(ReservationController::class)->verificarAsistenciasAutomaticamente();
        $this->info('Asistencias verificadas correctamente.');
    }
}
