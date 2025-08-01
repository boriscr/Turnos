<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Doctor;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('available_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turno_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained(Doctor::TABLE)->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora')->nullable(); // Si no se asignan horarios específicos, queda null
            $table->integer('cupos_disponibles')->default(1);
            $table->integer('cupos_reservados')->default(0);
            
            $table->unique(['turno_id', 'fecha', 'hora']);
            $table->index(['fecha', 'hora']);
            $table->timestamps();
        });
        
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('available_appointments');
    }
};
