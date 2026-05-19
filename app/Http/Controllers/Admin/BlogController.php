<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Tag;
use App\Services\BlogService;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        private BlogService $blogService,
        private FileUploadService $fileUpload
    ) {
    }

    public function index(): View
    {
        $blogs = Blog::with(['thumbnail', 'tags', 'blogCategory'])->latest()->get();

        $stats = [
            'total' => Blog::count(),
            'published' => Blog::where('status', 1)->count(),
            'drafts' => Blog::where('status', 0)->count(),
            'visible' => Blog::where('active', 1)->count(),
        ];

        return view('admin.blogs.index', compact('blogs', 'stats'));
    }

    public function create(): View
    {
        $blogCategories = BlogCategory::query()->orderBy('sort_order')->orderBy('name')->get();
        $tags = Tag::query()->orderBy('name')->get();

        return view('admin.blogs.create', compact('blogCategories', 'tags'));
    }

    public function store(StoreBlogRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids']);

        $data['account_id'] = Auth::id();
        $blog = $this->blogService->createBlog($data);
        $this->blogService->syncBlogTagIds($blog, $tagIds);

        if ($request->hasFile('card')) {
            $this->fileUpload->upload($request->file('card'), $blog, 'card', 'uploads/blogs');
        }

        if ($request->hasFile('writer_image')) {
            $this->fileUpload->upload($request->file('writer_image'), $blog, 'writer_image', 'uploads/blogs');
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created.');
    }

    public function edit(int $id): View
    {
        $blog = Blog::with(['documents', 'thumbnail', 'tags', 'blogCategory'])->findOrFail($id);
        $blogCategories = BlogCategory::query()->orderBy('sort_order')->orderBy('name')->get();
        $tags = Tag::query()->orderBy('name')->get();

        return view('admin.blogs.edit', compact('blog', 'blogCategories', 'tags'));
    }

    public function update(UpdateBlogRequest $request, int $id): RedirectResponse
    {
        $blog = Blog::findOrFail($id);
        $validated = $request->validated();
        $tagIds = $validated['tag_ids'] ?? [];
        $data = collect($validated)->except(['tag_ids', 'card', 'writer_image'])->all();
        $data['status'] = $request->boolean('status') ? 1 : 0;
        $data['active'] = $request->boolean('active') ? 1 : 0;
        $this->blogService->updateBlog($blog, $data);
        $this->blogService->syncBlogTagIds($blog, $tagIds);

        if ($request->hasFile('card')) {
            $this->fileUpload->replaceDocument($request->file('card'), $blog, 'card', 'uploads/blogs');
        }

        if ($request->hasFile('writer_image')) {
            $this->fileUpload->replaceDocument($request->file('writer_image'), $blog, 'writer_image', 'uploads/blogs');
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $blog = Blog::findOrFail($id);
        $blog->tags()->detach();
        $blog->documents->each(fn ($doc) => $this->fileUpload->deleteDocument($doc));
        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted.');
    }
}
