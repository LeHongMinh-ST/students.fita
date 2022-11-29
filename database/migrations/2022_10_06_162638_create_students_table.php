<?php

use App\Enums\Student\StudentGender;
use App\Enums\Student\StudentRole;
use App\Enums\Student\StudentSocialPolicyObject;
use App\Enums\Student\StudentStatus;
use App\Enums\Student\StudentTrainingType;
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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $this->extractedStudentColumn($table);
        });

        Schema::create('student_temps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id')->nullable();
            $table->tinyInteger('status_approved')->nullable();
            $table->bigInteger('student_approved')->nullable();
            $table->bigInteger('teacher_approved')->nullable();
            $table->bigInteger('admin_approved')->nullable();
            $this->extractedStudentColumn($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
        Schema::dropIfExists('student_temps');
    }

    /**
     * @param Blueprint $table
     * @return void
     */
    public function extractedStudentColumn(Blueprint $table): void
    {
        $table->string('user_name')->nullable();
        $table->string('full_name')->nullable();
        $table->string('password')->nullable();
        $table->string('student_code')->nullable();
        $table->tinyInteger('gender')->default(StudentGender::Male)->nullable();
        $table->text('permanent_residence')->nullable();
        $table->bigInteger('class_id')->nullable();
        $table->string('major')->nullable();
        $table->string('dob')->nullable();
        $table->string('pob')->nullable();
        $table->text('address')->nullable();
        $table->text('countryside')->nullable();
        $table->tinyInteger('training_type')->default(StudentTrainingType::FormalUniversity)->nullable();
        $table->string('school_year')->nullable();
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        $table->string('thumbnail')->nullable();
        $table->string('citizen_identification')->nullable();
        $table->string('nationality')->nullable();
        $table->integer('ethnic')->nullable();
        $table->integer('religion')->nullable();
        $table->integer('academic_level')->nullable();
        $table->tinyInteger('social_policy_object')->default(StudentSocialPolicyObject::None)->nullable();
        $table->text('note')->nullable();
        $table->tinyInteger('status')->default(StudentStatus::Studying)->nullable();
        $table->tinyInteger('role')->default(StudentRole::Normal)->nullable();
        $table->timestamps();
    }
};
