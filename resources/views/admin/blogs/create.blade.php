@extends('admin.layout')

@section('title', 'Create Blog')
@section('page_title', 'Create Blog')
@section('page_subtitle', 'Add a new blog post with images, visibility, and content.')

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
                        <h5 class="m-b-10">Create post</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}">Blogs</a></li>
                        <li class="breadcrumb-item">Create</li>
                    </ul>
                </div>
            </div>

            <div class="main-content admin-blog-form-shell">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="card stretch stretch-full profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="feather-info me-2 text-primary"></i>Basic information
                                    </h5>
                                    <p class="text-muted fs-12 mb-0">Post title and author name as shown to readers.</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Post title <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                                   value="{{ old('name') }}" placeholder="e.g. Market trends for 2026" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Writer name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('writer_name') is-invalid @enderror" name="writer_name"
                                                   value="{{ old('writer_name') }}" placeholder="Author display name" required>
                                            @error('writer_name')
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
                                    <p class="text-muted fs-12 mb-0">Choose one blog category and any approved tags (hold Ctrl or Cmd to select multiple).</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Blog category</label>
                                            <select name="blog_category_id" class="form-select @error('blog_category_id') is-invalid @enderror">
                                                <option value="">— None —</option>
                                                @foreach ($blogCategories as $cat)
                                                    <option value="{{ $cat->id }}" @selected((string) old('blog_category_id') === (string) $cat->id)>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('blog_category_id')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Manage list under <a href="{{ route('admin.blog-categories.index') }}">Blog categories</a>.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tags</label>
                                            <select name="tag_ids[]" class="form-select @error('tag_ids') is-invalid @enderror @error('tag_ids.*') is-invalid @enderror" multiple size="12">
                                                @foreach ($tags as $tag)
                                                    <option value="{{ $tag->id }}" @selected(collect(old('tag_ids', []))->contains((string) $tag->id))>{{ $tag->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('tag_ids')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            @error('tag_ids.*')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Up to 50 tags. Manage under <a href="{{ route('admin.blog-tags.index') }}">Blog tags</a>.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card stretch stretch-full profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="feather-eye me-2 text-primary"></i>Visibility
                                    </h5>
                                    <p class="text-muted fs-12 mb-0">Control publication state and whether the post can appear on the site.</p>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="status" value="0">
                                    <input type="hidden" name="active" value="0">
                                    <div class="row g-4">
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded border bg-light-subtle">
                                                <div>
                                                    <p class="mb-0 fw-semibold">Published</p>
                                                    <small class="text-muted">Turn off to save as draft</small>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" value="1"
                                                           {{ old('status', true) ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded border bg-light-subtle">
                                                <div>
                                                    <p class="mb-0 fw-semibold">Visible on site</p>
                                                    <small class="text-muted">Hide without deleting the post</small>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" value="1"
                                                           {{ old('active', true) ? 'checked' : '' }}>
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
                                    <p class="text-muted fs-12 mb-0">Card image is the listing thumbnail; writer image is optional.</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Card image</label>
                                            <input type="file" class="form-control @error('card') is-invalid @enderror" name="card" accept="image/*">
                                            @error('card')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Optional · max 5 MB</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Writer image</label>
                                            <input type="file" class="form-control @error('writer_image') is-invalid @enderror" name="writer_image" accept="image/*">
                                            @error('writer_image')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Optional · max 5 MB</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card stretch stretch-full profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="feather-align-left me-2 text-primary"></i>Content
                                    </h5>
                                    <p class="text-muted fs-12 mb-0">Short excerpt and full body (HTML allowed in body if your front-end supports it).</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <label class="form-label">Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" required
                                                      placeholder="Summary or excerpt shown in listings">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Full content</label>
                                            <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="12" placeholder="Main article content">{{ old('content') }}</textarea>
                                            @error('content')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="profile-form-note">
                                                <div class="fw-semibold text-dark mb-1">URL</div>
                                                <div class="text-muted fs-12 mb-0">A unique URL slug is generated automatically when the post is created.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center gap-3 mb-5">
                                <a href="{{ route('admin.blogs.index') }}" class="btn btn-light-brand px-4">
                                    <i class="feather-arrow-left me-1"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="feather-save me-1"></i> Create post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
