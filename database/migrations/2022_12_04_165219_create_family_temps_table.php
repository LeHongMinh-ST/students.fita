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
        Schema::create('family_temps', function (Blueprint $table) {
            $table->id();
            $table->string('relationship')->nullable();
            $table->string('full_name')->nullable();
            $table->string('job')->nullable();
            $table->string('phone')->nullable();
            $table->bigInteger('student_temp_id')->nullable();
            $table->bigInteger('student_id')->nullable();
            $table->bigInteger('family_id')->nullable();
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
        Schema::dropIfExists('family_temps');
    }
};
