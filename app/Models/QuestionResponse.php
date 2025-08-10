<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\QuestionType;

class QuestionResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_submission_id',
        'question_text',
        'question_type',
        'question_order',
        'question_required',
        'user_answer',
        'selected_alternative_response_id',
    ];

    protected $casts = [
        'question_type' => QuestionType::class,
        'question_order' => 'integer',
        'question_required' => 'boolean',
    ];

    public function formSubmission(): BelongsTo
    {
        return $this->belongsTo(FormSubmission::class);
    }

    public function alternativeResponses(): HasMany
    {
        return $this->hasMany(AlternativeResponse::class);
    }

    public function selectedAlternativeResponse(): BelongsTo
    {
        return $this->belongsTo(AlternativeResponse::class, 'selected_alternative_response_id');
    }

    public function isMultipleChoice(): bool
    {
        return $this->question_type === QuestionType::MULTIPLE_CHOICE;
    }
}
