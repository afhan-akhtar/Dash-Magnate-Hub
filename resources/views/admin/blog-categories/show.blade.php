@extends('admin.layout')

@section('title', 'Blog category — ' . $category->name)
@section('page_title', 'Blog category')
@section('page_subtitle', \Illuminate\Support\Str::limit($category->name, 80))

@push('styles')
    <style>
        .bc-shell { padding-bottom: 2rem; }
        .bc-card {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 12px 40px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            margin-bottom: 1.25rem;
        }
        .bc-card-hd {
            padding: 0.85rem 1.25rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            font-size: 0.6875rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #3454d1;
            background: linear-gradient(90deg, rgba(52, 84, 209, 0.06) 0%, #fff 50%);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .bc-card-bd { padding: 1.15rem 1.35rem 1.35rem; }
        .bc-meta {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.75rem;
        }
        .bc-meta-item {
            padding: 0.75rem 0.9rem;
            border-radius: 0.65rem;
            border: 1px solid rgba(15, 23, 42, 0.06);
            background: #fafbfd;
        }
        .bc-meta-label {
            font-size: 0.625rem;
            font-weight: 800;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 0.25rem;
        }
        .bc-meta-val {
            font-size: 0.9rem;
            font-weight: 600;
            color: #0f172a;
            word-break: break-word;
        }
        .bc-table .table { margin-bottom: 0; }
    </style>
@endpush

@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Blog category</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.blog-categories.index') }}">Blog categories</a></li>
                        <li class="breadcrumb-item">{{ \Illuminate\Support\Str::limit($category->name, 36) }}</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper flex-wrap justify-content-end">
                            <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-light-brand">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Back to list</span>
                            </a>
                            <a href="{{ route('admin.blog-categories.edit', $category->id) }}" class="btn btn-primary">
                                <i class="feather-edit-3 me-2"></i>
                                <span>Edit</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-content bc-shell">
                <div class="row">
                    <div class="col-12">
                        <div class="bc-card">
                            <div class="bc-card-hd">
                                <i class="feather-folder"></i> Summary
                            </div>
                            <div class="bc-card-bd">
                                <div class="bc-meta">
                                    <div class="bc-meta-item">
                                        <div class="bc-meta-label">ID</div>
                                        <div class="bc-meta-val">{{ $category->id }}</div>
                                    </div>
                                    <div class="bc-meta-item">
                                        <div class="bc-meta-label">Name</div>
                                        <div class="bc-meta-val">{{ $category->name }}</div>
                                    </div>
                                    <div class="bc-meta-item">
                                        <div class="bc-meta-label">Slug</div>
                                        <div class="bc-meta-val"><code class="text-primary">{{ $category->slug }}</code></div>
                                    </div>
                                    <div class="bc-meta-item">
                                        <div class="bc-meta-label">Sort order</div>
                                        <div class="bc-meta-val">{{ $category->sort_order }}</div>
                                    </div>
                                    <div class="bc-meta-item">
                                        <div class="bc-meta-label">Active posts</div>
                                        <div class="bc-meta-val">{{ $category->active_blogs_count }}</div>
                                    </div>
                                    <div class="bc-meta-item">
                                        <div class="bc-meta-label">Status</div>
                                        <div class="bc-meta-val">
                                            @if ($category->status)
                                                <span class="badge bg-soft-success text-success text-uppercase">Active</span>
                                            @else
                                                <span class="badge bg-soft-danger text-danger text-uppercase">Inactive</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="bc-meta-item">
                                        <div class="bc-meta-label">Created</div>
                                        <div class="bc-meta-val">{{ $category->created_at?->format('Y-m-d, h:iA') ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bc-card">
                            <div class="bc-card-hd">
                                <i class="feather-file-text"></i> Recent posts in this category
                            </div>
                            <div class="bc-card-bd p-0">
                                <div class="table-responsive bc-table">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-4">Title</th>
                                                <th>URL</th>
                                                <th>Published</th>
                                                <th class="text-end pe-4">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($category->blogs as $blog)
                                                <tr>
                                                    <td class="ps-4 fw-semibold">{{ \Illuminate\Support\Str::limit($blog->name, 70) }}</td>
                                                    <td><code class="small text-primary">{{ $blog->url ?? '—' }}</code></td>
                                                    <td>
                                                        @if ((int) $blog->active === 1)
                                                            <span class="badge bg-soft-success text-success">Active</span>
                                                        @else
                                                            <span class="badge bg-soft-secondary text-secondary">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-light-brand">Edit post</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-4">No posts use this category yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
