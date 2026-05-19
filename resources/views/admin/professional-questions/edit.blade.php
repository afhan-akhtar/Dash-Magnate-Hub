@extends('admin.layout')

@section('title', 'Edit professional question')
@section('page_title', 'Edit question')
@section('page_subtitle', 'Update role, wording, options, and visibility.')

@push('styles')
    <style>
        .admin-pq-form-shell .profile-form-card {
            border: 0;
            box-shadow: 0 18px 45px rgba(40, 60, 80, 0.08);
        }
        .admin-pq-form-shell .profile-form-card .card-header {
            padding: 1.25rem 1.5rem 0;
            border-bottom: 0;
            background: transparent;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.35rem;
        }
        .admin-pq-form-shell .profile-form-card .card-body {
            padding: 1.5rem;
        }
    </style>
@endpush

@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="fw-semibold mb-2">Please review the form errors.</div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Edit question</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.professional-questions.index') }}">Professional questions</a></li>
                        <li class="breadcrumb-item">#{{ $question->id }}</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <a href="{{ route('admin.professional-questions.index') }}" class="btn btn-light-brand">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Back to list</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="main-content admin-pq-form-shell">
                <div class="row">
                    <div class="col-12">

                        <form method="POST" action="{{ route('admin.professional-questions.update', $question->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="card stretch stretch-full profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="feather-help-circle me-2 text-primary"></i>Question details
                                    </h5>
                                    <p class="text-muted fs-12 mb-0">Audience role, answer type, and the text shown to professionals.</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Role <span class="text-danger">*</span></label>
                                            <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role }}" @selected(old('role', $question->role) === $role)>
                                                        {{ \Illuminate\Support\Str::headline(str_replace('_', ' ', $role)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Answer type <span class="text-danger">*</span></label>
                                            <select class="form-select @error('type') is-invalid @enderror" name="type" id="question-type" required>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type }}" @selected(old('type', $question->type) === $type)>
                                                        {{ \App\Models\ProfessionalQuestion::typeLabel($type) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Question text <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('question') is-invalid @enderror" name="question" value="{{ old('question', $question->question) }}" required maxlength="255">
                                            @error('question')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card stretch stretch-full profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="feather-settings me-2 text-primary"></i>Settings
                                    </h5>
                                    <p class="text-muted fs-12 mb-0">Whether the question is mandatory and shown in live flows.</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded border bg-light-subtle">
                                                <div>
                                                    <p class="mb-0 fw-semibold">Required</p>
                                                    <small class="text-muted">Professionals must answer this</small>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="is_required" name="is_required" value="1" @checked(old('is_required', $question->is_required))>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded border bg-light-subtle">
                                                <div>
                                                    <p class="mb-0 fw-semibold">Active</p>
                                                    <small class="text-muted">Use in onboarding</small>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" value="1" @checked(old('status', $question->status))>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Sort order</label>
                                            <input type="number" min="0" class="form-control" name="sort_order" value="{{ old('sort_order', $question->sort_order) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card stretch stretch-full profile-form-card mb-4">
                                <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <div>
                                        <h5 class="card-title mb-0">
                                            <i class="feather-list me-2 text-primary"></i>Answer options
                                        </h5>
                                        <p class="text-muted fs-12 mb-0">Used when the type is single or multiple choice.</p>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-option">
                                        <i class="feather-plus me-1"></i>
                                        <span>Add option</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="options-block" style="display: none;">
                                        <div id="option-rows">
                                            @php $existingOptions = old('options', $question->options->map(fn ($o) => ['label' => $o->label, 'sort_order' => $o->sort_order])->toArray()); @endphp
                                            @foreach ($existingOptions as $index => $option)
                                                <div class="row g-2 align-items-end option-row mb-3">
                                                    <div class="col-12 col-md-7">
                                                        <label class="form-label small text-muted mb-1">Label</label>
                                                        <input type="text" class="form-control" name="options[{{ $index }}][label]" placeholder="Option label" value="{{ $option['label'] ?? '' }}">
                                                    </div>
                                                    <div class="col-12 col-md-3">
                                                        <label class="form-label small text-muted mb-1">Sort order</label>
                                                        <input type="number" min="0" class="form-control" name="options[{{ $index }}][sort_order]" value="{{ $option['sort_order'] ?? ($index + 1) }}">
                                                    </div>
                                                    <div class="col-12 col-md-2 text-md-end">
                                                        <button type="button" class="btn btn-sm btn-outline-danger remove-option w-100 w-md-auto" title="Remove this option">
                                                            <i class="feather-x me-1"></i>
                                                            <span>Remove</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <p id="options-hint-when-hidden" class="text-muted small mb-0">Change the answer type to single or multiple choice to edit options.</p>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-5">
                                <a href="{{ route('admin.professional-questions.index') }}" class="btn btn-light-brand px-4">
                                    <i class="feather-x me-1"></i>
                                    <span>Cancel</span>
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="feather-save me-2"></i>
                                    <span>Save changes</span>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        (function () {
            const typeSelect = document.getElementById('question-type');
            const optionsBlock = document.getElementById('options-block');
            const optionsHint = document.getElementById('options-hint-when-hidden');
            const optionRows = document.getElementById('option-rows');
            const addBtn = document.getElementById('add-option');

            const toggleOptions = () => {
                const val = typeSelect.value;
                const show = (val === 'single_choice' || val === 'multi_choice');
                optionsBlock.style.display = show ? 'block' : 'none';
                if (optionsHint) optionsHint.style.display = show ? 'none' : 'block';
            };

            addBtn?.addEventListener('click', () => {
                const index = optionRows.querySelectorAll('.option-row').length;
                const wrapper = document.createElement('div');
                wrapper.className = 'row g-2 align-items-end option-row mb-3';
                wrapper.innerHTML = `
                    <div class="col-12 col-md-7">
                        <label class="form-label small text-muted mb-1">Label</label>
                        <input type="text" class="form-control" name="options[${index}][label]" placeholder="Option label">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small text-muted mb-1">Sort order</label>
                        <input type="number" min="0" class="form-control" name="options[${index}][sort_order]" value="${index + 1}">
                    </div>
                    <div class="col-12 col-md-2 text-md-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-option w-100 w-md-auto" title="Remove this option">
                            <i class="feather-x me-1"></i>
                            <span>Remove</span>
                        </button>
                    </div>
                `;
                optionRows.appendChild(wrapper);
            });

            optionRows?.addEventListener('click', (e) => {
                const btn = e.target.closest('.remove-option');
                if (btn) btn.closest('.option-row')?.remove();
            });

            typeSelect?.addEventListener('change', toggleOptions);
            toggleOptions();
        })();
    </script>
@endpush
