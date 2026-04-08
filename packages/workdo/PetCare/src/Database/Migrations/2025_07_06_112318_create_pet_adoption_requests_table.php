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
        if (!Schema::hasTable('pet_adoption_requests')) {
            Schema::create('pet_adoption_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('pet_adoption_id');
                $table->string('adoption_request_number')->nullable();
                $table->string('adopter_name')->nullable();
                $table->string('email')->nullable();
                $table->string('contact_number')->nullable();
                $table->text('address')->nullable();
                $table->text('reason_for_adoption')->nullable();
                $table->enum('request_status', ['pending', 'approved', 'rejected', 'completed']);
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->timestamps();

                $table->foreign('pet_adoption_id')->references('id')->on('pet_adoptions')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_adoption_requests');
    }
};
