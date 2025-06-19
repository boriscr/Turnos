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
        Schema::create(Medico::TABLE, function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('nombre');
            $table->string('apellido');
            $table->integer('dni');
            $table->string('email');
            $table->string('telefono');
            $table->foreignId('especialidad_id')->nullable()->constrained('especialidades')->onDelete('cascade');
            $table->string('matricula')->nullable();
            $table->string('role');
            $table->boolean('estado')->default(true); // Valor por defecto true
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Medico::TABLE);
    }
};
