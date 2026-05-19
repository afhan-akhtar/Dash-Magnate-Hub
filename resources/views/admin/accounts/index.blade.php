@extends('admin.layout')

@section('title', 'Admin Accounts')
@section('page_title', 'Admin Accounts')
@section('page_subtitle', 'Manage administrator access levels.')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <!-- <div class="page-header-title">
                    <h5 class="m-b-10">Admin Accounts</h5>
                </div> -->
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Accounts</li>
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
                        <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAdminModal">
                            <i class="feather-plus me-2"></i>
                            <span>Add administrator</span>
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
                                            <th>Administrator</th>
                                            <th>Email</th>
                                            <th>Joined Date</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($accounts as $account)
                                            <tr class="single-item">
                                                <td>
                                                    <div class="hstack gap-3">
                                                        <div class="avatar-text avatar-md bg-soft-primary text-primary">
                                                            {{ strtoupper(substr($account->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <span class="fw-bold d-block text-dark">{{ $account->name }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $account->email }}</td>
                                                <td>{{ $account->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        @if (auth()->id() !== $account->id)
                                                            <form method="POST" action="{{ route('admin.accounts.destroy', $account->id) }}" class="d-inline js-admin-confirm-submit" data-confirm-title="Delete admin account" data-confirm-message="Delete this admin account?">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="avatar-text avatar-md border-0 bg-transparent text-danger">
                                                                    <i class="feather feather-trash-2"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="badge bg-soft-success text-success">Current User</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5 text-muted">No admin accounts found.</td>
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

<!-- Create Admin Modal -->
<div class="modal fade" id="createAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Administrator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.accounts.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Create a secure password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-brand" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
