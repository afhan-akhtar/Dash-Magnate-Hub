@php
    $project = $project ?? null;
    $listingType = (int) ($listingType ?? 0);
    $isBrokerListing = $listingType === 4;
    $val = fn (string $key, $default = '') => old($key, $project?->{$key} ?? $default);
@endphp

<div class="col-12">
    <div class="listing-seller-hero {{ $isBrokerListing ? 'listing-broker-hero' : '' }}">
        <div class="listing-seller-hero__inner">
            <span class="listing-seller-hero__icon" aria-hidden="true"><i class="feather-{{ $isBrokerListing ? 'layers' : 'briefcase' }}"></i></span>
            <div>
                <h2 class="listing-seller-hero__title">{{ $isBrokerListing ? 'Build your broker / franchise listing profile' : 'Build your seller listing profile' }}</h2>
                <p class="listing-seller-hero__text">{{ $isBrokerListing ? 'Each field includes expert guidance, a real-world example, and a practical tip — structured for brokers and franchise opportunities on Magnate Hub.' : "Each field includes expert guidance, a real-world example, and a practical tip — the same structure used across Magnate Hub's professional listings." }}</p>
                <div class="listing-seller-hero__chips">
                    <span class="listing-seller-hero__chip"><i class="feather-check-circle"></i> Category *</span>
                    <span class="listing-seller-hero__chip"><i class="feather-map-pin"></i> Location *</span>
                    <span class="listing-seller-hero__chip"><i class="feather-dollar-sign"></i> Price *</span>
                    <span class="listing-seller-hero__chip"><i class="feather-clock"></i> Years Trading *</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12 listing-section-wrap">
    <div class="listing-section-panel">
        @include('professional.dashboard.listings.partials.listing-section-heading', [
            'number' => 1,
            'title' => 'Financial Details',
            'subtitle' => 'The numbers buyers evaluate first — price, trading history, and earnings.',
            'icon' => 'feather-dollar-sign',
        ])
        <div class="row g-4 pb-3">
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'price',
                'label' => 'Price',
                'required' => true,
                'inputType' => 'number',
                'inputAttributes' => ['min' => '0'],
                'colClass' => 'col-md-6',
                'value' => $val('price'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'trading',
                'label' => 'Years Trading',
                'required' => true,
                'colClass' => 'col-md-6',
                'value' => $val('trading'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'earning_type',
                'label' => 'Earning Type',
                'colClass' => 'col-lg-4',
                'value' => $val('earning_type'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'stock_level',
                'label' => 'Stock Level',
                'colClass' => 'col-lg-4',
                'value' => $val('stock_level'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'business_established',
                'label' => 'Business Established',
                'colClass' => 'col-lg-4',
                'value' => $val('business_established'),
            ])
        </div>
    </div>
</div>

<div class="col-12 listing-section-wrap">
    <div class="listing-section-panel">
        @include('professional.dashboard.listings.partials.listing-section-heading', [
            'number' => 2,
            'title' => 'Business Overview',
            'subtitle' => 'Lead with what the business does and where it operates.',
            'icon' => 'feather-file-text',
        ])
        <div class="row g-4 pb-3">
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'summary',
                'label' => 'Summary',
                'inputType' => 'textarea',
                'rows' => 5,
                'colClass' => 'col-lg-6',
                'value' => $val('summary'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'location_information',
                'label' => 'Location Details',
                'inputType' => 'textarea',
                'rows' => 5,
                'colClass' => 'col-lg-6',
                'value' => $val('location_information'),
            ])
        </div>
    </div>
</div>

<div class="col-12 listing-section-wrap">
    <div class="listing-section-panel">
        @include('professional.dashboard.listings.partials.listing-section-heading', [
            'number' => 3,
            'title' => 'Operations & Team',
            'subtitle' => 'Staff, hours, lease, skills, and growth potential.',
            'icon' => 'feather-users',
        ])
        <div class="row g-4 pb-3">
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'skills',
                'label' => 'Skills',
                'inputType' => 'textarea',
                'rows' => 4,
                'colClass' => 'col-lg-6',
                'value' => $val('skills'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'potential',
                'label' => 'Potential',
                'inputType' => 'textarea',
                'rows' => 4,
                'colClass' => 'col-lg-6',
                'value' => $val('potential'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'hours',
                'label' => 'Hours of Operation',
                'inputType' => 'textarea',
                'rows' => 4,
                'colClass' => 'col-lg-6',
                'value' => $val('hours'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'staff',
                'label' => 'Staff',
                'inputType' => 'textarea',
                'rows' => 4,
                'colClass' => 'col-lg-6',
                'value' => $val('staff'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'lease',
                'label' => 'Lease',
                'inputType' => 'textarea',
                'rows' => 4,
                'colClass' => 'col-lg-6',
                'value' => $val('lease'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'training',
                'label' => 'Training',
                'inputType' => 'textarea',
                'rows' => 4,
                'colClass' => 'col-lg-6',
                'value' => $val('training'),
            ])
        </div>
    </div>
</div>

<div class="col-12 listing-section-wrap">
    <div class="listing-section-panel">
        @include('professional.dashboard.listings.partials.listing-section-heading', [
            'number' => 4,
            'title' => 'Credibility & Sale Details',
            'subtitle' => 'Awards, handover, and your reason for selling.',
            'icon' => 'feather-award',
        ])
        <div class="row g-4 pb-3">
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'awards',
                'label' => 'Awards',
                'inputType' => 'textarea',
                'rows' => 4,
                'colClass' => 'col-lg-6',
                'value' => $val('awards'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'reason_for_sale',
                'label' => 'Reason for Sale',
                'inputType' => 'textarea',
                'rows' => 4,
                'colClass' => 'col-lg-6',
                'value' => $val('reason_for_sale'),
            ])
        </div>
    </div>
</div>

<div class="col-12 listing-section-wrap">
    <div class="listing-description-card">
        <header class="listing-field-card__header">
            <h3 class="listing-field-card__title">Description</h3>
        </header>
        <p class="listing-field-guide__desc px-1 mb-3">Full listing description shown to buyers. Include operational detail, assets, and anything not covered above.</p>
        <div class="listing-field-card__input-area" style="border-top: none; padding-top: 0;">
            <textarea class="form-control listing-field-card__input" id="description" name="description" rows="6" placeholder="Expand on your summary with full operational detail…">{{ $val('description') }}</textarea>
        </div>
    </div>
</div>
