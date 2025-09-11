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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('address');
            $table->foreignId('specialty_id')->constrained('specialties')->onDelete('cascade'); // OK
            $table->foreignId('doctor_id')->constrained(Doctor::TABLE)->onDelete('cascade'); // OK
            $table->string('shift');
            $table->integer('number_of_reservations');
            $table->time('start_time');
            $table->time('end_time');
            $table->json('available_time_slots')->nullable();
            $table->json('available_dates');
            $table->boolean('status')->default(true);
            // Modificado: sin onDelete('cascade') para users
            $table->foreignId('create_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('update_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
