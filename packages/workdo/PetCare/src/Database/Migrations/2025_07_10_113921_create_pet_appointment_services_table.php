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
        if (!Schema::hasTable('pet_appointment_services')) {
            Schema::create('pet_appointment_services', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('appointment_id');
                $table->unsignedBigInteger('service_id');
                $table->timestamps();

                $table->foreign('appointment_id')->references('id')->on('pet_appointments')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('service_id')->references('id')->on('pet_services')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_appointment_services');
    }
};
