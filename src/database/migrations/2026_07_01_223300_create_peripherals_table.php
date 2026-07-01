<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peripherals', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('marca');
            $table->string('modelo')->nullable();
            $table->string('serie')->unique()->nullable();
            $table->string('estado_operativo')->default('disponible');
            $table->foreignId('site_id')->constrained('sites');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peripherals');
    }
};