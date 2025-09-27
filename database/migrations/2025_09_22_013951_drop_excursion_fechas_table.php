<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('excursion_fechas'); // elimina tabla dependiente primero
    }

    public function down(): void
    {
        // opcional: recrear la tabla si querés rollback
        
    }
};
