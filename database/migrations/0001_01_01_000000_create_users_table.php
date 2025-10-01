<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('surname', 40);
            $table->string('idNumber', 8)->unique();
            $table->date('birthdate');
            $table->string('gender');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('address', 100);
            $table->string('phone', 15)->unique();
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedSmallInteger('faults')->default(0); // 0 a 65,535
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            // Índices para optimizar búsquedas comunes
            $table->index(['surname', 'name']); // Búsquedas por nombre
            $table->index('status'); // Usuarios activos/inactivos
            $table->index('birthdate'); // Búsquedas por edad
            $table->index(['country_id', 'state_id', 'city_id']); // Búsquedas geográficas
            $table->index('created_at'); // Reportes por fecha

            $table->index('country_id');
            $table->index('state_id');
            $table->index('city_id');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
   public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
