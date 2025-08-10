<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormResource\Pages;
use App\Models\Form;
use App\Enums\QuestionType;
use App\Filament\Components\QuestionFormSchema;
use Filament\Forms;
use Filament\Forms\Form as FilamentForm;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FormResource extends Resource
{
    protected static ?string $model = Form::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = null;
    
    protected static ?string $modelLabel = null;
    
    protected static ?string $pluralModelLabel = null;

    public static function form(FilamentForm $form): FilamentForm
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('forms.labels.title'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('forms.placeholders.form_title')),
                    
                Forms\Components\Textarea::make('description')
                    ->label(__('forms.labels.description'))
                    ->rows(3)
                    ->placeholder(__('forms.placeholders.form_description')),
                    
                Forms\Components\Toggle::make('active')
                    ->label(__('forms.labels.active'))
                    ->default(true)
                    ->helperText(__('forms.help.form_active')),

                Forms\Components\Repeater::make('questions')
                    ->relationship('questions')
                    ->label(__('forms.labels.questions'))
                    ->schema(QuestionFormSchema::make())
                    ->columns(2)
                    ->defaultItems(0)
                    ->addActionLabel(__('questions.buttons.add_question'))
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['text'] ?? null),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(50)
                    ->searchable(),
                    
                Tables\Columns\IconColumn::make('active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label(__('forms.labels.status'))
                    ->placeholder(__('forms.filters.all'))
                    ->trueLabel(__('forms.filters.active_only'))
                    ->falseLabel(__('forms.filters.inactive_only')),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListForms::route('/'),
            'create' => Pages\CreateForm::route('/create'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationLabel(): string
    {
        return __('forms.resources.form.navigation_label');
    }
    
    public static function getModelLabel(): string
    {
        return __('forms.resources.form.model_label');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('forms.resources.form.plural_model_label');
    }
}
