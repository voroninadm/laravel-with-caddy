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
        Schema::create('hiring_tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('about');
            $table->json('raw_test_results');
            $table->unsignedBigInteger('recommender_id')->nullable();
            $table->foreign('recommender_id')->references('id')->on('workers');
            $table->date('hired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hiring');
    }
};
