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
            $table->dropColumn('aniloks');
            $table->dropColumn('clean_machine');
            $table->dropColumn('clean_dry');
            $table->dropColumn('clean_val');
        });
    }
};
