@extends('professional.dashboard.layouts.app')

@section('title', 'MagnateHub || My Listings')
@section('page_title', 'My Listings')
@section('page_summary', 'View, review, and open each professional listing from your dashboard workspace.')

@section('content')
<style>
    .dropdown-toggle::after {
        display: none;
    }
</style>
<main class="nxl-container">
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Listings</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('professional.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Listings</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                @unless ($hasNoPlan)
                    <a href="{{ route('professional.listings.create') }}" class="btn btn-primary">
                        <i class="feather-plus me-2"></i>
                        <span>Create Listing</span>
                    </a>
                @else
                    <a href="{{ route('professional.plans.index') }}" class="btn btn-warning">
                        <i class="feather-zap me-2"></i>
                        <span>Subscribe to Create</span>
                    </a>
                @endunless
            </div>
        </div>

        <div class="main-content">
            @if (!empty($hasExpiredPlan))
                <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                    <i class="feather-alert-triangle me-2"></i>
                    <div>
                        Your plan has expired. Listings remain visible in your portal but are hidden from the website until you purchase a new plan.
                    </div>
                </div>
            @endif

            @if (($remainingPremiumMarks ?? 0) > 0)
                <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                    <i class="feather-star me-2"></i>
                    <div>
                        You have {{ $remainingPremiumMarks }} premium listing {{ $remainingPremiumMarks === 1 ? 'credit' : 'credits' }} available on your active plan.
                    </div>
                </div>
            @endif

            @if ($projects->count())
                <div class="row g-4">
                    @foreach ($projects as $project)
                        <div class="col-xxl-4 col-md-6">
                            <div class="card stretch stretch-full">
                                <div class="position-relative">
                                    <img
                                        src="{{ optional($project->thumbnail)->url ?: '/dashboard/assets/images/empty.jpg' }}"
                                        alt="{{ $project->name }}"
                                        class="img-fluid w-100 rounded-top"
                                        style="height: 220px; object-fit: cover;"
                                    >
                                    <div class="position-absolute top-0 end-0 p-3">
                                        @if ($project->premium)
                                            <span class="badge bg-primary">Premium</span>
                                        @endif
                                        @if ((int) $project->under_offer === 1)
                                            <span class="badge bg-info">Under Offer</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                                        <div>
                                            <h5 class="mb-1">{{ $project->name }}</h5>
                                            <div class="text-muted">{{ $project->category->name ?? 'No category' }}</div>
                                        </div>
                                        @if ($project->isDeleted())
                                            <span class="badge bg-soft-danger text-danger">Deleted</span>
                                        @elseif ($project->active)
                                            <span class="badge bg-soft-success text-success">Active</span>
                                        @else
                                            <span class="badge bg-soft-warning text-warning">Inactive</span>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex align-items-center text-muted mb-2">
                                            <i class="feather-map-pin me-2"></i>
                                            <span>{{ $project->location->name ?? 'No location' }}</span>
                                        </div>
                                        <div class="d-flex align-items-center text-muted">
                                            <i class="feather-dollar-sign me-2"></i>
                                            <span>{{ $project->price ? '$' . number_format($project->price) : 'Price not set' }}</span>
                                        </div>
                                    </div>

                                    <p class="text-muted mb-3 text-truncate-2-line">
                                        {{ $project->summary ?: 'Add a summary to help buyers quickly understand the opportunity.' }}
                                    </p>

                                    <div class="d-flex align-items-center justify-content-between border-top pt-3">
                                        <small class="text-muted">Created {{ $project->created_at?->format('M d, Y') }}</small>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light-brand dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="feather-more-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('professional.listings.show', $project->id) }}">
                                                        <i class="feather-eye me-2"></i>View
                                                    </a>
                                                </li>
                                                @if (!$project->isDeleted())
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('professional.listings.edit', $project->id) }}">
                                                            <i class="feather-edit-2 me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="{{ route('professional.listings.update-status', $project->id) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="field" value="active">
                                                            <input type="hidden" name="value" value="{{ (int) $project->active === 1 ? 0 : 1 }}">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="feather-power me-2"></i>{{ (int) $project->active === 1 ? 'In-active' : 'Active' }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="{{ route('professional.listings.update-status', $project->id) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="field" value="under_offer">
                                                            <input type="hidden" name="value" value="{{ (int) $project->under_offer === 1 ? 0 : 1 }}">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="feather-tag me-2"></i>{{ (int) $project->under_offer === 1 ? 'Remove Under Offer' : 'Under Offer' }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @if ((int) $project->active === 1 && (int) $project->premium === 0 && ($remainingPremiumMarks ?? 0) > 0)
                                                        <li>
                                                            <form method="POST" action="{{ route('professional.listings.mark-premium', $project->id) }}">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="feather-star me-2"></i>Mark Premium
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" action="{{ route('professional.listings.destroy', $project->id) }}" class="js-admin-confirm-submit" data-confirm-title="Delete listing" data-confirm-message="Delete this listing?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="feather-trash-2 me-2"></i>Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $projects->links() }}
                </div>
            @else
                <div class="card stretch stretch-full">
                    <div class="card-body text-center py-5">
                        <h5 class="mb-2">No listings yet</h5>
                        <p class="text-muted mb-4">Your projects will appear here once you create them.</p>
                        @unless ($hasNoPlan)
                            <a href="{{ route('professional.listings.create') }}" class="btn btn-primary">
                                <i class="feather-plus me-2"></i>
                                <span>Create Your First Listing</span>
                            </a>
                        @else
                            <a href="{{ route('professional.plans.index') }}" class="btn btn-warning">
                                <i class="feather-zap me-2"></i>
                                <span>Get a Plan to Start Listing</span>
                            </a>
                        @endunless
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection


