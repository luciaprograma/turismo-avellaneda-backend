<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('excursion_payments', function (Blueprint $table) {
            $table->bigIncrements('id');

            // FK hacia excursion_registrations
            $table->foreignId('registration_id')
                  ->constrained('excursion_registrations')
                  ->onDelete('cascade');

            // URL del comprobante en S3
            $table->string('receipt_url', 255);

            // Nombre original del archivo subido
            $table->string('receipt_filename', 100)->nullable();

            // Status: pending, verified, rejected
            $table->string('status', 20)->default('pending');

            // Fecha/hora de subida del comprobante
            $table->timestamp('uploaded_at')->nullable();

            // Timestamps automáticos de Laravel
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excursion_payments');
    }
};
