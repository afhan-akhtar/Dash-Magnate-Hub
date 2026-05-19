@extends('professional.dashboard.layouts.app')

@php
    use App\Support\ListingFormType;

    extract(ListingFormType::resolve(isset($listingType) ? (int) $listingType : null, null));

    $fieldHelp = config('listing_field_help', []);
    $fieldDescription = fn (string $field) => trim((string) data_get($fieldHelp, $field . '.description', ''));
    $fieldExample = fn (string $field) => trim((string) data_get($fieldHelp, $field . '.example', ''));
    $fieldTip = fn (string $field) => trim((string) data_get($fieldHelp, $field . '.tip', ''));
    $fieldLabelWithTip = function (string $label, string $field, bool $required = false): string {
        $requiredClass = $required ? ' required' : '';
        $html = '<label class="form-label'.$requiredClass.'">'.e($label);
        $html .= '</label>';
        return $html;
    };
    $fieldPlaceholder = function (string $field, string $fallback = '') use ($fieldExample): string {
        $example = trim(strip_tags($fieldExample($field)));
        return $example !== '' ? $example : $fallback;
    };
    $fieldAfterHelpHtml = function (string $field) use ($fieldTip): string {
        $tip = $fieldTip($field);
        return $tip !== ''
            ? '<div class="mb-2 text-muted"><span class="fw-semibold text-dark">Tip:</span> '.e($tip).'</div>'
            : '';
    };
    $typeLabel = match ($currentType) {
        1 => 'Buyer / Investor Listing',
        2 => 'Seller Listing',
        3 => 'Capital Raise Listing',
        4 => 'Broker / Franchise Listing',
        default => 'Professional Listing',
    };
@endphp

@section('title', 'MagnateHub || Create Listing')
@section('page_title', 'Create Listing')
@section('page_summary', 'Create a new listing in one continuous form, with fields matched to the selected professional role.')

@section('content')
@include('professional.dashboard.listings.partials.listing-field-styles')
<main class="nxl-container">
    <div class="nxl-content">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="fw-semibold mb-2">Please fix the highlighted issues.</div>
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
                    <h5 class="m-b-10">Create Listing</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('professional.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('professional.listings.index') }}">Listings</a></li>
                    <li class="breadcrumb-item">Create</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <a href="{{ route('professional.listings.index') }}" class="btn btn-light-brand">
                    <i class="feather-arrow-left me-2"></i>
                    <span>Back to Listings</span>
                </a>
            </div>
        </div>

        <div class="main-content">
            @unless ($hasQuota)
                <div class="alert alert-warning border-0 mb-4" role="alert">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            @if ($hasNoPlan ?? false)
                                <div class="fw-semibold text-dark mb-1">No active plan</div>
                                <div class="text-muted">You don't have an active subscription. Subscribe to a plan to start creating listings.</div>
                            @else
                                <div class="fw-semibold text-dark mb-1">Listing limit reached</div>
                                <div class="text-muted">Your current subscription does not have room for another listing. Upgrade your plan to continue publishing.</div>
                            @endif
                        </div>
                        <a href="{{ route('professional.plans.index') }}" class="btn btn-primary">View Plans</a>
                    </div>
                </div>
            @endunless

            <form method="POST" action="{{ route('professional.listings.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $currentType }}">

                <div class="card stretch stretch-full">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h5 class="card-title mb-1">{{ $typeLabel }}</h5>
                            <div class="text-muted">@if ($isCapitalRaiseListing)Use the guided sections below — each field includes an example and tip to help you attract the right investors.@elseif ($isBrokerListing)Use the guided sections below — each field includes an example and tip to help you attract serious buyers for broker and franchise opportunities.@elseif ($isSaleListing)Use the guided sections below — each field includes an example and tip to help you attract serious buyers.@elseThe form below follows the raising workflow, so only the fields relevant to this role are shown.@endif</div>
                        </div>
                        <span class="badge listing-plan-limit-badge">{{ $activePlan?->listing_limit === null ? 'Unlimited listings' : ($activePlan?->listing_limit ? $activePlan->listing_limit . ' listing limit' : 'No active plan') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-4 listing-form-grid listing-form-premium">
                            @if ($isGuidedListing)
                                @include('professional.dashboard.listings.partials.listing-essentials', [
                                    'categories' => $categories,
                                    'locations' => $locations,
                                    'regions' => $regions,
                                    'locationHelpFieldKey' => $isCapitalRaiseListing ? 'capital_location' : 'location_id',
                                ])
                            @else
                                <div class="col-md-4">
                                    <label class="form-label required">Category</label>
                                    <select class="form-control" name="category_id" required>
                                        <option value="">Select category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ (string) old('category_id') === (string) $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @include('professional.dashboard.listings.partials.listing-location-field', [
                                    'locations' => $locations,
                                    'regions' => $regions,
                                ])
                            @endif

                            @unless ($isGuidedListing)
                                <div class="col-12">
                                    <label class="form-label required">Title</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter listing title" required>
                                </div>
                            @endunless

                            @if ($isSaleListing)
                                <div class="col-12">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter business name">
                                </div>
                                @include('professional.dashboard.listings.partials.sale-listing-fields', ['listingType' => $currentType])
                            @elseif ($isCapitalRaiseListing)
                                <div class="col-12">
                                    <article class="listing-field-card">
                                        <header class="listing-field-card__header">
                                            <h3 class="listing-field-card__title">
                                                Listing Title
                                                <span class="listing-required-mark" aria-hidden="true">*</span>
                                            </h3>
                                        </header>
                                        <div class="listing-field-card__input-area" style="border-top: none; padding-top: 0;">
                                            <input type="text" class="form-control listing-field-card__input" name="name" value="{{ old('name') }}" placeholder="e.g. NovaCare — Seed round capital raise" required>
                                        </div>
                                    </article>
                                </div>
                                @include('professional.dashboard.listings.partials.capital-raise-listing-fields')
                            @else
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Seeking Investment', 'seeking_investment', true) !!}
                                    @if ($fieldDescription('seeking_investment'))
                                        <div class="form-text mb-2">{{ $fieldDescription('seeking_investment') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="seeking_investment" value="{{ old('seeking_investment') }}" placeholder="{{ $fieldPlaceholder('seeking_investment', 'Enter investment amount') }}" required>
                                    @if ($fieldTip('seeking_investment'))
                                        {!! $fieldAfterHelpHtml('seeking_investment') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Reported Sales', 'reported_sales') !!}
                                    @if ($fieldDescription('reported_sales'))
                                        <div class="form-text mb-2">{{ $fieldDescription('reported_sales') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="reported_sales" value="{{ old('reported_sales') }}" placeholder="{{ $fieldPlaceholder('reported_sales', 'Enter reported sales') }}">
                                    @if ($fieldTip('reported_sales'))
                                        {!! $fieldAfterHelpHtml('reported_sales') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Run Rate Sales', 'run_rate_sales') !!}
                                    @if ($fieldDescription('run_rate_sales'))
                                        <div class="form-text mb-2">{{ $fieldDescription('run_rate_sales') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="run_rate_sales" value="{{ old('run_rate_sales') }}" placeholder="{{ $fieldPlaceholder('run_rate_sales', 'Enter run rate sales') }}">
                                    @if ($fieldTip('run_rate_sales'))
                                        {!! $fieldAfterHelpHtml('run_rate_sales') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('EBITDA Margin', 'ebitda_margin') !!}
                                    @if ($fieldDescription('ebitda_margin'))
                                        <div class="form-text mb-2">{{ $fieldDescription('ebitda_margin') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="ebitda_margin" value="{{ old('ebitda_margin') }}" placeholder="{{ $fieldPlaceholder('ebitda_margin', 'Enter EBITDA margin') }}">
                                    @if ($fieldTip('ebitda_margin'))
                                        {!! $fieldAfterHelpHtml('ebitda_margin') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Industry', 'industry', true) !!}
                                    @if ($fieldDescription('industry'))
                                        <div class="form-text mb-2">{{ $fieldDescription('industry') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="industry" value="{{ old('industry') }}" placeholder="{{ $fieldPlaceholder('industry', 'Enter industry') }}" required>
                                    @if ($fieldTip('industry'))
                                        {!! $fieldAfterHelpHtml('industry') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Assets Or Collateral', 'assets_or_collateral') !!}
                                    @if ($fieldDescription('assets_or_collateral'))
                                        <div class="form-text mb-2">{{ $fieldDescription('assets_or_collateral') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="assets_or_collateral" value="{{ old('assets_or_collateral') }}" placeholder="{{ $fieldPlaceholder('assets_or_collateral', 'Enter assets or collateral') }}">
                                    @if ($fieldTip('assets_or_collateral'))
                                        {!! $fieldAfterHelpHtml('assets_or_collateral') !!}
                                    @endif
                                </div>
                                <div class="col-12">
                                    {!! $fieldLabelWithTip('Interested To Connect With Advisors', 'interested_to_connect_with_advisors') !!}
                                    @if ($fieldDescription('interested_to_connect_with_advisors'))
                                        <div class="form-text mb-2">{{ $fieldDescription('interested_to_connect_with_advisors') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="interested_to_connect_with_advisors" value="{{ old('interested_to_connect_with_advisors') }}" placeholder="{{ $fieldPlaceholder('interested_to_connect_with_advisors', 'Yes or No') }}">
                                    @if ($fieldTip('interested_to_connect_with_advisors'))
                                        {!! $fieldAfterHelpHtml('interested_to_connect_with_advisors') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Business Overview', 'business_overview', true) !!}
                                    @if ($fieldDescription('business_overview'))
                                        <div class="form-text mb-2">{{ $fieldDescription('business_overview') }}</div>
                                    @endif
                                    <textarea class="form-control" name="business_overview" rows="4" placeholder="{{ $fieldPlaceholder('business_overview', 'Enter business overview') }}" required>{{ old('business_overview') }}</textarea>
                                    @if ($fieldTip('business_overview'))
                                        {!! $fieldAfterHelpHtml('business_overview') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Products & Services Overview', 'products_and_services_overview') !!}
                                    @if ($fieldDescription('products_and_services_overview'))
                                        <div class="form-text mb-2">{{ $fieldDescription('products_and_services_overview') }}</div>
                                    @endif
                                    <textarea class="form-control" name="products_and_services_overview" rows="4" placeholder="{{ $fieldPlaceholder('products_and_services_overview', 'Enter products and services overview') }}">{{ old('products_and_services_overview') }}</textarea>
                                    @if ($fieldTip('products_and_services_overview'))
                                        {!! $fieldAfterHelpHtml('products_and_services_overview') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Assets Overview', 'assets_overview') !!}
                                    @if ($fieldDescription('assets_overview'))
                                        <div class="form-text mb-2">{{ $fieldDescription('assets_overview') }}</div>
                                    @endif
                                    <textarea class="form-control" name="assets_overview" rows="4" placeholder="{{ $fieldPlaceholder('assets_overview', 'Enter assets overview') }}">{{ old('assets_overview') }}</textarea>
                                    @if ($fieldTip('assets_overview'))
                                        {!! $fieldAfterHelpHtml('assets_overview') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Facilities Overview', 'facilities_overview') !!}
                                    @if ($fieldDescription('facilities_overview'))
                                        <div class="form-text mb-2">{{ $fieldDescription('facilities_overview') }}</div>
                                    @endif
                                    <textarea class="form-control" name="facilities_overview" rows="4" placeholder="{{ $fieldPlaceholder('facilities_overview', 'Enter facilities overview') }}">{{ old('facilities_overview') }}</textarea>
                                    @if ($fieldTip('facilities_overview'))
                                        {!! $fieldAfterHelpHtml('facilities_overview') !!}
                                    @endif
                                </div>
                                <div class="col-12">
                                    {!! $fieldLabelWithTip('Capitalization Overview', 'capitalization_overview') !!}
                                    @if ($fieldDescription('capitalization_overview'))
                                        <div class="form-text mb-2">{{ $fieldDescription('capitalization_overview') }}</div>
                                    @endif
                                    <textarea class="form-control" name="capitalization_overview" rows="4" placeholder="{{ $fieldPlaceholder('capitalization_overview', 'Enter capitalization overview') }}">{{ old('capitalization_overview') }}</textarea>
                                    @if ($fieldTip('capitalization_overview'))
                                        {!! $fieldAfterHelpHtml('capitalization_overview') !!}
                                    @endif
                                </div>
                            @endif

                            @if ($isSaleListing)
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-4 mt-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="franchise" name="franchise" value="1" {{ old('franchise') ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2" for="franchise">Franchise</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="multiple_locations" name="multiple_locations" value="1" {{ old('multiple_locations') ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2" for="multiple_locations">Multiple Locations</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="urgent_sale" name="urgent_sale" value="1" {{ old('urgent_sale') ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2" for="urgent_sale">Urgent Sale</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="under_offer" name="under_offer" value="1" {{ old('under_offer') ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2" for="under_offer">Under Offer</label>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if ($isGuidedListing)
                                <div class="col-12 listing-section-wrap">
                                    <div class="listing-section-panel">
                                        @include('professional.dashboard.listings.partials.listing-section-heading', [
                                            'number' => 5,
                                            'title' => 'Media',
                                            'subtitle' => 'Cover image and gallery photos for your listing.',
                                            'icon' => 'feather-image',
                                        ])
                                        <div class="row g-4 listing-media-panel pb-3">
                            <div class="col-md-6 listing-media-field">
                                <label class="form-label">Cover Image</label>
                                <input type="file" class="form-control" name="card" id="card_input" accept="image/*">
                                <div class="fs-12 text-muted mt-2">Upload the main image for the listing.</div>
                                <div class="mt-3 d-none" id="card_preview_wrapper">
                                    <img src="" alt="Cover preview" id="card_preview" class="img-fluid rounded-3 border">
                                </div>
                            </div>
                            <div class="col-md-6 listing-media-field">
                                <label class="form-label">Gallery Images</label>
                                <input type="file" class="form-control" name="gallery[]" id="gallery_input" accept="image/*" multiple>
                                <div class="fs-12 text-muted mt-2">Add supporting images for the listing.</div>
                                <div class="row g-2 mt-2" id="gallery_preview"></div>
                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($isGuidedListing)
                                @include('professional.dashboard.listings.partials.listing-form-footer', [
                                    'submitLabel' => 'Create Listing',
                                    'cancelUrl' => route('professional.listings.index'),
                                    'submitDisabled' => ! $hasQuota,
                                    'hint' => $isCapitalRaiseListing
                                        ? 'Complete all required fields before publishing your capital raise listing.'
                                        : ($isBrokerListing
                                            ? 'Complete all required fields before publishing your broker listing.'
                                            : 'Complete all required fields before saving your listing.'),
                                ])
                            @else
                            <div class="col-12">
                                <div class="listing-form-sticky-footer d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <p class="text-muted small mb-0">Strong titles, clear information, and quality photos help listings perform better.</p>
                                    <button type="submit" class="btn btn-primary btn-listing-save" {{ $hasQuota ? '' : 'disabled' }}>
                                        <i class="feather-save me-2"></i>
                                        <span>Create Listing</span>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<div class="modal fade" id="listing-image-preview-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="listing-image-preview-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center bg-light">
                <img id="listing-image-preview-src" src="" alt="Listing image preview" class="img-fluid rounded-3 border">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formGrid = document.querySelector('.listing-form-grid');
        const locationSelect = document.getElementById('location_id');
        const regionSelect = document.getElementById('region_id');
        const cardInput = document.getElementById('card_input');
        const cardPreview = document.getElementById('card_preview');
        const cardPreviewWrapper = document.getElementById('card_preview_wrapper');
        const galleryInput = document.getElementById('gallery_input');
        const galleryPreview = document.getElementById('gallery_preview');
        const franchiseInput = document.getElementById('franchise');
        const multipleLocationsInput = document.getElementById('multiple_locations');
        const urgentSaleInput = document.getElementById('urgent_sale');
        const underOfferInput = document.getElementById('under_offer');
        const imagePreviewModalEl = document.getElementById('listing-image-preview-modal');
        const imagePreviewTitle = document.getElementById('listing-image-preview-title');
        const imagePreviewSrc = document.getElementById('listing-image-preview-src');
        const imagePreviewModal = (imagePreviewModalEl && window.bootstrap && window.bootstrap.Modal)
            ? window.bootstrap.Modal.getOrCreateInstance(imagePreviewModalEl)
            : null;

        const syncRegions = () => {
            const selectedLocation = locationSelect.value;

            Array.from(regionSelect.options).forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    return;
                }

                const matches = !selectedLocation || option.dataset.locationId === selectedLocation;
                option.hidden = !matches;

                if (!matches && option.selected) {
                    regionSelect.value = '';
                }
            });
        };

        locationSelect.addEventListener('change', syncRegions);
        syncRegions();

        const normalTagInputs = [franchiseInput, multipleLocationsInput, urgentSaleInput].filter(Boolean);
        const syncTagRules = () => {
            if (!underOfferInput) {
                return;
            }

            const hasExclusiveTag = underOfferInput.checked;
            if (hasExclusiveTag) {
                normalTagInputs.forEach((input) => {
                    input.checked = false;
                    input.disabled = true;
                });
            } else {
                normalTagInputs.forEach((input) => {
                    input.disabled = false;
                });
            }
        };

        [underOfferInput, ...normalTagInputs].forEach((input) => {
            if (!input) {
                return;
            }
            input.addEventListener('change', syncTagRules);
        });
        syncTagRules();

        const equalizeGridRowHeights = () => {
            if (!formGrid) {
                return;
            }

            const columns = Array.from(formGrid.querySelectorAll(':scope > [class*="col-"]'));
            columns.forEach((col) => {
                col.style.minHeight = '';
            });

            if (window.innerWidth < 768) {
                return;
            }

            const rowsByTop = new Map();
            columns.forEach((col) => {
                const top = Math.round(col.getBoundingClientRect().top);
                if (!rowsByTop.has(top)) {
                    rowsByTop.set(top, []);
                }
                rowsByTop.get(top).push(col);
            });

            rowsByTop.forEach((rowCols) => {
                const maxHeight = Math.max(...rowCols.map((col) => col.offsetHeight));
                rowCols.forEach((col) => {
                    col.style.minHeight = `${maxHeight}px`;
                });
            });
        };
        equalizeGridRowHeights();
        window.addEventListener('resize', equalizeGridRowHeights);
        cardInput.addEventListener('change', function () {
            const [file] = this.files;
            if (!file) {
                cardPreviewWrapper.classList.add('d-none');
                cardPreview.src = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                cardPreview.src = event.target.result;
                cardPreview.classList.add('js-listing-image-preview');
                cardPreview.dataset.previewSrc = event.target.result;
                cardPreview.dataset.previewTitle = file.name || 'Cover image';
                cardPreview.style.cursor = 'zoom-in';
                cardPreviewWrapper.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        });

        galleryInput.addEventListener('change', function () {
            galleryPreview.innerHTML = '';

            Array.from(this.files).forEach((file) => {
                const reader = new FileReader();
                reader.onload = (event) => {
                    const col = document.createElement('div');
                    col.className = 'col-6 col-md-4';
                    col.innerHTML = `<div class="listing-media-thumb border rounded-3 p-2 h-100"><img src="${event.target.result}" class="img-fluid rounded-2 border js-listing-image-preview" data-preview-src="${event.target.result}" data-preview-title="${file.name || 'Gallery image'}" alt="Gallery preview" style="height: 120px; object-fit: cover; width: 100%; cursor: zoom-in;"></div>`;
                    galleryPreview.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        });

        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('.js-listing-image-preview');
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


