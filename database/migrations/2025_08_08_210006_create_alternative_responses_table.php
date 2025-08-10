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
        Schema::create('alternative_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_response_id')->constrained()->onDelete('cascade');
            $table->text('alternative_text');
            $table->integer('alternative_order');
            $table->boolean('alternative_is_correct');
            $table->timestamps();

            $table->index(['question_response_id', 'alternative_order'], 'alt_resp_qr_order_idx');
        });

        Schema::table('question_responses', function (Blueprint $table) {
            $table->foreign('selected_alternative_response_id')->references('id')->on('alternative_responses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_responses', function (Blueprint $table) {
            $table->dropForeign(['selected_alternative_response_id']);
        });
        
        Schema::dropIfExists('alternative_responses');
    }
};
