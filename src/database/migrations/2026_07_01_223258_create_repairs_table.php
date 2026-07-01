<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment');
            $table->string('tipo');
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_fin')->nullable();
            $table->text('motivo');
            $table->text('diagnostico')->nullable();
            $table->text('reparacion_realizada')->nullable();
            $table->text('refacciones')->nullable();
            $table->foreignId('responsable')->constrained('system_users');
            $table->text('observaciones')->nullable();
            $table->string('estado_resultante')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};