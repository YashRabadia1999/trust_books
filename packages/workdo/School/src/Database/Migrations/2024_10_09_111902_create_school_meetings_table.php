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
        if (!Schema::hasTable('school_meetings')) {
            Schema::create('school_meetings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('parent_id');
                $table->unsignedBigInteger('teacher_id');
                $table->date('meeting_date');
                $table->string('agenda');
                $table->integer('workspace');
                $table->integer('created_by');
                $table->foreign('parent_id')->references('id')->on('school_parents')->onDelete('cascade');
                $table->foreign('teacher_id')->references('id')->on('employees')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_meetings');
    }
};
