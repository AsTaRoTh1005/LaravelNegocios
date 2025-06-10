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
        Schema::create('negocios', function (Blueprint $table) {
            $table->id('negocio_id');
            $table->unsignedBigInteger('id_usuario'); 
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('imagen')->nullable();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id_usuario')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negocios');
    }
};
