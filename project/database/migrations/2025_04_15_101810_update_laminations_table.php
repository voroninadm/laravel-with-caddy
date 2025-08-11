<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Миграция, актуализирующая простои ламинации с Энкостом
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laminations', function (Blueprint $table) {
            $table->dropColumn('prepare_shirt');
            $table->dropColumn('flushing');
            $table->dropColumn('calibrating');
            $table->dropColumn('mat3count_plan');

            $table->renameColumn('mat3_id', 'covering_id');
            $table->renameColumn('width3', 'covering_width');
            $table->renameColumn('thickness3', 'covering_thickness');
            $table->renameColumn('mat3count', 'covering_count');
            $table->renameColumn('prepare', 'prepare_hours');

            $table->float('work_productive', 2)->after('work_fact')->nullable();
            $table->float('short_downtime',2)->after('no_raw')->nullable();
            $table->float('total_downtime',2)->after('short_downtime')->nullable();
            $table->float('no_reason_downtime',2)->after('total_downtime')->nullable();
            $table->boolean('is_complete')->after('is_idle')->nullable();
        });
    }
};
