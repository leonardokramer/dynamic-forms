<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\QuestionType;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'text',
        'type',
        'order',
        'required',
    ];

    protected $casts = [
        'type' => QuestionType::class,
        'required' => 'boolean',
        'order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::updating(function (Question $question) {
            if ($question->isDirty('type')) {
                $oldType = $question->getOriginal('type');
                $newType = $question->type;

                if ($oldType === QuestionType::MULTIPLE_CHOICE && $newType !== QuestionType::MULTIPLE_CHOICE) {
                    $question->alternatives()->delete();
                }
            }
        });

        static::saving(function (Question $question) {
            $question->validateMultipleChoiceAlternatives();
        });
    }

    public function validateMultipleChoiceAlternatives(): void
    {
        if ($this->type === QuestionType::MULTIPLE_CHOICE) {
            if ($this->relationLoaded('alternatives') && $this->alternatives->isEmpty()) {
                throw new \InvalidArgumentException(__('questions.errors.multiple_choice_at_least_one_alternative'));
            }
        }
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function alternatives(): HasMany
    {
        return $this->hasMany(Alternative::class)->orderBy('order');
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type->label();
    }

    public function isString(): bool
    {
        return $this->type === QuestionType::STRING;
    }

    public function isInteger(): bool
    {
        return $this->type === QuestionType::INTEGER;
    }

    public function isDecimal(): bool
    {
        return $this->type === QuestionType::DECIMAL;
    }

    public function isMultipleChoice(): bool
    {
        return $this->type === QuestionType::MULTIPLE_CHOICE;
    }
}