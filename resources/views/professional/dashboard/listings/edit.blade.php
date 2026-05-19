@extends('professional.dashboard.layouts.app')

@php
    $currentType = (int) ($projectType ?? session()->get('type', 0));
    $isSaleListing = $isSaleListing ?? in_array($currentType, [2, 4], true);
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
<style>
    .listing-form-grid > [class*="col-"] {
        transition: min-height 0.15s ease;
    }

    .listing-form-grid textarea.form-control {
        border: 1px solid #ced4da;
        border-radius: 10px;
        padding: 0.7rem 0.85rem;
        background-color: #fff;
    }

    .listing-form-grid textarea.form-control:focus {
        border-color: #5e72e4;
        box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.15);
    }
</style>
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
                        <div class="text-muted">Update your listing fields. Leave unchanged fields as-is.</div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4 listing-form-grid">
                            <div class="col-md-4">
                                <label class="form-label {{ $isSaleListing ? 'required' : '' }}">Category</label>
                                <select class="form-control" name="category_id" {{ $isSaleListing ? 'required' : '' }}>
                                    <option value="">Select category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ (string) old('category_id', $project->category_id) === (string) $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                {!! $fieldLabelWithTip('Location', 'location_id', true) !!}
                                @if ($fieldDescription('location_id'))
                                    <div class="form-text mb-2">{{ $fieldDescription('location_id') }}</div>
                                @endif
                                <select class="form-control" name="location_id" id="location_id" required>
                                    <option value="">Select location</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}" {{ (string) old('location_id', $project->location_id) === (string) $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Region</label>
                                <select class="form-control" name="region_id" id="region_id">
                                    <option value="">Select region</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}" data-location-id="{{ $region->location_id }}" {{ (string) old('region_id', $project->region_id) === (string) $region->id ? 'selected' : '' }}>
                                            {{ $region->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label required">{{ $isSaleListing ? 'Name' : 'Title' }}</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $project->name) }}" required>
                            </div>

                            @if ($isSaleListing)
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Price', 'price', true) !!}
                                    @if ($fieldDescription('price'))
                                        <div class="form-text mb-2">{{ $fieldDescription('price') }}</div>
                                    @endif
                                    <input type="number" min="0" class="form-control" name="price" value="{{ old('price', $project->price) }}" placeholder="{{ $fieldPlaceholder('price', 'Enter asking price') }}" required>
                                    @if ($fieldTip('price'))
                                        {!! $fieldAfterHelpHtml('price') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Year Trading', 'trading', true) !!}
                                    @if ($fieldDescription('trading'))
                                        <div class="form-text mb-2">{{ $fieldDescription('trading') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="trading" value="{{ old('trading', $project->trading) }}" placeholder="{{ $fieldPlaceholder('trading', 'e.g. 6 years') }}" required>
                                    @if ($fieldTip('trading'))
                                        {!! $fieldAfterHelpHtml('trading') !!}
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    {!! $fieldLabelWithTip('Earning Type', 'earning_type') !!}
                                    @if ($fieldDescription('earning_type'))
                                        <div class="form-text mb-2">{{ $fieldDescription('earning_type') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="earning_type" value="{{ old('earning_type', $project->earning_type) }}" placeholder="{{ $fieldPlaceholder('earning_type', 'e.g. EBITDA') }}">
                                    @if ($fieldTip('earning_type'))
                                        {!! $fieldAfterHelpHtml('earning_type') !!}
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    {!! $fieldLabelWithTip('Stock Level', 'stock_level') !!}
                                    @if ($fieldDescription('stock_level'))
                                        <div class="form-text mb-2">{{ $fieldDescription('stock_level') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="stock_level" value="{{ old('stock_level', $project->stock_level) }}" placeholder="{{ $fieldPlaceholder('stock_level', 'e.g. inventory value') }}">
                                    @if ($fieldTip('stock_level'))
                                        {!! $fieldAfterHelpHtml('stock_level') !!}
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    {!! $fieldLabelWithTip('Business Established', 'business_established') !!}
                                    @if ($fieldDescription('business_established'))
                                        <div class="form-text mb-2">{{ $fieldDescription('business_established') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="business_established" value="{{ old('business_established', $project->business_established) }}" placeholder="{{ $fieldPlaceholder('business_established', 'e.g. 2015') }}">
                                    @if ($fieldTip('business_established'))
                                        {!! $fieldAfterHelpHtml('business_established') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Summary', 'summary', true) !!}
                                    @if ($fieldDescription('summary'))
                                        <div class="form-text mb-2">{{ $fieldDescription('summary') }}</div>
                                    @endif
                                    <textarea class="form-control" name="summary" rows="4" placeholder="{{ $fieldPlaceholder('summary', 'Share a short business summary') }}" required>{{ old('summary', $project->summary) }}</textarea>
                                    @if ($fieldTip('summary'))
                                        {!! $fieldAfterHelpHtml('summary') !!}
                                    @endif
                                </div>
                                <div class="col-md-6"><label class="form-label">Location Information</label><textarea class="form-control" name="location_information" rows="4">{{ old('location_information', $project->location_information) }}</textarea></div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Skills', 'skills') !!}
                                    @if ($fieldDescription('skills'))
                                        <div class="form-text mb-2">{{ $fieldDescription('skills') }}</div>
                                    @endif
                                    <textarea class="form-control" name="skills" rows="4" placeholder="{{ $fieldPlaceholder('skills', 'Enter team or business strengths') }}">{{ old('skills', $project->skills) }}</textarea>
                                    @if ($fieldTip('skills'))
                                        {!! $fieldAfterHelpHtml('skills') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Potential', 'potential') !!}
                                    @if ($fieldDescription('potential'))
                                        <div class="form-text mb-2">{{ $fieldDescription('potential') }}</div>
                                    @endif
                                    <textarea class="form-control" name="potential" rows="4" placeholder="{{ $fieldPlaceholder('potential', 'Describe growth potential') }}">{{ old('potential', $project->potential) }}</textarea>
                                    @if ($fieldTip('potential'))
                                        {!! $fieldAfterHelpHtml('potential') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Hours', 'hours') !!}
                                    @if ($fieldDescription('hours'))
                                        <div class="form-text mb-2">{{ $fieldDescription('hours') }}</div>
                                    @endif
                                    <textarea class="form-control" name="hours" rows="4" placeholder="{{ $fieldPlaceholder('hours', 'Enter hours of operation') }}">{{ old('hours', $project->hours) }}</textarea>
                                    @if ($fieldTip('hours'))
                                        {!! $fieldAfterHelpHtml('hours') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Staff', 'staff') !!}
                                    @if ($fieldDescription('staff'))
                                        <div class="form-text mb-2">{{ $fieldDescription('staff') }}</div>
                                    @endif
                                    <textarea class="form-control" name="staff" rows="4" placeholder="{{ $fieldPlaceholder('staff', 'Enter staffing details') }}">{{ old('staff', $project->staff) }}</textarea>
                                    @if ($fieldTip('staff'))
                                        {!! $fieldAfterHelpHtml('staff') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Lease', 'lease') !!}
                                    @if ($fieldDescription('lease'))
                                        <div class="form-text mb-2">{{ $fieldDescription('lease') }}</div>
                                    @endif
                                    <textarea class="form-control" name="lease" rows="4" placeholder="{{ $fieldPlaceholder('lease', 'Enter lease information') }}">{{ old('lease', $project->lease) }}</textarea>
                                    @if ($fieldTip('lease'))
                                        {!! $fieldAfterHelpHtml('lease') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Training', 'training') !!}
                                    @if ($fieldDescription('training'))
                                        <div class="form-text mb-2">{{ $fieldDescription('training') }}</div>
                                    @endif
                                    <textarea class="form-control" name="training" rows="4" placeholder="{{ $fieldPlaceholder('training', 'Enter training or handover details') }}">{{ old('training', $project->training) }}</textarea>
                                    @if ($fieldTip('training'))
                                        {!! $fieldAfterHelpHtml('training') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Awards', 'awards') !!}
                                    @if ($fieldDescription('awards'))
                                        <div class="form-text mb-2">{{ $fieldDescription('awards') }}</div>
                                    @endif
                                    <textarea class="form-control" name="awards" rows="4" placeholder="{{ $fieldPlaceholder('awards', 'Enter awards or notable recognitions') }}">{{ old('awards', $project->awards) }}</textarea>
                                    @if ($fieldTip('awards'))
                                        {!! $fieldAfterHelpHtml('awards') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Reason For Sale', 'reason_for_sale') !!}
                                    @if ($fieldDescription('reason_for_sale'))
                                        <div class="form-text mb-2">{{ $fieldDescription('reason_for_sale') }}</div>
                                    @endif
                                    <textarea class="form-control" name="reason_for_sale" rows="4" placeholder="{{ $fieldPlaceholder('reason_for_sale', 'Why is the business being sold?') }}">{{ old('reason_for_sale', $project->reason_for_sale) }}</textarea>
                                    @if ($fieldTip('reason_for_sale'))
                                        {!! $fieldAfterHelpHtml('reason_for_sale') !!}
                                    @endif
                                </div>
                                <div class="col-12"><label class="form-label required">Description</label><textarea class="form-control" name="description" rows="5" required>{{ old('description', $project->description) }}</textarea></div>
                            @else
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Seeking Investment', 'seeking_investment', true) !!}
                                    @if ($fieldDescription('seeking_investment'))
                                        <div class="form-text mb-2">{{ $fieldDescription('seeking_investment') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="seeking_investment" value="{{ old('seeking_investment', $project->seeking_investment) }}" placeholder="{{ $fieldPlaceholder('seeking_investment', 'Enter investment amount') }}" required>
                                    @if ($fieldTip('seeking_investment'))
                                        {!! $fieldAfterHelpHtml('seeking_investment') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Reported Sales', 'reported_sales') !!}
                                    @if ($fieldDescription('reported_sales'))
                                        <div class="form-text mb-2">{{ $fieldDescription('reported_sales') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="reported_sales" value="{{ old('reported_sales', $project->reported_sales) }}" placeholder="{{ $fieldPlaceholder('reported_sales', 'Enter reported sales') }}">
                                    @if ($fieldTip('reported_sales'))
                                        {!! $fieldAfterHelpHtml('reported_sales') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Run Rate Sales', 'run_rate_sales') !!}
                                    @if ($fieldDescription('run_rate_sales'))
                                        <div class="form-text mb-2">{{ $fieldDescription('run_rate_sales') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="run_rate_sales" value="{{ old('run_rate_sales', $project->run_rate_sales) }}" placeholder="{{ $fieldPlaceholder('run_rate_sales', 'Enter run rate sales') }}">
                                    @if ($fieldTip('run_rate_sales'))
                                        {!! $fieldAfterHelpHtml('run_rate_sales') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('EBITDA Margin', 'ebitda_margin') !!}
                                    @if ($fieldDescription('ebitda_margin'))
                                        <div class="form-text mb-2">{{ $fieldDescription('ebitda_margin') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="ebitda_margin" value="{{ old('ebitda_margin', $project->ebitda_margin) }}" placeholder="{{ $fieldPlaceholder('ebitda_margin', 'Enter EBITDA margin') }}">
                                    @if ($fieldTip('ebitda_margin'))
                                        {!! $fieldAfterHelpHtml('ebitda_margin') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Industry', 'industry', true) !!}
                                    @if ($fieldDescription('industry'))
                                        <div class="form-text mb-2">{{ $fieldDescription('industry') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="industry" value="{{ old('industry', $project->industry) }}" placeholder="{{ $fieldPlaceholder('industry', 'Enter industry') }}" required>
                                    @if ($fieldTip('industry'))
                                        {!! $fieldAfterHelpHtml('industry') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Assets Or Collateral', 'assets_or_collateral') !!}
                                    @if ($fieldDescription('assets_or_collateral'))
                                        <div class="form-text mb-2">{{ $fieldDescription('assets_or_collateral') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="assets_or_collateral" value="{{ old('assets_or_collateral', $project->assets_or_collateral) }}" placeholder="{{ $fieldPlaceholder('assets_or_collateral', 'Enter assets or collateral') }}">
                                    @if ($fieldTip('assets_or_collateral'))
                                        {!! $fieldAfterHelpHtml('assets_or_collateral') !!}
                                    @endif
                                </div>
                                <div class="col-12">
                                    {!! $fieldLabelWithTip('Interested To Connect With Advisors', 'interested_to_connect_with_advisors') !!}
                                    @if ($fieldDescription('interested_to_connect_with_advisors'))
                                        <div class="form-text mb-2">{{ $fieldDescription('interested_to_connect_with_advisors') }}</div>
                                    @endif
                                    <input type="text" class="form-control" name="interested_to_connect_with_advisors" value="{{ old('interested_to_connect_with_advisors', $project->interested_to_connect_with_advisors) }}" placeholder="{{ $fieldPlaceholder('interested_to_connect_with_advisors', 'Yes or No') }}">
                                    @if ($fieldTip('interested_to_connect_with_advisors'))
                                        {!! $fieldAfterHelpHtml('interested_to_connect_with_advisors') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Business Overview', 'business_overview', true) !!}
                                    @if ($fieldDescription('business_overview'))
                                        <div class="form-text mb-2">{{ $fieldDescription('business_overview') }}</div>
                                    @endif
                                    <textarea class="form-control" name="business_overview" rows="4" placeholder="{{ $fieldPlaceholder('business_overview', 'Enter business overview') }}" required>{{ old('business_overview', $project->business_overview) }}</textarea>
                                    @if ($fieldTip('business_overview'))
                                        {!! $fieldAfterHelpHtml('business_overview') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Products & Services Overview', 'products_and_services_overview') !!}
                                    @if ($fieldDescription('products_and_services_overview'))
                                        <div class="form-text mb-2">{{ $fieldDescription('products_and_services_overview') }}</div>
                                    @endif
                                    <textarea class="form-control" name="products_and_services_overview" rows="4" placeholder="{{ $fieldPlaceholder('products_and_services_overview', 'Enter products and services overview') }}">{{ old('products_and_services_overview', $project->products_and_services_overview) }}</textarea>
                                    @if ($fieldTip('products_and_services_overview'))
                                        {!! $fieldAfterHelpHtml('products_and_services_overview') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Assets Overview', 'assets_overview') !!}
                                    @if ($fieldDescription('assets_overview'))
                                        <div class="form-text mb-2">{{ $fieldDescription('assets_overview') }}</div>
                                    @endif
                                    <textarea class="form-control" name="assets_overview" rows="4" placeholder="{{ $fieldPlaceholder('assets_overview', 'Enter assets overview') }}">{{ old('assets_overview', $project->assets_overview) }}</textarea>
                                    @if ($fieldTip('assets_overview'))
                                        {!! $fieldAfterHelpHtml('assets_overview') !!}
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {!! $fieldLabelWithTip('Facilities Overview', 'facilities_overview') !!}
                                    @if ($fieldDescription('facilities_overview'))
                                        <div class="form-text mb-2">{{ $fieldDescription('facilities_overview') }}</div>
                                    @endif
                                    <textarea class="form-control" name="facilities_overview" rows="4" placeholder="{{ $fieldPlaceholder('facilities_overview', 'Enter facilities overview') }}">{{ old('facilities_overview', $project->facilities_overview) }}</textarea>
                                    @if ($fieldTip('facilities_overview'))
                                        {!! $fieldAfterHelpHtml('facilities_overview') !!}
                                    @endif
                                </div>
                                <div class="col-12">
                                    {!! $fieldLabelWithTip('Capitalization Overview', 'capitalization_overview') !!}
                                    @if ($fieldDescription('capitalization_overview'))
                                        <div class="form-text mb-2">{{ $fieldDescription('capitalization_overview') }}</div>
                                    @endif
                                    <textarea class="form-control" name="capitalization_overview" rows="4" placeholder="{{ $fieldPlaceholder('capitalization_overview', 'Enter capitalization overview') }}">{{ old('capitalization_overview', $project->capitalization_overview) }}</textarea>
                                    @if ($fieldTip('capitalization_overview'))
                                        {!! $fieldAfterHelpHtml('capitalization_overview') !!}
                                    @endif
                                </div>
                            @endif

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

                            <div class="col-md-6">
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
                            <div class="col-md-6">
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
                                                    <div class="border rounded-3 p-2 h-100">
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

                            <div class="col-12 d-flex flex-wrap align-items-center justify-content-end gap-3 pt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="feather-save me-2"></i>
                                    <span>Save Changes</span>
                                </button>
                            </div>
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
                    col.innerHTML = `<img src="${event.target.result}" class="img-fluid rounded-3 border js-listing-image-preview" data-preview-src="${event.target.result}" data-preview-title="${file.name || 'Gallery image'}" alt="Gallery preview" style="height: 110px; object-fit: cover; width: 100%; cursor: zoom-in;">`;
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
