<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfessionalQuestion;
use App\Models\ProfessionalQuestionOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfessionalQuestionController extends Controller
{
    public function index(): View
    {
        $questions = ProfessionalQuestion::query()
            ->with('options')
            ->orderBy('role')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $stats = [
            'total' => ProfessionalQuestion::query()->count(),
            'active' => ProfessionalQuestion::query()->where('status', 1)->count(),
        ];

        return view('admin.professional-questions.index', compact('questions', 'stats'));
    }

    public function create(): View
    {
        $roles = ProfessionalQuestion::ROLES;
        $types = ProfessionalQuestion::TYPES;

        return view('admin.professional-questions.create', compact('roles', 'types'));
    }

    public function edit(int $id): View
    {
        $question = ProfessionalQuestion::query()->with('options')->findOrFail($id);
        $roles = ProfessionalQuestion::ROLES;
        $types = ProfessionalQuestion::TYPES;

        return view('admin.professional-questions.edit', compact('question', 'roles', 'types'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(ProfessionalQuestion::ROLES)],
            'question' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(ProfessionalQuestion::TYPES)],
            'is_required' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'options' => ['required_if:type,single_choice,multi_choice', 'array'],
            'options.*.label' => ['required_if:type,single_choice,multi_choice', 'string', 'max:255'],
            'options.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'options.*.is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $question = ProfessionalQuestion::create([
                'role' => $validated['role'],
                'question' => $validated['question'],
                'type' => $validated['type'],
                'is_required' => $request->boolean('is_required', true),
                'sort_order' => $validated['sort_order'] ?? 0,
                'status' => $request->boolean('status') ? 1 : 0,
                'code' => md5(uniqid((string) mt_rand(), true)),
            ]);

            $this->syncOptions($question, $validated['options'] ?? []);
        });

        return redirect()->route('admin.professional-questions.index')->with('success', 'Question created.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in(ProfessionalQuestion::ROLES)],
            'question' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(ProfessionalQuestion::TYPES)],
            'is_required' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
            'options' => ['required_if:type,single_choice,multi_choice', 'array'],
            'options.*.label' => ['required_if:type,single_choice,multi_choice', 'string', 'max:255'],
            'options.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'options.*.is_active' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($id, $request, $validated) {
            $question = ProfessionalQuestion::findOrFail($id);
            $question->update([
                'role' => $validated['role'],
                'question' => $validated['question'],
                'type' => $validated['type'],
                'is_required' => $request->boolean('is_required'),
                'sort_order' => $validated['sort_order'] ?? 0,
                'status' => $request->boolean('status') ? 1 : 0,
            ]);

            $this->syncOptions($question, $validated['options'] ?? []);
        });

        return redirect()->route('admin.professional-questions.index')->with('success', 'Question updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        ProfessionalQuestion::findOrFail($id)->delete();

        return redirect()->route('admin.professional-questions.index')->with('success', 'Question deleted.');
    }

    private function syncOptions(ProfessionalQuestion $question, array $options): void
    {
        $options = collect($options)
            ->filter(fn ($opt) => isset($opt['label']) && trim($opt['label']) !== '')
            ->values();

        ProfessionalQuestionOption::query()
            ->where('professional_question_id', $question->id)
            ->delete();

        foreach ($options as $idx => $opt) {
            ProfessionalQuestionOption::create([
                'professional_question_id' => $question->id,
                'label' => trim($opt['label']),
                'value' => $opt['value'] ?? null,
                'sort_order' => $opt['sort_order'] ?? ($idx + 1),
                'is_active' => array_key_exists('is_active', $opt) ? (bool) $opt['is_active'] : true,
            ]);
        }
    }
}
