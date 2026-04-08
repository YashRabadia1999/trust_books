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
        if (!Schema::hasTable('pet_appointments')) {
            Schema::create('pet_appointments', function (Blueprint $table) {
                $table->id();
                $table->string('appointment_number')->nullable();
                $table->unsignedBigInteger('pet_owner_id');
                $table->unsignedBigInteger('pet_id');
                $table->unsignedBigInteger('assigned_staff_id')->nullable();
                $table->date('appointment_date')->nullable();
                $table->string('appointment_time')->nullable();
                $table->enum('appointment_status', ['pending', 'approved', 'rejected', 'completed']);
                $table->decimal('total_service_package_amount', 15, 2)->default(0.00);
                $table->text('notes')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->timestamps();

                $table->foreign('pet_owner_id')->references('id')->on('pet_owners')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade')->onUpdate('cascade');
                $table->foreign('assigned_staff_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_appointments');
    }
};
