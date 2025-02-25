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
        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->string('year')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Drop foreign key only if 'semesters' table exists
        if (Schema::hasTable('semesters')) {
            Schema::table('semesters', function (Blueprint $table) {
                $table->dropForeign(['school_year_id']);
            });
        }

        Schema::dropIfExists('school_years');
    }
};
