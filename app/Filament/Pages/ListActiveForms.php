<?php

namespace App\Filament\Pages;

use App\Models\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class ListActiveForms extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = null;
    
    protected static ?string $title = null;
    
    protected static ?string $slug = null;
    
    protected static ?int $navigationSort = 1;
    
    protected static string $view = 'filament.pages.list-active-forms';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Form::query()
                    ->where('active', true)
                    ->with('questions')
            )
            ->columns([
                TextColumn::make('title')
                    ->label(__('forms.labels.title'))
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('description')
                    ->label(__('forms.labels.description'))
                    ->limit(100)
                    ->searchable(),
                    
                TextColumn::make('questions_count')
                    ->label(__('forms.labels.questions'))
                    ->counts('questions')
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->label(__('forms.labels.created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Action::make('responder')
                    ->label(__('forms.buttons.create_answer'))
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->url(fn (Form $record): string => "/responder/" . __('forms.pages.answer_form.slug') . "?id={$record->id}")
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }
    
    public static function getNavigationLabel(): string
    {
        return __('forms.pages.list_active_forms.navigation_label');
    }
    
    public function getTitle(): string
    {
        return __('forms.pages.list_active_forms.title');
    }
    
    public static function getSlug(): string
    {
        return __('forms.pages.list_active_forms.slug');
    }
}
