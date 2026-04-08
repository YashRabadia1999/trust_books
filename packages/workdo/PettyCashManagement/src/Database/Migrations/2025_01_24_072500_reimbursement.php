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
        if(!Schema::hasTable('reimbursements'))
        {
            Schema::create('reimbursements', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('category_id');
                $table->decimal('amount', 10, 2);
                $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
                $table->text('description')->nullable();
                $table->timestamp('request_date')->useCurrent();
                $table->timestamp('approved_date')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->string('receipt_path')->nullable();
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
        Schema::dropIfExists('reimbursements');
    }
};
