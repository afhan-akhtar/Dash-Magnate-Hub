@extends('professional.dashboard.layouts.app')

@php
    $planLabels = [
        1 => ['name' => 'Essentials Package', 'price' => 149, 'accent' => 'primary'],
        2 => ['name' => 'Premium Package', 'price' => 1999, 'accent' => 'success'],
        3 => ['name' => 'Broker Pro Package', 'price' => 1499, 'accent' => 'warning'],
        4 => ['name' => 'Capital Raise Package', 'price' => 249, 'accent' => 'danger'],
        5 => ['name' => 'Free Package', 'price' => 0, 'accent' => 'secondary'],
    ];

    $activeMeta = $activePlan
        ? array_merge(
            $planLabels[$activePlan->type] ?? ['name' => 'Unknown', 'price' => 0, 'accent' => 'secondary'],
            [
                'name' => $activePlan->name ?: ($planLabels[$activePlan->type]['name'] ?? 'Unknown'),
                'price' => $activePlan->price ?? ($planLabels[$activePlan->type]['price'] ?? 0),
            ]
        )
        : null;
    $activePlanCount = $plans->filter(function ($plan) {
        return (int) $plan->status === 0
            && (!$plan->expiry || $plan->expiry->isToday() || $plan->expiry->isFuture());
    })->count();
    $expiredPlanCount = $plans->count() - $activePlanCount;
    $activeBilling = $activePlan ? [
        'First Name' => $activePlan->billing_first_name,
        'Last Name' => $activePlan->billing_last_name,
        'Business Name' => $activePlan->billing_business_name,
        'Australian Business Number' => $activePlan->billing_abn,
        'Email' => $activePlan->billing_email,
        'Phone Number' => $activePlan->billing_phone,
        'Billing Address' => $activePlan->billing_address,
    ] : [];
@endphp

@section('title', 'MagnateHub || Pricing & Plans')
@section('page_title', 'Pricing & Plans')
@section('page_summary', 'Review your active subscription, listing allowance, and plan history from the professional workspace.')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Pricing & Plans</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('professional.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item">Pricing</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="d-flex align-items-center gap-2">
                    <a href="https://magnatehub.au/pricing" target="_blank" rel="noreferrer" class="btn btn-primary">
                        <i class="feather-external-link me-2"></i>
                        <span>Pricing Packages</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="row g-4 mb-4">
                <div class="col-xxl-4 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="avatar-text avatar-lg bg-soft-primary text-primary border-soft-primary rounded">
                                    <i class="feather-award"></i>
                                </div>
                                <div class="text-end">
                                    <p class="fs-11 fw-medium text-uppercase text-muted mb-1">Current Plan</p>
                                    <h3 class="tx-20 tx-semibold tx-left mb-0">{{ $activeMeta['name'] ?? 'No Active Plan' }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 pb-4 fs-12 text-muted">
                            {{ $activeMeta ? '$' . number_format($activeMeta['price']) . ' billed plan' : 'Choose a plan to unlock listing capacity.' }}
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="avatar-text avatar-lg bg-soft-success text-success border-soft-success rounded">
                                    <i class="feather-briefcase"></i>
                                </div>
                                <div class="text-end">
                                    <p class="fs-11 fw-medium text-uppercase text-muted mb-1">Listing Allowance</p>
                                    <h3 class="tx-20 tx-semibold tx-left mb-0">
                                        @if ($activePlan)
                                            {{ $activePlan->listing_limit === null ? 'Unlimited' : $activePlan->listing_limit }}
                                        @else
                                            0
                                        @endif
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 pb-4 fs-12 text-muted">
                            {{ $activePlan ? 'Available on your current subscription.' : 'No active subscription found.' }}
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-md-12">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="avatar-text avatar-lg bg-soft-warning text-warning border-soft-warning rounded">
                                    <i class="feather-clock"></i>
                                </div>
                                <div class="text-end">
                                    <p class="fs-11 fw-medium text-uppercase text-muted mb-1">Plan Timeline</p>
                                    <h3 class="tx-20 tx-semibold tx-left mb-0">{{ optional($activePlan?->expiry)->format('M d, Y') ?: 'N/A' }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 pb-4 fs-12 text-muted">{{ $activePlan ? 'Your active plan expiry date.' : 'No active expiry date available.' }}</div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-xxl-5">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Current Subscription</h5>
                        </div>
                        <div class="card-body">
                            @if ($activePlan && $activeMeta)
                                <div class="p-4 rounded-3 border border-dashed">
                                    <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
                                        <div>
                                            <div class="fs-12 text-muted text-uppercase mb-2">Active Plan</div>
                                            <h3 class="text-dark mb-1">{{ $activeMeta['name'] }}</h3>
                                            <div class="fs-12 text-muted">Professional subscription overview</div>
                                        </div>
                                        <span class="badge bg-soft-{{ $activeMeta['accent'] }} text-{{ $activeMeta['accent'] }}">Active</span>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="border rounded-3 p-3 h-100">
                                                <div class="fs-11 text-muted mb-1">Price</div>
                                                <div class="fw-bold text-dark">${{ number_format($activeMeta['price']) }}</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="border rounded-3 p-3 h-100">
                                                <div class="fs-11 text-muted mb-1">Listing Limit</div>
                                                <div class="fw-bold text-dark">{{ $activePlan->listing_limit === null ? 'Unlimited' : $activePlan->listing_limit }}</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="border rounded-3 p-3 h-100">
                                                <div class="fs-11 text-muted mb-1">Expiry</div>
                                                <div class="fw-bold text-dark">{{ optional($activePlan->expiry)->format('M d, Y') ?: 'N/A' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="border rounded-3 p-3 h-100">
                                                <div class="fs-11 text-muted mb-1">Status</div>
                                                <div class="fw-bold text-success">Live</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="feather-credit-card fs-1 d-block mb-3"></i>
                                    No active plan found for this account.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xxl-7">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Subscription Snapshot</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="p-4 rounded-3 border border-dashed h-100">
                                        <div class="fs-12 text-muted mb-2">Plans Purchased</div>
                                        <h3 class="text-dark mb-1">{{ $plans->count() }}</h3>
                                        <div class="fs-12 text-muted">Total plan records on this account.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-4 rounded-3 border border-dashed h-100">
                                        <div class="fs-12 text-muted mb-2">Active Records</div>
                                        <h3 class="text-dark mb-1">{{ $activePlanCount }}</h3>
                                        <div class="fs-12 text-muted">Currently valid plan entries.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-4 rounded-3 border border-dashed h-100">
                                        <div class="fs-12 text-muted mb-2">Expired Records</div>
                                        <h3 class="text-dark mb-1">{{ $expiredPlanCount }}</h3>
                                        <div class="fs-12 text-muted">Previous subscriptions no longer active.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-1">
                <div class="col-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Billing Details</h5>
                        </div>
                        <div class="card-body">
                            @if ($activePlan && collect($activeBilling)->filter()->isNotEmpty())
                                <div class="row g-3">
                                    @foreach ($activeBilling as $label => $value)
                                        @if (filled($value))
                                            <div class="col-md-6">
                                                <div class="border rounded-3 p-3 h-100">
                                                    <div class="fs-11 text-muted mb-1">{{ $label }}</div>
                                                    <div class="fw-semibold text-dark">{{ $value }}</div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-muted">Billing details are not available for the active subscription.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Plan History</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Plan</th>
                                            <th>Price</th>
                                            <th>Listing Limit</th>
                                            <th>Expiry</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($plans as $plan)
                                            @php
                                                $meta = array_merge(
                                                    $planLabels[$plan->type] ?? ['name' => 'Unknown', 'price' => 0, 'accent' => 'secondary'],
                                                    [
                                                        'name' => $plan->name ?: ($planLabels[$plan->type]['name'] ?? 'Unknown'),
                                                        'price' => $plan->price ?? ($planLabels[$plan->type]['price'] ?? 0),
                                                    ]
                                                );
                                                $isActive = (int) $plan->status === 0
                                                    && (!$plan->expiry || $plan->expiry->isToday() || $plan->expiry->isFuture());
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span class="avatar-text avatar-md bg-soft-{{ $meta['accent'] }} text-{{ $meta['accent'] }} border-soft-{{ $meta['accent'] }} rounded">
                                                            <i class="feather-package"></i>
                                                        </span>
                                                        <div>
                                                            <div class="fw-semibold text-dark">{{ $meta['name'] }}</div>
                                                            <div class="fs-11 text-muted">Type #{{ $plan->type }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>${{ number_format($meta['price']) }}</td>
                                                <td>{{ $plan->listing_limit === null ? 'Unlimited' : $plan->listing_limit }}</td>
                                                <td>{{ optional($plan->expiry)->format('M d, Y') ?: 'N/A' }}</td>
                                                <td>
                                                    <span class="badge {{ $isActive ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $isActive ? 'Active' : 'Expired' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-5">No plans found.</td>
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
    </div>
</main>

@include('professional.dashboard.partials.footer')
@endsection

