@php
    $categories = $categories ?? collect();
    $selectedCategoryId = $selectedCategoryId ?? old('category_id', '');
    $required = (bool) ($required ?? true);
@endphp

<div class="col-12">
    <div class="listing-essentials-panel">
        <div class="listing-essentials-panel__label">
            <i class="feather-grid"></i>
            <span>Getting started</span>
        </div>
        <div class="row g-3 align-items-start listing-essentials-row">
            <div class="col-md-4">
                <div class="listing-meta-field">
                    <label class="form-label" for="category_id">
                        Category
                        @if ($required)
                            <span class="listing-required-mark" aria-hidden="true">*</span>
                        @endif
                    </label>
                    <select class="form-control listing-field-card__input" name="category_id" id="category_id" {{ $required ? 'required' : '' }}>
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ (string) $selectedCategoryId === (string) $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @include('professional.dashboard.listings.partials.listing-region-field', [
                'selectedRegionId' => $selectedRegionId ?? old('region_id', ''),
                'regions' => $regions ?? collect(),
                'colClass' => 'col-md-4',
            ])

            @include('professional.dashboard.listings.partials.listing-location-field', [
                'selectedLocationId' => $selectedLocationId ?? old('location_id', ''),
                'selectedRegionId' => $selectedRegionId ?? old('region_id', ''),
                'locations' => $locations ?? collect(),
                'regions' => $regions ?? collect(),
                'showRegion' => false,
                'locationColClass' => 'col-md-4',
                'helpFieldKey' => $locationHelpFieldKey ?? 'location_id',
            ])
        </div>
    </div>
</div>
