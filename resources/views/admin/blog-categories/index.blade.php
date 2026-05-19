@extends('admin.layout')

@section('title', 'Blog categories')
@section('page_title', 'Blog categories')
@section('page_subtitle', 'Organise posts for the site and public API filters.')

@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <!-- <div class="page-header-title">
                        <h5 class="m-b-10">Blog categories</h5>
                    </div> -->
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Blog categories</li>
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
                            <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>Add category</span>
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
                                                <th>Category</th>
                                                <th>Slug</th>
                                                <th>Sort</th>
                                                <th>Active posts</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($categories as $cat)
                                                <tr class="single-item">
                                                    <td>
                                                        <a href="{{ route('admin.blog-categories.show', $cat->id) }}" class="fw-bold">{{ $cat->name }}</a>
                                                    </td>
                                                    <td><code class="text-primary">{{ $cat->slug }}</code></td>
                                                    <td>{{ $cat->sort_order }}</td>
                                                    <td><span class="text-muted fw-semibold">{{ $cat->active_blogs_count }}</span></td>
                                                    <td>{{ $cat->created_at?->format('Y-m-d, h:iA') ?? 'N/A' }}</td>
                                                    <td>
                                                        @if ($cat->status)
                                                            <div class="badge bg-soft-success text-success text-uppercase">Active</div>
                                                        @else
                                                            <div class="badge bg-soft-danger text-danger text-uppercase">Inactive</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <a href="{{ route('admin.blog-categories.show', $cat->id) }}" class="avatar-text avatar-md" title="View">
                                                                <i class="feather feather-eye"></i>
                                                            </a>
                                                            <a href="{{ route('admin.blog-categories.edit', $cat->id) }}" class="avatar-text avatar-md" title="Edit">
                                                                <i class="feather feather-edit-3"></i>
                                                            </a>
                                                            <form method="POST" action="{{ route('admin.blog-categories.destroy', $cat->id) }}" class="d-inline js-admin-confirm-submit" data-confirm-title="Delete blog category" data-confirm-message="Delete this category? Posts will keep but lose this category link.">
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
                                                    <td colspan="7" class="text-center py-5 text-muted">No categories found.</td>
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
