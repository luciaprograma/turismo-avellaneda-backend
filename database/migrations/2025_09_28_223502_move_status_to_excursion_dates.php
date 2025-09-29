<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('excursion_dates', function (Blueprint $table) {
            // Agregamos la columna status con default true
            $table->boolean('status')->default(true)->after('price');
        });

        Schema::table('excursions', function (Blueprint $table) {
            // Eliminamos la columna status de excursions
            $table->dropColumn('status');
        });
    }


    /**
     * Reverse the migrations.
     */
        public function down()
    {
        Schema::table('excursion_dates', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('excursions', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('description');
        });
    }
};
