@extends('admin.layout')

@section('title', 'Create Location')
@section('page_title', 'Create Location')
@section('page_subtitle', 'Add a new location with name, URL, visibility, and optional image.')

@push('styles')
    <style>
        .admin-location-form-shell .profile-form-card {
            border: 0;
            box-shadow: 0 18px 45px rgba(40, 60, 80, 0.08);
        }

        .admin-location-form-shell .profile-form-card .card-header {
            padding: 1.25rem 1.5rem 0;
            border-bottom: 0;
            background: transparent;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.35rem;
        }

        .admin-location-form-shell .profile-form-card .card-header .card-title {
            margin-bottom: 0;
        }

        .admin-location-form-shell .profile-form-card .card-body {
            padding: 1.5rem;
        }

        .admin-location-form-shell .profile-form-note {
            padding: 1rem 1.1rem;
            border: 1px dashed rgba(52, 84, 209, 0.25);
            border-radius: 1rem;
            background: rgba(52, 84, 209, 0.04);
        }

        .admin-doc-thumb-preview {
            min-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: repeating-conic-gradient(var(--bs-gray-200, #e9ecef) 0% 25%, #fff 0% 50%) 50% / 20px 20px;
        }

        .admin-doc-thumb-img {
            max-height: 200px;
            max-width: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            vertical-align: middle;
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
                        <h5 class="m-b-10">Create location</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.locations.index') }}">Locations</a></li>
                        <li class="breadcrumb-item">Create</li>
                    </ul>
                </div>
            </div>

            <div class="main-content admin-location-form-shell">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <form method="POST" action="{{ route('admin.locations.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="card profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">Basic information</h5>
                                    <p class="text-muted fs-12 mb-0">Name and URL path used across listings and navigation.</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Location name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   name="name" value="{{ old('name') }}"
                                                   placeholder="e.g. Victoria - VIC" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">URL slug <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('url') is-invalid @enderror"
                                                   name="url" value="{{ old('url') }}"
                                                   placeholder="e.g. victoria-vic" required>
                                            @error('url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="profile-form-note">
                                                <div class="fw-semibold text-dark mb-1">URL tip</div>
                                                <div class="text-muted fs-12 mb-0">Use lowercase letters, numbers, and hyphens only. This becomes the public path segment for the location.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">Visibility</h5>
                                    <p class="text-muted fs-12 mb-0">Control whether the location is enabled and shown on the site.</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded border bg-light-subtle">
                                                <div>
                                                    <p class="mb-0 fw-semibold">Active status</p>
                                                    <small class="text-muted">Enable or disable this location</small>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                           id="status" name="status" value="1"
                                                           {{ old('status', true) ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded border bg-light-subtle">
                                                <div>
                                                    <p class="mb-0 fw-semibold">Visible</p>
                                                    <small class="text-muted">Show this location publicly</small>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                           id="active" name="active" value="1"
                                                           {{ old('active', true) ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">Location image</h5>
                                    <p class="text-muted fs-12 mb-0">Optional thumbnail for cards and listings (SVG, PNG, JPG, GIF).</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4 align-items-start">
                                        <div class="col-12">
                                            <label class="form-label">Upload image</label>
                                            <div class="upload-area border rounded p-4 text-center position-relative"
                                                 id="upload-area"
                                                 style="border-style: dashed !important; cursor: pointer; transition: background 0.2s;">
                                                <i class="feather-upload-cloud fs-2 text-muted mb-2 d-block"></i>
                                                <p class="mb-1 text-muted">Click to browse or drag &amp; drop</p>
                                                <small class="text-muted">Max 2 MB recommended</small>
                                                <input type="file" class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0"
                                                       name="card" id="card-upload" accept="image/*"
                                                       style="cursor: pointer;">
                                            </div>
                                            @error('card')
                                                <div class="text-danger small mt-2">{{ $message }}</div>
                                            @enderror
                                            <div id="new-preview" class="mt-3 text-center d-none">
                                                <p class="text-muted small mb-2 fw-semibold text-uppercase">Preview</p>
                                                <div class="border rounded overflow-hidden d-inline-block" style="max-width: 180px;">
                                                    <img src="" alt="Preview" id="preview-img"
                                                         style="height: 160px; width: 100%; object-fit: cover;">
                                                </div>
                                                <div class="mt-2">
                                                    <small class="text-success fw-semibold" id="file-name-label"></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center gap-3 mb-5">
                                <a href="{{ route('admin.locations.index') }}" class="btn btn-light-brand px-4">
                                    <i class="feather-arrow-left me-1"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="feather-save me-1"></i> Create location
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
        const fileInput = document.getElementById('card-upload');
        const previewBox = document.getElementById('new-preview');
        const previewImg = document.getElementById('preview-img');
        const fileLabel = document.getElementById('file-name-label');
        const uploadArea = document.getElementById('upload-area');

        if (fileInput && uploadArea) {
            fileInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        previewImg.src = e.target.result;
                        fileLabel.textContent = this.files[0].name;
                        previewBox.classList.remove('d-none');
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });

            uploadArea.addEventListener('dragover', () => uploadArea.style.background = '#f0f4ff');
            uploadArea.addEventListener('dragleave', () => uploadArea.style.background = '');
            uploadArea.addEventListener('drop', () => uploadArea.style.background = '');
        }
    </script>
@endpush
