<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment');
            $table->string('tipo_cambio');
            $table->string('componente');
            $table->string('valor_anterior')->nullable();
            $table->string('valor_nuevo')->nullable();
            $table->timestamp('fecha');
            $table->foreignId('registrado_por')->constrained('system_users');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_history');
    }
};