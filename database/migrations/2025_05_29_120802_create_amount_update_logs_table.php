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
        Schema::create('amount_update_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submitted_survey_id'); 
            $table->unsignedBigInteger('survey_id');           

            $table->decimal('old_amount', 10, 2)->nullable();
            $table->decimal('new_amount', 10, 2)->nullable();

            $table->decimal('old_amount_1', 10, 2)->nullable();
            $table->decimal('new_amount_1', 10, 2)->nullable();

            $table->unsignedBigInteger('updated_by');
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('submitted_survey_id')->references('id')->on('submitted_surveys')->onDelete('cascade');
            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amount_update_logs');
    }
};
