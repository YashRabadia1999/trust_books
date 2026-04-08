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
        if (!Schema::hasTable('pet_services')) {
            Schema::create('pet_services', function (Blueprint $table) {
                $table->id();
                $table->string('service_name');
                $table->string('service_icon')->nullable();
                $table->decimal('price', 15, 2)->default(0.00);
                $table->integer('duration')->default(0);
                $table->string('description');
                $table->string('service_image')->nullable();
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
        Schema::dropIfExists('pet_services');
    }
};
