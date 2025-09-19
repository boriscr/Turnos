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
        Schema::create(Doctor::TABLE, function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('surname');
            $table->string('idNumber')->unique();
            $table->string('email');
            $table->string('phone');
            $table->foreignId('specialty_id')->nullable()->constrained('specialties')->onDelete('cascade');
            $table->string('licenseNumber')->nullable()->unique();
            $table->string('role');
            $table->boolean('status')->default(true); // Valor por defecto true
            $table->foreignId('create_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('update_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->index('specialty_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Doctor::TABLE);
    }
};
