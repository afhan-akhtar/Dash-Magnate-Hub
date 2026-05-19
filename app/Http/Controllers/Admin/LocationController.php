<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function __construct(private FileUploadService $fileUpload)
    {
    }

    public function index(): View
    {
        $locations = Location::with('thumbnail')->orderBy('name')->get();

        return view('admin.locations.index', compact('locations'));
    }

    public function create(): View
    {
        return view('admin.locations.create');
    }

    public function edit(int $id): View
    {
        $location = Location::with('thumbnail')->findOrFail($id);

        return view('admin.locations.edit', compact('location'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string',
            'card' => 'nullable|image|max:2048',
        ]);

        $location = Location::create([
            'name' => $request->name,
            'url' => $request->url,
            'ref_id' => $request->ref_id,
            'account_id' => Auth::id(),
            'status' => $request->boolean('status') ? 1 : 0,
            'active' => $request->boolean('active') ? 1 : 0,
            'code' => md5(uniqid()),
        ]);

        if ($request->hasFile('card')) {
            $this->fileUpload->upload($request->file('card'), $location, 'card', 'uploads/locations');
        }

        return redirect()->route('admin.locations.index')->with('success', 'Location created.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string',
            'card' => 'nullable|image|max:2048',
        ]);

        $location = Location::findOrFail($id);
        $location->update([
            'name' => $request->name,
            'url' => $request->url,
            'status' => $request->boolean('status') ? 1 : 0,
            'active' => $request->boolean('active') ? 1 : 0,
        ]);

        if ($request->hasFile('card')) {
            $this->fileUpload->replaceDocument($request->file('card'), $location, 'card', 'uploads/locations');
        }

        return redirect()->route('admin.locations.index')->with('success', 'Location updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $location = Location::findOrFail($id);
        $location->documents->each(fn ($doc) => $this->fileUpload->deleteDocument($doc));
        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Location deleted.');
    }
}
