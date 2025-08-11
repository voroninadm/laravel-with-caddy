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
        Schema::create('flexoforms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('machine_id');
            $table->foreign('machine_id')->references('id')->on('machines');
            $table->unsignedBigInteger('print_machine_id');
            $table->foreign('print_machine_id')->references('id')->on('machines');
            $table->date('work_date');
            $table->integer('work_shift');

            $table->unsignedBigInteger('master_id')->nullable();
            $table->foreign('master_id')->references('id')->on('workers');
            $table->unsignedBigInteger('collector_id')->nullable();
            $table->foreign('collector_id')->references('id')->on('workers');

            $table->string('tkn');
            $table->dateTime('work_start')->nullable();
            $table->dateTime('work_finish')->nullable();
            $table->float('work_fact')->nullable();
            $table->string('customer');
            $table->string('print_title');

            $table->string('design_number')->nullable();
            $table->integer('streams')->nullable();
            $table->integer('sleeves_diameter')->nullable();
            $table->integer('sleeves_fact')->nullable();
            $table->integer('paints_count')->nullable();
            $table->string('new_forms')->nullable();
            $table->integer('aniloks')->nullable();

            $table->json('paints_and_sticky');

            $table->string('unwind_direction', 20);
            $table->boolean('is_streams_distance_ok');
            $table->boolean('is_mounting_dots_ok');

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flexoforms');
    }
};
