<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ReservaController;

class VerificarAsistencias extends Command
{
    protected $signature = 'asistencias:verificar';
    protected $description = 'Verifica y actualiza las asistencias de los turnos pasados';

    public function handle()
    {
        app(ReservaController::class)->verificarAsistenciasAutomaticamente();
        $this->info('Asistencias verificadas correctamente.');
    }
}
