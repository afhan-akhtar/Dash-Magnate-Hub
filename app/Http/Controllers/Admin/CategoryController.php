<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(private FileUploadService $fileUpload)
    {
    }

    public function index(): View
    {
        $categories = Category::with('thumbnail')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function edit(int $id): View
    {
        $category = Category::with('thumbnail')->findOrFail($id);

        return view('admin.categories.edit', compact('category'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string',
            'card' => 'nullable|image|max:2048',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'url' => $request->url,
            'ref_id' => $request->ref_id,
            'account_id' => Auth::id(),
            'status' => $request->boolean('status') ? 1 : 0,
            'active' => $request->boolean('active') ? 1 : 0,
            'code' => md5(uniqid()),
        ]);

        if ($request->hasFile('card')) {
            $this->fileUpload->upload($request->file('card'), $category, 'card', 'uploads/categories');
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string',
            'card' => 'nullable|image|max:2048',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'url' => $request->url,
            'status' => $request->boolean('status') ? 1 : 0,
            'active' => $request->boolean('active') ? 1 : 0,
        ]);

        if ($request->hasFile('card')) {
            $this->fileUpload->replaceDocument($request->file('card'), $category, 'card', 'uploads/categories');
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $category = Category::findOrFail($id);
        $category->documents->each(fn ($doc) => $this->fileUpload->deleteDocument($doc));
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }
}
