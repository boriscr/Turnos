<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Medico;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->foreignId('especialidad_id')->constrained('especialidades')->onDelete('cascade'); // OK
            $table->foreignId('medico_id')->constrained(Medico::TABLE)->onDelete('cascade'); // OK
            $table->string('turno')->nullable();
            $table->integer('cantidad_turnos');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->json('horarios_disponibles')->nullable();
            $table->json('fechas_disponibles');
            $table->boolean('isActive')->default(true);
            // Modificado: sin onDelete('cascade') para usuarios
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id_update')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
