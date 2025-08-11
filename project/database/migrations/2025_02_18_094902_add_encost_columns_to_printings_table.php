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
        Schema::table('printings', function (Blueprint $table) {
            $table->float('work_productive', 2)->after('work_fact')->nullable();
            $table->float('tech_service', 2)->after('mechanical')->nullable();
            $table->float('short_downtime',2)->after('no_raw')->nullable();
            $table->float('total_downtime',2)->after('short_downtime')->nullable();
            $table->float('no_reason_downtime',2)->after('total_downtime')->nullable();
            $table->boolean('is_complete')->after('is_idle')->nullable();
            $table->renameColumn('rakel', 'service_replacement');
        });
    }
};
