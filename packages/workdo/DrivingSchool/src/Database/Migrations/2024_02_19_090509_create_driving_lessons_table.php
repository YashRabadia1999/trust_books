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
        if(!Schema::hasTable('driving_lessons'))
        {
            Schema::create('driving_lessons', function (Blueprint $table) {
                $table->id();
                $table->integer('driving_lessons_id');
                $table->integer('class_id')->nullable();
                $table->string('name');
                $table->datetime('start_date_time')->nullable();
                $table->datetime('end_date_time')->nullable();
                $table->string('student_id')->nullable();
                $table->string('absent_student_id')->nullable();
                $table->string('present_student_id')->nullable();
                $table->string('status')->default(0);
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
        Schema::dropIfExists('driving_lessons');
    }
};
