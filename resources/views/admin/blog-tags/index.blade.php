@extends('admin.layout')

@section('title', 'Blog tags')
@section('page_title', 'Blog tags')
@section('page_subtitle', 'Approved labels for posts (many-to-many).')

@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <!-- <div class="page-header-title">
                        <h5 class="m-b-10">Blog tags</h5>
                    </div> -->
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Blog tags</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex d-md-none">
                            <a href="javascript:void(0)" class="page-header-right-close-toggle">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                            <a href="{{ route('admin.blog-tags.create') }}" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>Add tag</span>
                            </a>
                        </div>
                    </div>
                    <div class="d-md-none d-flex align-items-center">
                        <a href="javascript:void(0)" class="page-header-right-open-toggle">
                            <i class="feather-align-right fs-20"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- [ page-header ] end -->
            <!-- [ Main Content ] start -->
            <div class="main-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card stretch stretch-full">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="proposalList">
                                        <thead>
                                            <tr>
                                                <th>Tag</th>
                                                <th>Slug</th>
                                                <th>Active posts</th>
                                                <th>Date</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($tags as $tag)
                                                <tr class="single-item">
                                                    <td>
                                                        <a href="{{ route('admin.blog-tags.show', $tag->id) }}" class="fw-bold">{{ $tag->name }}</a>
                                                    </td>
                                                    <td><code class="text-primary">{{ $tag->slug }}</code></td>
                                                    <td><span class="text-muted fw-semibold">{{ $tag->active_blogs_count }}</span></td>
                                                    <td>{{ $tag->created_at?->format('Y-m-d, h:iA') ?? 'N/A' }}</td>
                                                    <td>
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <a href="{{ route('admin.blog-tags.show', $tag->id) }}" class="avatar-text avatar-md" title="View">
                                                                <i class="feather feather-eye"></i>
                                                            </a>
                                                            <a href="{{ route('admin.blog-tags.edit', $tag->id) }}" class="avatar-text avatar-md" title="Edit">
                                                                <i class="feather feather-edit-3"></i>
                                                            </a>
                                                            <form method="POST" action="{{ route('admin.blog-tags.destroy', $tag->id) }}" class="d-inline js-admin-confirm-submit" data-confirm-title="Delete blog tag" data-confirm-message="Delete this tag? It will be removed from all posts.">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="avatar-text avatar-md border-0 bg-transparent text-danger" title="Delete">
                                                                    <i class="feather feather-trash-2"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5 text-muted">No tags found.</td>
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
            <!-- [ Main Content ] end -->
        </div>
    </main>
@endsection
