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
        if (!Schema::hasTable('school_transport_fees')) {
            Schema::create('school_transport_fees', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('route_id');
                $table->float('amount',15,2)->default('0.00');
                $table->enum('status', ['Paid', 'Unpaid'])->default('Unpaid');
                $table->integer('workspace');
                $table->integer('created_by');

                $table->foreign('student_id')->references('id')->on('school_students')->onDelete('cascade');
                $table->foreign('route_id')->references('id')->on('school_transport_routes')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_transport_fees');
    }
};
