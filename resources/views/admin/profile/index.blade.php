@extends('admin.layout')

@php
    $displayName = $account->name ?: 'Admin';
    $avatarLetter = strtoupper(substr($displayName, 0, 1));
    $roleLabel = ucwords(str_replace('_', ' ', $account->role ?: 'admin'));
    $memberSince = optional($account->created_at)->format('M d, Y') ?: 'N/A';
@endphp

@section('title', 'MagnateHub || Admin Profile')
@section('page_title', 'Admin Profile')

@push('styles')
    <style>
        .admin-profile-shell .profile-summary-card {
            overflow: hidden;
            border: 0;
            background: linear-gradient(180deg, #f8fbff 0%, #ffffff 58%);
            box-shadow: 0 18px 45px rgba(40, 60, 80, 0.08);
        }

        .admin-profile-shell .profile-summary-top {
            padding: 2rem 2rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background:
                radial-gradient(circle at top right, rgba(52, 84, 209, 0.12), transparent 42%),
                linear-gradient(180deg, rgba(52, 84, 209, 0.05), rgba(255, 255, 255, 0));
        }

        .admin-profile-shell .profile-avatar {
            width: 124px;
            height: 124px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 6px solid rgba(52, 84, 209, 0.1);
            background: linear-gradient(135deg, rgba(52, 84, 209, 0.16), rgba(52, 84, 209, 0.04));
            color: #560ce3;
            font-size: 2.5rem;
            font-weight: 700;
            box-shadow: 0 14px 30px rgba(52, 84, 209, 0.14);
        }

        .admin-profile-shell .profile-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .admin-profile-shell .profile-info-tile {
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            background-color: #fff;
        }

        .admin-profile-shell .profile-info-label {
            margin-bottom: 0.35rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .admin-profile-shell .profile-info-value {
            color: #0f172a;
            font-weight: 700;
        }

        .admin-profile-shell .profile-help-item {
            display: flex;
            gap: 0.875rem;
            align-items: flex-start;
        }

        .admin-profile-shell .profile-help-item + .profile-help-item {
            margin-top: 1rem;
        }

        .admin-profile-shell .profile-help-icon {
            width: 2.5rem;
            height: 2.5rem;
            flex: 0 0 2.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.9rem;
            background-color: rgba(52, 84, 209, 0.08);
            color: #560ce3;
        }

        .admin-profile-shell .profile-form-card {
            border: 0;
            box-shadow: 0 18px 45px rgba(40, 60, 80, 0.08);
        }

        .admin-profile-shell .profile-form-card .card-header {
            padding: 1.25rem 1.5rem 0;
            border-bottom: 0;
            background: transparent;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.35rem;
        }

        .admin-profile-shell .profile-form-card .card-header .card-title {
            margin-bottom: 0;
        }

        .admin-profile-shell .profile-form-card .card-body {
            padding: 2.5rem;
        }

        .admin-profile-shell .profile-form-note {
            padding: 1rem 1.1rem;
            border: 1px dashed rgba(52, 84, 209, 0.25);
            border-radius: 1rem;
            background: rgba(52, 84, 209, 0.04);
        }

        .admin-profile-shell .password-input-group {
            border: 1px solid #d0d5dd;
            border-radius: 0.5rem;
            overflow: hidden;
            background-color: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .admin-profile-shell .password-input-group:focus-within {
            border-color: #560ce3;
            box-shadow: 0 0 0 1px #560ce3;
        }

        .admin-profile-shell .password-input-group .form-control {
            border: 0;
            box-shadow: none !important;
            background-color: transparent;
        }

        .admin-profile-shell .password-toggle-btn {
            min-width: 52px;
            border: 0;
            background-color: #fff;
            color: #64748b;
        }

        .admin-profile-shell .password-toggle-btn:hover,
        .admin-profile-shell .password-toggle-btn:focus {
            background-color: #fff;
            color: #0f172a;
            box-shadow: none;
        }

        @media (max-width: 767.98px) {
            .admin-profile-shell .profile-summary-top {
                padding: 1.5rem 1.25rem 1.25rem;
            }

            .admin-profile-shell .profile-info-grid {
                grid-template-columns: 1fr;
            }
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
                <!-- <div class="page-header-title">
                    <h5 class="m-b-10">Account Settings</h5>
                </div> -->
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Profile</li>
                </ul>
            </div>
        </div>

        <div class="main-content admin-profile-shell">
            <div class="row g-4">
                <div class="col-xxl-4 col-xl-5">
                    <div class="card profile-summary-card">
                        <div class="profile-summary-top text-center">
                            <div class="profile-avatar mx-auto mb-4">{{ $avatarLetter }}</div>
                            <h3 class="text-dark mb-1">{{ $displayName }}</h3>
                            <div class="text-muted mb-3">{{ $account->email }}</div>
                            <span class="badge bg-soft-primary text-primary text-uppercase">{{ $roleLabel }}</span>
                        </div>

                        <div class="card-body p-4">
                            <div class="profile-info-grid">
                                <div class="profile-info-tile">
                                    <div class="profile-info-label">Role</div>
                                    <div class="profile-info-value">Administrator</div>
                                </div>
                                <div class="profile-info-tile">
                                    <div class="profile-info-label">Member Since</div>
                                    <div class="profile-info-value">{{ $memberSince }}</div>
                                </div>
                                <div class="profile-info-tile">
                                    <div class="profile-info-label">Access</div>
                                    <div class="profile-info-value">Full Panel Access</div>
                                </div>
                                <div class="profile-info-tile">
                                    <div class="profile-info-label">Status</div>
                                    <div class="profile-info-value">
                                        <span class="badge bg-soft-success text-success">Active</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xxl-8 col-xl-7">
                    <div class="card profile-form-card mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Profile Details</h5>
                            <p class="text-muted fs-12 mb-0">Update your display name and primary email address.</p>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.profile.update') }}">
                                @csrf
                                @method('PUT')

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name', $account->name) }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email', $account->email) }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Account Role</label>
                                        <input type="text" class="form-control" value="Administrator" disabled>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Member Since</label>
                                        <input type="text" class="form-control" value="{{ $memberSince }}" disabled>
                                    </div>

                                    <div class="col-12">
                                        <div class="profile-form-note">
                                            <div class="fw-semibold text-dark mb-1">Admin identity</div>
                                            <div class="text-muted fs-12 mb-0">These details appear across the panel and are used to identify your account in admin-level actions.</div>
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Save Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="col-12">
                    <div class="card profile-form-card">
                        <div class="card-header">
                            <h5 class="card-title">Password & Security</h5>
                            <p class="text-muted fs-12 mb-0">Change your current password without affecting the rest of your account details.</p>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.profile.password') }}">
                                @csrf
                                @method('PUT')

                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="form-label">Current Password</label>
                                        <div class="input-group password-input-group">
                                            <input type="password" class="form-control" name="current_password" data-password-toggle-input required>
                                            <button type="button" class="btn password-toggle-btn" data-password-toggle aria-label="Show password">
                                                <i class="feather-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">New Password</label>
                                        <div class="input-group password-input-group">
                                            <input type="password" class="form-control" name="password" data-password-toggle-input required>
                                            <button type="button" class="btn password-toggle-btn" data-password-toggle aria-label="Show password">
                                                <i class="feather-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Confirm Password</label>
                                        <div class="input-group password-input-group">
                                            <input type="password" class="form-control" name="password_confirmation" data-password-toggle-input required>
                                            <button type="button" class="btn password-toggle-btn" data-password-toggle aria-label="Show password">
                                                <i class="feather-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-dark">Update Password</button>
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('click', function (event) {
            const toggle = event.target.closest('[data-password-toggle]');

            if (!toggle) {
                return;
            }

            const group = toggle.closest('.password-input-group');
            const input = group ? group.querySelector('[data-password-toggle-input]') : null;
            const icon = toggle.querySelector('i');

            if (!input) {
                return;
            }

            const isVisible = input.type === 'text';
            input.type = isVisible ? 'password' : 'text';
            toggle.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');

            if (icon) {
                icon.className = isVisible ? 'feather-eye' : 'feather-eye-off';
            }
        });
    </script>
@endpush
