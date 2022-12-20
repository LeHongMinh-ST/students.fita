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
            if(!Schema::hasColumn('student_temps', 'reject_note')) {
                $table->text('reject_note')->nullable();
            }

            if(!Schema::hasColumn('student_temps', 'change_column')) {
                $table->text('change_column')->nullable();
            }
            if(!Schema::hasColumn('student_temps', 'email_edu')) {
                $table->string('email_edu')->nullable();
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
