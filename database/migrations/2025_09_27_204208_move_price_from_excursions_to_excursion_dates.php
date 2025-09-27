<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Quitar columna price de excursions
        Schema::table('excursions', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        // Agregar columna price a excursion_dates
        Schema::table('excursion_dates', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        // Volver atrás: agregar de nuevo price a excursions
        Schema::table('excursions', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable();
        });

        // Quitar columna price de excursion_dates
        Schema::table('excursion_dates', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
