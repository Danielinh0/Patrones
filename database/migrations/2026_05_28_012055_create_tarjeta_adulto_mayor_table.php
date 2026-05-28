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
        Schema::create('tarjeta_adulto_mayor', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tarjeta_adulto_mayor')->primary();
            $table->foreign('id_tarjeta_adulto_mayor')
                ->references('id_tarjeta')->on('tarjeta')->onDelete('cascade');
            
            $table->string('folio_inapam');    

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarjeta_adulto_mayor');
    }
};
