<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignment_entries', function (Blueprint $table) {
            $table->id();

            // If you already have classrooms & subjects table, you can store FK
            $table->foreignId('class_id')->constrained('classrooms')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');

            // Students JSON
            $table->json('students');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_entries');
    }
};
