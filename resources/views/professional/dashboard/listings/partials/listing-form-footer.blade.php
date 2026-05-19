@php
    $submitLabel = $submitLabel ?? 'Save Changes';
    $submitIcon = $submitIcon ?? 'feather-save';
    $hint = $hint ?? 'Complete all required fields before saving your listing.';
    $cancelUrl = $cancelUrl ?? route('professional.listings.index');
    $cancelLabel = $cancelLabel ?? 'Back to listings';
    $submitDisabled = (bool) ($submitDisabled ?? false);
@endphp

<div class="col-12">
    <div class="listing-form-sticky-footer d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <p class="text-muted small mb-0 d-flex align-items-center gap-1">
                <i class="feather-info"></i>
                <span>{{ $hint }}</span>
            </p>
            <a href="{{ $cancelUrl }}" class="listing-form-footer-link">
                <i class="feather-arrow-left me-1"></i>{{ $cancelLabel }}
            </a>
        </div>
        <button type="submit" class="btn btn-primary btn-listing-save" @if ($submitDisabled) disabled @endif>
            <i class="{{ $submitIcon }} me-2"></i>
            <span>{{ $submitLabel }}</span>
        </button>
    </div>
</div>
