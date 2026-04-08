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
        if(!Schema::hasTable('petty_cashes'))
        {
            Schema::create('petty_cashes', function (Blueprint $table) {
                $table->id();
                $table->date('date');
                $table->decimal('opening_balance', 10, 2);
                $table->decimal('added_amount', 10, 2)->default(0);
                $table->decimal('total_balance', 10, 2)->default(0);
                $table->decimal('total_expense', 10, 2)->default(0);
                $table->decimal('closing_balance', 10, 2);
                $table->text('remarks')->nullable();
                $table->integer('workspace');
                $table->integer('created_by');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petty_cash');
    }
};
