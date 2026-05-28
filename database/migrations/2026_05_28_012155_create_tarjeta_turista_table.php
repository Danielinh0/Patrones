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
        Schema::create('tarjeta_turista', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tarjeta_turista')->primary();
            $table->foreign('id_tarjeta_turista')
                ->references('id_tarjeta')->on('tarjeta')->onDelete('cascade');
            
            $table->date('fecha_vigencia_turista');    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarjeta_turista');
    }
};
