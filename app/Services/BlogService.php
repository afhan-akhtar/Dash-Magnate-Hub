<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class BlogService
{
    public function getPublicListing(int $perPage = 10): LengthAwarePaginator
    {
        return Blog::with('thumbnail')
            ->where('status', 0)
            ->where('active', 1)
            ->latest()
            ->paginate($perPage);
    }

    public function findByUrl(string $url): ?Blog
    {
        return Blog::with(['documents', 'comments' => fn ($q) => $q->where('status', 1)])
            ->where('url', $url)
            ->where('active', 1)
            ->first();
    }

    public function createBlog(array $data): Blog
    {
        $data['code'] = md5(uniqid());
        $data['url'] = Str::slug($data['name']).'-'.time();

        return Blog::create($data);
    }

    public function updateBlog(Blog $blog, array $data): Blog
    {
        $blog->update($data);

        return $blog->fresh();
    }

    public function addComment(Blog $blog, array $data): Comment
    {
        return $blog->comments()->create(array_merge($data, ['code' => md5(uniqid())]));
    }

    public function incrementViews(Blog $blog): void
    {
        $blog->increment('views');
    }

    /**
     * Sync many-to-many tags from a comma- or semicolon-separated string (admin / API style).
     */
    public function syncBlogTags(Blog $blog, ?string $raw): void
    {
        if ($raw === null || trim($raw) === '') {
            $blog->tags()->detach();

            return;
        }

        $names = collect(preg_split('/[,;]+/', $raw))
            ->map(fn ($s) => trim((string) $s))
            ->filter(fn ($s) => $s !== '')
            ->unique(fn ($s) => Str::lower($s))
            ->take(50);

        $ids = [];
        foreach ($names as $name) {
            $slug = Str::slug($name);
            if ($slug === '') {
                $slug = 'tag-'.substr(md5($name), 0, 12);
            }

            $tag = Tag::query()->firstOrCreate(
                ['slug' => $slug],
                ['name' => Str::limit($name, 120)]
            );

            $ids[] = $tag->id;
        }

        $blog->tags()->sync(array_values(array_unique($ids)));
    }

    /**
     * Sync tags from validated tag primary keys (admin multi-select).
     *
     * @param  array<int|string>|null  $tagIds
     */
    public function syncBlogTagIds(Blog $blog, ?array $tagIds): void
    {
        if ($tagIds === null || $tagIds === []) {
            $blog->tags()->detach();

            return;
        }

        $ids = collect($tagIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn (int $id) => $id > 0)
            ->unique()
            ->values()
            ->take(50)
            ->all();

        if ($ids === []) {
            $blog->tags()->detach();

            return;
        }

        $valid = Tag::query()->whereKey($ids)->pluck('id')->all();
        $blog->tags()->sync($valid);
    }
}
