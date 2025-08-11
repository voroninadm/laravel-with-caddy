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
        Schema::create('laminations', function (Blueprint $table) {
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
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('workers');
            $table->unsignedBigInteger('helper_id')->nullable();
            $table->foreign('helper_id')->references('id')->on('workers');

            $table->string('tkn')->nullable();
            $table->float('work_plan')->nullable();
            $table->dateTime('work_start')->nullable();
            $table->dateTime('work_finish')->nullable();
            $table->float('work_fact')->nullable();
            $table->string('customer')->nullable();
            $table->string('print_title')->nullable();
            $table->float('circulation', 15, 2)->nullable();

            $table->unsignedBigInteger('mat1_id')->nullable();
            $table->foreign('mat1_id')->references('id')->on('materials');
            $table->unsignedBigInteger('mat2_id')->nullable();
            $table->foreign('mat2_id')->references('id')->on('materials');
            $table->unsignedBigInteger('mat3_id')->nullable();
            $table->foreign('mat3_id')->references('id')->on('materials');
            $table->float('width1', 15, 2)->nullable();
            $table->float('width2', 15, 2)->nullable();
            $table->float('width3', 15, 2)->nullable();
            $table->float('thickness1', 15, 2)->nullable();
            $table->float('thickness2', 15, 2)->nullable();
            $table->float('thickness3', 15, 2)->nullable();
            $table->float('mat1count_plan', 15, 2)->nullable();
            $table->float('mat2count_plan', 15, 2)->nullable();
            $table->float('mat3count_plan', 15, 2)->nullable();
            $table->float('mat1count', 15, 2)->nullable();
            $table->float('mat2count', 15, 2)->nullable();
            $table->float('mat3count', 15, 2)->nullable();

            $table->float('workout_mass', 15, 2)->nullable();
            $table->float('workout_length', 15, 2)->nullable();
            $table->float('workout_m2', 15, 2)->nullable();
            $table->float('otk_mass', 15, 2)->nullable();
            $table->float('waste_plan', 15, 2)->nullable();
            $table->float('waste_print', 15, 2)->nullable();
            $table->float('waste_lam', 15, 2)->nullable();
            $table->float('waste_sum', 15, 2)->nullable();

            $table->float('prepare')->nullable();
            $table->float('prepare_shirt')->nullable();
            $table->float('flushing')->nullable();
            $table->float('tech_clean')->nullable();
            $table->float('change_glue')->nullable();
            $table->float('electro')->nullable();
            $table->float('mechanical')->nullable();
            $table->float('tech_service')->nullable();
            $table->float('calibrating')->nullable();
            $table->float('no_human')->nullable();
            $table->float('no_work')->nullable();
            $table->float('no_raw')->nullable();
            $table->float('remain_perv')->nullable();
            $table->float('remain_sec')->nullable();
            $table->float('diff_circulation', 15, 2)->nullable();
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
        Schema::dropIfExists('laminations');
    }
};
