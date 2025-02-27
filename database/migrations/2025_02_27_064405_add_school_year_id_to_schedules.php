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
        if (!Schema::hasColumn('schedules', 'school_year_id')) {
            $table->unsignedBigInteger('school_year_id')->nullable()->after('id');
            $table->foreign('school_year_id')->references('id')->on('school_years')->onDelete('set null');
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
