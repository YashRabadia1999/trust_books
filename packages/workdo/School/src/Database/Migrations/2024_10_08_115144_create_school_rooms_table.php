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
        if (!Schema::hasTable('school_rooms')) {
            Schema::create('school_rooms', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('hostel_id');
                $table->integer('room_number');
                $table->integer('capacity');
                $table->integer('workspace');
                $table->integer('created_by');
                $table->foreign('hostel_id')->references('id')->on('school_hostels')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_rooms');
    }
};
