@extends('admin.layout')

@section('title', 'Blogs')
@section('page_title', 'Blogs')
@section('page_subtitle', 'Review and edit site blog posts.')

@push('styles')
<style>
    .blog-thumb {
        width: 52px;
        height: 52px;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 1px solid var(--bs-border-color, #e5e7eb);
        background: #f8f9fa;
    }
    .blog-title-wrap { min-width: 0; }
    .blog-title-wrap a { max-width: 260px; }

    .listing-metrics {
        border-radius: 1rem;
        border: 1px solid rgba(52, 84, 209, 0.1);
        background:
            radial-gradient(ellipse 120% 80% at 100% 0%, rgba(52, 84, 209, 0.07), transparent 50%),
            radial-gradient(ellipse 90% 60% at 0% 100%, rgba(15, 23, 42, 0.04), transparent 45%),
            linear-gradient(180deg, #fbfcff 0%, #ffffff 100%);
        box-shadow: 0 14px 42px rgba(15, 23, 42, 0.06);
        padding: 1.25rem 1.35rem 1.35rem;
        margin-bottom: 1.5rem;
    }
    .listing-metrics-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: space-between;
        gap: 0.75rem 1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    }
    .listing-metrics-head h6 {
        margin: 0;
        font-size: 0.8125rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
    }
    .listing-metrics-head p {
        margin: 0.2rem 0 0;
        font-size: 0.8125rem;
        color: #94a3b8;
        max-width: 36rem;
        line-height: 1.45;
    }
    .listing-metric-tile {
        height: 100%;
        border-radius: 0.875rem;
        border: 1px solid rgba(15, 23, 42, 0.06);
        background: #fff;
        padding: 1rem 1.1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.875rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .listing-metric-tile:hover {
        border-color: rgba(52, 84, 209, 0.2);
        box-shadow: 0 8px 24px rgba(52, 84, 209, 0.08);
    }
    .listing-metric-icon {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.75rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
    }
    .listing-metric-icon--total { background: rgba(52, 84, 209, 0.1); color: #3454d1; }
    .listing-metric-icon--published { background: rgba(34, 197, 94, 0.12); color: #15803d; }
    .listing-metric-icon--draft { background: rgba(100, 116, 139, 0.12); color: #475569; }
    .listing-metric-icon--visible { background: rgba(14, 165, 233, 0.14); color: #0369a1; }
    .listing-metric-label {
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 0.2rem;
    }
    .listing-metric-value {
        font-size: 1.625rem;
        font-weight: 800;
        line-height: 1.1;
        color: #0f172a;
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.02em;
    }
    .listing-metric-hint {
        font-size: 0.6875rem;
        color: #cbd5e1;
        margin-top: 0.35rem;
    }
    @media (max-width: 575.98px) {
        .listing-metrics { padding: 1rem 1rem 1.1rem; }
        .listing-metric-value { font-size: 1.35rem; }
    }
</style>
@endpush

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <!-- <div class="page-header-title">
                    <h5 class="m-b-10">Blogs</h5>
                </div> -->
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Blogs</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i>
                            <span>Add post</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="row">
                <div class="col-12">

                    <div class="listing-metrics">
                        <div class="listing-metrics-head">
                            <div>
                                <h6>Blog overview</h6>
                                <p>Counts reflect your full post library, not only the current page.</p>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6 col-lg-3">
                                <div class="listing-metric-tile">
                                    <span class="listing-metric-icon listing-metric-icon--total"><i class="feather-file-text"></i></span>
                                    <div class="min-w-0">
                                        <div class="listing-metric-label">Total posts</div>
                                        <div class="listing-metric-value">{{ number_format($stats['total']) }}</div>
                                        <div class="listing-metric-hint">All records</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="listing-metric-tile">
                                    <span class="listing-metric-icon listing-metric-icon--published"><i class="feather-check-circle"></i></span>
                                    <div class="min-w-0">
                                        <div class="listing-metric-label">Published</div>
                                        <div class="listing-metric-value">{{ number_format($stats['published']) }}</div>
                                        <div class="listing-metric-hint">Status: live</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="listing-metric-tile">
                                    <span class="listing-metric-icon listing-metric-icon--draft"><i class="feather-edit-3"></i></span>
                                    <div class="min-w-0">
                                        <div class="listing-metric-label">Drafts</div>
                                        <div class="listing-metric-value">{{ number_format($stats['drafts']) }}</div>
                                        <div class="listing-metric-hint">Not published</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="listing-metric-tile">
                                    <span class="listing-metric-icon listing-metric-icon--visible"><i class="feather-eye"></i></span>
                                    <div class="min-w-0">
                                        <div class="listing-metric-label">Visible</div>
                                        <div class="listing-metric-value">{{ number_format($stats['visible']) }}</div>
                                        <div class="listing-metric-hint">Active on site</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card stretch stretch-full">
                        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <h5 class="card-title mb-0">
                                <i class="feather-edit-3 me-2 text-primary"></i>All posts
                            </h5>
                            <span class="badge bg-soft-primary text-primary">{{ $blogs->count() }} on this list</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Blog post</th>
                                            <th>Category</th>
                                            <th>Writer</th>
                                            <th>Date</th>
                                            <th>Views</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($blogs as $blog)
                                            @php
                                                $thumb = $blog->thumbnail;
                                                $thumbUrl = null;
                                                if ($thumb?->path) {
                                                    $rawPath = str_replace('\\', '/', $thumb->path);
                                                    $base = rtrim(request()->getBaseUrl(), '/');
                                                    $thumbUrl = $base !== ''
                                                        ? $base . '/storage/' . ltrim($rawPath, '/')
                                                        : asset('storage/' . ltrim($rawPath, '/'));
                                                }
                                                if ($thumbUrl === null && !empty($thumb?->url)) {
                                                    $thumbUrl = $thumb->url;
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        @if ($thumbUrl)
                                                            <img src="{{ $thumbUrl }}" alt="" class="blog-thumb" loading="lazy">
                                                        @else
                                                            <div class="blog-thumb d-flex align-items-center justify-content-center">
                                                                <i class="feather-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div class="blog-title-wrap">
                                                            <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="fw-bold d-block text-truncate text-dark">{{ $blog->name }}</a>
                                                            <small class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($blog->description), 50) }}</small>
                                                            @if ($blog->tags->isNotEmpty())
                                                                <div class="d-flex flex-wrap gap-1 mt-2">
                                                                    @foreach ($blog->tags->take(5) as $tag)
                                                                        <span class="badge bg-soft-info text-info fw-normal">{{ $tag->name }}</span>
                                                                    @endforeach
                                                                    @if ($blog->tags->count() > 5)
                                                                        <span class="badge bg-light text-muted">+{{ $blog->tags->count() - 5 }}</span>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($blog->blogCategory)
                                                        <span class="badge bg-soft-primary text-primary fw-normal">{{ $blog->blogCategory->name }}</span>
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($blog->writer_name)
                                                        <span class="badge bg-soft-primary text-primary">{{ $blog->writer_name }}</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td class="text-muted">{{ $blog->created_at?->format('M d, Y') ?? '—' }}</td>
                                                <td class="text-muted">{{ number_format((int) $blog->views) }}</td>
                                                <td>
                                                    @if($blog->status)
                                                        <span class="badge bg-soft-success text-success">Published</span>
                                                    @else
                                                        <span class="badge bg-soft-secondary text-secondary">Draft</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="avatar-text avatar-md" title="Edit">
                                                            <i class="feather-edit-3"></i>
                                                        </a>
                                                        <form method="POST" action="{{ route('admin.blogs.destroy', $blog->id) }}" class="d-inline js-admin-confirm-submit" data-confirm-title="Delete blog post" data-confirm-message="Delete this blog post?">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="avatar-text avatar-md border-0 bg-transparent text-danger" title="Delete">
                                                                <i class="feather-trash-2"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    <i class="feather-inbox fs-3 d-block mb-2 opacity-50"></i>
                                                    No blog posts found.
                                                </td>
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
