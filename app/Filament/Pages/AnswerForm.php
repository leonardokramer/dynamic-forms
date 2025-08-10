<?php

namespace App\Filament\Pages;

use App\Models\Form;
use Filament\Pages\Page;
use Filament\Forms\Form as FilamentForm;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Placeholder;
use App\Enums\QuestionType;
use App\Services\FormSubmissionService;
use Filament\Notifications\Notification;

class AnswerForm extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    
    protected static ?string $title = null;
    
    protected static ?string $slug = null;
    
    protected static bool $shouldRegisterNavigation = false;
    
    protected static string $view = 'filament.pages.answer-form';
    
    public ?array $data = [];
    
    public Form $form;

    public function mount(): void
    {
        $formId = request()->query('id');
        
        if (!$formId) {
            abort(404, __('forms.messages.form_not_found'));
        }
        
        $this->form = Form::findOrFail($formId);
        
        if (!$this->form->active) {
            abort(403, __('forms.messages.form_not_active'));
        }
        
        $this->form->load('questions.alternatives');
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
        
        $schema[] = Section::make($this->form->title)
            ->schema([Placeholder::make($this->form->description ?? '')])
            ->columns(1)
            ->collapsible(false);
        
        foreach ($this->form->questions->sortBy('order') as $question) {
            $component = $this->getQuestionComponent($question);
            if ($component) {
                $schema[] = Section::make($question->text)
                    ->schema([$component])
                    ->collapsible(false);
            }
        }
        
        return $form
            ->schema($schema)
            ->statePath('data');
    }

    protected function getQuestionComponent($question)
    {
        $label = __('forms.labels.answer');

        return match($question->type) {
            QuestionType::STRING => TextInput::make((string) $question->id)
                ->label($label)
                ->required($question->required)
                ->maxLength(255)
                ->placeholder(__('forms.placeholders.text_answer')),

            QuestionType::INTEGER => TextInput::make((string) $question->id)
                ->label($label)
                ->required($question->required)
                ->numeric()
                ->integer()
                ->placeholder(__('forms.placeholders.integer_answer')),

            QuestionType::DECIMAL => TextInput::make((string) $question->id)
                ->label($label)
                ->required($question->required)
                ->numeric()
                ->step(0.01)
                ->placeholder(__('forms.placeholders.decimal_answer')),

            QuestionType::MULTIPLE_CHOICE => $this->getMultipleChoiceComponent($question, $label),

            default => null,
        };
    }

    protected function getMultipleChoiceComponent($question, $label)
    {
        $alternatives = $question->alternatives->sortBy('order')->pluck('text', 'id')->toArray();
        
        if (empty($alternatives)) {
            return TextInput::make((string) $question->id)
                ->label($label)
                ->required($question->required)
                ->disabled()
                ->placeholder(__('forms.placeholders.multiple_choice_answer'));
        }
        
        return Radio::make((string) $question->id)
            ->label($label)
            ->required($question->required)
            ->options($alternatives)
            ->columns(1);
    }

    public function submit(): void
    {
        $this->validate();
        
        try {
            $answers = $this->extractAnswers();
            
            $submissionService = new FormSubmissionService();
            $userId = auth()->id();
            $submission = $submissionService->submitForm(
                $this->form, 
                $userId, 
                $answers
            );
            
            Notification::make()
                ->title(__('forms.messages.form_submitted_successfully'))
                ->success()
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('forms.messages.form_submission_error'))
                ->body($e->getMessage())
                ->danger()
                ->send();
                
            return;
        }
        
        $this->redirect(route('filament.public.pages.' . __('forms.pages.list_active_forms.slug')));
    }

    protected function extractAnswers(): array
    {
        $answers = [];
        
        foreach ($this->data as $questionId => $value) {
            if (filled($value)) {
                $answers[(int) $questionId] = $value;
            }
        }
        
        return $answers;
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('back')
                ->label(__('forms.buttons.back'))
                ->url(route('filament.public.pages.' . __('forms.pages.list_active_forms.slug')))
                ->color('gray'),
            \Filament\Actions\Action::make('submit')
                ->label(__('forms.buttons.submit_answers'))
                ->submit('submit')
                ->color('primary'),
        ];
    }
    
    public function getTitle(): string
    {
        return __('forms.pages.answer_form.title');
    }
    
    public static function getSlug(): string
    {
        return __('forms.pages.answer_form.slug');
    }
}
