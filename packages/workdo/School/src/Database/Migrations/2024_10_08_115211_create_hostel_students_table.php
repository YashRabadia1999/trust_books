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
        if (!Schema::hasTable('hostel_students')) {
            Schema::create('hostel_students', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('hostel_id');
                $table->unsignedBigInteger('room_id');
                $table->integer('workspace');
                $table->integer('created_by');
                $table->foreign('student_id')->references('id')->on('school_students')->onDelete('cascade');
                $table->foreign('hostel_id')->references('id')->on('school_hostels')->onDelete('cascade');
                $table->foreign('room_id')->references('id')->on('school_rooms')->onDelete('cascade');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostel_students');
    }
};
