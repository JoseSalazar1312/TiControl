<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_user_id')->constrained('system_users');
            $table->string('accion');
            $table->string('tabla');
            $table->unsignedBigInteger('registro_id');
            $table->text('detalles')->nullable();
            $table->timestamp('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }
};