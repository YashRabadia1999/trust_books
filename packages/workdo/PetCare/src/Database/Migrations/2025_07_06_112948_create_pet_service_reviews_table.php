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
        if (!Schema::hasTable('pet_service_reviews')) {
            Schema::create('pet_service_reviews', function (Blueprint $table) {
                $table->id();
                $table->string('reviewer_name')->nullable();
                $table->string('reviewer_email')->nullable();
                $table->unsignedBigInteger('service_id');
                $table->integer('rating')->default(0);
                $table->enum('display_status', ['on', 'off']);
                $table->enum('review_status', ['pending', 'approved', 'rejected']);
                $table->text('review')->nullable();
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
        Schema::dropIfExists('pet_service_reviews');
    }
};
