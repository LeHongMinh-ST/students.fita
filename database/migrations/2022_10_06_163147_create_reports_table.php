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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id')->nullable();
            $table->string('title')->nullable();
            $table->tinyInteger('subjects')->nullable();
            $table->text('content')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('status_approve')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('approved_by')->nullable();
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
        Schema::dropIfExists('reports');
    }
};
