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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->integer('zone_id')->nullable();
            $table->integer('market_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('product_ids', 255)->nullable();
            $table->string('surveyor_id', 255)->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->enum('is_complete', ['1', '0'])->default('0')->comment('1=complete, 0=incomplete');
            $table->enum('status', ['1', '0'])->default('1')->comment('1=active,0=inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
