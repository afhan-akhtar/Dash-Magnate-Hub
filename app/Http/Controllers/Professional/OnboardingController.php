<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use App\Models\ProfessionalQuestion;
use App\Models\ProfessionalQuestionAnswer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function show(): View|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $questions = ProfessionalQuestion::where('role', $user->role)
            ->where('status', 1)
            ->with(['options' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($questions->isEmpty()) {
            // No questions for this role — go straight to dashboard
            return redirect()->route('professional.dashboard');
        }

        // Load existing answers keyed by question_id
        $existing = ProfessionalQuestionAnswer::where('user_id', $user->id)
            ->whereIn('professional_question_id', $questions->pluck('id'))
            ->get()
            ->keyBy('professional_question_id');

        return view('professional.onboarding', [
            'questions' => $questions,
            'existing'  => $existing,
            'user'      => $user,
        ]);
    }

    public function submit(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $questions = ProfessionalQuestion::where('role', $user->role)
            ->where('status', 1)
            ->with(['options' => fn ($q) => $q->where('is_active', true)])
            ->get()
            ->keyBy('id');

        $errors = [];

        foreach ($questions as $question) {
            $value = $request->input('answers.' . $question->id);

            if ($question->is_required && empty($value)) {
                $errors['answers.' . $question->id] = 'This question is required.';
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        foreach ($questions as $question) {
            $value = $request->input('answers.' . $question->id);

            if ($value === null) {
                continue;
            }

            $answerText     = null;
            $selectedOptions = [];

            if (in_array($question->type, ['single_choice', 'multi_choice'], true)) {
                $selectedOptions = is_array($value) ? array_map('intval', $value) : [(int) $value];
            } else {
                $answerText = trim((string) $value) ?: null;
            }

            ProfessionalQuestionAnswer::updateOrCreate(
                ['professional_question_id' => $question->id, 'user_id' => $user->id],
                ['answer_text' => $answerText, 'selected_option_ids' => $selectedOptions]
            );
        }

        return redirect()->route('professional.dashboard')
            ->with('success', 'Your profile questions have been saved. Welcome to the dashboard!');
    }
}
