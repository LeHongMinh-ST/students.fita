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
        Schema::create('learning_outcomes', function (Blueprint $table) {
            $table->id();
            $table->integer('semester')->nullable();
            $table->integer('year_school_start')->nullable();
            $table->integer('year_school_end')->nullable();
            $table->float('semester_average_10')->nullable();
            $table->float('semester_average_4')->nullable();
            $table->float('cumulative_average_10')->nullable();
            $table->float('cumulative_average_4')->nullable();
            $table->integer('credits')->nullable();
            $table->integer('cumulative_credits')->nullable();
            $table->string('type_average')->nullable();
            $table->bigInteger('student_id')->nullable();
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
        Schema::dropIfExists('learning_outcomes');
    }
};
