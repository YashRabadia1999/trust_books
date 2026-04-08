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
        if (!Schema::hasTable('pet_service_included_features')) {
            Schema::create('pet_service_included_features', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('service_id');
                $table->string('feature_icon')->nullable();
                $table->string('feature_name')->nullable();
                $table->text('feature_description')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->timestamps();

                $table->foreign('service_id')->references('id')->on('pet_services')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_service_included_features');
    }
};
