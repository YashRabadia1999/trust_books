<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('project_payments', 'type')) {
                $table->string('type')->default('payment')->after('amount');
            }
            if (!Schema::hasColumn('project_payments', 'task_id')) {
                $table->unsignedBigInteger('task_id')->nullable()->after('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_payments', function (Blueprint $table) {
            $table->dropColumn(['type', 'task_id']);
        });
    }
};
