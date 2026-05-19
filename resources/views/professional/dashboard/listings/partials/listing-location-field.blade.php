@php
    $helpFieldKey = $helpFieldKey ?? 'location_id';
    $fieldHelp = config('listing_field_help.' . $helpFieldKey, config('listing_field_help.location_id', []));
    $description = trim((string) ($fieldHelp['description'] ?? ''));
    $example = trim((string) ($fieldHelp['example'] ?? ''));
    $tip = trim((string) ($fieldHelp['tip'] ?? ''));
    $selectedLocationId = $selectedLocationId ?? old('location_id', '');
    $selectedRegionId = $selectedRegionId ?? old('region_id', '');
    $locations = $locations ?? collect();
    $regions = $regions ?? collect();
    $showRegion = $showRegion ?? true;
@endphp

<div class="{{ $locationColClass ?? 'col-md-4' }}">
    <article class="listing-field-card h-100">
        <header class="listing-field-card__header">
            <h3 class="listing-field-card__title">
                Location
                <span class="listing-required-mark" aria-hidden="true">*</span>
            </h3>
        </header>

        @if ($description !== '' || $example !== '' || $tip !== '')
            <div class="listing-field-card__guide">
                @if ($description !== '')
                    <p class="listing-field-guide__desc">{{ $description }}</p>
                @endif
                @if ($example !== '')
                    <div class="listing-callout listing-callout--example" role="note">
                        <span class="listing-callout__icon" aria-hidden="true"><i class="feather-edit-3"></i></span>
                        <p class="listing-callout__text">
                            <em><strong>Example:</strong> {{ $example }}</em>
                        </p>
                    </div>
                @endif
                @if ($tip !== '')
                    <div class="listing-callout listing-callout--tip" role="note">
                        <span class="listing-callout__icon" aria-hidden="true"><i class="feather-zap"></i></span>
                        <p class="listing-callout__text">
                            <strong class="listing-field-guide__tip-label">Tip:</strong> {{ $tip }}
                        </p>
                    </div>
                @endif
            </div>
        @endif

        <div class="listing-field-card__input-area">
            <select class="form-control listing-field-card__input" name="location_id" id="location_id" required>
                <option value="">Select location</option>
                @foreach ($locations as $location)
                    <option value="{{ $location->id }}" {{ (string) $selectedLocationId === (string) $location->id ? 'selected' : '' }}>
                        {{ $location->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </article>
</div>

@if ($showRegion)
    @include('professional.dashboard.listings.partials.listing-region-field', [
        'selectedRegionId' => $selectedRegionId,
        'regions' => $regions,
        'colClass' => $regionColClass ?? 'col-md-4',
    ])
@endif
