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
        if (!Schema::hasTable('pet_package_service')) {
            Schema::create('pet_package_service', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('package_id');
                $table->unsignedBigInteger('service_id');
                $table->decimal('service_price', 15, 2)->default(0.00);
                $table->timestamps();

                $table->foreign('package_id')->references('id')->on('pet_grooming_packages')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('service_id')->references('id')->on('pet_services')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_package_service');
    }
};
