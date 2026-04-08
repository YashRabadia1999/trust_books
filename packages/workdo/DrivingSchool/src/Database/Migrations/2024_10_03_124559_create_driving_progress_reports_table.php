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
        if (!Schema::hasTable('driving_progress_reports'))
        {
            Schema::create('driving_progress_reports', function (Blueprint $table) {
                $table->id();
                $table->string('student_id')->nullable();
                $table->string('class_id')->nullable();
                $table->string('teacher_id')->nullable();
                $table->date('progress_date')->nullable();
                $table->text('skills_assessed')->nullable();
                $table->text('comments')->nullable();
                $table->string('rating')->nullable();
                $table->integer('workspace')->default(0);
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
        Schema::dropIfExists('driving_progress_reports');
    }
};
