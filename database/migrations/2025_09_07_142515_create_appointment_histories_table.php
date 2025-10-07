<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointment_histories', function (Blueprint $table) {
            $table->id();
            // Relación con la cita original
            $table->foreignId('appointment_id')
                ->nullable()
                ->constrained('appointments')
                ->onDelete('set null');
            $table->string('appointment_name', 80); // Nombre de la cita
            // Relación con la reserva creada
            $table->foreignId('reservation_id')
                ->nullable()
                ->constrained('reservations')
                ->onDelete('set null');
            $table->foreignId('user_id')->constrained('user')->onDelete('cascade'); // Paciente
            $table->string('doctor_name', 56); // Nombre del doctor
            $table->string('specialty', 50); // Especialidad médica
            $table->date('appointment_date');
            $table->time('appointment_time');            // Estado de la cita
            $table->enum('status', [
                'pending',            // Pendiente
                'assisted',           // Asistido
                'not_attendance',     // No asistido
                'cancelled_by_user',  // Cancelado por usuario
                'cancelled_by_admin',  // Cancelado por admin
                'deleted_by_admin'  // Eliminado por admin
            ])->default('pending');

            // Auditoría - quién realizó el cambio
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');            // Razón de cancelación (importante para analytics)
            $table->text('cancellation_reason')->nullable();
            // Timestamps
            $table->timestamps(); // Incluye created_at y updated_at
            $table->timestamp('cancelled_at')->nullable(); // Solo para cancelaciones
            // Índices para mejorar rendimiento en búsquedas
            $table->index('user_id');
            $table->index('appointment_id');
            $table->index(['user_id', 'appointment_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_histories');
    }
};
