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
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('student_id')->nullable();
        $table->unsignedBigInteger('file_id')->nullable()->change();
        $table->date('preferred_date');
        $table->enum('preferred_time', ['AM', 'PM'])->default('AM');
        $table->text('reason')->nullable();
        $table->string('school_year')->nullable();
        $table->string('semester')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
        $table->text('remarks')->nullable();
        $table->timestamp('approved_at')->nullable();
        $table->timestamps();

        $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
