<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('sms_credit_purchases')) {
            Schema::create('sms_credit_purchases', function (Blueprint $table) {
                $table->id();
                $table->integer('client_id')->default(0);
                $table->integer('workspace')->default(0);
                $table->integer('created_by')->default(0);
                $table->integer('credits_purchased')->default(0);
                $table->decimal('amount_paid', 10, 2)->default(0);
                $table->string('payment_method')->nullable();
                $table->string('transaction_id')->nullable();
                $table->string('mobile_number')->nullable();
                $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
                $table->text('payment_response')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('sms_credit_balances')) {
            Schema::create('sms_credit_balances', function (Blueprint $table) {
                $table->id();
                $table->integer('client_id')->default(0);
                $table->integer('workspace')->default(0);
                $table->integer('total_credits')->default(0);
                $table->integer('used_credits')->default(0);
                $table->integer('remaining_credits')->default(0);
                $table->timestamps();

                $table->unique(['client_id', 'workspace']);
            });
        }

        if (!Schema::hasTable('sms_credit_transactions')) {
            Schema::create('sms_credit_transactions', function (Blueprint $table) {
                $table->id();
                $table->integer('client_id')->default(0);
                $table->integer('workspace')->default(0);
                $table->integer('credits')->default(0);
                $table->enum('type', ['purchase', 'usage', 'refund', 'adjustment'])->default('usage');
                $table->text('description')->nullable();
                $table->string('reference')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_credit_purchases');
        Schema::dropIfExists('sms_credit_balances');
        Schema::dropIfExists('sms_credit_transactions');
    }
};
