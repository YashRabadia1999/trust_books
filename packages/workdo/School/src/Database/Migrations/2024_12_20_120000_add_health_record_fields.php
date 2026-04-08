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
        if (Schema::hasTable('school_health_records')) {
            Schema::table('school_health_records', function (Blueprint $table) {
                // Add new health record fields
                $table->longText('allergies')->nullable()->after('treatment');
                $table->longText('chronic_conditions')->nullable()->after('allergies');
                
                // Make doctor_name and vaccination_status optional
                $table->string('doctor_name')->nullable()->change();
                $table->enum('vaccination_status', ['Completed', 'Pending'])->nullable()->change();
            });
        }
        
        // Add health fields to school_students table
        if (Schema::hasTable('school_students')) {
            Schema::table('school_students', function (Blueprint $table) {
                $table->string('blood_group')->nullable()->after('mother_image');
                $table->longText('allergies')->nullable()->after('blood_group');
                $table->longText('chronic_conditions')->nullable()->after('allergies');
                $table->string('emergency_contact')->nullable()->after('chronic_conditions');
                $table->date('last_checkup')->nullable()->after('emergency_contact');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('school_health_records')) {
            Schema::table('school_health_records', function (Blueprint $table) {
                $table->dropColumn(['allergies', 'chronic_conditions']);
                $table->string('doctor_name')->nullable(false)->change();
                $table->enum('vaccination_status', ['Completed', 'Pending'])->nullable(false)->change();
            });
        }
        
        if (Schema::hasTable('school_students')) {
            Schema::table('school_students', function (Blueprint $table) {
                $table->dropColumn(['blood_group', 'allergies', 'chronic_conditions', 'emergency_contact', 'last_checkup']);
            });
        }
    }
};
