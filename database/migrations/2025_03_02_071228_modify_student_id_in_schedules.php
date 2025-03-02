<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Drop existing foreign key if it exists
            $table->dropForeign(['student_id']);

            // Ensure no NULL student_id before modifying the column
            DB::statement("UPDATE schedules SET student_id = 1 WHERE student_id IS NULL");

            // Modify student_id to be NOT NULL
            $table->unsignedBigInteger('student_id')->nullable(false)->change();

            // Re-add the foreign key constraint
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['student_id']);

            // Revert student_id to nullable
            $table->unsignedBigInteger('student_id')->nullable()->change();
        });
    }
};
