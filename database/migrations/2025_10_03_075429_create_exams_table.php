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
    Schema::create('exams', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('academic_year_id');
        $table->unsignedBigInteger('term_id');
        $table->unsignedBigInteger('classroom_id');
        $table->string('exam_name')->nullable();
        $table->unsignedBigInteger('created_by')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
