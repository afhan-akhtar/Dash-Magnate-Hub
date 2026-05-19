@extends('admin.layout')

@section('title', 'Professionals')
@section('page_title', 'Professionals')
@section('page_subtitle', 'Review portal accounts, verification, and access status.')

@push('styles')
    <style>
        .prof-thumb {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid var(--bs-border-color, #e5e7eb);
            background: #f8f9fa;
        }
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
        .listing-metric-icon--verified { background: rgba(34, 197, 94, 0.12); color: #15803d; }
        .listing-metric-icon--active { background: rgba(14, 165, 233, 0.14); color: #0369a1; }
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
    </style>
@endpush

@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <!-- <div class="page-header-title">
                        <h5 class="m-b-10">Professionals</h5>
                    </div> -->
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Professionals</li>
                    </ul>
                </div>
            </div>

            <div class="main-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">

                        <div class="listing-metrics">
                            <div class="listing-metrics-head">
                                <div>
                                    <h6>Directory overview</h6>
                                    <p>Totals include every professional profile that is not marked as removed.</p>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <div class="listing-metric-tile">
                                        <span class="listing-metric-icon listing-metric-icon--total"><i class="feather-users"></i></span>
                                        <div class="min-w-0">
                                            <div class="listing-metric-label">Total profiles</div>
                                            <div class="listing-metric-value">{{ number_format($stats['total']) }}</div>
                                            <div class="listing-metric-hint">In directory</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="listing-metric-tile">
                                        <span class="listing-metric-icon listing-metric-icon--verified"><i class="feather-shield"></i></span>
                                        <div class="min-w-0">
                                            <div class="listing-metric-label">Verified</div>
                                            <div class="listing-metric-value">{{ number_format($stats['verified']) }}</div>
                                            <div class="listing-metric-hint">Email or account verified</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="listing-metric-tile">
                                        <span class="listing-metric-icon listing-metric-icon--active"><i class="feather-activity"></i></span>
                                        <div class="min-w-0">
                                            <div class="listing-metric-label">Active access</div>
                                            <div class="listing-metric-value">{{ number_format($stats['active']) }}</div>
                                            <div class="listing-metric-hint">Status: active</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <h5 class="card-title mb-0">
                                    <i class="feather-briefcase me-2 text-primary"></i>All professionals
                                </h5>
                                <span class="badge bg-soft-primary text-primary">{{ $professionals->count() }} {{ $professionals->count() === 1 ? 'record' : 'records' }}</span>
                            </div>

                            @if ($professionals->isEmpty())
                                <div class="card-body py-5">
                                    <div class="text-center text-muted mx-auto" style="max-width: 420px;">
                                        <span class="avatar-text avatar-xl bg-soft-primary text-primary mx-auto mb-3 d-inline-flex">
                                            <i class="feather-users fs-3"></i>
                                        </span>
                                        <h6 class="text-dark fw-semibold mb-2">No professionals yet</h6>
                                        <p class="small mb-0">When users complete professional signup they will appear in this list.</p>
                                    </div>
                                </div>
                            @else
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0" id="adminProfessionalsTable">
                                            <thead>
                                                <tr>
                                                    <th>Professional</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Verified</th>
                                                    <th>Status</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($professionals as $professional)
                                                    @php
                                                        $thumb = $professional->thumbnail;
                                                        $thumbUrl = $thumb?->url ?: ($thumb?->path ? asset(ltrim(str_replace('\\', '/', $thumb->path), '/')) : null);
                                                        $roleLabel = \Illuminate\Support\Str::headline((string) $professional->role);
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="hstack gap-3">
                                                                @if ($thumbUrl)
                                                                    <img src="{{ $thumbUrl }}" alt="" class="prof-thumb" width="44" height="44">
                                                                @else
                                                                    <span class="avatar-text avatar-md bg-soft-secondary text-secondary">{{ \Illuminate\Support\Str::substr($professional->full_name, 0, 1) }}</span>
                                                                @endif
                                                                <div class="min-w-0">
                                                                    <a href="{{ route('admin.professionals.show', $professional->id) }}" class="fw-bold text-dark d-block text-truncate">{{ $professional->full_name }}</a>
                                                                    <small class="text-muted">ID {{ $professional->id }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span class="text-muted small text-break">{{ $professional->email }}</span></td>
                                                        <td>
                                                            <span class="badge bg-soft-secondary text-secondary fw-normal">{{ $roleLabel }}</span>
                                                        </td>
                                                        <td>
                                                            @if ($professional->verified)
                                                                <span class="badge bg-soft-success text-success fw-normal">Verified</span>
                                                            @else
                                                                <span class="badge bg-soft-warning text-warning fw-normal">Not verified</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($professional->status == 0)
                                                                <span class="badge bg-soft-primary text-primary fw-normal">Active</span>
                                                            @else
                                                                <span class="badge bg-soft-danger text-danger fw-normal">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="gap-2 justify-content-end flex-wrap">
                                                                <a href="{{ route('admin.professionals.show', $professional->id) }}" class="btn btn-sm btn-light-brand">
                                                                    <i class="feather-eye me-1"></i>
                                                                    <span>View profile</span>
                                                                </a>
                                                                
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
