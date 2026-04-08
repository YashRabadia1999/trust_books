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
        if(!Schema::hasTable('petty_cash_expenses'))
        {
            Schema::create('petty_cash_expenses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('request_id')->nullable();
                $table->enum('type', ['reimbursement', 'pettycash']);
                $table->decimal('amount', 15, 2);
                $table->text('remarks')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->unsignedBigInteger('workspace');
                $table->unsignedBigInteger('created_by');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cash_expenses');
    }
};
