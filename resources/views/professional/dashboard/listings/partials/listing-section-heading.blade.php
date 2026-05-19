@php
    $icon = $icon ?? 'feather-layers';
    $title = $title ?? 'Section';
    $subtitle = $subtitle ?? '';
    $number = $number ?? null;
    $colClass = $colClass ?? 'col-12';
@endphp

<div class="{{ $colClass }}">
    <div class="listing-section-header">
        @if ($number !== null)
            <span class="listing-section-header__number">{{ str_pad((string) $number, 2, '0', STR_PAD_LEFT) }}</span>
        @endif
        <span class="listing-section-header__icon" aria-hidden="true"><i class="{{ $icon }}"></i></span>
        <div class="listing-section-header__copy">
            <h2 class="listing-section-header__title">{{ $title }}</h2>
            @if ($subtitle !== '')
                <p class="listing-section-header__subtitle">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</div>
