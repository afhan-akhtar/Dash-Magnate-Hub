<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ProjectService
{
    public function __construct(private FileUploadService $fileUpload) {}

    public function getPublicListing(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Project::with(['category', 'location', 'region', 'thumbnail'])
            ->visibleOnWebsite();

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['location_id'])) {
            $query->where('location_id', $filters['location_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function findByUrl(string $url): ?Project
    {
        return Project::with(['category', 'location', 'region', 'professional', 'documents'])
            ->where('url', $url)
            ->visibleOnWebsite()
            ->first();
    }

    public function createProject(array $data, int $professionalId): Project
    {
        $data['professional_id'] = $professionalId;
        $data['user_id'] = $professionalId;
        $data['code']            = md5(uniqid());
        $data['url']             = Str::slug($data['name']) . '-' . time();
        $data['active'] = (int) ($data['active'] ?? 1);
        $data['status'] = 0;
        $data['deleted_at'] = null;

        return Project::create($data);
    }

    public function updateProject(Project $project, array $data): Project
    {
        $project->update($data);
        return $project->fresh();
    }

    public function deleteProject(Project $project): void
    {
        $project->update([
            'status' => 1,
            'active' => 0,
            'deleted_at' => now(),
        ]);
    }

    public function incrementViews(Project $project): void
    {
        $project->increment('views');
    }
}
