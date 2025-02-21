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
        // Modify the 'preferred_time' column to be an ENUM with 'AM' and 'PM'
        $table->enum('preferred_time', ['AM', 'PM'])->default('AM')->change();
    });
}

public function down()
{
    Schema::table('schedules', function (Blueprint $table) {
        // Rollback the change (if necessary)
        $table->string('preferred_time')->change(); // or the previous column type
    });
}

};
