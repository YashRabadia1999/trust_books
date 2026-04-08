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
        if (!Schema::hasTable('school_parents')) {

            Schema::create('school_parents', function (Blueprint $table) {
                $table->id();
                $table->integer('parent_id');
                $table->string('client')->nullable();
                $table->string('student')->nullable();
                $table->integer('user_id')->nullable();
                $table->string('name')->nullable();
                $table->string('gender')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('relation')->nullable();
                $table->longText('address')->nullable();
                $table->string('state')->nullable();
                $table->string('city')->nullable();
                $table->string('zip_code')->nullable();
                $table->string('contact')->nullable();
                $table->string('email')->nullable();
                $table->string('password')->nullable();
                $table->string('parent_image')->nullable();
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
        Schema::dropIfExists('school_parents');
    }
};
