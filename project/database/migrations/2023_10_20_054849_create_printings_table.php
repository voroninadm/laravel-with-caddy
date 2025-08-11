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
        Schema::create('printings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('machine_id');
            $table->foreign('machine_id')->references('id')->on('machines');
            $table->date('work_date');
            $table->integer('work_shift');

            $table->unsignedBigInteger('master_id')->nullable();
            $table->foreign('master_id')->references('id')->on('workers');
            $table->unsignedBigInteger('operator1_id')->nullable();
            $table->foreign('operator1_id')->references('id')->on('workers');
            $table->unsignedBigInteger('operator2_id')->nullable();
            $table->foreign('operator2_id')->references('id')->on('workers');
            $table->unsignedBigInteger('operator3_id')->nullable();
            $table->foreign('operator3_id')->references('id')->on('workers');
            $table->unsignedBigInteger('operator_helper')->nullable();
            $table->foreign('operator_helper')->references('id')->on('workers');

            $table->string('tkn')->nullable();
            $table->float('work_plan' , 15, 2)->nullable();
            $table->dateTime('work_start')->nullable();
            $table->dateTime('work_finish')->nullable();
            $table->float('work_fact')->nullable();
            $table->string('customer')->nullable();
            $table->string('print_title')->nullable();
            $table->float('circulation', 15, 2)->nullable();
            $table->unsignedBigInteger('mat1_id')->nullable();
            $table->foreign('mat1_id')->references('id')->on('materials');
            $table->float('mat1count_plan', 15, 2)->nullable();
            $table->float('mat1count', 15, 2)->nullable();
            $table->float('width1', 15, 2)->nullable();
            $table->float('thickness1', 15, 2)->nullable();
            $table->integer('colors')->nullable();
            $table->float('workout_mass', 15, 2)->nullable();
            $table->float('workout_length', 15, 2)->nullable();
            $table->float('workout_m2', 15, 2)->nullable();
            $table->float('otk_mass', 15, 2)->nullable();
            $table->float('waste_plan', 15, 2)->nullable();
            $table->float('waste_print', 15, 2)->nullable();
            $table->float('waste_raw', 15, 2)->nullable();
            $table->float('waste_sum', 15, 2)->nullable();
            $table->float('prepare_mass')->nullable();
            $table->float('prepare_hours')->nullable();
            $table->float('correction_PN')->nullable();
            $table->float('correction_CMYK')->nullable();
            $table->float('electro')->nullable();
            $table->float('mechanical')->nullable();
            $table->float('aniloks')->nullable();
            $table->float('clean_machine')->nullable();
            $table->float('form_glue')->nullable();
            $table->float('rakel')->nullable();
            $table->float('clean_dry')->nullable();
            $table->float('clean_val')->nullable();
            $table->float('speed')->nullable();
            $table->float('no_human')->nullable();
            $table->float('no_work')->nullable();
            $table->float('no_raw')->nullable();
            $table->float('diff_circulation', 15, 2)->nullable();
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
        Schema::dropIfExists('printings');
    }
};
