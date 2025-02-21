<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('schedules', function (Blueprint $table) {
        // Ensure the column is defined as a foreign key
        $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('schedules', function (Blueprint $table) {
        // Drop the foreign key constraint
        $table->dropForeign(['student_id']);
    });
}

};
