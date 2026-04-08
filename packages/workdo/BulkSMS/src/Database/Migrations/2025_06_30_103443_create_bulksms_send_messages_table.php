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
        if (!Schema::hasTable('bulksms_send_messages')) {
            Schema::create('bulksms_send_messages', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('group_id')->nullable();
                $table->longText('mobile_no')->nullable();
                $table->longText('sms')->nullable();
                $table->string('status')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulksms_send_messages');
    }
};
