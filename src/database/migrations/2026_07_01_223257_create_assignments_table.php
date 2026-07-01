<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('equipment_id')->constrained('equipment');
            $table->string('jira_ticket')->nullable();
            $table->timestamp('fecha_asignacion');
            $table->timestamp('fecha_liberacion')->nullable();
            $table->foreignId('asignado_por')->constrained('system_users');
            $table->foreignId('liberado_por')->nullable()->constrained('system_users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};