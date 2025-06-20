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
        Schema::create('turno_disponibles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turno_id')->constrained()->onDelete('cascade');
            $table->foreignId('medico_id')->constrained(Medico::TABLE)->onDelete('cascade');
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
        Schema::dropIfExists('turno_disponibles');
    }
};
