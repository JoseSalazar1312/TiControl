<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('marca');
            $table->string('modelo');
            $table->string('serie')->unique();
            $table->string('color')->nullable();
            $table->string('procesador')->nullable();
            $table->string('ram')->nullable();
            $table->string('almacenamiento')->nullable();
            $table->string('gpu')->nullable();
            $table->string('estado_operativo')->default('disponible');
            $table->string('estado_fisico')->default('bueno');
            $table->foreignId('site_id')->constrained('sites');
            $table->date('fecha_compra')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};