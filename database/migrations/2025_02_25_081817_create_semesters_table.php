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
        if (!Schema::hasTable('semesters')) { // ✅ Check if 'semesters' table exists
            Schema::create('semesters', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Example: "1st Semester"
                $table->unsignedBigInteger('school_year_id');
                $table->foreign('school_year_id')->references('id')->on('school_years')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // ✅ Check if 'schedules' exists before modifying it
        if (Schema::hasTable('schedules')) {
            Schema::table('schedules', function (Blueprint $table) {
                if (Schema::hasColumn('schedules', 'semester_id')) {
                    $table->dropForeign(['semester_id']);
                }
            });
        }

        // ✅ Drop 'semesters' only if it exists
        if (Schema::hasTable('semesters')) {
            Schema::dropIfExists('semesters');
        }
    }
};
