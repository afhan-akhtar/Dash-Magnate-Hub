@props([
    'icon' => 'feather-inbox',
    'title' => 'No Data Found',
    'message' => 'Items will appear here.',
    'padding' => 'py-5',
])

<div class="d-flex flex-column align-items-center justify-content-center {{ $padding }}">
    <div class="rounded-circle bg-soft-primary d-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
        <i class="{{ $icon }}" style="font-size:32px;color:#560ce3;"></i>
    </div>
    <h5 class="fw-bold text-dark mb-1">{{ $title }}</h5>
    <p class="text-muted mb-0 small text-center">{{ $message }}</p>
</div>
