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
        if (!Schema::hasTable('driving_test_hubs'))
        {
            Schema::create('driving_test_hubs', function (Blueprint $table) {
                $table->id();
                $table->string('student_id')->nullable();
                $table->string('teacher_id')->nullable();
                $table->string('test_type_id')->nullable();
                $table->date('test_date')->nullable();
                $table->integer('test_score')->nullable();
                $table->enum('test_result', ['pass', 'fail'])->default('pass')->nullable();
                $table->text('remarks')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driving_test_hubs');
    }
};
