<?php

namespace App\Filament\Pages;

use App\Models\FormSubmission;
use Filament\Pages\Page;

class ViewSubmission extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-eye';
    
    protected static ?string $title = null;
    
    protected static ?string $slug = null;
    
    protected static bool $shouldRegisterNavigation = false;
    
    protected static string $view = 'filament.pages.view-submission';
    
    public FormSubmission $submission;

    public function mount(): void
    {
        $submissionId = request()->query('id');
        
        if (!$submissionId) {
            abort(404, __('forms.messages.submission_not_found'));
        }
        
        $this->submission = FormSubmission::findOrFail($submissionId);
        
        if ($this->submission->user_id !== (auth()->id())) {
            abort(403, __('forms.messages.unauthorized_access'));
        }
    }

    public function getTitle(): string
    {
        return __('forms.pages.view_submission.title');
    }
    
    public static function getSlug(): string
    {
        return __('forms.pages.view_submission.slug');
    }
}

