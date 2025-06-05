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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nit', 11)->unique()->comment('NIT de la empresa (formato colombiano: 123456789-0)');
            $table->string('nombre', 255)->comment('Nombre de la empresa');
            $table->text('direccion')->comment('Direccion fisica de la empresa');
            $table->string('telefono', 10)->comment('Número de teléfono de contacto (10 dígitos)');
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo')->comment('Estado actual de la empresa');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
