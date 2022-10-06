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
        Schema::create('learning_outcome_details', function (Blueprint $table) {
            $table->id();
            $table->integer('order')->nullable();
            $table->string('subject_code')->nullable();
            $table->string('subject_name')->nullable();
            $table->integer('credits')->nullable();
            $table->integer('percent_test')->nullable();
            $table->integer('percent_exam')->nullable();
            $table->float('diligence_point')->nullable();
            $table->float('progress_point')->nullable();
            $table->float('exam_point')->nullable();
            $table->float('total_point_number')->nullable();
            $table->string('total_point_string')->nullable();
            $table->bigInteger('learning_outcome_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('learning_outcome_details');
    }
};
