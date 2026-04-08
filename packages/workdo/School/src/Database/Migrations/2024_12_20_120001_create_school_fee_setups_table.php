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
        if (!Schema::hasTable('school_fee_setups')) {
            Schema::create('school_fee_setups', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Fee structure name
                $table->unsignedBigInteger('academic_year_id');
                $table->unsignedBigInteger('term_id');
                $table->unsignedBigInteger('class_id');
                $table->decimal('total_amount', 15, 2)->default(0.00);
                $table->decimal('discount_amount', 15, 2)->default(0.00);
                $table->boolean('auto_invoice')->default(true); // Auto generate invoices
                $table->boolean('send_email')->default(true); // Send email notifications
                $table->boolean('send_sms')->default(false); // Send SMS notifications
                $table->enum('status', ['Active', 'Inactive'])->default('Active');
                $table->date('due_date');
                $table->json('items'); // JSON of selected items/services with quantities
                $table->text('description')->nullable();
                $table->integer('workspace');
                $table->integer('created_by');
                $table->timestamps();

                $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
                $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
                $table->foreign('class_id')->references('id')->on('classrooms')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_fee_setups');
    }
};
