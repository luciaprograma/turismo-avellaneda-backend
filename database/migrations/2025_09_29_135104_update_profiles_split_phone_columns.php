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
        Schema::table('profiles', function (Blueprint $table) {
            // Eliminar los campos actuales
            $table->dropColumn(['phone_number', 'emergency_contact']);

            // Nuevos campos para teléfono principal
            $table->string('phone_country_code', 5)->after('address');
            $table->string('phone_area_code', 10)->after('phone_country_code');
            $table->string('phone_number', 15)->after('phone_area_code');

            // Nuevos campos para teléfono de emergencia
            $table->string('emergency_country_code', 5)->after('phone_number');
            $table->string('emergency_area_code', 10)->after('emergency_country_code');
            $table->string('emergency_number', 15)->after('emergency_area_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Borrar los campos nuevos
            $table->dropColumn([
                'phone_country_code',
                'phone_area_code',
                'phone_number',
                'emergency_country_code',
                'emergency_area_code',
                'emergency_number',
            ]);

            // Restaurar los originales
            $table->string('phone_number')->nullable();
            $table->string('emergency_contact')->nullable();
        });
    }
};
