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
            if (!Schema::hasColumn('schedules', 'file_name')) {
                $table->string('file_name')->nullable();
            }
            if (!Schema::hasColumn('schedules', 'preferred_time')) {
                $table->enum('preferred_time', ['AM', 'PM'])->default('AM');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'preferred_time')) {
                $table->dropColumn('preferred_time');
            }
        });
    }
};
