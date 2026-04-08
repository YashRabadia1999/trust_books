<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('school_students', function (Blueprint $table) {
        $table->unsignedBigInteger('class_id')->nullable();  // Adjust type if necessary
    });
}

public function down()
{
    Schema::table('school_students', function (Blueprint $table) {
        $table->dropColumn('class_id');
    });
}

};
