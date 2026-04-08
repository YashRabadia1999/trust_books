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
        if (!Schema::hasTable('pet_service_process_steps')) {
            Schema::create('pet_service_process_steps', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('service_id');
                $table->string('process_name')->nullable();
                $table->text('process_description')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->timestamps();

                $table->foreign('service_id')->references('id')->on('pet_services')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_service_process_steps');
    }
};
