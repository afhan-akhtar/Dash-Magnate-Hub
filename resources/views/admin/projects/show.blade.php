@extends('admin.layout')
@section('title', 'Listing — ' . $project->name)
@section('page_title', 'Listing details')
@section('page_subtitle', $project->name)

@push('styles')
<style>
    .listing-detail-shell {
        padding-bottom: 2.5rem;
    }

    .alv {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    /* Summary — title & meta, no image */
    .alv-summary {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .alv-summary-inner {
        padding: 1.35rem 1.5rem 1.4rem;
        background:
            radial-gradient(ellipse 80% 120% at 100% 0%, rgba(52, 84, 209, 0.06), transparent 55%),
            linear-gradient(180deg, #fbfcff 0%, #fff 100%);
    }
    .alv-kicker {
        font-size: 0.6875rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.35rem;
    }
    .alv-title {
        font-size: 1.375rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.025em;
        line-height: 1.25;
        margin: 0 0 0.65rem;
        max-width: 48rem;
    }
    .alv-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        margin-bottom: 0.75rem;
    }
    .alv-badges .badge {
        font-size: 0.625rem;
        padding: 0.3rem 0.6rem;
        font-weight: 700;
        letter-spacing: 0.03em;
    }
    .alv-ref {
        font-size: 0.8125rem;
        color: #64748b;
        font-weight: 600;
        font-variant-numeric: tabular-nums;
        margin-bottom: 0.5rem;
    }
    .alv-lead {
        font-size: 0.9375rem;
        color: #475569;
        line-height: 1.65;
        margin: 0;
        max-width: 52rem;
    }
    .alv-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(15, 23, 42, 0.06);
    }
    .alv-meta-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.9rem;
        border-radius: 0.65rem;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        font-size: 0.8125rem;
        font-weight: 600;
        color: #334155;
    }
    .alv-meta-chip i {
        color: #3454d1;
        font-size: 0.9rem;
    }

    /* Featured image — gallery frame, contain (no harsh crop) */
    .alv-media {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .alv-media-hd {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        background: #f8fafc;
    }
    .alv-media-hd span {
        font-size: 0.6875rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }
    .alv-media-hd span i { color: #3454d1; }
    .alv-media-hd a {
        font-size: 0.75rem;
        font-weight: 600;
    }
    .alv-media-body {
        padding: 1.25rem 1.5rem 1.35rem;
    }
    .alv-media-frame {
        border-radius: 0.75rem;
        border: 1px solid rgba(15, 23, 42, 0.08);
        background:
            repeating-conic-gradient(#f1f5f9 0% 25%, #fff 0% 50%) 50% / 18px 18px;
        min-height: 220px;
        max-height: min(52vh, 420px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        overflow: hidden;
    }
    .alv-media-img {
        max-width: 100%;
        max-height: min(48vh, 400px);
        width: auto;
        height: auto;
        object-fit: contain;
        border-radius: 0.35rem;
        box-shadow: 0 8px 32px rgba(15, 23, 42, 0.12);
    }
    .alv-media-empty {
        text-align: center;
        padding: 2.5rem 1.5rem;
        color: #94a3b8;
    }
    .alv-media-empty i {
        font-size: 2.5rem;
        color: rgba(52, 84, 209, 0.25);
        display: block;
        margin-bottom: 0.5rem;
    }
    .alv-media-empty strong {
        display: block;
        font-size: 0.8125rem;
        color: #64748b;
        margin-bottom: 0.2rem;
    }
    .alv-media-empty small {
        font-size: 0.75rem;
    }

    /* Section cards */
    .alv-section {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 8px 28px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }
    .alv-section-hd {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid rgba(15, 23, 42, 0.06);
        font-size: 0.6875rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #3454d1;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(90deg, rgba(52, 84, 209, 0.06) 0%, #fff 45%);
    }
    .alv-section-hd i { font-size: 0.9rem; opacity: 0.9; }
    .alv-section-hd .ms-auto {
        font-size: 0.625rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        color: #94a3b8;
    }

    .alv-tiles {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
        padding: 1rem 1.2rem 1.2rem;
    }
    .alv-tile {
        padding: 0.9rem 1rem;
        border-radius: 0.65rem;
        border: 1px solid rgba(15, 23, 42, 0.06);
        background: linear-gradient(180deg, #fafbfd 0%, #fff 100%);
    }
    .alv-tile-label {
        font-size: 0.625rem;
        font-weight: 800;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 0.25rem;
    }
    .alv-tile-val {
        font-size: 0.9375rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.35;
        word-break: break-word;
    }
    .alv-tile-sub {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.2rem;
        font-weight: 500;
    }

    .alv-prose {
        padding: 1.15rem 1.35rem 1.35rem;
        font-size: 0.9375rem;
        color: #475569;
        line-height: 1.75;
        max-height: 380px;
        overflow-y: auto;
    }
    .alv-prose img { max-width: 100%; height: auto; border-radius: 0.35rem; }

    .alv-ov-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0;
    }
    @media (max-width: 767.98px) {
        .alv-ov-grid { grid-template-columns: 1fr; }
    }
    .alv-ov {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        border-right: 1px solid #f1f5f9;
    }
    .alv-ov:nth-child(2n) { border-right: none; }
    @media (max-width: 767.98px) {
        .alv-ov { border-right: none !important; }
    }
    .alv-ov h6 {
        font-size: 0.625rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #3454d1;
        margin: 0 0 0.4rem;
    }
    .alv-ov p {
        font-size: 0.8125rem;
        color: #64748b;
        margin: 0;
        line-height: 1.6;
    }

    .alv-files {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(118px, 1fr));
        gap: 0.75rem;
        padding: 1.1rem 1.25rem 1.25rem;
    }
    .alv-file {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 0.65rem;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        background: #fff;
        transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
    }
    .alv-file:hover {
        border-color: rgba(52, 84, 209, 0.3);
        box-shadow: 0 10px 26px rgba(52, 84, 209, 0.12);
        color: inherit;
        transform: translateY(-2px);
    }
    .alv-file-thumb {
        height: 76px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
    }
    .alv-file-thumb img {
        max-height: 68px;
        max-width: 88%;
        object-fit: contain;
    }
    .alv-file-label {
        padding: 0.4rem 0.45rem;
        font-size: 0.625rem;
        font-weight: 700;
        text-align: center;
        color: #334155;
        border-top: 1px solid #f1f5f9;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .alv-file-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }

    .alv-foot {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem 1.75rem;
        padding: 0.85rem 1.25rem;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 1rem;
        background: #f8fafc;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
    }
    .alv-foot i {
        margin-right: 0.35rem;
        color: #3454d1;
        opacity: 0.85;
    }
</style>
@endpush

@php
    $owner = $project->professional ?? $project->user;
    $ownerLabel = $owner?->full_name ?? '—';

    $coverThumb = $project->thumbnail;
    $coverUrl = null;
    if ($coverThumb?->path) {
        $rawPath = str_replace('\\', '/', $coverThumb->path);
        $base = rtrim(request()->getBaseUrl(), '/');
        $coverUrl = $base !== ''
            ? $base . '/storage/' . ltrim($rawPath, '/')
            : asset('storage/' . ltrim($rawPath, '/'));
    }
    if ($coverUrl === null && ! empty($coverThumb?->url)) {
        $coverUrl = $coverThumb->url;
    }

    $overviews = collect([
        'Business'        => $project->business_overview,
        'Products'        => $project->products_and_services_overview,
        'Assets'          => $project->assets_overview,
        'Facilities'      => $project->facilities_overview,
        'Capitalization'  => $project->capitalization_overview,
        'Reason for Sale' => $project->reason_for_sale,
        'Training'        => $project->training,
        'Awards'          => $project->awards,
    ])->filter(fn ($v) => ! empty($v));

    $allDetails = collect([
        ['Owner',       $ownerLabel, null],
        ['Category',    $project->category?->name ?? '—', null],
        ['Location',    $project->location?->name ?? '—', $project->region?->name],
        ['Price',       $project->price !== null ? '$' . number_format((float) $project->price) : '—', null],
        ['Reference',   $project->ref_id ?? '—', null],
        ['Views',       number_format((int) $project->views), null],
    ]);
    $extras = collect([
        ['Trading',      $project->trading],
        ['Earning Type', $project->earning_type],
        ['Stock Level',  $project->stock_level],
        ['Staff',        $project->staff],
        ['Hours',        $project->hours],
        ['Lease',        $project->lease],
        ['Established',  $project->business_established],
        ['Rating',       $project->rating],
    ])->filter(fn ($r) => ! empty($r[1]));
@endphp

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Listing details</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.projects.index') }}">Listings</a></li>
                    <li class="breadcrumb-item">{{ \Illuminate\Support\Str::limit($project->name, 42) }}</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-light-brand">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Back to listings</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content listing-detail-shell">
            <div class="row">
                <div class="col-12">
                    <div class="alv">

                        <div class="alv-summary">
                            <div class="alv-summary-inner">
                                <div class="alv-kicker">Marketplace listing</div>
                                <h2 class="alv-title">{{ $project->name }}</h2>
                                <div class="alv-badges">
                                    @if ($project->isDeleted())
                                        <span class="badge bg-soft-danger text-danger">Deleted</span>
                                        @if ($project->deleted_at)
                                            <span class="badge bg-soft-secondary text-secondary">{{ $project->deleted_at->format('M j, Y g:i A') }}</span>
                                        @endif
                                    @elseif ($project->active)
                                        <span class="badge bg-soft-success text-success">On website</span>
                                    @else
                                        <span class="badge bg-soft-secondary text-secondary">Hidden</span>
                                    @endif
                                    @if ($project->premium)
                                        <span class="badge bg-soft-warning text-warning">Premium</span>
                                    @endif
                                    @if ($project->sold)
                                        <span class="badge bg-soft-info text-info">Sold</span>
                                    @endif
                                    @if ($project->block)
                                        <span class="badge bg-soft-danger text-danger">Blocked</span>
                                    @endif
                                    @if ($project->franchise)
                                        <span class="badge bg-soft-primary text-primary">Franchise</span>
                                    @endif
                                </div>
                                @if ($project->ref_id)
                                    <div class="alv-ref">Reference <span class="text-dark">#{{ $project->ref_id }}</span></div>
                                @endif
                                @if ($project->summary || $project->description)
                                    <p class="alv-lead">{{ $project->summary ?: \Illuminate\Support\Str::limit(strip_tags($project->description), 220) }}</p>
                                @endif
                                <div class="alv-meta-row">
                                    <span class="alv-meta-chip"><i class="feather-tag"></i>{{ $project->category?->name ?? '—' }}</span>
                                    <span class="alv-meta-chip"><i class="feather-map-pin"></i>{{ $project->location?->name ?? '—' }}</span>
                                    @if ($project->price !== null)
                                        <span class="alv-meta-chip"><i class="feather-dollar-sign"></i>${{ number_format((float) $project->price) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="alv-media">
                            <div class="alv-media-hd">
                                <span><i class="feather-image"></i> Featured image</span>
                                @if ($coverUrl)
                                    <a href="{{ $coverUrl }}" target="_blank" rel="noopener" class="text-primary text-decoration-none">Open full size <i class="feather-external-link ms-1" style="font-size:0.7rem;"></i></a>
                                @endif
                            </div>
                            <div class="alv-media-body">
                                @if ($coverUrl)
                                    <div class="alv-media-frame">
                                        <img src="{{ $coverUrl }}" alt="" class="alv-media-img" loading="lazy"
                                             onerror="this.closest('.alv-media-body').querySelector('[data-alv-media-fallback]').classList.remove('d-none'); this.closest('.alv-media-frame').classList.add('d-none');">
                                    </div>
                                    <div class="alv-media-empty d-none text-center pt-2" data-alv-media-fallback>
                                        <i class="feather-alert-circle"></i>
                                        <strong>Image could not be loaded</strong>
                                        <small class="d-block">Check the file on disk or storage URL.</small>
                                    </div>
                                @else
                                    <div class="alv-media-empty">
                                        <i class="feather-image"></i>
                                        <strong>No featured image</strong>
                                        <small>This listing does not have a thumbnail attached.</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="alv-section">
                            <div class="alv-section-hd">
                                <i class="feather-grid"></i> Key details
                            </div>
                            <div class="alv-tiles">
                                @foreach ($allDetails as [$label, $value, $sub])
                                    <div class="alv-tile">
                                        <div class="alv-tile-label">{{ $label }}</div>
                                        <div class="alv-tile-val">{{ $value }}</div>
                                        @if ($sub)
                                            <div class="alv-tile-sub">{{ $sub }}</div>
                                        @endif
                                    </div>
                                @endforeach
                                @foreach ($extras as [$label, $value])
                                    <div class="alv-tile">
                                        <div class="alv-tile-label">{{ $label }}</div>
                                        <div class="alv-tile-val">{{ $value }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if (! empty($project->description) && strlen(strip_tags($project->description)) > 180)
                            <div class="alv-section">
                                <div class="alv-section-hd">
                                    <i class="feather-file-text"></i> Full description
                                </div>
                                <div class="alv-prose">{!! $project->description !!}</div>
                            </div>
                        @endif

                        @if ($overviews->isNotEmpty())
                            <div class="alv-section">
                                <div class="alv-section-hd">
                                    <i class="feather-layers"></i> Additional information
                                </div>
                                <div class="alv-ov-grid">
                                    @foreach ($overviews as $lbl => $txt)
                                        <div class="alv-ov">
                                            <h6>{{ $lbl }}</h6>
                                            <p>{!! nl2br(e(\Illuminate\Support\Str::limit(strip_tags($txt), 400))) !!}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="alv-section">
                            <div class="alv-section-hd">
                                <i class="feather-paperclip"></i> Attachments
                                <span class="ms-auto">{{ $project->documents->count() }} file(s)</span>
                            </div>
                            @if ($project->documents->count())
                                <div class="alv-files">
                                    @foreach ($project->documents as $document)
                                        @php
                                            $dPath = $document->path ? str_replace('\\', '/', $document->path) : '';
                                            $base = rtrim(request()->getBaseUrl(), '/');
                                            $fileUrl = $dPath
                                                ? ($base !== '' ? $base . '/storage/' . ltrim($dPath, '/') : asset('storage/' . ltrim($dPath, '/')))
                                                : ($document->url ?? '#');
                                            $isImage = ($document->mime_type && str_starts_with($document->mime_type, 'image'));
                                            if (! $isImage && $dPath) {
                                                $ext = strtolower(pathinfo($dPath, PATHINFO_EXTENSION));
                                                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp','svg','bmp'], true);
                                            }
                                            $cLabel = ucwords(str_replace('_', ' ', $document->collection));
                                        @endphp
                                        <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="alv-file" title="{{ $document->original_name ?: $cLabel }}">
                                            <div class="alv-file-thumb">
                                                @if ($isImage && $fileUrl !== '#')
                                                    <img src="{{ $fileUrl }}" alt="{{ $cLabel }}" loading="lazy" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                                    <span class="alv-file-fallback d-none"><i class="feather-image text-muted fs-4"></i></span>
                                                @else
                                                    <i class="feather-file text-muted fs-3"></i>
                                                @endif
                                            </div>
                                            <div class="alv-file-label">{{ $cLabel }}</div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-4 px-3">
                                    <span class="small">No files uploaded for this listing.</span>
                                </div>
                            @endif
                        </div>

                        <div class="alv-foot">
                            <span><i class="feather-calendar"></i>Created {{ $project->created_at?->format('M d, Y — h:i A') ?? '—' }}</span>
                            <span><i class="feather-refresh-cw"></i>Updated {{ $project->updated_at?->format('M d, Y — h:i A') ?? '—' }}</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
