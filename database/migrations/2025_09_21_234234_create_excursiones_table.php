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
        Schema::create('excursiones', function (Blueprint $table) {
            $table->id(); // id BIGINT PK
            $table->string('nombre', 100); // obligatorio
            $table->text('descripcion')->nullable(); // opcional
            $table->json('iconos'); // emojis o iconos
            $table->string('lugar', 100)->nullable(); // opcional
            $table->decimal('precio', 8, 2)->nullable(); // opcional
            $table->integer('cupo_maximo')->nullable(); // opcional
            $table->boolean('activo')->default(true); // obligatorio, default true
            $table->string('url_proveedor', 255)->nullable(); // opcional, enlace externo
            $table->timestamps(); // created_at y updated_at
            $table->softDeletes(); // deleted_at
        });
    }

        /**
         * Reverse the migrations.
         */
        public function down(): void
    {
        Schema::dropIfExists('excursiones');
    }

    };
