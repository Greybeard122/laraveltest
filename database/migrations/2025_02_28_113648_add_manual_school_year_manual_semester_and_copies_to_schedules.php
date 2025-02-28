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
        $table->string('manual_school_year')->nullable()->after('school_year_id');
        $table->string('manual_semester')->nullable()->after('semester_id');
        $table->integer('copies')->default(1)->after('reason');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('schedules', function (Blueprint $table) {
        $table->dropColumn(['manual_school_year', 'manual_semester', 'copies']);
    });
}

};
