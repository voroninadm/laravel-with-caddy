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
        Schema::create('weldings', function (Blueprint $table) {
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
            $table->unsignedBigInteger('operator4_id')->nullable();
            $table->foreign('operator4_id')->references('id')->on('workers');
            $table->unsignedBigInteger('operator5_id')->nullable();
            $table->foreign('operator5_id')->references('id')->on('workers');
            $table->unsignedBigInteger('student1_id')->nullable();
            $table->foreign('student1_id')->references('id')->on('workers');
            $table->unsignedBigInteger('student2_id')->nullable();
            $table->foreign('student2_id')->references('id')->on('workers');
            $table->unsignedBigInteger('acceptor_id')->nullable();
            $table->foreign('acceptor_id')->references('id')->on('workers');
            $table->string('tkn')->nullable();
            $table->float('work_plan')->nullable();
            $table->dateTime('work_start')->nullable();
            $table->dateTime('work_finish')->nullable();
            $table->float('work_fact')->nullable();
            $table->string('customer')->nullable();
            $table->string('print_title')->nullable();
            $table->float('circulation', 15, 2)->nullable();

            $table->integer('product_type')->nullable();
            $table->unsignedBigInteger('mat1_id')->nullable();
            $table->foreign('mat1_id')->references('id')->on('materials');
            $table->unsignedBigInteger('mat2_id')->nullable();
            $table->foreign('mat2_id')->references('id')->on('materials');
            $table->unsignedBigInteger('mat3_id')->nullable();
            $table->foreign('mat3_id')->references('id')->on('materials');

            $table->float('product_width', 15, 2)->nullable();
            $table->float('thickness1')->nullable();
            $table->float('thickness2')->nullable();
            $table->float('thickness3')->nullable();
            $table->float('bottom', 15, 2)->nullable();
            $table->float('length', 15, 2)->nullable();
            $table->float('count_plan', 15, 2)->nullable();
            $table->float('count', 15, 2)->nullable();
            $table->integer('bottom_type')->nullable();

            $table->integer('is_pocket')->nullable();
            $table->integer('is_handle')->nullable();
            $table->integer('is_edge')->nullable();
            $table->integer('is_perforation')->nullable();
            $table->integer('is_ziplock')->nullable();

            $table->float('workout_count', 15, 2)->nullable();
            $table->float('workout_otk', 15, 2)->nullable();


            $table->float('waste_plan', 15, 2)->nullable();
            $table->float('waste_print', 15, 2)->nullable();
            $table->float('waste_edge', 15, 2)->nullable();
            $table->float('waste_welding', 15, 2)->nullable();
            $table->float('waste_sum', 15, 2)->nullable();

            $table->integer('box_size_id')->nullable();

            $table->float('electro')->nullable();
            $table->float('mechanical')->nullable();
            $table->float('speed')->nullable();
            $table->float('reconfig')->nullable();
            $table->float('calibrating')->nullable();
            $table->float('change_teflon')->nullable();
            $table->float('tech_service')->nullable();
            $table->float('no_human')->nullable();
            $table->float('no_work')->nullable();
            $table->float('no_raw')->nullable();
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
        Schema::dropIfExists('weldings');
    }
};
