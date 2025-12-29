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
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained(Doctor::TABLE)->onDelete('cascade');
            $table->foreignId('specialty_id')->constrained('specialties')->onDelete('cascade');
            $table->date('date');
            $table->time('time');
            $table->integer('available_spots')->default(1);
            $table->integer('reserved_spots')->default(0);

            //$table->unique(['appointment_id', 'date', 'time']);
            $table->index([
                'appointment_id',
                'date',
                'time',
                'available_spots'
            ]);

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
