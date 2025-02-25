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
        $table->text('reason')->after('preferred_time');
        $table->string('school_year')->after('reason');
        $table->string('semester')->after('school_year');
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
