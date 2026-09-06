<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('academic_schedules', function (Blueprint $table) {
            $table->id();
                        $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->string('subject_name');
            $table->string('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('academic_schedules');
    }
};