<?php

namespace App\Filament\Components;

use App\Models\FormSubmission;
use Filament\Forms\Form as FilamentForm;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Placeholder;
use App\Enums\QuestionType;
use Livewire\Component;

class SubmissionViewer extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public FormSubmission $submission;
    public ?string $backUrl = null;

    public function mount(FormSubmission $submission, ?string $backUrl = null): void
    {
        $this->submission = $submission->load([
            'form', 
            'user',
            'questionResponses.alternativeResponses',
            'questionResponses.selectedAlternativeResponse'
        ]);
        
        $this->backUrl = $backUrl;
        $this->populateFormData();
    }

    protected function populateFormData(): void
    {
        foreach ($this->submission->questionResponses as $response) {
            if ($response->isMultipleChoice()) {
                $this->data[$response->id] = $response->selectedAlternativeResponse?->id;
            } else {
                $this->data[$response->id] = $response->user_answer;
            }
        }
    }

    public function getForm(string $name = 'form'): ?FilamentForm
    {
        return $this->form($this->makeForm());
    }

    protected function makeForm(): FilamentForm
    {
        return FilamentForm::make($this);
    }

    public function form(FilamentForm $form): FilamentForm
    {
        $schema = [];
        
        $schema[] = Section::make(__('forms.labels.submission_info'))
            ->schema([  
                Placeholder::make('form_title')
                    ->label(__('forms.labels.form'))
                    ->content($this->submission->form_title),
                Placeholder::make('submitted_at')
                    ->label(__('forms.labels.submitted_at'))
                    ->content($this->submission->submitted_at->format('d/m/Y H:i:s')),
                Placeholder::make('user_name')
                    ->label(__('forms.labels.submitted_by'))
                    ->content($this->submission->user_name),
                Placeholder::make('user_email')
                    ->label(__('forms.labels.user_email'))
                    ->content($this->submission->user_email),
            ])
            ->columns(4)
            ->collapsible(false);
        
        $schema[] = Section::make($this->submission->form_title)
            ->schema([Placeholder::make($this->submission->form_description ?? '')])
            ->columns(1)
            ->collapsible(false);
        
        foreach ($this->submission->questionResponses->sortBy('question_order') as $response) {
            $component = $this->getResponseComponent($response);
            if ($component) {
                $schema[] = Section::make($response->question_text)
                    ->schema([$component])
                    ->collapsible(false);
            }
        }
        
        return $form
            ->schema($schema)
            ->statePath('data');
    }

    protected function getResponseComponent($response)
    {
        $label = __('forms.labels.answer');

        return match($response->question_type) {
            QuestionType::STRING => TextInput::make((string) $response->id)
                ->label($label)
                ->disabled()
                ->placeholder(__('forms.placeholders.no_answer')),

            QuestionType::INTEGER => TextInput::make((string) $response->id)
                ->label($label)
                ->disabled()
                ->numeric()
                ->placeholder(__('forms.placeholders.no_answer')),

            QuestionType::DECIMAL => TextInput::make((string) $response->id)
                ->label($label)
                ->disabled()
                ->numeric()
                ->placeholder(__('forms.placeholders.no_answer')),

            QuestionType::MULTIPLE_CHOICE => $this->getMultipleChoiceComponent($response, $label),

            default => null,
        };
    }

    protected function getMultipleChoiceComponent($response, $label)
    {
        $alternatives = $response->alternativeResponses
            ->sortBy('alternative_order')
            ->pluck('alternative_text', 'id')
            ->toArray();
        
        if (empty($alternatives)) {
            return TextInput::make((string) $response->id)
                ->label($label)
                ->disabled()
                ->placeholder(__('forms.placeholders.no_alternatives'));
        }
        
        return Radio::make((string) $response->id)
            ->label($label)
            ->disabled()
            ->options($alternatives)
            ->columns(1);
    }

    protected function getFormActions(): array
    {
        $actions = [];
        
        if ($this->backUrl) {
            $actions[] = \Filament\Actions\Action::make('back')
                ->label(__('forms.buttons.back'))
                ->url($this->backUrl)
                ->color('gray');
        }
        
        return $actions;
    }

    protected function areFormActionsSticky(): bool
    {
        return false;
    }

    protected function getFormActionsAlignment(): string
    {
        return 'left';
    }

    public function render()
    {
        return view('filament.components.submission-viewer');
    }
}
