@extends('admin.layout')

@section('title', 'Locations')
@section('page_title', 'Locations')
@section('page_subtitle', 'Review location records and use separate create or edit pages.')

@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <!-- <div class="page-header-title">
                        <h5 class="m-b-10">Locations</h5>
                    </div> -->
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Locations</li>
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
                            <a href="{{ route('admin.locations.create') }}" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>Add location</span>
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="proposalList">
                                        <thead>
                                            <tr>
                                                <th>Location</th>
                                                <th>URL Path</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($locations as $location)
                                                <tr class="single-item">
                                                    <td><a href="{{ route('admin.locations.edit', $location->id) }}" class="fw-bold">{{ $location->name }}</a></td>
                                                    <td>
                                                        <code class="text-primary">{{ $location->url }}</code>
                                                    </td>
                                                    <td>{{ $location->created_at?->format('Y-m-d, h:iA') ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($location->status)
                                                            <div class="badge bg-soft-success text-success text-uppercase">Active</div>
                                                        @else
                                                            <div class="badge bg-soft-danger text-danger text-uppercase">Inactive</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <a href="{{ route('admin.locations.edit', $location->id) }}" class="avatar-text avatar-md">
                                                                <i class="feather feather-edit-3"></i>
                                                            </a>
                                                            <form method="POST" action="{{ route('admin.locations.destroy', $location->id) }}" class="d-inline js-admin-confirm-submit" data-confirm-title="Delete location" data-confirm-message="Delete this location?">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="avatar-text avatar-md border-0 bg-transparent text-danger">
                                                                    <i class="feather feather-trash-2"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5 text-muted">No locations found.</td>
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
