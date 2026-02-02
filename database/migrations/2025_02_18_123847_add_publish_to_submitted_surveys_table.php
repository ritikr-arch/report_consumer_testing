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
            $table->enum('publish', ['1', '0'])->default('0')->comment('1=Yes, 0=No')->after('status'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submitted_surveys', function (Blueprint $table) {
            $table->dropColumn('publish');
        });
    }
};
