<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogTagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::query()
            ->withCount(['blogs as active_blogs_count' => fn ($q) => $q->where('active', 1)])
            ->orderBy('name')
            ->get();

        return view('admin.blog-tags.index', compact('tags'));
    }

    public function show(int $id): View
    {
        $tag = Tag::query()
            ->withCount(['blogs as active_blogs_count' => fn ($q) => $q->where('active', 1)])
            ->with(['blogs' => fn ($q) => $q->orderByDesc('id')->limit(15)])
            ->findOrFail($id);

        return view('admin.blog-tags.show', compact('tag'));
    }

    public function create(): View
    {
        return view('admin.blog-tags.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', 'unique:tags,slug'],
        ]);

        $slug = $data['slug'] ?? Str::slug($data['name']);
        if ($slug === '') {
            $slug = 'tag-'.substr(md5($data['name']), 0, 12);
        }

        Tag::create([
            'name' => Str::limit($data['name'], 120),
            'slug' => Str::limit($slug, 140),
        ]);

        return redirect()->route('admin.blog-tags.index')->with('success', 'Blog tag created.');
    }

    public function edit(int $id): View
    {
        $tag = Tag::findOrFail($id);

        return view('admin.blog-tags.edit', compact('tag'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $tag = Tag::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', Rule::unique('tags', 'slug')->ignore($tag->id)],
        ]);

        $slug = $data['slug'] ?? Str::slug($data['name']);
        if ($slug === '') {
            $slug = 'tag-'.$tag->id;
        }

        $tag->update([
            'name' => Str::limit($data['name'], 120),
            'slug' => Str::limit($slug, 140),
        ]);

        return redirect()->route('admin.blog-tags.index')->with('success', 'Blog tag updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        Tag::findOrFail($id)->delete();

        return redirect()->route('admin.blog-tags.index')->with('success', 'Blog tag deleted.');
    }
}
