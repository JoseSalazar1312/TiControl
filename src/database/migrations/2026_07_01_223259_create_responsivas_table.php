<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('responsivas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->unique()->constrained('assignments');
            $table->string('archivo_generado');
            $table->timestamp('fecha_generacion');
            $table->string('version_plantilla')->default('1.0');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responsivas');
    }
};