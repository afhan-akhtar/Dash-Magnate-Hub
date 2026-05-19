@php
    $project = $project ?? null;
    $val = fn (string $key, $default = '') => old($key, $project?->{$key} ?? $default);
@endphp

<div class="col-12">
    <div class="listing-seller-hero listing-capital-hero">
        <div class="listing-seller-hero__inner">
            <span class="listing-seller-hero__icon" aria-hidden="true"><i class="feather-trending-up"></i></span>
            <div>
                <h2 class="listing-seller-hero__title">Build your capital raise listing</h2>
                <p class="listing-seller-hero__text">Each field includes expert guidance, a real-world example, and a practical tip — structured to help sophisticated investors assess your opportunity quickly.</p>
                <div class="listing-seller-hero__chips">
                    <span class="listing-seller-hero__chip"><i class="feather-dollar-sign"></i> Seeking Investment *</span>
                    <span class="listing-seller-hero__chip"><i class="feather-briefcase"></i> Industry *</span>
                    <span class="listing-seller-hero__chip"><i class="feather-file-text"></i> Business Overview *</span>
                    <span class="listing-seller-hero__chip"><i class="feather-map-pin"></i> Location *</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12 listing-section-wrap">
    <div class="listing-section-panel">
        @include('professional.dashboard.listings.partials.listing-section-heading', [
            'number' => 1,
            'title' => 'Funding & Financials',
            'subtitle' => 'Raise size, revenue history, run-rate, and profitability metrics investors review first.',
            'icon' => 'feather-dollar-sign',
        ])
        <div class="row g-4 pb-3">
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'seeking_investment',
                'label' => 'Seeking Investment Of',
                'required' => true,
                'colClass' => 'col-lg-6',
                'value' => $val('seeking_investment'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'reported_sales',
                'label' => 'Reported Sales',
                'colClass' => 'col-lg-6',
                'value' => $val('reported_sales'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'run_rate_sales',
                'label' => 'Run Rate Sales',
                'colClass' => 'col-lg-6',
                'value' => $val('run_rate_sales'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'ebitda_margin',
                'label' => 'EBITDA Margin',
                'colClass' => 'col-lg-6',
                'value' => $val('ebitda_margin'),
            ])
        </div>
    </div>
</div>

<div class="col-12 listing-section-wrap">
    <div class="listing-section-panel">
        @include('professional.dashboard.listings.partials.listing-section-heading', [
            'number' => 2,
            'title' => 'Sector & Security',
            'subtitle' => 'Industry classification, collateral, and advisor introductions.',
            'icon' => 'feather-layers',
        ])
        <div class="row g-4 pb-3">
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'industry',
                'label' => 'Industry',
                'required' => true,
                'colClass' => 'col-lg-6',
                'value' => $val('industry'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'assets_or_collateral',
                'label' => 'Assets or Collateral',
                'colClass' => 'col-lg-6',
                'value' => $val('assets_or_collateral'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'interested_to_connect_with_advisors',
                'label' => 'Interested to Connect with Advisors',
                'colClass' => 'col-12',
                'value' => $val('interested_to_connect_with_advisors'),
            ])
        </div>
    </div>
</div>

<div class="col-12 listing-section-wrap">
    <div class="listing-section-panel">
        @include('professional.dashboard.listings.partials.listing-section-heading', [
            'number' => 3,
            'title' => 'Business Narrative',
            'subtitle' => 'Your pitch story and what you sell — written for investors seeing your raise for the first time.',
            'icon' => 'feather-file-text',
        ])
        <div class="row g-4 pb-3">
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'business_overview',
                'label' => 'Business Overview',
                'required' => true,
                'inputType' => 'textarea',
                'rows' => 6,
                'colClass' => 'col-lg-6',
                'value' => $val('business_overview'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'products_and_services_overview',
                'label' => 'Products & Services Overview',
                'inputType' => 'textarea',
                'rows' => 6,
                'colClass' => 'col-lg-6',
                'value' => $val('products_and_services_overview'),
            ])
        </div>
    </div>
</div>

<div class="col-12 listing-section-wrap">
    <div class="listing-section-panel">
        @include('professional.dashboard.listings.partials.listing-section-heading', [
            'number' => 4,
            'title' => 'Assets, Facilities & Cap Table',
            'subtitle' => 'Detailed asset breakdown, infrastructure, and ownership structure for due diligence.',
            'icon' => 'feather-pie-chart',
        ])
        <div class="row g-4 pb-3">
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'assets_overview',
                'label' => 'Assets Overview',
                'inputType' => 'textarea',
                'rows' => 5,
                'colClass' => 'col-lg-6',
                'value' => $val('assets_overview'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'facilities_overview',
                'label' => 'Facilities Overview',
                'inputType' => 'textarea',
                'rows' => 5,
                'colClass' => 'col-lg-6',
                'value' => $val('facilities_overview'),
            ])
            @include('professional.dashboard.listings.partials.listing-field', [
                'field' => 'capitalization_overview',
                'label' => 'Capitalisation Overview',
                'inputType' => 'textarea',
                'rows' => 5,
                'colClass' => 'col-12',
                'value' => $val('capitalization_overview'),
            ])
        </div>
    </div>
</div>
