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
            $table->string('name');
            $table->string('surname');
            $table->string('idNumber');
            $table->string('email');
            $table->string('phone');
            $table->foreignId('specialty_id')->nullable()->constrained('specialties')->onDelete('cascade');
            $table->string('licenseNumber')->nullable();
            $table->string('role');
            $table->boolean('status')->default(true); // Valor por defecto true
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
