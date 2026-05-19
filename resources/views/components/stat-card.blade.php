@props([
    'icon' => 'feather-bar-chart',
    'label' => 'Stat',
    'value' => 0,
    'color' => 'primary',
])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3 d-flex align-items-center" style="min-height:72px;">
        <div class="d-flex align-items-center gap-3 w-100">
            <div class="rounded-circle bg-soft-{{ $color }} d-flex align-items-center justify-content-center flex-shrink-0" style="width:44px;height:44px;min-width:44px;min-height:44px;">
                <i class="{{ $icon }} text-{{ $color }}" style="font-size:18px;line-height:1;"></i>
            </div>
            <div class="d-flex flex-column justify-content-center" style="line-height:1.3;">
                <div class="text-muted text-uppercase fw-semibold" style="font-size:11px;">{{ $label }}</div>
                <div class="fw-bold text-dark" style="font-size:18px;">{{ $value }}</div>
            </div>
        </div>
    </div>
</div>
