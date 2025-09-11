<?php

namespace App\Jobs;

use App\Models\AppointmentHistory;
use App\Models\AppointmentHistoryArchive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
//php artisan make:job ArchiveOldAppointments
class ArchiveOldAppointments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $threshold = now()->subYears(2)->startOfDay();

        DB::transaction(function () use ($threshold) {
            $oldAppointments = AppointmentHistory::where('appointment_date', '<', $threshold)->get();

            foreach ($oldAppointments as $appointment) {
                AppointmentHistoryArchive::create($appointment->toArray());
                $appointment->delete();
            }
        });
    }
}
