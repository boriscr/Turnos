<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_histories_archive', function (Blueprint $table) {
            $table->id();
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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('doctor_name', 56);
            $table->string('specialty', 30);
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status', [
                'pending',
                'assisted',
                'not_attendance',
                'cancelled_by_user',
                'cancelled_by_admin',
                'deleted_by_admin'  // Eliminado por admin
            ])->default('pending');
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            $table->timestamp('cancelled_at')->nullable();
            // Índices
            $table->index('user_id');
            $table->index('appointment_id');
            $table->index(['user_id', 'appointment_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_histories_archive');
    }
};
