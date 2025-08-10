<?php

namespace App\Enums;

enum QuestionType: string
{
    case STRING = 'string';
    case INTEGER = 'integer';
    case DECIMAL = 'decimal';
    case MULTIPLE_CHOICE = 'multiple_choice';

    public function label(): string
    {
        return match($this) {
            self::STRING => __('enums.question_type.string'),
            self::INTEGER => __('enums.question_type.integer'),
            self::DECIMAL => __('enums.question_type.decimal'),
            self::MULTIPLE_CHOICE => __('enums.question_type.multiple_choice'),
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}