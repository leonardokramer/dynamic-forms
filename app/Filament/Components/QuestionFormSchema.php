<?php

namespace App\Filament\Components;

use App\Models\Form;
use App\Enums\QuestionType;
use Filament\Forms;

class QuestionFormSchema
{
    public static function make(): array
    {
        return [
            Forms\Components\TextInput::make('text')
                ->label(__('questions.labels.text'))
                ->required()
                ->maxLength(255)
                ->placeholder(__('questions.placeholders.text')),

            Forms\Components\Select::make('type')
                ->label(__('questions.labels.type'))
                ->options(QuestionType::options())
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state !== QuestionType::MULTIPLE_CHOICE->value) {
                        $set('alternatives', []);
                    }
                }),

            Forms\Components\Toggle::make('required')
                ->label(__('questions.labels.required'))
                ->default(false),

            Forms\Components\TextInput::make('order')
                ->label(__('questions.labels.order'))
                ->numeric()
                ->default(0)
                ->helperText(__('questions.help.order')),

            Forms\Components\Repeater::make('alternatives')
                ->relationship('alternatives')
                ->label('Alternativas')
                ->schema([
                    Forms\Components\TextInput::make('text')
                        ->label('Texto')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Toggle::make('is_correct')
                        ->label('Correta?')
                        ->default(false),

                    Forms\Components\TextInput::make('order')
                        ->label('Ordem')
                        ->numeric()
                        ->default(0),
                ])
                ->columns(3)
                ->defaultItems(0)
                ->orderable('order')
                ->visible(fn (callable $get) => $get('type') === QuestionType::MULTIPLE_CHOICE->value)
                ->rules([
                    'required_if:type,' . QuestionType::MULTIPLE_CHOICE->value,
                    'array',
                    'min:1',
                ])
                ->validationMessages([
                    'required_if' => __('questions.errors.multiple_choice_at_least_one_alternative'),
                    'min' => __('questions.errors.multiple_choice_at_least_one_alternative'),
                ]),
        ];
    }

    public static function withFormSelect(): array
    {
        return array_merge([
            Forms\Components\Select::make('form_id')
                ->label(__('questions.labels.form'))
                ->options(Form::pluck('title', 'id'))
                ->required()
                ->searchable()
                ->placeholder(__('questions.placeholders.form')),
        ], self::make());
    }
}
