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
        Schema::table('student_temps', function (Blueprint $table) {
            if (!Schema::hasColumn('student_temps','rejectable_type')) {
                $table->string('rejectable_type')->nullable();
            }

            if (!Schema::hasColumn('student_temps','rejectable_id')) {
                $table->bigInteger('rejectable_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_temps', function (Blueprint $table) {
            //
        });
    }
};
