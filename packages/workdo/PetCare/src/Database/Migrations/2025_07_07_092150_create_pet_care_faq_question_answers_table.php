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
        if (!Schema::hasTable('pet_care_faq_question_answers')) {
            Schema::create('pet_care_faq_question_answers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('faq_id')->nullable();
                $table->string('question')->nullable();
                $table->longText('answer')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->timestamps();

                $table->foreign('faq_id')->references('id')->on('pet_care_faqs')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_care_faq_question_answers');
    }
};
