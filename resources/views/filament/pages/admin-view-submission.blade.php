<x-filament-panels::page>
    @livewire('submission-viewer', [
        'submission' => $this->submission,
        'backUrl' => route('filament.admin.pages.' . __('forms.pages.admin_submissions.slug'))
    ])
</x-filament-panels::page>
