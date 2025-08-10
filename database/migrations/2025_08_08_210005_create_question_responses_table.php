<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_submission_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->string('question_type');
            $table->integer('question_order');
            $table->boolean('question_required');
            $table->text('user_answer')->nullable();
            $table->unsignedBigInteger('selected_alternative_response_id')->nullable();
            $table->timestamps();

            $table->index(['form_submission_id', 'question_order'], 'q_resp_fs_order_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_responses');
    }
};
