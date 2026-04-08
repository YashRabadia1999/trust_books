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
        if (!Schema::hasTable('school_generated_invoices')) {
            Schema::create('school_generated_invoices', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('fee_setup_id');
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('invoice_id');
                $table->decimal('amount', 15, 2)->default(0.00);
                $table->enum('status', ['Generated', 'Sent', 'Paid', 'Overdue'])->default('Generated');
                $table->boolean('email_sent')->default(false);
                $table->boolean('sms_sent')->default(false);
                $table->date('due_date');
                $table->integer('workspace');
                $table->integer('created_by');
                $table->timestamps();

                $table->foreign('fee_setup_id')->references('id')->on('school_fee_setups')->onDelete('cascade');
                $table->foreign('student_id')->references('id')->on('school_students')->onDelete('cascade');
                $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_generated_invoices');
    }
};
