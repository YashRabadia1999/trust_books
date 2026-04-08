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
            if (!Schema::hasTable('school_homework')) {

                Schema::create('school_homework', function (Blueprint $table) {
                    $table->id();
                    $table->string('title')->nullable();
                    $table->string('classroom')->nullable();
                    $table->string('subject')->nullable();
                    $table->date('submission_date')->nullable();
                    $table->string('homework')->nullable();
                    $table->longText('content')->nullable();
                    $table->string('student_homework')->nullable();
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
            Schema::dropIfExists('school_homework');
        }
    };
