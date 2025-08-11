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
        Schema::create('extrusions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('machine_id');
            $table->foreign('machine_id')->references('id')->on('machines');
            $table->date('work_date');
            $table->integer('work_shift');

            $table->unsignedBigInteger('master_id')->nullable();
            $table->foreign('master_id')->references('id')->on('workers');
            $table->unsignedBigInteger('machinist1_id')->nullable();
            $table->foreign('machinist1_id')->references('id')->on('workers');
            $table->unsignedBigInteger('machinist2_id')->nullable();
            $table->foreign('machinist2_id')->references('id')->on('workers');
            $table->unsignedBigInteger('machinist3_id')->nullable();
            $table->foreign('machinist3_id')->references('id')->on('workers');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('workers');

            $table->string('tkn')->nullable();
            $table->string('part_number')->nullable();
            $table->float('work_plan')->nullable();
            $table->dateTime('work_start')->nullable();
            $table->dateTime('work_finish')->nullable();
            $table->float('work_fact')->nullable();
            $table->string('customer')->nullable();
            $table->string('print_title')->nullable();
            $table->float('circulation_kg', 15, 2)->nullable();
            $table->float('circulation_length', 15, 2)->nullable();

            $table->integer('product_type')->nullable();
            $table->float('width', 15, 2)->nullable();
            $table->float('thickness', 15, 2)->nullable();
            $table->float('activation_pole', 15, 2)->nullable();
            $table->float('shrink_long', 15, 2)->nullable();
            $table->float('shrink_trans', 15, 2)->nullable();
            $table->float('edge', 15, 2)->nullable();
            $table->float('streams', 15, 2)->nullable();

            $table->float('roll_mass', 15, 2)->nullable();
            $table->float('roll_length', 15, 2)->nullable();
            $table->float('roll_diameter', 15, 2)->nullable();

            $table->float('workout_mass', 15, 2)->nullable();
            $table->float('workout_otk', 15, 2)->nullable();
            $table->float('workout_length', 15, 2)->nullable();
            $table->float('workout_m2', 15, 2)->nullable();

            $table->float('waste_plan_roll', 15, 2)->nullable();
            $table->float('waste_roll', 15, 2)->nullable();
            $table->float('waste_plan_edge', 15, 2)->nullable();
            $table->float('waste_edge', 15, 2)->nullable();
            $table->float('waste_plan_ingets', 15, 2)->nullable();
            $table->float('waste_ingets', 15, 2)->nullable();
            $table->float('waste_slice', 15, 2)->nullable();
            $table->float('waste_trans', 15, 2)->nullable();
            $table->float('waste_sum', 15, 2)->nullable();

            $table->float('electro')->nullable();
            $table->float('mechanical')->nullable();
            $table->float('electronics')->nullable();
            $table->float('tech_service')->nullable();
            $table->float('change_net')->nullable();
            $table->float('change_raw')->nullable();
            $table->float('change_order')->nullable();
            $table->float('clean_machine')->nullable();
            $table->float('no_human')->nullable();
            $table->float('no_work')->nullable();
            $table->float('no_raw')->nullable();
            $table->float('prepare_hours', 15, 2)->nullable();
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
        Schema::dropIfExists('extrusion');
    }
};
