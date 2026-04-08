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
        if (!Schema::hasTable('school_health_records')) {
            Schema::create('school_health_records', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->date('checkup_date');
                $table->string('doctor_name');
                $table->longText('diagnosis')->nullable();
                $table->longText('treatment')->nullable();
                $table->enum('vaccination_status', ['Completed' , 'Pending'])->default('Pending');
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
        Schema::dropIfExists('school_health_records');
    }
};
