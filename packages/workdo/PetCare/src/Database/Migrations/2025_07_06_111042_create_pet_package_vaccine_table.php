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
        if (!Schema::hasTable('pet_package_vaccine')) {
            Schema::create('pet_package_vaccine', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('package_id');
                $table->unsignedBigInteger('vaccine_id');
                $table->decimal('vaccine_price', 15, 2)->default(0.00);
                $table->timestamps();

                $table->foreign('package_id')->references('id')->on('pet_grooming_packages')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('vaccine_id')->references('id')->on('pet_vaccines')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_package_vaccine');
    }
};
