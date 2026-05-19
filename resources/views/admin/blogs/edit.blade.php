@extends('admin.layout')

@section('title', 'Edit Blog')
@section('page_title', 'Edit Blog')
@section('page_subtitle', 'Update blog content, images, and visibility.')

@push('styles')
    <style>
        .admin-blog-form-shell .profile-form-card {
            border: 0;
            box-shadow: 0 18px 45px rgba(40, 60, 80, 0.08);
        }

        .admin-blog-form-shell .profile-form-card .card-header {
            padding: 1.25rem 1.5rem 0;
            border-bottom: 0;
            background: transparent;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.35rem;
        }

        .admin-blog-form-shell .profile-form-card .card-header .card-title {
            margin-bottom: 0;
        }

        .admin-blog-form-shell .profile-form-card .card-body {
            padding: 1.5rem;
        }

        .admin-blog-form-shell .profile-form-note {
            padding: 1rem 1.1rem;
            border: 1px dashed rgba(52, 84, 209, 0.25);
            border-radius: 1rem;
            background: rgba(52, 84, 209, 0.04);
        }

        .admin-doc-thumb-preview {
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: repeating-conic-gradient(var(--bs-gray-200, #e9ecef) 0% 25%, #fff 0% 50%) 50% / 20px 20px;
        }
        .admin-doc-thumb-img {
            max-height: 180px;
            max-width: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
        }
    </style>
@endpush

@php
    $blogCardThumb = $blog->thumbnail;
    $blogCardThumbUrl = null;
    if ($blogCardThumb) {
        $rawPath = $blogCardThumb->path ? str_replace('\\', '/', $blogCardThumb->path) : null;
        if ($rawPath) {
            $base = rtrim(request()->getBaseUrl(), '/');
            $blogCardThumbUrl = $base !== '' ? $base . '/storage/' . ltrim($rawPath, '/') : asset('storage/' . ltrim($rawPath, '/'));
        }
        if ($blogCardThumbUrl === null && ! empty($blogCardThumb->url)) {
            $blogCardThumbUrl = $blogCardThumb->url;
        }
    }
    $blogWriterDoc = $blog->documents->firstWhere('collection', 'writer_image');
    $blogWriterThumbUrl = null;
    if ($blogWriterDoc) {
        $wPath = $blogWriterDoc->path ? str_replace('\\', '/', $blogWriterDoc->path) : null;
        if ($wPath) {
            $base = rtrim(request()->getBaseUrl(), '/');
            $blogWriterThumbUrl = $base !== '' ? $base . '/storage/' . ltrim($wPath, '/') : asset('storage/' . ltrim($wPath, '/'));
        }
        if ($blogWriterThumbUrl === null && ! empty($blogWriterDoc->url)) {
            $blogWriterThumbUrl = $blogWriterDoc->url;
        }
    }
@endphp

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
                        <h5 class="m-b-10">Edit post</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}">Blogs</a></li>
                        <li class="breadcrumb-item">{{ \Illuminate\Support\Str::limit($blog->name, 40) }}</li>
                    </ul>
                </div>
            </div>

            <div class="main-content admin-blog-form-shell">
                <div class="row justify-content-center">
                    <div class="col-12">

                        <form method="POST" action="{{ route('admin.blogs.update', $blog->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card stretch stretch-full profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="feather-info me-2 text-primary"></i>Basic information
                                    </h5>
                                    <p class="text-muted fs-12 mb-0">Post title, writer, and public URL slug.</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Post title <span class="text-danger">*</span></label>
                                            <input class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $blog->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Writer name <span class="text-danger">*</span></label>
                                            <input class="form-control @error('writer_name') is-invalid @enderror" name="writer_name" value="{{ old('writer_name', $blog->writer_name) }}" required>
                                            @error('writer_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">URL slug</label>
                                            <input class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url', $blog->url) }}" placeholder="Path or slug">
                                            @error('url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card stretch stretch-full profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="feather-folder me-2 text-primary"></i>Category &amp; tags
                                    </h5>
                                    <p class="text-muted fs-12 mb-0">One category and multiple approved tags.</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Blog category</label>
                                            <select name="blog_category_id" class="form-select @error('blog_category_id') is-invalid @enderror">
                                                <option value="">— None —</option>
                                                @foreach ($blogCategories as $cat)
                                                    <option value="{{ $cat->id }}" @selected((string) old('blog_category_id', $blog->blog_category_id) === (string) $cat->id)>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('blog_category_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tags</label>
                                            @php
                                                $selectedTagIds = old('tag_ids', $blog->tags->modelKeys());
                                            @endphp
                                            <select name="tag_ids[]" class="form-select @error('tag_ids') is-invalid @enderror @error('tag_ids.*') is-invalid @enderror" multiple size="12">
                                                @foreach ($tags as $tag)
                                                    <option value="{{ $tag->id }}" @selected(collect($selectedTagIds)->contains((string) $tag->id))>{{ $tag->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('tag_ids')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            @error('tag_ids.*')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card stretch stretch-full profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="feather-eye me-2 text-primary"></i>Visibility
                                    </h5>
                                    <p class="text-muted fs-12 mb-0">Published posts are marked live; visibility controls site display.</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded border bg-light-subtle">
                                                <div>
                                                    <p class="mb-0 fw-semibold">Published</p>
                                                    <small class="text-muted">Off = draft</small>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                           id="status" name="status" value="1"
                                                           {{ old('status', $blog->status) ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded border bg-light-subtle">
                                                <div>
                                                    <p class="mb-0 fw-semibold">Visible on site</p>
                                                    <small class="text-muted">Hide without deleting</small>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                           id="active" name="active" value="1"
                                                           {{ old('active', $blog->active) ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card stretch stretch-full profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="feather-image me-2 text-primary"></i>Images
                                    </h5>
                                    <p class="text-muted fs-12 mb-0">Card image is the main cover; writer image appears with author details.</p>
                                </div>
                                <div class="card-body">
                                    <h6 class="fw-semibold mb-3 text-dark">Card image</h6>
                                    <div class="row g-4 align-items-start mb-4">
                                        @if ($blogCardThumb && $blogCardThumbUrl)
                                            <div class="col-lg-5">
                                                <div class="border rounded-3 shadow-sm overflow-hidden">
                                                    <div class="px-3 py-2 border-bottom bg-light small fw-semibold text-dark">Current card image</div>
                                                    <div class="admin-doc-thumb-preview bg-white p-3 text-center">
                                                        <img src="{{ $blogCardThumbUrl }}" alt="{{ $blog->name }}" class="img-fluid admin-doc-thumb-img" loading="lazy">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                        @else
                                            <div class="col-12">
                                        @endif
                                            <label class="form-label">{{ $blogCardThumb ? 'Replace card image' : 'Upload card image' }}</label>
                                            <div class="upload-area border rounded p-4 text-center position-relative" id="upload-area-card"
                                                 style="border-style: dashed !important; cursor: pointer; transition: background 0.2s;">
                                                <i class="feather-upload-cloud fs-2 text-muted mb-2 d-block"></i>
                                                <p class="mb-1 text-muted small">Click to browse or drag &amp; drop</p>
                                                <small class="text-muted">SVG, PNG, JPG, GIF</small>
                                                <input type="file" class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0"
                                                       name="card" id="card-upload" accept="image/*" style="cursor: pointer;">
                                            </div>
                                            @error('card')
                                                <div class="text-danger small mt-2">{{ $message }}</div>
                                            @enderror
                                            <div id="new-preview-card" class="mt-3 text-center d-none">
                                                <p class="text-muted small mb-2 fw-semibold text-uppercase">New preview</p>
                                                <div class="border rounded overflow-hidden d-inline-block" style="max-width: 180px;">
                                                    <img src="" alt="" id="preview-img-card" class="w-100" style="height: 140px; object-fit: cover;">
                                                </div>
                                                <div class="mt-2"><small class="text-success fw-semibold" id="file-name-card"></small></div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <h6 class="fw-semibold mb-3 text-dark">Writer image</h6>
                                    <div class="row g-4 align-items-start">
                                        @if ($blogWriterDoc && $blogWriterThumbUrl)
                                            <div class="col-lg-5">
                                                <div class="border rounded-3 shadow-sm overflow-hidden">
                                                    <div class="px-3 py-2 border-bottom bg-light small fw-semibold text-dark">Current writer image</div>
                                                    <div class="admin-doc-thumb-preview bg-white p-3 text-center">
                                                        <img src="{{ $blogWriterThumbUrl }}" alt="{{ $blog->writer_name }}" class="img-fluid admin-doc-thumb-img" loading="lazy">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                        @else
                                            <div class="col-12">
                                        @endif
                                            <label class="form-label">{{ $blogWriterDoc ? 'Replace writer image' : 'Upload writer image' }}</label>
                                            <div class="upload-area border rounded p-4 text-center position-relative" id="upload-area-writer"
                                                 style="border-style: dashed !important; cursor: pointer; transition: background 0.2s;">
                                                <i class="feather-upload-cloud fs-2 text-muted mb-2 d-block"></i>
                                                <p class="mb-1 text-muted small">Click to browse or drag &amp; drop</p>
                                                <small class="text-muted">SVG, PNG, JPG, GIF</small>
                                                <input type="file" class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0"
                                                       name="writer_image" id="writer-upload" accept="image/*" style="cursor: pointer;">
                                            </div>
                                            @error('writer_image')
                                                <div class="text-danger small mt-2">{{ $message }}</div>
                                            @enderror
                                            <div id="new-preview-writer" class="mt-3 text-center d-none">
                                                <p class="text-muted small mb-2 fw-semibold text-uppercase">New preview</p>
                                                <div class="border rounded overflow-hidden d-inline-block" style="max-width: 180px;">
                                                    <img src="" alt="" id="preview-img-writer" class="w-100" style="height: 140px; object-fit: cover;">
                                                </div>
                                                <div class="mt-2"><small class="text-success fw-semibold" id="file-name-writer"></small></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card stretch stretch-full profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="feather-align-left me-2 text-primary"></i>Content
                                    </h5>
                                    <p class="text-muted fs-12 mb-0">Excerpt and main article body.</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <label class="form-label">Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" required>{{ old('description', $blog->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Full content</label>
                                            <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="10">{{ old('content', $blog->content) }}</textarea>
                                            @error('content')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center gap-3 mb-5">
                                <a href="{{ route('admin.blogs.index') }}" class="btn btn-light-brand px-4">
                                    <i class="feather-arrow-left me-1"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="feather-save me-1"></i> Save changes
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
        function bindFilePreview(fileInputId, uploadAreaId, previewBoxId, previewImgId, fileLabelId) {
            const fileInput = document.getElementById(fileInputId);
            const uploadArea = document.getElementById(uploadAreaId);
            const previewBox = document.getElementById(previewBoxId);
            const previewImg = document.getElementById(previewImgId);
            const fileLabel = document.getElementById(fileLabelId);
            if (!fileInput || !uploadArea || !previewBox || !previewImg || !fileLabel) return;

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
            ['dragover', 'dragleave', 'drop'].forEach(ev => {
                uploadArea.addEventListener(ev, () => {
                    uploadArea.style.background = ev === 'dragover' ? '#f0f4ff' : '';
                });
            });
        }
        bindFilePreview('card-upload', 'upload-area-card', 'new-preview-card', 'preview-img-card', 'file-name-card');
        bindFilePreview('writer-upload', 'upload-area-writer', 'new-preview-writer', 'preview-img-writer', 'file-name-writer');
    })();
</script>
@endpush
