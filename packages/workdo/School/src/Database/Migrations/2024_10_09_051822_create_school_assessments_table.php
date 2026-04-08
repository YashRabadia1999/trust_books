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
        if (!Schema::hasTable('school_assessments')) {
            Schema::create('school_assessments', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->unsignedBigInteger('class_id');
                $table->unsignedBigInteger('subject_id');
                $table->date('due_date');
                $table->longText('description')->nullable();
                $table->integer('workspace');
                $table->integer('created_by');
                $table->foreign('class_id')->references('id')->on('classrooms')->onDelete('cascade');
                $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_assessments');
    }
};
