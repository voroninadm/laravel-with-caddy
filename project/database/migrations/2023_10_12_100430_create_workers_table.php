<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('job')->nullable();
            $table->string('category')->nullable();
            $table->tinyInteger('is_test_passed')->nullable();
            $table->date('last_test_date')->nullable();
            $table->string('last_test_result')->nullable();
            $table->boolean('is_blocked');
            $table->string('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
