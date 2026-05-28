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
        Schema::create('transaccion', function (Blueprint $table) {
            $table->id('id_transaccion');
            $table->integer('monto');
            $table->date('fecha');
            $table->string('tipo');

            $table->unsignedBigInteger('id_tarjeta');
            $table->foreign('id_tarjeta')->references('id_tarjeta')->on('tarjeta');
            
            $table->unsignedBigInteger('id_ruta')->nullable();
            $table->foreign('id_ruta')->references('id_ruta')->on('tipo_ruta');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaccion');
    }
};
