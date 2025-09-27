<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('excursions', function (Blueprint $table) {
            $table->bigIncrements('id');        // PK
            $table->string('name');             // Obligatorio
            $table->text('description')->nullable(); // Opcional
            $table->decimal('price', 10, 2)->nullable(); // Opcional
            $table->string('location')->nullable();      // Opcional
            $table->boolean('status');          // Obligatorio: activa/inactiva
            $table->timestamps();               // created_at / updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excursions');
    }
};
