<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('belarc_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->unique()->constrained('equipment');
            $table->string('archivo_pdf');
            $table->text('datos_json');
            $table->timestamp('fecha_analisis');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('belarc_reports');
    }
};