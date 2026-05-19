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
    $listingAllowance = $activePlan
        ? ($activePlan->listing_limit === null ? 'Unlimited' : $activePlan->listing_limit)
        : '0';
@endphp

@section('title', 'MagnateHub || Pricing & Plans')
@section('page_title', 'Pricing & Plans')
@section('page_summary', 'Review your active subscription, listing allowance, and plan history from the professional workspace.')

@section('styles')
    @include('professional.dashboard.plans.partials.plans-styles')
@endsection

@section('content')
<main class="nxl-container pricing-page-premium">
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
        </div>

        <div class="main-content">
            <div class="pricing-hero">
                <div class="pricing-hero__inner">
                    <div class="pricing-hero__left">
                        <div class="pricing-hero__badge" aria-hidden="true">
                            <i class="feather-layers"></i>
                        </div>
                        <div>
                            <h1 class="pricing-hero__title">Your subscription at a glance</h1>
                            <p class="pricing-hero__text">Manage your plan, listing allowance, and billing from one place. Upgrade anytime to unlock more listings and features.</p>
                            @if ($activeMeta)
                                <div class="pricing-hero__chips">
                                    <span class="pricing-hero__chip">{{ $activeMeta['name'] }}</span>
                                    <span class="pricing-hero__chip">{{ $listingAllowance }} listing{{ $listingAllowance === 1 ? '' : 's' }}</span>
                                    @if ($activePlan?->expiry)
                                        <span class="pricing-hero__chip">Expires {{ $activePlan->expiry->format('M j, Y') }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <a href="https://magnatehub.au/pricing" target="_blank" rel="noreferrer" class="btn pricing-hero__btn pricing-cta-btn">
                        <i class="feather-external-link me-2"></i>
                        <span>View pricing packages</span>
                    </a>
                </div>
            </div>

            <div class="pricing-section-label">
                <i class="feather-bar-chart-2"></i>
                <span>Overview</span>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-xxl-4 col-md-6">
                    <div class="card pricing-stat-card pricing-stat-card--primary">
                        <div class="pricing-stat-card__body">
                            <div class="pricing-stat-card__top">
                                <div class="pricing-stat-card__icon"><i class="feather-award"></i></div>
                                <div class="text-end flex-grow-1">
                                    <div class="pricing-stat-card__label">Current Plan</div>
                                    <p class="pricing-stat-card__value">{{ $activeMeta['name'] ?? 'No Active Plan' }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="pricing-stat-card__foot mb-0">
                            {{ $activeMeta ? '$' . number_format($activeMeta['price']) . ' billed plan' : 'Choose a plan to unlock listing capacity.' }}
                        </p>
                    </div>
                </div>
                <div class="col-xxl-4 col-md-6">
                    <div class="card pricing-stat-card pricing-stat-card--success">
                        <div class="pricing-stat-card__body">
                            <div class="pricing-stat-card__top">
                                <div class="pricing-stat-card__icon"><i class="feather-briefcase"></i></div>
                                <div class="text-end flex-grow-1">
                                    <div class="pricing-stat-card__label">Listing Allowance</div>
                                    <p class="pricing-stat-card__value">{{ $listingAllowance }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="pricing-stat-card__foot mb-0">
                            {{ $activePlan ? 'Available on your current subscription.' : 'No active subscription found.' }}
                        </p>
                    </div>
                </div>
                <div class="col-xxl-4 col-md-12">
                    <div class="card pricing-stat-card pricing-stat-card--warning">
                        <div class="pricing-stat-card__body">
                            <div class="pricing-stat-card__top">
                                <div class="pricing-stat-card__icon"><i class="feather-calendar"></i></div>
                                <div class="text-end flex-grow-1">
                                    <div class="pricing-stat-card__label">Plan Timeline</div>
                                    <p class="pricing-stat-card__value">{{ optional($activePlan?->expiry)->format('M d, Y') ?: 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="pricing-stat-card__foot mb-0">
                            {{ $activePlan ? 'Your active plan expiry date.' : 'No active expiry date available.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="pricing-section-label">
                <i class="feather-credit-card"></i>
                <span>Subscription details</span>
            </div>

            <div class="row g-4">
                <div class="col-xxl-5">
                    <div class="card pricing-panel stretch stretch-full">
                        <div class="pricing-panel__header">
                            <div class="pricing-panel__header-left">
                                <span class="pricing-panel__header-icon"><i class="feather-shield"></i></span>
                                <div>
                                    <h5 class="pricing-panel__title">Current Subscription</h5>
                                    <p class="pricing-panel__subtitle">Your live plan on Magnate Hub</p>
                                </div>
                            </div>
                            @if ($activePlan && $activeMeta)
                                <span class="pricing-status-pill">Active</span>
                            @endif
                        </div>
                        <div class="card-body">
                            @if ($activePlan && $activeMeta)
                                <div class="pricing-active-plan">
                                    <div class="pricing-active-plan__head">
                                        <div class="pricing-active-plan__eyebrow">Active plan</div>
                                        <h3 class="pricing-active-plan__name">{{ $activeMeta['name'] }}</h3>
                                        <div class="pricing-active-plan__price-tag">
                                            <span class="currency">$</span>
                                            <span class="amount">{{ number_format($activeMeta['price']) }}</span>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="pricing-metric">
                                                <div class="pricing-metric__icon"><i class="feather-tag"></i></div>
                                                <div class="pricing-metric__label">Listing limit</div>
                                                <div class="pricing-metric__value">{{ $listingAllowance }}</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="pricing-metric">
                                                <div class="pricing-metric__icon"><i class="feather-calendar"></i></div>
                                                <div class="pricing-metric__label">Expiry</div>
                                                <div class="pricing-metric__value">{{ optional($activePlan->expiry)->format('M d, Y') ?: 'N/A' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="pricing-metric">
                                                <div class="pricing-metric__icon"><i class="feather-activity"></i></div>
                                                <div class="pricing-metric__label">Status</div>
                                                <div class="pricing-metric__value pricing-metric__value--live">
                                                    <i class="feather-check-circle"></i> Live
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="pricing-metric">
                                                <div class="pricing-metric__icon"><i class="feather-hash"></i></div>
                                                <div class="pricing-metric__label">Plan type</div>
                                                <div class="pricing-metric__value">#{{ $activePlan->type }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="pricing-empty">
                                    <div class="pricing-empty__icon"><i class="feather-credit-card"></i></div>
                                    <p class="fw-bold text-dark mb-1">No active plan</p>
                                    <p class="text-muted small mb-3">Choose a plan to unlock listing capacity on Magnate Hub.</p>
                                    <a href="https://magnatehub.au/pricing" target="_blank" rel="noreferrer" class="btn btn-primary btn-sm">Browse packages</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xxl-7">
                    <div class="card pricing-panel stretch stretch-full">
                        <div class="pricing-panel__header">
                            <div class="pricing-panel__header-left">
                                <span class="pricing-panel__header-icon"><i class="feather-pie-chart"></i></span>
                                <div>
                                    <h5 class="pricing-panel__title">Subscription Snapshot</h5>
                                    <p class="pricing-panel__subtitle">Account plan history at a glance</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="pricing-snapshot-card pricing-snapshot-card--total">
                                        <div class="pricing-snapshot-card__icon"><i class="feather-shopping-bag"></i></div>
                                        <div class="pricing-snapshot-card__label">Plans purchased</div>
                                        <div class="pricing-snapshot-card__value">{{ $plans->count() }}</div>
                                        <div class="pricing-snapshot-card__desc">Total plan records on this account.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="pricing-snapshot-card pricing-snapshot-card--active">
                                        <div class="pricing-snapshot-card__icon"><i class="feather-check-circle"></i></div>
                                        <div class="pricing-snapshot-card__label">Active records</div>
                                        <div class="pricing-snapshot-card__value">{{ $activePlanCount }}</div>
                                        <div class="pricing-snapshot-card__desc">Currently valid plan entries.</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="pricing-snapshot-card pricing-snapshot-card--expired">
                                        <div class="pricing-snapshot-card__icon"><i class="feather-clock"></i></div>
                                        <div class="pricing-snapshot-card__label">Expired records</div>
                                        <div class="pricing-snapshot-card__value">{{ $expiredPlanCount }}</div>
                                        <div class="pricing-snapshot-card__desc">Previous subscriptions no longer active.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pricing-section-label mt-4">
                <i class="feather-file-text"></i>
                <span>Billing & history</span>
            </div>

            <div class="row g-4">
                <div class="col-12">
                    <div class="card pricing-panel stretch stretch-full">
                        <div class="pricing-panel__header">
                            <div class="pricing-panel__header-left">
                                <span class="pricing-panel__header-icon"><i class="feather-map-pin"></i></span>
                                <div>
                                    <h5 class="pricing-panel__title">Billing Details</h5>
                                    <p class="pricing-panel__subtitle">Contact and business information on file</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($activePlan && collect($activeBilling)->filter()->isNotEmpty())
                                <div class="row g-3">
                                    @foreach ($activeBilling as $label => $value)
                                        @if (filled($value))
                                            <div class="col-md-6">
                                                <div class="pricing-metric">
                                                    <div class="pricing-metric__label">{{ $label }}</div>
                                                    <div class="pricing-metric__value fs-6">{{ $value }}</div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="pricing-empty py-4">
                                    <div class="pricing-empty__icon"><i class="feather-file-text"></i></div>
                                    <p class="fw-bold text-dark mb-1">No billing details on file</p>
                                    <p class="text-muted small mb-0">Billing details are not available for the active subscription.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card pricing-panel stretch stretch-full">
                        <div class="pricing-panel__header">
                            <div class="pricing-panel__header-left">
                                <span class="pricing-panel__header-icon"><i class="feather-list"></i></span>
                                <div>
                                    <h5 class="pricing-panel__title">Plan History</h5>
                                    <p class="pricing-panel__subtitle">All subscriptions linked to your account</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 pt-1">
                            <div class="table-responsive pricing-table-wrap">
                                <table class="table table-hover align-middle mb-0 pricing-table">
                                    <thead>
                                        <tr>
                                            <th>Plan</th>
                                            <th>Price</th>
                                            <th>Listing limit</th>
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
                                            <tr class="{{ $isActive ? 'pricing-row--active' : '' }}">
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span class="pricing-plan-icon bg-soft-{{ $meta['accent'] }} text-{{ $meta['accent'] }}">
                                                            <i class="feather-package"></i>
                                                        </span>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ $meta['name'] }}</div>
                                                            <div class="fs-11 text-muted">Type #{{ $plan->type }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="fw-bold">${{ number_format($meta['price']) }}</td>
                                                <td>{{ $plan->listing_limit === null ? 'Unlimited' : $plan->listing_limit }}</td>
                                                <td>{{ optional($plan->expiry)->format('M d, Y') ?: 'N/A' }}</td>
                                                <td>
                                                    <span class="badge {{ $isActive ? 'pricing-badge-active' : 'pricing-badge-expired' }}">
                                                        {{ $isActive ? 'Active' : 'Expired' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    <div class="pricing-empty py-4">
                                                        <p class="text-muted mb-0">No plans found.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="pricing-upgrade-cta">
                        <div>
                            <h3 class="pricing-upgrade-cta__title">Need more listings or features?</h3>
                            <p class="pricing-upgrade-cta__text">Compare packages on Magnate Hub and upgrade when you are ready to grow.</p>
                        </div>
                        <a href="https://magnatehub.au/pricing" target="_blank" rel="noreferrer" class="btn pricing-cta-btn">
                            <i class="feather-arrow-up-right me-2"></i>
                            <span>Explore plans</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('professional.dashboard.partials.footer')
@endsection
