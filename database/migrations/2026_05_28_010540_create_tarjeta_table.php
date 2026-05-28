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
        Schema::create('tarjeta', function (Blueprint $table) {
            $table->id('id_tarjeta');
            
            $table->integer('saldo_actual')->default(0);
            $table->enum('tipo', ['GENERAL', 'ESTUDIANTE', 'ADULTO_MAYOR', 'TURISTA']);
            $table->string('estado');

            $table->unsignedBigInteger('id_titular');
            $table->foreign('id_titular')->references('id_titular')->on('titular');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarjeta');
    }
};
