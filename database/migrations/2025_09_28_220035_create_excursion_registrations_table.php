<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('excursion_registrations', function (Blueprint $table) {
            $table->bigIncrements('id');

            
            $table->foreignId('excursion_date_id')
                  ->constrained('excursion_dates')
                  ->onDelete('cascade');

            
            $table->foreignId('profile_id')
                  ->constrained('profiles')
                  ->onDelete('cascade');

            
            $table->string('status', 20)->default('registered');

            
            $table->timestamps();

           
            $table->unique(['excursion_date_id', 'profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excursion_registrations');
    }
};
