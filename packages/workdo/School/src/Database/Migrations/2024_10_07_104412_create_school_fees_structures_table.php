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
        if (!Schema::hasTable('school_fees_structures')) {
            Schema::create('school_fees_structures', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('class_id');
                $table->string('fee_type');
                $table->float('amount' ,15,2)->default('0.00');
                $table->date('due_date');
                $table->integer('workspace')->nullable();
                $table->integer('created_by');
                
                $table->foreign('class_id')->references('id')->on('classrooms')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_fees_structures');
    }
};
