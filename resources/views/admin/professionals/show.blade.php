@extends('admin.layout')

@section('title', 'Professional — ' . $professional->full_name)
@section('page_title', 'Professional profile')
@section('page_subtitle', $professional->email)

@push('styles')
    <style>
        .ap-shell { padding-bottom: 2rem; }
        .ap-card {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 12px 40px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            margin-bottom: 1.25rem;
        }
        .ap-card-hd {
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
        .ap-card-bd { padding: 1.15rem 1.35rem 1.35rem; }
        .ap-meta {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.75rem;
        }
        .ap-meta-item {
            padding: 0.75rem 0.9rem;
            border-radius: 0.65rem;
            border: 1px solid rgba(15, 23, 42, 0.06);
            background: #fafbfd;
        }
        .ap-meta-label {
            font-size: 0.625rem;
            font-weight: 800;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 0.25rem;
        }
        .ap-meta-val {
            font-size: 0.9rem;
            font-weight: 600;
            color: #0f172a;
            word-break: break-word;
        }
        .ap-hero {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            gap: 1.25rem;
        }
        .ap-avatar {
            width: 88px;
            height: 88px;
            border-radius: 1rem;
            object-fit: cover;
            border: 1px solid rgba(15, 23, 42, 0.08);
            background: #f1f5f9;
            flex-shrink: 0;
        }
        .ap-hero-body { min-width: 0; flex: 1; }
        .ap-hero-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.35rem;
            letter-spacing: -0.02em;
        }
        .ap-table thead th {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #64748b;
            border-bottom-width: 1px;
        }
    </style>
@endpush

@section('content')
    @php
        $thumb = $professional->thumbnail;
        $avatarUrl = $thumb?->url ?: ($thumb?->path ? asset(ltrim(str_replace('\\', '/', $thumb->path), '/')) : null);
        $roleLabel = \Illuminate\Support\Str::headline((string) $professional->role);
    @endphp
    <main class="nxl-container">
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Professional profile</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.professionals.index') }}">Professionals</a></li>
                        <li class="breadcrumb-item">{{ $professional->full_name }}</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                            <a href="{{ route('admin.professionals.index') }}" class="btn btn-light-brand">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Back to directory</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-content ap-shell">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">

                        <div class="ap-card">
                            <div class="ap-card-hd">
                                <i class="feather-user"></i> Summary
                            </div>
                            <div class="ap-card-bd">
                                <div class="ap-hero mb-4">
                                    @if ($avatarUrl)
                                        <img src="{{ $avatarUrl }}" alt="" class="ap-avatar" width="88" height="88">
                                    @else
                                        <span class="ap-avatar d-inline-flex align-items-center justify-content-center fs-2 fw-bold text-secondary bg-light">{{ \Illuminate\Support\Str::substr($professional->full_name, 0, 1) }}</span>
                                    @endif
                                    <div class="ap-hero-body">
                                        <h1 class="ap-hero-title">{{ $professional->full_name }}</h1>
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                            <span class="badge bg-soft-secondary text-secondary">{{ $roleLabel }}</span>
                                            @if ($professional->verified)
                                                <span class="badge bg-soft-success text-success">Verified</span>
                                            @else
                                                <span class="badge bg-soft-warning text-warning">Not verified</span>
                                            @endif
                                            @if ($professional->status === 0)
                                                <span class="badge bg-soft-primary text-primary">Active</span>
                                            @else
                                                <span class="badge bg-soft-danger text-danger">Inactive</span>
                                            @endif
                                        </div>
                                        <p class="text-muted small mb-0">Member since {{ optional($professional->created_at)->format('M j, Y \a\t g:i A') }}</p>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-12 col-md-6 col-xl-4">
                                        <label class="form-label small text-muted mb-1">Verification</label>
                                        <form method="POST" action="{{ route('admin.professionals.update-status', $professional->id) }}" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <select name="verified" class="form-select" onchange="this.form.submit()">
                                                <option value="1" @selected($professional->verified)>Verified</option>
                                                <option value="0" @selected(! $professional->verified)>Not verified</option>
                                            </select>
                                        </form>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-4">
                                        <label class="form-label small text-muted mb-1">Account status</label>
                                        <form method="POST" action="{{ route('admin.professionals.update-status', $professional->id) }}" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select" onchange="this.form.submit()">
                                                <option value="0" @selected($professional->status == 0)>Active</option>
                                                <option value="1" @selected($professional->status == 1)>Inactive</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>

                                <div class="ap-meta">
                                    <div class="ap-meta-item">
                                        <div class="ap-meta-label">Email</div>
                                        <div class="ap-meta-val">{{ $professional->email ?: '—' }}</div>
                                    </div>
                                    <div class="ap-meta-item">
                                        <div class="ap-meta-label">Phone</div>
                                        <div class="ap-meta-val">{{ $professional->phone ?: '—' }}</div>
                                    </div>
                                    <div class="ap-meta-item">
                                        <div class="ap-meta-label">Company</div>
                                        <div class="ap-meta-val">{{ $professional->company_name ?: '—' }}</div>
                                    </div>
                                    <div class="ap-meta-item">
                                        <div class="ap-meta-label">Nationality</div>
                                        <div class="ap-meta-val">{{ $professional->nationality ?: '—' }}</div>
                                    </div>
                                    <div class="ap-meta-item">
                                        <div class="ap-meta-label">User ID</div>
                                        <div class="ap-meta-val">{{ $professional->id }}</div>
                                    </div>
                                    <div class="ap-meta-item">
                                        <div class="ap-meta-label">Last updated</div>
                                        <div class="ap-meta-val">{{ optional($professional->updated_at)->format('M j, Y g:i A') ?: '—' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ap-card">
                            <div class="ap-card-hd">
                                <i class="feather-credit-card"></i> Subscription plans
                            </div>
                            <div class="ap-card-bd p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover ap-table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">Type</th>
                                                <th>Expiry</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($professional->plans as $plan)
                                                <tr>
                                                    <td class="ps-4 fw-semibold text-dark">{{ $plan->type ?? '—' }}</td>
                                                    <td class="text-muted">{{ optional($plan->expiry)->format('M j, Y') ?: '—' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted py-5">No plans on file.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="ap-card">
                            <div class="ap-card-hd">
                                <i class="feather-layers"></i> Listings
                            </div>
                            <div class="ap-card-bd p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover ap-table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">Name</th>
                                                <th class="text-end pe-4">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($professional->projects as $project)
                                                <tr>
                                                    <td class="ps-4">
                                                        <span class="fw-semibold text-dark">{{ $project->name }}</span>
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        <a href="{{ route('admin.projects.show', $project->id) }}" class="btn btn-sm btn-light-brand">
                                                            <i class="feather-external-link me-1"></i>
                                                            <span>Open in admin</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted py-5">No listings yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="ap-card">
                            <div class="ap-card-hd">
                                <i class="feather-help-circle"></i> Onboarding answers
                            </div>
                            <div class="ap-card-bd p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover ap-table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">Question</th>
                                                <th>Type</th>
                                                <th class="pe-4">Answer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($professional->professionalQuestionAnswers as $answer)
                                                <tr>
                                                    <td class="ps-4 fw-semibold text-dark">{{ $answer->question?->question ?? '—' }}</td>
                                                    <td>
                                                        @if ($answer->question?->type)
                                                            <span class="badge bg-soft-secondary text-secondary fw-normal">{{ \App\Models\ProfessionalQuestion::typeLabel((string) $answer->question->type) }}</span>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="pe-4 text-muted">
                                                        @if ($answer->question?->type === 'single_choice')
                                                            @php
                                                                $optionId = $answer->selected_option_ids[0] ?? null;
                                                                $label = $answer->question?->options->firstWhere('id', $optionId)?->label;
                                                            @endphp
                                                            {{ $label ?? '—' }}
                                                        @elseif ($answer->question?->type === 'multi_choice')
                                                            @php
                                                                $labels = $answer->question?->options
                                                                    ->whereIn('id', $answer->selected_option_ids ?? [])
                                                                    ->pluck('label')
                                                                    ->implode(', ');
                                                            @endphp
                                                            {{ $labels ?: '—' }}
                                                        @else
                                                            {{ $answer->answer_text ?: '—' }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-5">No answers submitted.</td>
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
