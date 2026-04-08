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
        if (!Schema::hasTable('book_issues')) {
            Schema::create('book_issues', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('book_id');
                $table->unsignedBigInteger('student_id');
                $table->date('issue_date');
                $table->date('return_date');
                $table->integer('workspace')->nullable();
                $table->integer('created_by');

                $table->foreign('student_id')->references('id')->on('school_students')->onDelete('cascade');
                $table->foreign('book_id')->references('id')->on('library_books')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_issues');
    }
};
