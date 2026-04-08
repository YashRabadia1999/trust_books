<?php
// Run this migration
// filepath: database/migrations/2025_10_01_000001_add_workspace_to_academic_years.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('academic_years', function (Blueprint $table) {
            $table->unsignedBigInteger('workspace')->nullable()->after('id');
        });
    }
    public function down()
    {
        Schema::table('academic_years', function (Blueprint $table) {
            $table->dropColumn('workspace');
        });
    }
};