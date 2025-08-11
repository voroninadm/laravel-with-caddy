<?php

use App\Models\InfoUser;
use App\Models\User;
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
        Schema::table('infos', function (Blueprint $table) {
            $table->foreignId('user_id')->after('is_done')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

};
