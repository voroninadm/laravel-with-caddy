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
        Schema::table('json_orders', function (Blueprint $table) {
            $table->json('extrusion')->nullable()->after('cutting');
            $table->json('recycling')->nullable()->after('extrusion');
        });
    }
};
