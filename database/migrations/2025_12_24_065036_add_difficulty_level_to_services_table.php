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
        Schema::table('services', function (Blueprint $table) {
            $table->enum('difficulty_level', ['easy', 'medium', 'hard', 'custom'])->default('medium')->after('points_earned');
            $table->boolean('has_custom_fee')->default(false)->after('difficulty_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('difficulty_level');
            $table->dropColumn('has_custom_fee');
        });
    }
};
