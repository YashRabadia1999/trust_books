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
        if (!Schema::hasTable('school_transport_routes')) {
            Schema::create('school_transport_routes', function (Blueprint $table) {
                $table->id();
                $table->string('route_name');
                $table->string('start_location');
                $table->string('end_location');
                $table->unsignedBigInteger('bus_id');
                $table->integer('workspace');
                $table->integer('created_by');

                $table->foreign('bus_id')->references('id')->on('school_buses')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_transport_routes');
    }
};
