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
        if(!Schema::hasTable('driving_classes'))
        {
            Schema::create('driving_classes', function (Blueprint $table) {
                $table->id();
                $table->integer('driving_class_id');
                $table->string('name');
                $table->datetime('start_date_time')->nullable();
                $table->datetime('end_date_time')->nullable();
                $table->string('vehicle_id')->nullable();
                $table->string('teacher_id')->nullable();
                $table->string('student_id')->nullable();
                $table->string('location')->nullable();
                $table->integer('fees')->nullable();
                // Enum column for schedule
                $table->enum('schedule', ['daily', 'weekly', 'monthly'])->default('daily')->nullable();
                // Additional columns for weekly and monthly schedules
                $table->json('weekly_days')->nullable();
                $table->json('monthly_date')->nullable();
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
        Schema::dropIfExists('driving_classes');
    }
};
