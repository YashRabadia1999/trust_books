<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('exam_entries', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('exam_id');
    $table->unsignedBigInteger('student_id'); // FK -> school_students.id
    $table->decimal('marks_obtained', 5, 2)->default(0);
    $table->decimal('assignment_marks', 5, 2)->default(0);
    $table->decimal('total_marks', 5, 2)->default(0);
    $table->integer('position')->nullable();
    $table->text('remarks')->nullable();
    $table->timestamps();

    $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
    $table->foreign('student_id')->references('id')->on('school_students')->onDelete('cascade');
});

    }

    public function down(): void
    {
        Schema::dropIfExists('exam_entries');
    }
};
