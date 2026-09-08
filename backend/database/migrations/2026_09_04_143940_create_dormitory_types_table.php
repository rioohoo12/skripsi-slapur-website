<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dormitory_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', ['sederhana', 'standar']);
            $table->enum('gender', ['L', 'P']);
            $table->integer('capacity_per_room');
            $table->integer('room_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dormitory_types');
    }
};
