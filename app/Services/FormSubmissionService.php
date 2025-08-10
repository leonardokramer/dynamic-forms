<?php

namespace App\Services;

use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\QuestionResponse;
use App\Models\AlternativeResponse;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FormSubmissionService
{
    public function submitForm(Form $form, int $userId, array $answers): FormSubmission
    {
        return DB::transaction(function () use ($form, $userId, $answers) {
            $user = User::findOrFail($userId);
            
            $submission = FormSubmission::create([
                'form_id' => $form->id,
                'user_id' => $userId,
                'form_title' => $form->title,
                'form_description' => $form->description,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'submitted_at' => now(),
            ]);

            foreach ($form->questions as $question) {
                $userAnswer = $answers[$question->id] ?? null;
                
                $questionResponse = QuestionResponse::create([
                    'form_submission_id' => $submission->id,
                    'question_text' => $question->text,
                    'question_type' => $question->type,
                    'question_order' => $question->order,
                    'question_required' => $question->required,
                    'user_answer' => $question->isMultipleChoice() ? null : $userAnswer,
                ]);

                if ($question->isMultipleChoice()) {
                    $this->saveAlternativeResponses($questionResponse, $question, $userAnswer);
                }
            }

            return $submission->load('questionResponses.alternativeResponses');
        });
    }

    private function saveAlternativeResponses(QuestionResponse $questionResponse, $question, $userAnswer): void
    {
        $selectedAlternativeResponse = null;
        
        foreach ($question->alternatives as $alternative) {
            $alternativeResponse = AlternativeResponse::create([
                'question_response_id' => $questionResponse->id,
                'alternative_text' => $alternative->text,
                'alternative_order' => $alternative->order,
                'alternative_is_correct' => $alternative->is_correct,
            ]);
            
            if ($alternative->id == $userAnswer) {
                $selectedAlternativeResponse = $alternativeResponse;
            }
        }
        
        if ($selectedAlternativeResponse) {
            $questionResponse->update(['selected_alternative_response_id' => $selectedAlternativeResponse->id]);
        }
    }
}
