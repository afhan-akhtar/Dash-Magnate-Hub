@extends('admin.layout')

@section('title', 'Add blog tag')
@section('page_title', 'Add blog tag')
@section('page_subtitle', 'Create a reusable tag for blog posts.')

@push('styles')
    <style>
        .admin-blog-tag-form-shell .profile-form-card {
            border: 0;
            box-shadow: 0 18px 45px rgba(40, 60, 80, 0.08);
        }

        .admin-blog-tag-form-shell .profile-form-card .card-header {
            padding: 1.25rem 1.5rem 0;
            border-bottom: 0;
            background: transparent;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.35rem;
        }

        .admin-blog-tag-form-shell .profile-form-card .card-header .card-title {
            margin-bottom: 0;
        }

        .admin-blog-tag-form-shell .profile-form-card .card-body {
            padding: 1.5rem;
        }

        .admin-blog-tag-form-shell .profile-form-note {
            padding: 1rem 1.1rem;
            border: 1px dashed rgba(52, 84, 209, 0.25);
            border-radius: 1rem;
            background: rgba(52, 84, 209, 0.04);
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
                        <h5 class="m-b-10">Add blog tag</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.blog-tags.index') }}">Blog tags</a></li>
                        <li class="breadcrumb-item">Create</li>
                    </ul>
                </div>
            </div>

            <div class="main-content admin-blog-tag-form-shell">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <form method="POST" action="{{ route('admin.blog-tags.store') }}">
                            @csrf

                            <div class="card stretch stretch-full profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="feather-tag me-2 text-primary"></i>Tag details
                                    </h5>
                                    <p class="text-muted fs-12 mb-0">Tags can be attached to many posts. Slug is used in API filters.</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   name="name" value="{{ old('name') }}"
                                                   placeholder="e.g. Product launch" required maxlength="120">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Slug</label>
                                            <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                                   name="slug" value="{{ old('slug') }}" maxlength="140"
                                                   placeholder="Leave blank to auto-generate">
                                            @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="profile-form-note">
                                                <div class="fw-semibold text-dark mb-1">Slug tip</div>
                                                <div class="text-muted fs-12 mb-0">Lowercase letters, numbers, and hyphens. Leave empty to generate from the name.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center gap-3 mb-5">
                                <a href="{{ route('admin.blog-tags.index') }}" class="btn btn-light-brand px-4">
                                    <i class="feather-arrow-left me-1"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="feather-save me-1"></i> Create tag
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
