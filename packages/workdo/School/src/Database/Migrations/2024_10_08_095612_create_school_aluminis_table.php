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
        if (!Schema::hasTable('school_aluminis')) {
            Schema::create('school_aluminis', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->integer('batch_year');
                $table->string('current_position');
                $table->string('contact');
                $table->string('email');
                $table->integer('workspace');
                $table->integer('created_by');

                $table->foreign('student_id')->references('id')->on('school_students')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_aluminis');
    }
};
