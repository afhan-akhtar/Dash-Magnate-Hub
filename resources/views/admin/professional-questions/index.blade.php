@extends('admin.layout')

@section('title', 'Professional questions')
@section('page_title', 'Onboarding questions')
@section('page_subtitle', 'Role-specific questions shown during professional signup.')

@push('styles')
    <style>
        .pq-metrics {
            border-radius: 1rem;
            border: 1px solid rgba(52, 84, 209, 0.1);
            background:
                radial-gradient(ellipse 120% 80% at 100% 0%, rgba(52, 84, 209, 0.07), transparent 50%),
                linear-gradient(180deg, #fbfcff 0%, #ffffff 100%);
            box-shadow: 0 14px 42px rgba(15, 23, 42, 0.06);
            padding: 1.25rem 1.35rem 1.35rem;
            margin-bottom: 1.5rem;
        }
        .pq-metrics-head {
            margin-bottom: 1rem;
            padding-bottom: 0.85rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        }
        .pq-metrics-head h6 {
            margin: 0;
            font-size: 0.8125rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #64748b;
        }
        .pq-metrics-head p {
            margin: 0.35rem 0 0;
            font-size: 0.8125rem;
            color: #94a3b8;
            max-width: 40rem;
            line-height: 1.45;
        }
        .pq-tile {
            height: 100%;
            border-radius: 0.875rem;
            border: 1px solid rgba(15, 23, 42, 0.06);
            background: #fff;
            padding: 1rem 1.1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
        }
        .pq-tile-ic {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
            background: rgba(52, 84, 209, 0.1);
            color: #3454d1;
        }
        .pq-tile-ic--on { background: rgba(34, 197, 94, 0.12); color: #15803d; }
        .pq-tile-lb {
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: #94a3b8;
        }
        .pq-tile-val {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            font-variant-numeric: tabular-nums;
        }
    </style>
@endpush

@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <!-- <div class="page-header-title">
                        <h5 class="m-b-10">Onboarding questions</h5>
                    </div> -->
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Professional questions</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                            <a href="{{ route('admin.professional-questions.create') }}" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>Add question</span>
                            </a>
                        </div>
                    </div>
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

                        <div class="pq-metrics">
                            <div class="pq-metrics-head">
                                <h6>Library overview</h6>
                                <p>Questions are grouped by role in the table below. Active items are used in live onboarding flows.</p>
                            </div>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="pq-tile">
                                        <span class="pq-tile-ic"><i class="feather-help-circle"></i></span>
                                        <div>
                                            <div class="pq-tile-lb">Total questions</div>
                                            <div class="pq-tile-val">{{ number_format($stats['total']) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="pq-tile">
                                        <span class="pq-tile-ic pq-tile-ic--on"><i class="feather-check-circle"></i></span>
                                        <div>
                                            <div class="pq-tile-lb">Active</div>
                                            <div class="pq-tile-val">{{ number_format($stats['active']) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <h5 class="card-title mb-0">
                                    <i class="feather-list me-2 text-primary"></i>All questions
                                </h5>
                                <span class="badge bg-soft-primary text-primary">{{ $questions->count() }} {{ $questions->count() === 1 ? 'record' : 'records' }}</span>
                            </div>

                            @if ($questions->isEmpty())
                                <div class="card-body py-5">
                                    <div class="text-center text-muted mx-auto" style="max-width: 420px;">
                                        <span class="avatar-text avatar-xl bg-soft-primary text-primary mx-auto mb-3 d-inline-flex">
                                            <i class="feather-help-circle fs-3"></i>
                                        </span>
                                        <h6 class="text-dark fw-semibold mb-2">No questions yet</h6>
                                        <p class="small mb-4">Create your first role-based question to collect structured answers during professional onboarding.</p>
                                        <a href="{{ route('admin.professional-questions.create') }}" class="btn btn-primary">
                                            <i class="feather-plus me-2"></i>
                                            <span>Add question</span>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0" id="adminProfessionalQuestionsTable">
                                            <thead>
                                                <tr>
                                                    <th class="ps-4">Question</th>
                                                    <th>Role</th>
                                                    <th>Type</th>
                                                    <th>Required</th>
                                                    <th>Status</th>
                                                    <th class="text-end pe-4">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($questions as $question)
                                                    @php
                                                        $roleLabel = \Illuminate\Support\Str::headline(str_replace('_', ' ', (string) $question->role));
                                                        $typeLabel = \App\Models\ProfessionalQuestion::typeLabel((string) $question->type);
                                                        $optCount = $question->options->count();
                                                    @endphp
                                                    <tr>
                                                        <td class="ps-4">
                                                            <span class="fw-semibold text-dark d-block">{{ \Illuminate\Support\Str::limit($question->question, 100) }}</span>
                                                            @if (in_array($question->type, ['single_choice', 'multi_choice'], true) && $optCount > 0)
                                                                <small class="text-muted">{{ $optCount }} {{ $optCount === 1 ? 'option' : 'options' }}</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-soft-primary text-primary fw-normal">{{ $roleLabel }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-soft-secondary text-secondary fw-normal">{{ $typeLabel }}</span>
                                                        </td>
                                                        <td>
                                                            @if ($question->is_required)
                                                                <span class="badge bg-soft-danger text-danger fw-normal">Required</span>
                                                            @else
                                                                <span class="badge bg-soft-secondary text-secondary fw-normal">Optional</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($question->status)
                                                                <span class="badge bg-soft-success text-success fw-normal">Active</span>
                                                            @else
                                                                <span class="badge bg-soft-warning text-warning fw-normal">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-end pe-4">
                                                            <div class="hstack gap-2 justify-content-end flex-wrap">
                                                                <a href="{{ route('admin.professional-questions.edit', $question->id) }}" class="btn btn-sm btn-light-brand">
                                                                    <i class="feather-edit-3 me-1"></i>
                                                                    <span>Edit question</span>
                                                                </a>
                                                                <form method="POST" action="{{ route('admin.professional-questions.destroy', $question->id) }}" class="d-inline m-0 js-admin-confirm-submit" data-confirm-title="Delete question" data-confirm-message="Delete this question? Existing answers may become orphaned.">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                        <i class="feather-trash-2 me-1"></i>
                                                                        <span>Delete</span>
                                                                    </button>
                                                                </form>
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
