<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('excursiones'); // elimina la tabla vieja
    }

    public function down(): void
    {
        // opcional: podrías recrearla tal como estaba antes si querés rollback
    }
};
