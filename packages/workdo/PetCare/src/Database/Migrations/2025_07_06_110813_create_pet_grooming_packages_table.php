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
        if (!Schema::hasTable('pet_grooming_packages')) {
            Schema::create('pet_grooming_packages', function (Blueprint $table) {
                $table->id();
                $table->string('package_name')->nullable();
                $table->string('package_icon')->nullable();
                $table->text('package_features')->nullable();
                $table->text('description')->nullable();
                $table->decimal('total_package_amount', 15, 2)->default(0.00);
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_grooming_packages');
    }
};
