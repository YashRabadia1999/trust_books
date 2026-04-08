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
        if (!Schema::hasTable('school_notices')) {
            Schema::create('school_notices', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->unsignedBigInteger('posted_by');
                $table->date('date_posted');
                $table->enum('target_audience', ['staff', 'parent' , 'student'])->default('student');
                $table->longText('description')->nullable();
                $table->integer('workspace');
                $table->integer('created_by');
                $table->foreign('posted_by')->references('id')->on('employees')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_notices');
    }
};
