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
        if (!Schema::hasTable('driving_licence_trackings'))
        {
            Schema::create('driving_licence_trackings', function (Blueprint $table) {
                $table->id();
                $table->string('student_id')->nullable();
                $table->string('licence_type_id')->nullable();
                $table->date('application_date')->nullable();
                $table->date('test_date')->nullable();
                $table->enum('test_result', ['pass', 'fail'])->default('pass')->nullable();
                $table->date('licence_issue_date')->nullable();
                $table->string('licence_number')->nullable();
                $table->date('licence_expiry_date')->nullable();
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
        Schema::dropIfExists('driving_licence_trackings');
    }
};
