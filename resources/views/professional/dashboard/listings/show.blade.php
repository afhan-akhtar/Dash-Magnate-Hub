@extends('professional.dashboard.layouts.app')

@php
    $pageTitle = $project->name ?: 'View Listing';
    $coverImage = optional($project->thumbnail)->url ?: '/dashboard/assets/images/empty.jpg';
    $galleryItems = $gallery
        ->map(function ($image) {
            $url = $image->url;
            if (blank($url) && !blank($image->path)) {
                $url = asset('storage/' . ltrim(str_replace('\\', '/', $image->path), '/'));
            }

            return [
                'url' => $url,
                'name' => $image->original_name ?: 'Gallery image',
            ];
        })
        ->filter(fn ($image) => filled($image['url']))
        ->values();

    $normalizeText = static fn ($value) => trim(strip_tags((string) $value));

    $stats = [
        ['label' => 'Price', 'value' => $project->price ? '$' . number_format($project->price) : 'N/A', 'icon' => 'feather-dollar-sign', 'tone' => 'primary'],
        ['label' => 'Status', 'value' => $project->isDeleted() ? 'Deleted' : ($project->active ? 'Active' : 'Inactive'), 'icon' => 'feather-activity', 'tone' => 'primary'],
    ];

    $detailGroups = [
        'Listing Basics' => [
            'Removed at' => $project->deleted_at?->format('M j, Y g:i A'),
            'On marketplace' => $project->isDeleted() ? null : ($project->active ? 'Yes (active)' : 'No (inactive)'),
            'Category' => $project->category->name ?? null,
            'Location' => $project->location->name ?? null,
            'Region' => $project->region->name ?? null,
            'Trading Years' => $project->trading,
            'Earning Type' => $project->earning_type,
            'Stock Level' => $project->stock_level,
            'Industry' => $project->industry,
            'Franchise' => $project->franchise ? 'Yes' : null,
            'Multiple Locations' => $project->multiple_locations ? 'Yes' : null,
            'Under Offer' => $project->under_offer ? 'Yes' : null,
            'Blocked' => $project->block ? 'Yes' : null,
        ],
        'Operations' => [
            'Skills' => $project->skills,
            'Growth Potential' => $project->potential,
            'Hours' => $project->hours,
            'Staff' => $project->staff,
            'Lease' => $project->lease,
            'Business Established' => $project->business_established,
            'Training' => $project->training,
            'Awards' => $project->awards,
            'Reason For Sale' => $project->reason_for_sale,
        ],
        'Financial Snapshot' => [
            'Seeking Investment' => $project->seeking_investment,
            'Reported Sales' => $project->reported_sales,
            'Run Rate Sales' => $project->run_rate_sales,
            'EBITDA Margin' => $project->ebitda_margin,
            'Assets Or Collateral' => $project->assets_or_collateral,
            'Connect With Advisors' => $project->interested_to_connect_with_advisors,
        ],
    ];
    $listingBasicsIcons = [
        'Removed at' => 'feather-clock',
        'On marketplace' => 'feather-activity',
        'Category' => 'feather-grid',
        'Location' => 'feather-map-pin',
        'Region' => 'feather-map',
        'Trading Years' => 'feather-calendar',
        'Earning Type' => 'feather-trending-up',
        'Stock Level' => 'feather-layers',
        'Industry' => 'feather-briefcase',
        'Franchise' => 'feather-award',
        'Multiple Locations' => 'feather-globe',
        'Under Offer' => 'feather-tag',
        'Blocked' => 'feather-slash',
    ];
    $financialSnapshotIcons = [
        'Seeking Investment' => 'feather-dollar-sign',
        'Reported Sales' => 'feather-bar-chart-2',
        'Run Rate Sales' => 'feather-trending-up',
        'EBITDA Margin' => 'feather-pie-chart',
        'Assets Or Collateral' => 'feather-package',
        'Connect With Advisors' => 'feather-users',
    ];
    $operationsFields = $detailGroups['Operations'] ?? [];
    unset($detailGroups['Operations']);

    $longSections = [
        'Summary' => $project->summary,
        'Description' => $project->description,
        'Location Information' => $project->location_information,
        'Business Overview' => $project->business_overview,
        'Products & Services Overview' => $project->products_and_services_overview,
        'Assets Overview' => $project->assets_overview,
        'Facilities Overview' => $project->facilities_overview,
        'Capitalization Overview' => $project->capitalization_overview,
    ];

    $visibleListingBasics = collect($detailGroups['Listing Basics'] ?? [])
        ->map(fn ($value, $label) => [
            'label' => $label,
            'value' => $normalizeText($value),
            'icon' => $listingBasicsIcons[$label] ?? 'feather-info',
        ])
        ->filter(fn ($item) => filled($item['value']))
        ->values();

    $visibleOperations = collect($operationsFields)
        ->map(fn ($value, $label) => [
            'label' => $label,
            'value' => $normalizeText($value),
        ])
        ->filter(fn ($item) => filled($item['value']))
        ->values();

    $visibleFinancialSnapshot = collect($detailGroups['Financial Snapshot'] ?? [])
        ->map(fn ($value, $label) => [
            'label' => $label,
            'value' => $normalizeText($value),
            'icon' => $financialSnapshotIcons[$label] ?? 'feather-info',
        ])
        ->filter(fn ($item) => filled($item['value']))
        ->values();

    $visibleLongSections = collect($longSections)
        ->map(fn ($value, $label) => [
            'label' => $label,
            'value' => $normalizeText($value),
        ])
        ->filter(fn ($item) => filled($item['value']))
        ->values();
@endphp

@section('title', 'MagnateHub || ' . $pageTitle)
@section('page_title', $pageTitle)
@section('page_summary', 'Review the full project profile, media, and the extended raising-style listing details in one place.')

@section('styles')
<style>
    .listing-view-shell {
        padding-bottom: 2rem;
    }

    .listing-view-stack {
        display: grid;
        gap: 1.75rem;
    }

    .listing-view-top {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(300px, 0.95fr);
        gap: 1.5rem;
        align-items: stretch;
    }

    .listing-view-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
    }

    .listing-view-hero {
        position: relative;
        overflow: hidden;
        min-height: 340px;
        cursor: zoom-in;
    }

    .listing-view-hero-image {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: zoom-in;
    }

    .listing-view-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.18) 0%, rgba(15, 23, 42, 0.78) 100%);
    }

    .listing-view-hero-body {
        position: relative;
        z-index: 1;
        min-height: 340px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 1.5rem 1.75rem;
        color: #ffffff;
    }

    .listing-view-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .listing-view-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.38rem 0.7rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.01em;
        line-height: 1;
    }

    .listing-view-badge--light {
        background: rgba(255, 255, 255, 0.95);
        color: #0f172a;
    }

    .listing-view-badge--success {
        background: rgba(23, 198, 102, 0.92);
        color: #ffffff;
    }

    .listing-view-badge--danger {
        background: rgba(234, 77, 77, 0.92);
        color: #ffffff;
    }

    .listing-view-badge--warning {
        background: rgba(255, 162, 29, 0.92);
        color: #0f172a;
    }

    .listing-view-badge--primary {
        background: rgba(86, 12, 227, 0.92);
        color: #ffffff;
    }

    .listing-view-badge--info {
        background: rgba(61, 199, 190, 0.92);
        color: #ffffff;
    }

    .listing-view-badge--dark {
        background: rgba(15, 23, 42, 0.92);
        color: #ffffff;
    }

    .listing-view-title {
        margin: 0;
        font-size: clamp(1.85rem, 2.4vw, 2.5rem);
        font-weight: 800;
        line-height: 1.12;
        color: #ffffff;
    }

    .listing-view-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.85rem 1.5rem;
        margin-top: 1rem;
        font-size: 0.98rem;
        font-weight: 600;
    }

    .listing-view-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 0;
    }

    .listing-view-meta-item i {
        flex: 0 0 auto;
    }

    .listing-view-stats {
        display: grid;
        grid-template-rows: repeat(2, minmax(0, 1fr));
        gap: 1.5rem;
    }

    .listing-view-stat {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.5rem;
        min-height: 160px;
    }

    .listing-view-stat-icon {
        width: 4rem;
        height: 4rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(86, 12, 227, 0.14);
        color: #560ce3;
        border: 1px solid rgba(86, 12, 227, 0.14);
        flex: 0 0 auto;
    }

    .listing-view-stat-icon i {
        font-size: 1.35rem;
    }

    .listing-view-stat-copy {
        min-width: 0;
        text-align: right;
    }

    .listing-view-stat-label {
        margin: 0 0 0.4rem;
        font-size: 0.84rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #64748b;
    }

    .listing-view-stat-value {
        margin: 0;
        font-size: clamp(1.55rem, 2vw, 2.25rem);
        font-weight: 800;
        line-height: 1.1;
        color: #0f172a;
        word-break: break-word;
    }

    .listing-view-panel-head {
        padding: 1.15rem 1.35rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .listing-view-panel-title {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
    }

    .listing-view-panel-body {
        padding: 1.35rem;
    }

    .listing-view-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 1rem;
    }

    .listing-view-gallery-item {
        display: block;
        width: 100%;
        padding: 0;
        appearance: none;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        background: #f8fafc;
        cursor: pointer;
        text-align: left;
    }

    .listing-view-gallery-item img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
    }

    .listing-view-gallery-empty {
        padding: 2rem;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        text-align: center;
        color: #64748b;
        background: #f8fafc;
    }

    .listing-view-section + .listing-view-section {
        margin-top: 0.5rem;
    }

    .listing-view-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.85rem;
    }

    .listing-view-section-title {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
    }

    .listing-view-section-divider {
        height: 1px;
        background: #e5e7eb;
        margin-bottom: 1.25rem;
    }

    .listing-view-group-card {
        overflow: hidden;
        box-shadow: none;
        background: #ffffff;
        border-color: #e6ebf2;
    }

    .listing-view-group-head {
        padding: 1.15rem 1.35rem;
        border-bottom: 1px solid #e6ebf2;
        background: #ffffff;
    }

    .listing-view-group-body {
        padding: 0;
        background: #ffffff;
    }

    .listing-view-basics-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0;
    }

    .listing-view-grid-two {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .listing-view-grid-three {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .listing-view-detail-card {
        min-width: 0;
        padding: 1.25rem;
    }

    .listing-view-basic-card {
        min-height: 210px;
        border: 0;
        border-radius: 0;
        background: #ffffff;
        box-shadow: inset -1px 0 0 #e6ebf2, inset 0 -1px 0 #e6ebf2;
        padding: 1.65rem 1.6rem 1.75rem;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: flex-start;
    }

    .listing-view-basic-card .listing-view-detail-icon {
        margin-bottom: 1.15rem;
        background: #f3ecff;
        border-color: #d7c4ff;
        box-shadow: none;
    }

    .listing-view-basic-card .listing-view-detail-label {
        margin-bottom: 0.6rem;
    }

    .listing-view-basic-card .listing-view-detail-value {
        font-size: 1.1rem;
        line-height: 1.45;
    }

    .listing-view-detail-icon {
        width: 2.9rem;
        height: 2.9rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        background: rgba(86, 12, 227, 0.12);
        color: #560ce3;
        border: 1px solid rgba(86, 12, 227, 0.12);
    }

    .listing-view-detail-icon i {
        font-size: 1.1rem;
    }

    .listing-view-detail-label {
        margin: 0 0 0.45rem;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
    }

    .listing-view-detail-value {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.65;
        color: #0f172a;
        white-space: pre-line;
        word-break: break-word;
    }

    .listing-view-content-card .listing-view-detail-value,
    .listing-view-operation-card .listing-view-detail-value {
        font-weight: 600;
    }

    .listing-view-content-card {
        overflow: hidden;
        padding: 0;
    }

    .listing-view-content-head {
        padding: 1.15rem 1.35rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .listing-view-content-title {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 800;
        line-height: 1.25;
        color: #0f172a;
    }

    .listing-view-content-body {
        padding: 1.35rem;
    }

    @media (max-width: 1399.98px) {
        .listing-view-basics-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .listing-view-grid-three {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 991.98px) {
        .listing-view-top {
            grid-template-columns: 1fr;
        }

        .listing-view-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            grid-template-rows: none;
        }

        .listing-view-basics-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .listing-view-shell {
            padding-bottom: 1.5rem;
        }

        .listing-view-top,
        .listing-view-stack,
        .listing-view-stats,
        .listing-view-grid-two,
        .listing-view-grid-three,
        .listing-view-basics-grid {
            grid-template-columns: 1fr;
        }

        .listing-view-hero,
        .listing-view-hero-body {
            min-height: 280px;
        }

        .listing-view-hero-body,
        .listing-view-stat,
        .listing-view-panel-body,
        .listing-view-content-body,
        .listing-view-detail-card {
            padding: 1rem;
        }

        .listing-view-group-body {
            padding: 0;
        }

        .listing-view-panel-head,
        .listing-view-group-head {
            padding: 1rem;
        }

        .listing-view-stat {
            min-height: 130px;
        }

        .listing-view-gallery-item img {
            height: 180px;
        }
    }
</style>
@endsection

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Listing View</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('professional.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('professional.listings.index') }}">Listings</a></li>
                    <li class="breadcrumb-item">{{ $project->name }}</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto d-flex gap-2">
                <a href="{{ route('professional.listings.index') }}" class="btn btn-light-brand">
                    <i class="feather-arrow-left me-2"></i>
                    <span>Back to Listings</span>
                </a>
                @if ($hasQuota ?? true)
                    <a href="{{ route('professional.listings.create') }}" class="btn btn-primary">
                        <i class="feather-plus me-2"></i>
                        <span>Create Another</span>
                    </a>
                @else
                    <a href="{{ route('professional.plans.index') }}" class="btn btn-warning">
                        <i class="feather-shopping-cart me-2"></i>
                        <span>Buy New Listing</span>
                    </a>
                @endif
            </div>
        </div>

        <div class="main-content">
            <div class="listing-view-shell">
                <div class="listing-view-stack">
                    <section class="listing-view-top">
                        <article
                            class="listing-view-card listing-view-hero js-listing-show-image-preview"
                            data-preview-src="{{ $coverImage }}"
                            data-preview-title="{{ $project->name }}"
                        >
                            <img
                                src="{{ $coverImage }}"
                                alt="{{ $project->name }}"
                                class="listing-view-hero-image js-listing-show-image-preview"
                                data-preview-src="{{ $coverImage }}"
                                data-preview-title="{{ $project->name }}"
                                loading="lazy"
                                decoding="async"
                            >
                            <div class="listing-view-hero-overlay"></div>
                            <div class="listing-view-hero-body">
                                <div class="listing-view-badges">
                                    <span class="listing-view-badge listing-view-badge--light">{{ $project->category->name ?? 'Uncategorised' }}</span>
                                    @if ($project->isDeleted())
                                        <span class="listing-view-badge listing-view-badge--danger">Deleted</span>
                                    @elseif ($project->active)
                                        <span class="listing-view-badge listing-view-badge--success">Active</span>
                                    @else
                                        <span class="listing-view-badge listing-view-badge--warning">Inactive</span>
                                    @endif
                                    @if ($project->premium)
                                        <span class="listing-view-badge listing-view-badge--primary">Premium</span>
                                    @endif
                                    @if ($project->franchise)
                                        <span class="listing-view-badge listing-view-badge--warning">Franchise</span>
                                    @endif
                                    @if ($project->multiple_locations)
                                        <span class="listing-view-badge listing-view-badge--info">Multiple Locations</span>
                                    @endif
                                    @if ($project->under_offer)
                                        <span class="listing-view-badge listing-view-badge--info">Under Offer</span>
                                    @endif
                                </div>

                                <h2 class="listing-view-title">{{ $project->name }}</h2>

                                <div class="listing-view-meta">
                                    <div class="listing-view-meta-item">
                                        <i class="feather-map-pin"></i>
                                        <span>{{ $project->location->name ?? 'No location' }}</span>
                                    </div>
                                    <div class="listing-view-meta-item">
                                        <i class="feather-calendar"></i>
                                        <span>Created {{ $project->created_at?->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <div class="listing-view-stats">
                            @foreach ($stats as $stat)
                                <article class="listing-view-card listing-view-stat">
                                    <span class="listing-view-stat-icon">
                                        <i class="{{ $stat['icon'] }}"></i>
                                    </span>
                                    <div class="listing-view-stat-copy">
                                        <p class="listing-view-stat-label">{{ $stat['label'] }}</p>
                                        <h3 class="listing-view-stat-value">{{ $stat['value'] }}</h3>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>

                    <section class="listing-view-card">
                        <div class="listing-view-panel-head">
                            <h3 class="listing-view-panel-title">Gallery</h3>
                        </div>
                        <div class="listing-view-panel-body">
                            @if ($galleryItems->count())
                                <div class="listing-view-gallery-grid">
                                    @foreach ($galleryItems as $image)
                                        <button
                                            type="button"
                                            class="listing-view-gallery-item js-listing-show-image-preview"
                                            data-preview-src="{{ $image['url'] }}"
                                            data-preview-title="{{ $image['name'] }}"
                                        >
                                            <img
                                                src="{{ $image['url'] }}"
                                                alt="{{ $image['name'] }}"
                                                loading="lazy"
                                                decoding="async"
                                            >
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <div class="listing-view-gallery-empty">
                                    No gallery images uploaded yet.
                                </div>
                            @endif
                        </div>
                    </section>

                    @if ($visibleListingBasics->isNotEmpty())
                        <section class="listing-view-card listing-view-group-card">
                            <div class="listing-view-group-head">
                                <h3 class="listing-view-panel-title">Listing Basics</h3>
                            </div>

                            <div class="listing-view-group-body">
                                <div class="listing-view-basics-grid">
                                    @foreach ($visibleListingBasics as $field)
                                        <article class="listing-view-detail-card listing-view-basic-card">
                                            <span class="listing-view-detail-icon">
                                                <i class="{{ $field['icon'] }}"></i>
                                            </span>
                                            <p class="listing-view-detail-label">{{ $field['label'] }}</p>
                                            <p class="listing-view-detail-value">{{ $field['value'] }}</p>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    @endif

                    @if ($visibleOperations->isNotEmpty())
                        <section class="listing-view-section">
                            <div class="listing-view-section-head">
                                <h3 class="listing-view-section-title">Operations</h3>
                            </div>
                            <div class="listing-view-section-divider"></div>

                            <div class="listing-view-grid-two">
                                @foreach ($visibleOperations as $field)
                                    <article class="listing-view-card listing-view-detail-card listing-view-operation-card">
                                        <p class="listing-view-detail-label">{{ $field['label'] }}</p>
                                        <p class="listing-view-detail-value">{{ $field['value'] }}</p>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if ($visibleFinancialSnapshot->isNotEmpty())
                        <section class="listing-view-section">
                            <div class="listing-view-section-head">
                                <h3 class="listing-view-section-title">Financial Snapshot</h3>
                            </div>
                            <div class="listing-view-section-divider"></div>

                            <div class="listing-view-grid-three">
                                @foreach ($visibleFinancialSnapshot as $field)
                                    <article class="listing-view-card listing-view-detail-card">
                                        <span class="listing-view-detail-icon">
                                            <i class="{{ $field['icon'] }}"></i>
                                        </span>
                                        <p class="listing-view-detail-label">{{ $field['label'] }}</p>
                                        <p class="listing-view-detail-value">{{ $field['value'] }}</p>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if ($visibleLongSections->isNotEmpty())
                        <section class="listing-view-section">
                            <div class="listing-view-grid-two">
                                @foreach ($visibleLongSections as $section)
                                    <article class="listing-view-card listing-view-content-card">
                                        <div class="listing-view-content-head">
                                            <h3 class="listing-view-content-title">{{ $section['label'] }}</h3>
                                        </div>
                                        <div class="listing-view-content-body">
                                            <p class="listing-view-detail-value">{{ $section['value'] }}</p>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="listing-show-image-preview-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="listing-show-image-preview-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center bg-light">
                <img id="listing-show-image-preview-src" src="" alt="Listing image preview" class="img-fluid rounded-3 border" loading="lazy" decoding="async">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imagePreviewModalEl = document.getElementById('listing-show-image-preview-modal');
        const imagePreviewTitle = document.getElementById('listing-show-image-preview-title');
        const imagePreviewSrc = document.getElementById('listing-show-image-preview-src');
        const imagePreviewModal = (imagePreviewModalEl && window.bootstrap && window.bootstrap.Modal)
            ? window.bootstrap.Modal.getOrCreateInstance(imagePreviewModalEl)
            : null;

        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('.js-listing-show-image-preview');
            if (!trigger || !imagePreviewModal || !imagePreviewSrc) {
                return;
            }

            const src = trigger.dataset.previewSrc || trigger.getAttribute('src');
            if (!src) {
                return;
            }

            imagePreviewSrc.src = src;
            imagePreviewSrc.alt = trigger.dataset.previewTitle || 'Listing image preview';
            if (imagePreviewTitle) {
                imagePreviewTitle.textContent = trigger.dataset.previewTitle || 'Image Preview';
            }
            imagePreviewModal.show();
        });
    });
</script>
@endsection
