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
            $table->string('name', 40);
            $table->string('surname', 40);
            $table->string('idNumber', 8)->unique();
            $table->string('email', 100);
            $table->string('phone', 15);
            $table->foreignId('specialty_id')->nullable()->constrained('specialties')->onDelete('cascade');
            $table->string('licenseNumber', 60)->nullable()->unique();
            $table->string('role');
            $table->boolean('status')->default(true); // Valor por defecto true
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
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
