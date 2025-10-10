<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // En tu migración, agrega constraints únicos
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('available_appointment_id')->constrained('available_appointments')->onDelete('cascade');
            $table->foreignId('specialty_id')->constrained('specialties')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
//            $table->boolean('status')->nullable();
            $table->enum('status', [
                'pending',            // Pendiente
                'assisted',           // Asistido
                'not_attendance'     // No asistido
            ])->default('pending');
            $table->timestamps();

            // EVITA que un usuario reserve el mismo turno múltiples veces
            $table->unique(['user_id', 'available_appointment_id']);
            $table->index(['user_id', 'status']);
            $table->index('specialty_id');
            // EVITA que un turno sea reservado por múltiples usuarios (si es 1 spot)
            // $table->unique(['available_appointment_id']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
