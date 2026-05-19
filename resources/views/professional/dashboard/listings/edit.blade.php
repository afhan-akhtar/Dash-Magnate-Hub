@extends('professional.dashboard.layouts.app')

@php
    use App\Support\ListingFormType;

    extract(ListingFormType::resolve(null, isset($projectType) ? (int) $projectType : null));

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
    $existingCard = $project->thumbnail;
    $existingCardUrl = $existingCard?->url;
    if (blank($existingCardUrl) && !blank($existingCard?->path)) {
        $existingCardUrl = asset('storage/' . ltrim(str_replace('\\', '/', $existingCard->path), '/'));
    }
    $existingGallery = $project->documentCollection('gallery')->get();
    $selectedRemovals = collect(old('remove_gallery', []))->map(fn ($id) => (int) $id)->all();
@endphp

@section('title', 'MagnateHub || Edit Listing')
@section('page_title', 'Edit Listing')
@section('page_summary', 'Update listing details, description, and media from your dashboard.')

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
                    <h5 class="m-b-10">Edit Listing</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('professional.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('professional.listings.index') }}">Listings</a></li>
                    <li class="breadcrumb-item">{{ $project->name }}</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto d-flex gap-2">
                <a href="{{ route('professional.listings.show', $project->id) }}" class="btn btn-light-brand">
                    <i class="feather-eye me-2"></i>
                    <span>View Listing</span>
                </a>
                <a href="{{ route('professional.listings.index') }}" class="btn btn-light-brand">
                    <i class="feather-arrow-left me-2"></i>
                    <span>Back to Listings</span>
                </a>
            </div>
        </div>

        <div class="main-content">
            <form method="POST" action="{{ route('professional.listings.update', $project->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $currentType }}">

                <div class="card stretch stretch-full">
                    <div class="card-header">
                        <h5 class="card-title mb-1">Listing Information</h5>
                        <div class="text-muted">@if ($isCapitalRaiseListing)Use the guided sections below — each field includes an example and tip to help you attract the right investors.@elseif ($isBrokerListing)Use the guided sections below — each field includes an example and tip to help you attract serious buyers for broker and franchise opportunities.@elseif ($isSaleListing)Use the guided sections below — each field includes an example and tip to help you attract serious buyers.@elseComplete each section below with accurate, up-to-date information.@endif</div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4 listing-form-grid listing-form-premium">
                            @if ($isGuidedListing)
                                @include('professional.dashboard.listings.partials.listing-essentials', [
                                    'categories' => $categories,
                                    'selectedCategoryId' => old('category_id', $project->category_id),
                                    'selectedLocationId' => old('location_id', $project->location_id),
                                    'selectedRegionId' => old('region_id', $project->region_id),
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
                                            <option value="{{ $category->id }}" {{ (string) old('category_id', $project->category_id) === (string) $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @include('professional.dashboard.listings.partials.listing-location-field', [
                                    'selectedLocationId' => old('location_id', $project->location_id),
                                    'selectedRegionId' => old('region_id', $project->region_id),
                                    'locations' => $locations,
                                    'regions' => $regions,
                                ])
                            @endif

                            @unless ($isGuidedListing)
                                <div class="col-12">
                                    <label class="form-label required">Title</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $project->name) }}" required>
                                </div>
                            @endunless

                            @if ($isSaleListing)
                                <div class="col-12">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $project->name) }}">
                                </div>
                                @include('professional.dashboard.listings.partials.sale-listing-fields', ['project' => $project, 'listingType' => $currentType])
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
                                            <input type="text" class="form-control listing-field-card__input" name="name" value="{{ old('name', $project->name) }}" placeholder="e.g. NovaCare — Seed round capital raise" required>
                                        </div>
                                    </article>
                                </div>
                                @include('professional.dashboard.listings.partials.capital-raise-listing-fields', ['project' => $project])
                            @endif

                            @if ($isSaleListing)
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-4 mt-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="franchise" name="franchise" value="1" {{ old('franchise', $project->franchise) ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2" for="franchise">Franchise</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="multiple_locations" name="multiple_locations" value="1" {{ old('multiple_locations', $project->multiple_locations) ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2" for="multiple_locations">Multiple Locations</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="urgent_sale" name="urgent_sale" value="1" {{ old('urgent_sale', $project->urgent_sale) ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2" for="urgent_sale">Urgent Sale</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="under_offer" name="under_offer" value="1" {{ old('under_offer', $project->under_offer) ? 'checked' : '' }}>
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
                                <label class="form-label">Replace Cover Image</label>
                                <input type="file" class="form-control" name="card" id="card_input" accept="image/*">
                                @if (filled($existingCardUrl))
                                    <div class="mt-3">
                                        <div class="fs-12 text-muted mb-2">Current cover image</div>
                                        <img src="{{ $existingCardUrl }}" alt="Current cover image" class="img-fluid rounded-3 border js-listing-image-preview" id="existing_card_preview" data-preview-src="{{ $existingCardUrl }}" data-preview-title="Current cover image" style="max-height: 220px; object-fit: cover; cursor: zoom-in;">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="remove_card" name="remove_card" value="1" {{ old('remove_card') ? 'checked' : '' }}>
                                            <label class="form-check-label text-danger" for="remove_card">Remove current cover image</label>
                                        </div>
                                    </div>
                                @endif
                                <div class="mt-3 d-none" id="card_preview_wrapper">
                                    <div class="fs-12 text-muted mb-2">New cover preview</div>
                                    <img src="" alt="New cover preview" id="card_preview" class="img-fluid rounded-3 border">
                                </div>
                            </div>
                            <div class="col-md-6 listing-media-field">
                                <label class="form-label">Add Gallery Images</label>
                                <input type="file" class="form-control" name="gallery[]" id="gallery_input" accept="image/*" multiple>
                                @if ($existingGallery->count())
                                    <div class="fs-12 text-muted mt-3 mb-2">Current gallery images</div>
                                    <div class="row g-2">
                                        @foreach ($existingGallery as $galleryDoc)
                                            @php
                                                $galleryUrl = $galleryDoc->url;
                                                if (blank($galleryUrl) && !blank($galleryDoc->path)) {
                                                    $galleryUrl = asset('storage/' . ltrim(str_replace('\\', '/', $galleryDoc->path), '/'));
                                                }
                                            @endphp
                                            @if (filled($galleryUrl))
                                                <div class="col-6">
                                                    <div class="listing-media-thumb border rounded-3 p-2 h-100">
                                                        <img src="{{ $galleryUrl }}" alt="{{ $galleryDoc->original_name ?: 'Gallery image' }}" class="img-fluid rounded-2 border js-listing-image-preview" data-preview-src="{{ $galleryUrl }}" data-preview-title="{{ $galleryDoc->original_name ?: 'Gallery image' }}" style="height: 110px; object-fit: cover; width: 100%; cursor: zoom-in;">
                                                        <div class="form-check mt-2">
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                id="remove_gallery_{{ $galleryDoc->id }}"
                                                                name="remove_gallery[]"
                                                                value="{{ $galleryDoc->id }}"
                                                                {{ in_array((int) $galleryDoc->id, $selectedRemovals, true) ? 'checked' : '' }}
                                                            >
                                                            <label class="form-check-label text-danger fs-12" for="remove_gallery_{{ $galleryDoc->id }}">Remove</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                <div class="row g-2 mt-2" id="gallery_preview"></div>
                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @include('professional.dashboard.listings.partials.listing-form-footer', [
                                'submitLabel' => 'Save Changes',
                                'cancelUrl' => route('professional.listings.index'),
                                'hint' => $isCapitalRaiseListing
                                    ? 'Complete all required fields before saving your capital raise listing.'
                                    : ($isBrokerListing
                                        ? 'Complete all required fields before saving your broker listing.'
                                        : 'Complete all required fields before saving your listing.'),
                            ])
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
                    col.className = 'col-6';
                    col.innerHTML = `<div class="listing-media-thumb border rounded-3 p-2 h-100"><img src="${event.target.result}" class="img-fluid rounded-2 border js-listing-image-preview" data-preview-src="${event.target.result}" data-preview-title="${file.name || 'Gallery image'}" alt="Gallery preview" style="height: 110px; object-fit: cover; width: 100%; cursor: zoom-in;"></div>`;
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
