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
        if (!Schema::hasTable('pet_adoptions')) {
            Schema::create('pet_adoptions', function (Blueprint $table) {
                $table->id();
                $table->string('adoption_number')->nullable();
                $table->string('pet_name')->nullable();
                $table->string('species')->nullable();
                $table->string('breed')->nullable();
                $table->decimal('adoption_amount', 15, 2)->default(0.00);
                $table->date('date_of_birth')->nullable();
                $table->enum('gender', ['Male', 'Female']);
                $table->string('availability')->nullable();
                $table->string('health_status')->nullable();
                $table->text('classification_tags')->nullable();
                $table->string('pet_image')->nullable();
                $table->text('description');
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_adoptions');
    }
};
