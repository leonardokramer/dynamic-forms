<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use App\Models\Form;
use App\Enums\QuestionType;
use App\Filament\Components\QuestionFormSchema;
use Filament\Forms;
use Filament\Forms\Form as FilamentForm;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    
    protected static ?string $navigationLabel = null;
    
    protected static ?string $modelLabel = null;
    
    protected static ?string $pluralModelLabel = null;

    public static function form(FilamentForm $form): FilamentForm
    {
        return $form
            ->schema(QuestionFormSchema::withFormSelect())
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('form'))
            ->columns([
                Tables\Columns\TextColumn::make('form.title')
                    ->label(__('questions.labels.form'))
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('text')
                    ->label(__('questions.labels.text'))
                    ->limit(50)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('type')
                    ->label(__('questions.labels.type'))
                    ->formatStateUsing(fn (QuestionType $state) => $state->label())
                    ->badge()
                    ->color(fn (QuestionType $state) => match($state) {
                        QuestionType::STRING => 'info',
                        QuestionType::INTEGER => 'warning',
                        QuestionType::DECIMAL => 'warning',
                        QuestionType::MULTIPLE_CHOICE => 'success',
                    }),
                    
                Tables\Columns\IconColumn::make('required')
                    ->label(__('questions.labels.required'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                    
                Tables\Columns\TextColumn::make('order')
                    ->label(__('questions.labels.order'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('form_id')
                    ->label(__('questions.labels.form'))
                    ->options(Form::pluck('title', 'id'))
                    ->searchable(),
                    
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('questions.labels.type'))
                    ->options(QuestionType::options()),
                    
                Tables\Filters\TernaryFilter::make('required')
                    ->label(__('questions.labels.required')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationLabel(): string
    {
        return __('questions.resources.question.navigation_label');
    }
    
    public static function getModelLabel(): string
    {
        return __('questions.resources.question.model_label');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('questions.resources.question.plural_model_label');
    }
}
