<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('exam_entries', function (Blueprint $table) {
            $table->unsignedBigInteger('academic_year_id')->nullable()->after('exam_id');
            $table->unsignedBigInteger('term_id')->nullable()->after('academic_year_id');

            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('set null');
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('exam_entries', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropForeign(['term_id']);
            $table->dropColumn(['academic_year_id', 'term_id']);
        });
    }
};
