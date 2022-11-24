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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('teacher_code')->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->boolean('is_super_admin')->default(false);
            $table->boolean('is_teacher')->default(false);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_super_admin');
            $table->dropColumn('is_teacher');
            $table->dropColumn('phone');
            $table->dropColumn('department_id');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');

        });
    }
};
