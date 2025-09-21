<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('dni', 15)->unique();
            $table->date('birth_date');
            $table->string('address', 255);
            $table->string('phone_number', 20);
            $table->string('emergency_contact', 20);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
