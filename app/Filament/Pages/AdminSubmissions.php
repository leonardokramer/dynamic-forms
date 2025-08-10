<?php

namespace App\Filament\Pages;

use App\Models\FormSubmission;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class AdminSubmissions extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    
    protected static string $view = 'filament.pages.admin-submissions';
    
    protected static bool $shouldRegisterNavigation = true;
    
    protected static ?string $slug = null;
        
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('forms.labels.user'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label(__('forms.labels.user_email'))
                    ->sortable()
                    ->searchable(),
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
            ->filters([
                SelectFilter::make('form_title')
                    ->label(__('forms.labels.form'))
                    ->options(function () {
                        return FormSubmission::query()
                            ->distinct()
                            ->pluck('form_title', 'form_title')
                            ->toArray();
                    }),
            ])
            ->actions([
                Action::make('view')
                    ->label(__('forms.buttons.view_response'))
                    ->icon('heroicon-m-eye')
                    ->url(fn($record) => route('filament.admin.pages.' . __('forms.pages.admin_view_submission.slug'), ['id' => $record->id]))
                    ->color('primary'),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->emptyStateHeading(__('forms.messages.no_submissions'))
            ->emptyStateDescription(__('forms.messages.no_submissions_admin_description'))
            ->emptyStateIcon('heroicon-o-document-text');
    }

    protected function getTableQuery(): Builder
    {
        return FormSubmission::query()
            ->with(['form', 'questionResponses', 'user']);
    }

    public function getTitle(): string
    {
        return __('forms.pages.admin_submissions.title');
    }
    
    public static function getNavigationLabel(): string
    {
        return __('forms.pages.admin_submissions.navigation_label');
    }
    
    public static function getSlug(): string
    {
        return __('forms.pages.admin_submissions.slug');
    }
}
