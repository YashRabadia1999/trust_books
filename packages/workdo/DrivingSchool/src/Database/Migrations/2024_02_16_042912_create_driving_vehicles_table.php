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
        if(!Schema::hasTable('driving_vehicles'))
        {
            Schema::create('driving_vehicles', function (Blueprint $table) {
                $table->id();
                $table->text('name');
                $table->integer('teacher_id');
                $table->text('location');
                $table->text('chassis_number');
                $table->integer('odometer');
                $table->year('model_year');
                $table->text('engine_transmission');
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
        Schema::dropIfExists('driving_vehicles');
    }
};
