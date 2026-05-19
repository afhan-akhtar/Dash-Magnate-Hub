@extends('professional.dashboard.layouts.app')

@php
    $isBroker = $professional->role === 'broker';
    $displayName = $professional->full_name ?: $professional->name ?: 'Professional';
    $avatarLetter = strtoupper(substr($displayName, 0, 1));
@endphp

@section('title', 'MagnateHub || Settings')
@section('page_title', 'Settings')
@section('page_summary', 'Manage your profile details, password, and brand assets from one organized professional workspace.')

@section('styles')

    <style>
        .stretch.stretch-full {
            height: calc(100% - 305px);
        }
    </style>

@endsection
@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Account Settings</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('professional.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Settings</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="row g-4">
                <div class="col-xxl-4 col-xl-5">
                    <div class="card stretch stretch-full">
                        <div class="card-body text-center">
                            <div class="mb-4">
                                @if ($profilePhoto)
                                    <img src="{{ $profilePhoto }}" alt="{{ $displayName }}" class="img-fluid rounded-circle border border-4" style="width: 132px; height: 132px; object-fit: cover;">
                                @else
                                    <span class="professional-empty-avatar mx-auto" style="width: 132px; height: 132px; font-size: 42px;">{{ $avatarLetter }}</span>
                                @endif
                            </div>
                            <h4 class="text-dark mb-1">{{ $displayName }}</h4>
                            <div class="fs-12 text-muted mb-3">{{ $professional->email }}</div>
                            <span class="badge bg-soft-primary text-primary text-uppercase">{{ str_replace('_', ' ', $professional->role) }}</span>

                            <div class="row g-3 text-start mt-4">
                                <div class="col-12">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="fs-11 text-muted mb-1">Phone</div>
                                        <div class="fw-semibold text-dark">{{ $professional->phone ?: 'Not added' }}</div>
                                    </div>
                                </div>
                                @if ($isBroker)
                                    <div class="col-12">
                                        <div class="border rounded-3 p-3 h-100">
                                            <div class="fs-11 text-muted mb-1">Company</div>
                                            <div class="fw-semibold text-dark">{{ $professional->company_name ?: 'Not added' }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card stretch stretch-full mt-4">
                        <div class="card-header">
                            <h5 class="card-title">Images</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="fw-semibold text-dark mb-2">Profile Photo</div>
                                <form method="POST" action="{{ route('professional.settings.avatar') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" class="form-control mb-3" name="avatar" accept="image/*" required>
                                    <button type="submit" class="btn btn-primary">Upload Photo</button>
                                </form>
                            </div>

                            @if ($isBroker)
                                <div>
                                    <div class="fw-semibold text-dark mb-2">Company Logo</div>
                                    @if ($companyLogo)
                                        <img src="{{ $companyLogo }}" alt="Company Logo" class="img-fluid rounded-3 border mb-3" style="width: 96px; height: 96px; object-fit: cover;">
                                    @endif
                                    <form method="POST" action="{{ route('professional.settings.logo') }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" class="form-control mb-3" name="logo" accept="image/*" required>
                                        <button type="submit" class="btn btn-primary">Upload Logo</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xxl-8 col-xl-7">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Profile Details</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('professional.settings.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $professional->first_name) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $professional->last_name) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control" value="{{ $professional->email }}" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $professional->phone) }}">
                                    </div>
                                    @if ($isBroker)
                                        <div class="col-12">
                                            <label class="form-label">Company Name</label>
                                            <input type="text" class="form-control" name="company_name" value="{{ old('company_name', $professional->company_name) }}">
                                        </div>
                                    @endif
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Save Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Security</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('professional.settings.password') }}">
                                @csrf
                                @method('PUT')
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" class="form-control" name="current_password" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" name="password_confirmation" required>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Update Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('professional.dashboard.partials.footer')
@endsection


