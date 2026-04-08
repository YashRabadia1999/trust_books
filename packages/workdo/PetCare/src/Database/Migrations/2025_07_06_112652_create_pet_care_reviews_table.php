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
        if (!Schema::hasTable('pet_care_reviews')) {
            Schema::create('pet_care_reviews', function (Blueprint $table) {
                $table->id();
                $table->string('reviewer_name')->nullable();
                $table->string('reviewer_email')->nullable();
                $table->integer('rating')->default(0);
                $table->enum('display_status', ['on', 'off']);
                $table->enum('review_status', ['pending', 'approved', 'rejected']);
                $table->text('review')->nullable();
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
        Schema::dropIfExists('pet_care_reviews');
    }
};
