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
        if (!Schema::hasTable('school_attendances')) {
            Schema::create('school_attendances', function (Blueprint $table) {
                $table->id();
                $table->integer('student_id');
                $table->date('date');
                $table->string('status');
                $table->time('clock_in')->nullable();
                $table->time('clock_out')->nullable();
                $table->time('total_rest')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by');
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
        Schema::dropIfExists('school_attendances');
    }
};
