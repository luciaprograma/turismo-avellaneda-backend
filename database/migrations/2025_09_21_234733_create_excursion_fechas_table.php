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
    Schema::create('excursion_fechas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('excursion_id')->constrained('excursiones')->onDelete('cascade'); // FK a excursiones
        $table->date('fecha');
        $table->time('hora_inicio');
        $table->time('hora_fin');
        $table->integer('cupo_disponible')->nullable();
        $table->boolean('activo')->default(true);
        $table->timestamps();
        $table->softDeletes();
    });
}


    /**
     * Reverse the migrations.
     */
   public function down(): void
    {
        Schema::dropIfExists('excursion_fechas');
    }
};
