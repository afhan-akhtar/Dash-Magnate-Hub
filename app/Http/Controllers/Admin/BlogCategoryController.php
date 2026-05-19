<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(): View
    {
        $categories = BlogCategory::query()
            ->withCount(['blogs as active_blogs_count' => fn ($q) => $q->where('active', 1)])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.blog-categories.index', compact('categories'));
    }

    public function show(int $id): View
    {
        $category = BlogCategory::query()
            ->withCount(['blogs as active_blogs_count' => fn ($q) => $q->where('active', 1)])
            ->with(['blogs' => fn ($q) => $q->orderByDesc('id')->limit(15)])
            ->findOrFail($id);

        return view('admin.blog-categories.show', compact('category'));
    }

    public function create(): View
    {
        return view('admin.blog-categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:blog_categories,slug'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $slug = $data['slug'] ?? Str::slug($data['name']);
        if ($slug === '') {
            $slug = 'category-'.substr(md5($data['name']), 0, 10);
        }

        BlogCategory::create([
            'name' => $data['name'],
            'slug' => $slug,
            'sort_order' => $data['sort_order'] ?? 0,
            'status' => $request->boolean('status', true) ? 1 : 0,
        ]);

        return redirect()->route('admin.blog-categories.index')->with('success', 'Blog category created.');
    }

    public function edit(int $id): View
    {
        $category = BlogCategory::findOrFail($id);

        return view('admin.blog-categories.edit', compact('category'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $category = BlogCategory::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blog_categories', 'slug')->ignore($category->id)],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $slug = $data['slug'] ?? Str::slug($data['name']);
        if ($slug === '') {
            $slug = 'category-'.$category->id;
        }

        $category->update([
            'name' => $data['name'],
            'slug' => $slug,
            'sort_order' => $data['sort_order'] ?? 0,
            'status' => $request->boolean('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.blog-categories.index')->with('success', 'Blog category updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        BlogCategory::findOrFail($id)->delete();

        return redirect()->route('admin.blog-categories.index')->with('success', 'Blog category deleted.');
    }
}
