@php
    $selectedRegionId = $selectedRegionId ?? old('region_id', '');
    $regions = $regions ?? collect();
    $colClass = $colClass ?? 'col-md-4';
@endphp

<div class="{{ $colClass }}">
    <div class="listing-meta-field listing-meta-field--region">
        <label class="form-label" for="region_id">Region</label>
        <select class="form-control listing-field-card__input" name="region_id" id="region_id">
            <option value="">Select region</option>
            @foreach ($regions as $region)
                <option value="{{ $region->id }}" data-location-id="{{ $region->location_id }}" {{ (string) $selectedRegionId === (string) $region->id ? 'selected' : '' }}>
                    {{ $region->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
