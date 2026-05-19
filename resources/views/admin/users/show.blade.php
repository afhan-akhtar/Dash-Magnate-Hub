@extends('admin.layout')

@section('title', 'User Details')
@section('page_title', 'User Details')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <div class="main-content">
            <div class="row g-4">
                <div class="col-xl-4">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="avatar-text avatar-xl bg-soft-primary text-primary">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="mb-1">{{ $user->name }}</h4>
                                    <div class="text-muted">{{ $user->email }}</div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="border rounded-3 p-3">
                                        <div class="fs-12 text-muted text-uppercase mb-1">Phone</div>
                                        <div class="fw-semibold text-dark">{{ $user->phone ?: '-' }}</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="border rounded-3 p-3">
                                        <div class="fs-12 text-muted text-uppercase mb-1">Status</div>
                                        <span class="badge {{ $user->status == 0 ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}">
                                            {{ $user->status == 0 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="border rounded-3 p-3">
                                        <div class="fs-12 text-muted text-uppercase mb-1">Wishlist Items</div>
                                        <div class="fw-semibold text-dark">{{ $user->wishlists->count() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Wishlist</h5>
                        </div>
                        <div class="card-body">
                            @forelse($user->wishlists as $wishlist)
                                <div class="d-flex align-items-center justify-content-between gap-3 border rounded-3 p-3 {{ $loop->last ? '' : 'mb-3' }}">
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $wishlist->project->name ?? 'Unknown project' }}</div>
                                        <div class="fs-12 text-muted">Saved listing</div>
                                    </div>
                                    <span class="badge bg-soft-primary text-primary">Wishlist</span>
                                </div>
                            @empty
                                <div class="text-center text-muted py-5">No wishlist items found.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection






