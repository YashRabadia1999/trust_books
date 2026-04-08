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
        if (!Schema::hasTable('school_assessment_results')) {
            Schema::create('school_assessment_results', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('assessment_id');
                $table->unsignedBigInteger('student_id');
                $table->float('marks_obtained');
                $table->string('grade')->nullable();
                $table->integer('workspace');
                $table->integer('created_by');
                $table->foreign('assessment_id')->references('id')->on('school_assessments')->onDelete('cascade');
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
        Schema::dropIfExists('school_assessment_results');
    }
};
