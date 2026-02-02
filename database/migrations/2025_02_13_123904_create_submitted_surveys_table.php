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
        Schema::create('submitted_surveys', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('zone_id')->nullable();
            $table->integer('survey_id')->nullable();
            $table->integer('market_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('commodity_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->decimal('amount', 15, 5)->default(0.00);
            $table->enum('availability', ['low', 'moderate', 'high'])->nullable();
            $table->string('commodity_image', 255)->nullable();
            $table->integer('submitted_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->enum('status', ['1', '0'])->default('1')->comment('1=active,0=inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submitted_surveys');
    }
};
