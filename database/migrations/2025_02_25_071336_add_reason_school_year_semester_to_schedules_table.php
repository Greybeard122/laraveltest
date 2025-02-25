<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'preferred_time')) {
                $table->enum('preferred_time', ['AM', 'PM'])->default('AM')->after('preferred_date');
            }
            if (!Schema::hasColumn('schedules', 'reason')) {
                $table->text('reason')->after('preferred_time');
            }
            if (!Schema::hasColumn('schedules', 'school_year')) {
                $table->string('school_year')->after('reason');
            }
            if (!Schema::hasColumn('schedules', 'semester')) {
                $table->string('semester')->after('school_year');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'reason')) {
                $table->dropColumn('reason');
            }
            if (Schema::hasColumn('schedules', 'school_year')) {
                $table->dropColumn('school_year');
            }
            if (Schema::hasColumn('schedules', 'semester')) {
                $table->dropColumn('semester');
            }
            if (Schema::hasColumn('schedules', 'preferred_time')) {
                $table->dropColumn('preferred_time');
            }
        });
    }
};
