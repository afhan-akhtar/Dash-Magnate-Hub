<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\FileUploadService;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(
        private ProjectService $projectService,
        private FileUploadService $fileUpload
    ) {
    }

    public function index(Request $request): View
    {
        $projects = Project::with(['professional', 'category', 'location', 'thumbnail'])
            ->latest()
            ->get();

        $stats = [
            'total' => Project::count(),
            'active' => Project::query()->notDeleted()->where('active', 1)->count(),
            'inactive' => Project::query()->notDeleted()->where('active', 0)->count(),
            'deleted' => Project::query()
                ->where(function ($q) {
                    $q->where('status', 1)->orWhereNotNull('deleted_at');
                })
                ->count(),
            'premium' => Project::where('premium', 1)->count(),
        ];

        return view('admin.projects.index', compact('projects', 'stats'));
    }

    public function show(int $id): View
    {
        $project = Project::with([
            'professional',
            'user',
            'category',
            'location',
            'region',
            'documents',
            'thumbnail',
        ])->findOrFail($id);

        return view('admin.projects.show', compact('project'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $project = Project::findOrFail($id);
        $project->update($request->only(['active', 'premium', 'block', 'sold']));

        return redirect()->route('admin.projects.index')->with('success', 'Project updated.');
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'active' => ['required', 'in:0,1'],
        ]);

        Project::findOrFail($id)->update(['active' => (int) $data['active']]);

        return redirect()->route('admin.projects.index')->with('success', 'Website visibility updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $project = Project::findOrFail($id);
        $this->projectService->deleteProject($project);

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted.');
    }
}
