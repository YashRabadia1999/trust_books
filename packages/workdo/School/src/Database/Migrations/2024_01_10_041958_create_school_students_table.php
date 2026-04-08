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
        if (!Schema::hasTable('school_students')) {

            Schema::create('school_students', function (Blueprint $table) {
                $table->id();
                $table->integer('student_id');
                $table->integer('user_id')->nullable();
                $table->string('parent_id')->nullable();
                $table->string('class_name')->nullable();
                $table->string('grade_name')->nullable();
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
                $table->string('client')->nullable();
                $table->string('attachments')->nullable();
                $table->integer('roll_number')->default(0);
                $table->string('name')->nullable();
                $table->string('student_gender')->nullable();
                $table->date('std_date_of_birth')->nullable();
                $table->longText('std_address')->nullable();
                $table->string('std_state')->nullable();
                $table->string('std_city')->nullable();
                $table->string('std_zip_code')->nullable();
                $table->string('contact')->nullable();
                $table->string('email')->nullable();
                $table->string('password')->nullable();
                $table->string('student_image')->nullable();
                $table->integer('workspace')->default(0);
                $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('school_students');
    }
};
