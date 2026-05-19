@extends('admin.layout')

@section('title', 'Listings')
@section('page_title', 'Listings')
@section('page_subtitle', 'Review and moderate marketplace listings.')

@push('styles')
<style>
    .listing-thumb {
        width: 52px;
        height: 52px;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 1px solid var(--bs-border-color, #e5e7eb);
        background: #f8f9fa;
    }
    .listing-name-wrap { min-width: 0; }
    .listing-name-wrap a { max-width: 240px; }

    /* Overview metrics — full-database counts from controller */
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
    .listing-metric-icon--active { background: rgba(34, 197, 94, 0.12); color: #15803d; }
    .listing-metric-icon--inactive { background: rgba(100, 116, 139, 0.12); color: #475569; }
    .listing-metric-icon--premium { background: rgba(245, 158, 11, 0.14); color: #b45309; }
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
                    <h5 class="m-b-10">Listings</h5>
                </div> -->
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Listings</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="row">
                <div class="col-12">

                    <div class="listing-metrics">
                        <div class="listing-metrics-head">
                            <div>
                                <h6>Marketplace overview</h6>
                                <p>Counts reflect your full listing catalog, not only the current page.</p>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6 col-lg-3">
                                <div class="listing-metric-tile">
                                    <span class="listing-metric-icon listing-metric-icon--total"><i class="feather-briefcase"></i></span>
                                    <div class="min-w-0">
                                        <div class="listing-metric-label">Total listings</div>
                                        <div class="listing-metric-value">{{ number_format($stats['total']) }}</div>
                                        <div class="listing-metric-hint">Including {{ number_format($stats['deleted']) }} removed</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="listing-metric-tile">
                                    <span class="listing-metric-icon listing-metric-icon--active"><i class="feather-check-circle"></i></span>
                                    <div class="min-w-0">
                                        <div class="listing-metric-label">On website</div>
                                        <div class="listing-metric-value">{{ number_format($stats['active']) }}</div>
                                        <div class="listing-metric-hint">Active &amp; not removed</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="listing-metric-tile">
                                    <span class="listing-metric-icon listing-metric-icon--inactive"><i class="feather-pause-circle"></i></span>
                                    <div class="min-w-0">
                                        <div class="listing-metric-label">Hidden</div>
                                        <div class="listing-metric-value">{{ number_format($stats['inactive']) }}</div>
                                        <div class="listing-metric-hint">Inactive (still in catalog)</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="listing-metric-tile">
                                    <span class="listing-metric-icon listing-metric-icon--premium"><i class="feather-star"></i></span>
                                    <div class="min-w-0">
                                        <div class="listing-metric-label">Premium</div>
                                        <div class="listing-metric-value">{{ number_format($stats['premium']) }}</div>
                                        <div class="listing-metric-hint">Featured tier</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Table card --}}
                    <div class="card stretch stretch-full">
                        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <h5 class="card-title mb-0">
                                <i class="feather-briefcase me-2 text-primary"></i>All listings
                            </h5>
                            <span class="badge bg-soft-primary text-primary">{{ $projects->count() }} on this list</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="proposalList">
                                    <thead>
                                        <tr>
                                            <th>Listing</th>
                                            <th>Owner</th>
                                            <th>Category</th>
                                            <th>Location</th>
                                            <th>Price</th>
                                            <th>Website</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($projects as $project)
                                            @php
                                                $thumb = $project->thumbnail;
                                                $thumbUrl = null;
                                                if ($thumb?->path) {
                                                    $rawPath = str_replace('\\', '/', $thumb->path);
                                                    $base = rtrim(request()->getBaseUrl(), '/');
                                                    $thumbUrl = $base !== ''
                                                        ? $base . '/storage/' . ltrim($rawPath, '/')
                                                        : asset('storage/' . ltrim($rawPath, '/'));
                                                }
                                                if ($thumbUrl === null && ! empty($thumb?->url)) {
                                                    $thumbUrl = $thumb->url;
                                                }
                                            @endphp
                                            <tr class="single-item">
                                                <td>
                                                    <div class="d-flex align-items-center gap-3 listing-name-wrap">
                                                        @if ($thumbUrl)
                                                            <img src="{{ $thumbUrl }}" alt="" class="listing-thumb flex-shrink-0" loading="lazy">
                                                        @else
                                                            <div class="listing-thumb flex-shrink-0 d-flex align-items-center justify-content-center bg-light">
                                                                <i class="feather-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div class="min-w-0">
                                                            <a href="{{ route('admin.projects.show', $project->id) }}" class="fw-bold d-block text-dark text-truncate">{{ $project->name }}</a>
                                                            <span class="text-muted fs-12"># {{ $project->id }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-dark small">{{ $project->professional?->full_name ?? '—' }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-info text-info">{{ $project->category?->name ?? '—' }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted small">{{ $project->location?->name ?? '—' }}</span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-dark">
                                                        @if ($project->price !== null)
                                                            ${{ number_format((float) $project->price) }}
                                                        @else
                                                            —
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <form method="POST" action="{{ route('admin.projects.update-status', $project->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="active" class="form-select form-select-sm" style="min-width: 100px;" onchange="this.form.submit()" @if ($project->isDeleted()) disabled title="Listing is removed" @endif>
                                                            <option value="1" {{ (int) $project->active === 1 ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ (int) $project->active === 0 ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </form>
                                                </td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <a href="{{ route('admin.projects.show', $project->id) }}" class="avatar-text avatar-md" title="View">
                                                            <i class="feather-eye text-primary"></i>
                                                        </a>
                                                        <form method="POST" action="{{ route('admin.projects.destroy', $project->id) }}" class="d-inline js-admin-confirm-submit" data-confirm-title="Delete listing" data-confirm-message="Delete this listing?">
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
                                                    <i class="feather-inbox fs-2 d-block mb-2"></i>
                                                    No marketplace listings found.
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
