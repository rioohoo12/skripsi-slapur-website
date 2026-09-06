<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dining_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner']);
            $table->string('menu_served')->nullable();
            $table->enum('status', ['eaten', 'missed'])->default('eaten');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dining_services');
    }
};