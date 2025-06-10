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
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre', 100);
            $table->string('apellidoP', 100)->nullable();
            $table->string('apellidoM', 100)->nullable();
            $table->string('correo', 100)->unique();
            $table->string('password'); 
            $table->rememberToken();
            $table->string('google_id')->unique()->nullable();
            $table->string('github_id')->unique()->nullable();
            $table->string('avatar')->nullable(); 
            $table->enum('rol', ['Administrador', 'Cliente', 'Vendedor'])->default('Cliente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
