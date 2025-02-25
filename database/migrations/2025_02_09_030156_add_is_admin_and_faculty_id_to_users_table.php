<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'is_admin')) {
            $table->boolean('is_admin')->default(1);
        }
        if (!Schema::hasColumn('users', 'faculty_id')) {
            $table->string('faculty_id')->unique();
        }
    });
}


    public function down()
    {
        /* Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'faculty_id']); // Rollback changes if needed
        }); */
    }
};
