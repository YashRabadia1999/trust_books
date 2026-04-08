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
        if (!Schema::hasTable('driving_invoices')) {
            Schema::create('driving_invoices', function (Blueprint $table) {
                $table->id();
                $table->integer('invoice_id');
                $table->integer('student_id');
                $table->date('issue_date');
                $table->date('due_date');
                $table->integer('status')->default('0');
                $table->integer('workspace')->default(0);
                $table->integer('created_by')->default('0');
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
        Schema::dropIfExists('driving_invoices');
    }
};