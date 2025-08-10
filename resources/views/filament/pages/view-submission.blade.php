<x-filament-panels::page>
    @livewire('submission-viewer', [
        'submission' => $this->submission,
        'backUrl' => route('filament.public.pages.' . __('forms.pages.my_submissions.slug'))
    ])
</x-filament-panels::page>

