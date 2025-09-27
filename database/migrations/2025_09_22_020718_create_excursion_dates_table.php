<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('excursion_dates', function (Blueprint $table) {
            $table->bigIncrements('id');              // Primary Key
            $table->foreignId('excursion_id')        // Foreign Key
                  ->constrained('excursions')
                  ->onDelete('cascade');
            $table->date('date');                     // Required
            $table->time('time');                     // Required
            $table->integer('capacity')->nullable();  // Optional
            $table->timestamps();                     // created_at / updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excursion_dates');
    }
};
