<?php

namespace App\Filament\Pages;

use App\Models\FormSubmission;
use App\Filament\Components\SubmissionViewer;
use Filament\Pages\Page;

class AdminViewSubmission extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-eye';
    
    protected static ?string $title = null;
    
    protected static ?string $slug = 'admin-view-submission';
    
    protected static bool $shouldRegisterNavigation = false;
    
    protected static string $view = 'filament.pages.admin-view-submission';
    
    public FormSubmission $submission;

    public function mount(): void
    {
        $submissionId = request()->query('id');
        
        if (!$submissionId) {
            abort(404, __('forms.messages.submission_not_found'));
        }
        
        $this->submission = FormSubmission::findOrFail($submissionId);
    }

    public function getTitle(): string
    {
        return __('forms.pages.view_submission.title');
    }
}
