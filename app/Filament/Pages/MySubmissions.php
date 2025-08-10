<?php

namespace App\Filament\Pages;

use App\Models\FormSubmission;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class MySubmissions extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    
    protected static string $view = 'filament.pages.my-submissions';
    
    protected static bool $shouldRegisterNavigation = true;
    
    protected static ?int $navigationSort = 2;
    
    protected static ?string $slug = null;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('form_title')
                    ->label(__('forms.labels.form'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('submitted_at')
                    ->label(__('forms.labels.submitted_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('questionResponses')
                    ->label(__('forms.labels.total_responses'))
                    ->getStateUsing(fn($record) => $record->questionResponses->count())
                    ->alignCenter(),
            ])
            ->actions([
                Action::make('view')
                    ->label(__('forms.buttons.view_response'))
                    ->icon('heroicon-m-eye')
                    ->url(fn($record) => route('filament.public.pages.' . __('forms.pages.view_submission.slug'), ['id' => $record->id]))
                    ->color('primary'),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->emptyStateHeading(__('forms.messages.no_submissions'))
            ->emptyStateDescription(__('forms.messages.no_submissions_description'))
            ->emptyStateIcon('heroicon-o-document-text');
    }

    protected function getTableQuery(): Builder
    {
        return FormSubmission::query()
            ->with(['form', 'questionResponses'])
            ->where('user_id', auth()->id());
    }

    public function getTitle(): string
    {
        return __('forms.pages.my_submissions.title');
    }
    
    public static function getNavigationLabel(): string
    {
        return __('forms.pages.my_submissions.navigation_label');
    }
    
    public static function getSlug(): string
    {
        return __('forms.pages.my_submissions.slug');
    }
}

