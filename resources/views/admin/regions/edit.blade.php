@extends('admin.layout')

@section('title', 'Edit Region')
@section('page_title', 'Edit Region')
@section('page_subtitle', 'Update region details and settings.')

@push('styles')
    <style>
        .admin-region-form-shell .profile-form-card {
            border: 0;
            box-shadow: 0 18px 45px rgba(40, 60, 80, 0.08);
        }

        .admin-region-form-shell .profile-form-card .card-header {
            padding: 1.25rem 1.5rem 0;
            border-bottom: 0;
            background: transparent;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.35rem;
        }

        .admin-region-form-shell .profile-form-card .card-header .card-title {
            margin-bottom: 0;
        }

        .admin-region-form-shell .profile-form-card .card-body {
            padding: 1.5rem;
        }

        .admin-region-form-shell .profile-form-note {
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
                        <h5 class="m-b-10">Edit region</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.regions.index') }}">Regions</a></li>
                        <li class="breadcrumb-item">{{ \Illuminate\Support\Str::limit($region->name, 40) }}</li>
                    </ul>
                </div>
            </div>

            <div class="main-content admin-region-form-shell">
                <div class="row justify-content-center">
                    <div class="col-12">

                        <form method="POST" action="{{ route('admin.regions.update', $region->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="card stretch stretch-full profile-form-card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="feather-info me-2 text-primary"></i>Region details
                                    </h5>
                                    <p class="text-muted fs-12 mb-0">Update the name or move this region to another location.</p>
                                </div>
                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Region name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   name="name" value="{{ old('name', $region->name) }}"
                                                   placeholder="e.g. Inner Melbourne" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Location <span class="text-danger">*</span></label>
                                            <select name="location_id" class="form-select @error('location_id') is-invalid @enderror" required>
                                                @foreach ($locations as $location)
                                                    <option value="{{ $location->id }}" {{ (string) old('location_id', $region->location_id) === (string) $location->id ? 'selected' : '' }}>
                                                        {{ $location->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('location_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="profile-form-note">
                                                <div class="fw-semibold text-dark mb-1">Locations</div>
                                                <div class="text-muted fs-12 mb-0">Add or edit locations under <a href="{{ route('admin.locations.index') }}">Locations</a> if the list here is incomplete.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center gap-3 mb-5">
                                <a href="{{ route('admin.regions.index') }}" class="btn btn-light-brand px-4">
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
