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
        if (!Schema::hasTable('school_fee_payments')) {
            Schema::create('school_fee_payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('fee_id');
                $table->decimal('amount', 15, 2);
                $table->enum('payment_method', ['Cash', 'Bank Transfer', 'Check', 'Credit Card', 'Online Payment', 'Other'])->default('Cash');
                $table->date('payment_date');
                $table->string('reference_number')->nullable();
                $table->text('notes')->nullable();
                $table->integer('workspace');
                $table->integer('created_by');
                $table->timestamps();

                $table->foreign('fee_id')->references('id')->on('school_fees')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_fee_payments');
    }
};
