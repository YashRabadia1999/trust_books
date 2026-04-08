<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        if (!Schema::hasTable('driving_invoices_items')) {
            Schema::create('driving_invoices_items', function (Blueprint $table) {
                $table->id();
                $table->integer('invoice_id')->default(0);
                $table->integer('driving_class_id')->nullable();
                $table->integer('quantity')->default(0);
                $table->integer('fees')->nullable();
                $table->integer('workspace')->default(0);
                $table->integer('created_by')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driving_invoices_items');
    }
};
