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
        // Check if semester_id does not exist before adding it
        if (!Schema::hasColumn('schedules', 'semester_id')) {
            $table->unsignedBigInteger('semester_id')->after('school_year_id');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
        }
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            //
        });
    }
};
