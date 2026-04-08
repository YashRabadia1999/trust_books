<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('admissions')) {
            Schema::create('admissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('admission_id');
                $table->date('date')->nullable();
                $table->string('student_name');
                $table->date('date_of_birth')->nullable();
                $table->string('gender')->nullable();
                $table->string('blood_group')->nullable();
                $table->longText('address')->nullable();
                $table->string('state')->nullable();
                $table->string('city')->nullable();
                $table->string('zip_code')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('password')->nullable();
                $table->string('previous_school')->nullable();
                $table->string('student_image')->nullable();
                $table->string('medical_history')->nullable();
                $table->string('father_name')->nullable();
                $table->string('father_number')->nullable();
                $table->string('father_occupation')->nullable();
                $table->string('father_email')->nullable();
                $table->string('father_password')->nullable();
                $table->longText('father_address')->nullable();
                $table->string('father_image')->nullable();
                $table->string('mother_name')->nullable();
                $table->string('mother_number')->nullable();
                $table->string('mother_occupation')->nullable();
                $table->string('mother_email')->nullable();
                $table->string('mother_password')->nullable();
                $table->longText('mother_address')->nullable();
                $table->string('mother_image')->nullable();
                $table->longText('guardian')->nullable();
                $table->string('leaving_certificate')->nullable();
                $table->string('marksheet')->nullable();
                $table->string('birth_certificate')->nullable();
                $table->string('address_proof')->nullable();
                $table->string('bonafide_certificate')->nullable();
                $table->integer('converted_student_id')->default(0);
                $table->integer('created_by')->default(0);
                $table->integer('workspace')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admissions');
    }
};
