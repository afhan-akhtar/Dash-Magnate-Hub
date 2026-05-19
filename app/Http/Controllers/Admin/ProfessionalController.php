<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfessionalController extends Controller
{
    public function index(): View
    {
        $professionalsBase = fn () => User::professionals()->where('is_deleted', 0);

        $stats = [
            'total' => $professionalsBase()->count(),
            'verified' => $professionalsBase()->where('verified', 1)->count(),
            'active' => $professionalsBase()->where('status', 0)->count(),
        ];

        $professionals = $professionalsBase()
            ->with('thumbnail')
            ->latest()
            ->get();

        return view('admin.professionals.index', compact('professionals', 'stats'));
    }

    public function show(int $id): View
    {
        $professional = User::professionals()
            ->with([
                'thumbnail',
                'documents',
                'plans',
                'projects',
                'professionalQuestionAnswers.question.options',
            ])
            ->findOrFail($id);

        return view('admin.professionals.show', compact('professional'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        User::professionals()->findOrFail($id)->update($request->only(['status', 'verified']));

        return redirect()
            ->back()
            ->with('success', 'Professional updated.');
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $professional = User::professionals()->findOrFail($id);

        $data = [];
        if ($request->has('status')) {
            $data['status'] = (int) $request->input('status');
        }
        if ($request->has('verified')) {
            $data['verified'] = (int) $request->input('verified');
        }

        if (! empty($data)) {
            $professional->update($data);
        }

        return redirect()
            ->back()
            ->with('success', 'Professional updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        User::professionals()->findOrFail($id)->update(['is_deleted' => 1]);

        return redirect()
            ->route('admin.professionals.index')
            ->with('success', 'Professional removed.');
    }
}
