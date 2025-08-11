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
        Schema::create('json_orders', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->json('printing')->nullable();
            $table->json('lamination')->nullable();
            $table->json('welding')->nullable();
            $table->json('cutting')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('json_orders');
    }
};
