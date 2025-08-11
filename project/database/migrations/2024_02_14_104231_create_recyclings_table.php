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
        Schema::create('recyclings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('machine_id');
            $table->foreign('machine_id')->references('id')->on('machines');
            $table->date('work_date');
            $table->integer('work_shift');

            $table->unsignedBigInteger('master_id')->nullable();
            $table->foreign('master_id')->references('id')->on('workers');
            $table->unsignedBigInteger('machinist_id')->nullable();
            $table->foreign('machinist_id')->references('id')->on('workers');
            $table->unsignedBigInteger('helper_id')->nullable();
            $table->foreign('helper_id')->references('id')->on('workers');

            $table->dateTime('work_start')->nullable();
            $table->dateTime('work_finish')->nullable();
            $table->float('work_fact')->nullable();

            $table->integer('nomenclature')->nullable();
            $table->float('workout_mass', 15, 2)->nullable();

            $table->integer('ingots_type')->nullable();
            $table->float('waste_mass')->nullable();

            $table->float('prepare_hours')->nullable();
            $table->float('electro')->nullable();
            $table->float('mechanical')->nullable();
            $table->float('electronics')->nullable();
            $table->float('tech_service')->nullable();
            $table->float('change_net')->nullable();
            $table->float('clean_machine')->nullable();
            $table->float('tech_clean')->nullable();
            $table->float('change_knifes')->nullable();
            $table->float('change_cutter')->nullable();
            $table->float('no_human')->nullable();
            $table->float('no_work')->nullable();
            $table->float('no_raw')->nullable();
            $table->tinyInteger('prepare_ok')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_idle')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recycling');
    }
};
