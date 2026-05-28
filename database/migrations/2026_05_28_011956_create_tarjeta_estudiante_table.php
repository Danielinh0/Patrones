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
        Schema::create('tarjeta_estudiante', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tarjeta_estudiante')->primary();
            $table->foreign('id_tarjeta_estudiante')
                ->references('id_tarjeta')->on('tarjeta')->onDelete('cascade');
            $table->string('institucion_educativa');
            
            $table->date('vigencia_estudiante');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarjeta_estudiante');
    }
};
