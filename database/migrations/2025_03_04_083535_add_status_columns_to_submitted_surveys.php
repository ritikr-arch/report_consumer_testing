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
        Schema::table('submitted_surveys', function (Blueprint $table) {
            $table->boolean('is_save')->default(false);
            $table->boolean('is_submit')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submitted_surveys', function (Blueprint $table) {
            $table->dropColumn(['is_save', 'is_submit']);
        });
    }
};
