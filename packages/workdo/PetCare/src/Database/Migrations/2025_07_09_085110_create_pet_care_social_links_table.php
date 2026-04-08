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
        if (!Schema::hasTable('pet_care_social_links')) {
            Schema::create('pet_care_social_links', function (Blueprint $table) {
                $table->id();
                $table->string('social_media_name')->nullable();
                $table->string('social_media_icon')->nullable();
                $table->string('social_media_link')->nullable();
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
        Schema::dropIfExists('pet_care_social_links');
    }
};
