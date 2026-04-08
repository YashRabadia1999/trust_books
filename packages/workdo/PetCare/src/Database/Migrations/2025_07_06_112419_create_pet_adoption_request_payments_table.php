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
        if (!Schema::hasTable('pet_adoption_request_payments')) {
            Schema::create('pet_adoption_request_payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('adoption_request_id')->nullable();
                $table->string('payer_name')->nullable();
                $table->date('payment_date')->nullable();
                $table->decimal('amount', 15, 2)->default('0.00');
                $table->text('description')->nullable();
                $table->enum('payment_method', ['cash', 'bank_transfer', 'cheque', 'other']);
                $table->string('payment_status')->nullable();
                $table->string('reference')->nullable();
                $table->string('payment_receipt')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->timestamps();

                $table->foreign('adoption_request_id')->references('id')->on('pet_adoption_requests')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_adoption_request_payments');
    }
};
