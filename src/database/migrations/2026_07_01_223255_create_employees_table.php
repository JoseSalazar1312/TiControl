<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('curp')->nullable();
            $table->string('rfc')->nullable();
            $table->string('puesto');
            $table->string('correo');
            $table->string('telefono')->nullable();
            $table->foreignId('site_id')->constrained('sites');
            $table->foreignId('jefe_directo_id')->nullable()->constrained('employees');
            $table->string('estado')->default('activo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};