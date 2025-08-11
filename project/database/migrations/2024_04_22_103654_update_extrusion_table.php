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
        Schema::table('extrusions', function (Blueprint $table) {
            $table->dropColumn('shrink_long');
            $table->dropColumn('shrink_trans');
            $table->integer('recipe_number')->after('tkn')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('extrusions', function (Blueprint $table) {
            $table->float('shrink_long')->after('activation_pole')->nullable();
            $table->float('shrink_trans')->after('shrink_long')->nullable();
            $table->dropColumn('recipe_number');
        });
    }
};
