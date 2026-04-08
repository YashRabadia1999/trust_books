<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('school_grades')) {
            Schema::table('school_grades', function (Blueprint $table) {
                if (!Schema::hasColumn('school_grades', 'min_marks')) {
                    $table->unsignedSmallInteger('min_marks')->default(0)->after('grade_name');
                }
                if (!Schema::hasColumn('school_grades', 'max_marks')) {
                    $table->unsignedSmallInteger('max_marks')->default(0)->after('min_marks');
                }
                if (!Schema::hasColumn('school_grades', 'remarks')) {
                    $table->string('remarks')->nullable()->after('max_marks');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('school_grades')) {
            Schema::table('school_grades', function (Blueprint $table) {
                if (Schema::hasColumn('school_grades', 'remarks')) {
                    $table->dropColumn('remarks');
                }
                if (Schema::hasColumn('school_grades', 'max_marks')) {
                    $table->dropColumn('max_marks');
                }
                if (Schema::hasColumn('school_grades', 'min_marks')) {
                    $table->dropColumn('min_marks');
                }
            });
        }
    }
};


