@extends('professional.dashboard.layouts.app')

@unless(isset($buyerChatCount))
@php
    $premiumPercent = $Total > 0 ? round(($Premium / max($Total, 1)) * 100) : 0;
    $activePercent = $Total > 0 ? round(($Active / max($Total, 1)) * 100) : 0;
    $normalPercent = $Total > 0 ? round(($Normal / max($Total, 1)) * 100) : 0;
    $blockedPercent = $Total > 0 ? round(($Block / max($Total, 1)) * 100) : 0;
    $sortedTop = $Top_Projects->sortBy(function ($p) {
        return optional($p->created_at)->timestamp ?? 0;
    })->values();

    $topProjectReach = $sortedTop->map(function ($p) {
        if (((int) ($p->premium ?? 0)) === 1) {
            return 0;
        }

        return (int) ($p->chats_count ?? 0);
    })->values();
    $topProjectPremiumReach = $sortedTop->map(function ($p) {
        return ((int) ($p->premium ?? 0)) === 1 ? (int) ($p->chats_count ?? 0) : 0;
    })->values();
    $topProjectDates = $sortedTop->map(function ($p) {
        return optional($p->created_at)->format('j/n/y') ?? '-';
    })->values();
    $topProjectShortNames = $sortedTop->pluck('name')->map(function ($name) {
        return \Illuminate\Support\Str::limit($name, 16);
    })->values();
    $topProjectFullNames = $sortedTop->pluck('name')->values();
@endphp
@endunless

@section('title', 'MagnateHub || Dashboard')
@section('page_title', 'Dashboard')

@section('styles')
<style>
    .stat-mini-card {
        border-radius: 18px;
        border: 1px solid #eef0f4;
        box-shadow: 0 6px 22px rgba(16, 24, 40, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-mini-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 32px rgba(16, 24, 40, 0.08);
    }
    .stat-mini-card .card-body {
        padding: 24px 24px 20px;
        overflow: hidden;
    }
    .stat-mini-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .stat-mini-left {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }
    .stat-mini-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }
    .stat-mini-icon-primary { background: rgba(91, 44, 215, 0.12); color: #5b2cd7; }
    .stat-mini-icon-danger  { background: rgba(239, 68, 68, 0.12); color: #ef4444; }
    .stat-mini-icon-success { background: rgba(34, 197, 94, 0.14); color: #16a34a; }
    .stat-mini-icon-warning { background: rgba(245, 158, 11, 0.15); color: #d97706; }
    .stat-mini-label {
        font-size: 13px;
        font-weight: 500;
        color: #6b7280;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .stat-mini-value {
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.02em;
    }
    .stat-mini-divider {
        height: 1px;
        background: linear-gradient(90deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.08) 50%, rgba(15, 23, 42, 0) 100%);
        margin: 18px 0 10px;
    }
    .stat-mini-bottom {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 12px;
    }
    .stat-mini-chart {
        flex: 1 1 auto;
        min-width: 0;
        max-width: 62%;
        height: 58px;
        position: relative;
        overflow: hidden;
    }
    .stat-mini-chart .apexcharts-canvas,
    .stat-mini-chart svg {
        width: 100% !important;
    }
    .stat-mini-chart .apexcharts-tooltip {
        font-size: 11px !important;
        border-radius: 8px !important;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.12) !important;
        padding: 6px 10px !important;
        border: 1px solid rgba(15, 23, 42, 0.08) !important;
        background: #ffffff !important;
    }

    .listings-reach-card {
        border-radius: 18px;
        border: 1px solid #eef0f4;
        box-shadow: 0 6px 22px rgba(16, 24, 40, 0.04);
    }
    .listings-reach-legend {
        display: inline-flex;
        align-items: center;
        gap: 16px;
        margin-right: 8px;
    }
    .lr-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 500;
        color: #475569;
    }
    .lr-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }
    .lr-dot-primary { background: #5b2cd7; box-shadow: 0 0 0 3px rgba(91, 44, 215, 0.15); }
    .lr-dot-cyan    { background: #22d3ee; box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.18); }
    .stat-mini-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        text-align: right;
        line-height: 1.2;
        flex-shrink: 0;
    }
    .stat-mini-meta-highlight {
        font-size: 13px;
        font-weight: 600;
    }
    .stat-mini-meta-sub {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 2px;
    }
</style>
@endsection

@section('page_summary')
    @isset($buyerChatCount)
        See how many active conversations you have and jump back into your inbox when you are ready.
    @else
        Track listing performance, premium visibility, and listing reach based on chat activity.
    @endisset
@endsection

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Dashboard</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="https://magnatehub.au/">Home</a></li>
                    <li class="breadcrumb-item">Dashboard</li>
                </ul>
            </div>
            @unless(isset($buyerChatCount))
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex d-md-none">
                        <a href="javascript:void(0)" class="page-header-right-close-toggle">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Back</span>
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <!-- <div id="reportrange" class="reportrange-picker d-flex align-items-center">
                            <i class="feather-calendar me-2"></i>
                            <span class="reportrange-picker-field">{{ $selectedRangeLabel ?? '' }}</span>
                        </div> -->
                        <!-- <div class="dropdown filter-dropdown">
                            <a class="btn btn-md btn-light-brand" data-bs-toggle="dropdown" data-bs-offset="0, 10" data-bs-auto-close="outside">
                                <i class="feather-filter me-2"></i>
                                <span>Filter</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <div class="dropdown-item">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="Role" checked="checked" />
                                        <label class="custom-control-label c-pointer" for="Role">Role</label>
                                    </div>
                                </div>
                                <div class="dropdown-item">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="Team" checked="checked" />
                                        <label class="custom-control-label c-pointer" for="Team">Team</label>
                                    </div>
                                </div>
                                <div class="dropdown-item">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="Email" checked="checked" />
                                        <label class="custom-control-label c-pointer" for="Email">Email</label>
                                    </div>
                                </div>
                                <div class="dropdown-item">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="Member" checked="checked" />
                                        <label class="custom-control-label c-pointer" for="Member">Member</label>
                                    </div>
                                </div>
                                <div class="dropdown-item">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="Recommendation" checked="checked" />
                                        <label class="custom-control-label c-pointer" for="Recommendation">Recommendation</label>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <i class="feather-plus me-3"></i>
                                    <span>Create New</span>
                                </a>
                                <a href="javascript:void(0);" class="dropdown-item">
                                    <i class="feather-filter me-3"></i>
                                    <span>Manage Filter</span>
                                </a>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="d-md-none d-flex align-items-center">
                    <a href="javascript:void(0)" class="page-header-right-open-toggle">
                        <i class="feather-align-right fs-20"></i>
                    </a>
                </div>
            </div>
            @endunless
        </div>

        <div class="main-content">
            @isset($buyerChatCount)
                <div class="row">
                    <div class="col-xxl-4 col-md-6">
                        <a href="{{ route('professional.chat') }}" class="text-decoration-none text-reset d-block h-100">
                            <div class="card stretch stretch-full h-100 border border-gray-200 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="avatar-text avatar-lg bg-soft-primary text-primary border-soft-primary rounded">
                                            <i class="feather-message-square"></i>
                                        </div>
                                        <div class="text-end">
                                            <p class="fs-11 fw-medium text-uppercase text-muted mb-1">Conversations</p>
                                            <h3 class="tx-20 tx-semibold tx-left mb-0">{{ (int) $buyerChatCount }}</h3>
                                            <span class="fs-12 text-primary">Open Chats <i class="feather-arrow-right fs-12"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @else
            <div class="row">
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full stat-mini-card">
                        <div class="card-body">
                            <div class="stat-mini-top">
                                <div class="stat-mini-left">
                                    <span class="stat-mini-icon stat-mini-icon-primary">
                                        <i class="feather-airplay"></i>
                                    </span>
                                    <span class="stat-mini-label">Total Listings</span>
                                </div>
                                <h3 class="stat-mini-value">{{ str_pad($Total, 2, '0', STR_PAD_LEFT) }}</h3>
                            </div>
                            <div class="stat-mini-divider"></div>
                            <div class="stat-mini-bottom">
                                <div class="stat-mini-chart" id="income-bar-chart"></div>
                                <div class="stat-mini-meta">
                                    <span class="stat-mini-meta-highlight text-primary">{{ $Premium }}+ more</span>
                                    <span class="stat-mini-meta-sub">{{ $Deleted }} deleted · {{ $premiumPercent }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full stat-mini-card">
                        <div class="card-body">
                            <div class="stat-mini-top">
                                <div class="stat-mini-left">
                                    <span class="stat-mini-icon stat-mini-icon-danger">
                                        <i class="feather-shopping-cart"></i>
                                    </span>
                                    <span class="stat-mini-label">Inactive Listings</span>
                                </div>
                                <h3 class="stat-mini-value">{{ str_pad($De_Active, 2, '0', STR_PAD_LEFT) }}</h3>
                            </div>
                            <div class="stat-mini-divider"></div>
                            <div class="stat-mini-bottom">
                                <div class="stat-mini-chart" id="expense-bar-chart"></div>
                                <div class="stat-mini-meta">
                                    <span class="stat-mini-meta-highlight text-danger">{{ $Block }}+ blocked</span>
                                    <span class="stat-mini-meta-sub">{{ $blockedPercent }}% risk window</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full stat-mini-card">
                        <div class="card-body">
                            <div class="stat-mini-top">
                                <div class="stat-mini-left">
                                    <span class="stat-mini-icon stat-mini-icon-success">
                                        <i class="feather-bluetooth"></i>
                                    </span>
                                    <span class="stat-mini-label">Active Listings</span>
                                </div>
                                <h3 class="stat-mini-value">{{ str_pad($Active, 2, '0', STR_PAD_LEFT) }}</h3>
                            </div>
                            <div class="stat-mini-divider"></div>
                            <div class="stat-mini-bottom">
                                <div class="stat-mini-chart" id="order-bar-chart"></div>
                                <div class="stat-mini-meta">
                                    <span class="stat-mini-meta-highlight text-success">{{ $activePercent }}% live</span>
                                    <span class="stat-mini-meta-sub">from last week</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full stat-mini-card">
                        <div class="card-body">
                            <div class="stat-mini-top">
                                <div class="stat-mini-left">
                                    <span class="stat-mini-icon stat-mini-icon-warning">
                                        <i class="feather-star"></i>
                                    </span>
                                    <span class="stat-mini-label">Premium Listings</span>
                                </div>
                                <h3 class="stat-mini-value">{{ str_pad($Premium, 2, '0', STR_PAD_LEFT) }}</h3>
                            </div>
                            <div class="stat-mini-divider"></div>
                            <div class="stat-mini-bottom">
                                <div class="stat-mini-chart" id="premium-bar-chart"></div>
                                <div class="stat-mini-meta">
                                    <span class="stat-mini-meta-highlight text-warning">{{ $Normal }}+ standard</span>
                                    <span class="stat-mini-meta-sub">from last week</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-8">
                    <div class="card stretch stretch-full professional-chart-card listings-reach-card">
                        <div class="card-header">
                            <h5 class="card-title">Listings Reach</h5>
                            <div class="card-header-action">
                                <div class="listings-reach-legend">
                                    <span class="lr-legend-item"><span class="lr-dot lr-dot-primary"></span>Chats</span>
                                    <span class="lr-legend-item"><span class="lr-dot lr-dot-cyan"></span>Premium Chats</span>
                                </div>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown"><i class="feather-more-horizontal"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="{{ route('professional.listings.index') }}" class="dropdown-item">View Listings</a>
                                        <a href="{{ route('professional.plans.index') }}" class="dropdown-item">Upgrade Plan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body"><div id="professional-dashboard-chart" style="min-height: 340px;"></div></div>
                    </div>
                </div>

                <div class="col-xxl-4">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Top Listings by Reach</h5>
                            <div class="card-header-action">
                                <div class="card-header-btn">
                                    <div><a href="javascript:void(0);" class="avatar-text avatar-xs bg-danger"></a></div>
                                    <div><a href="javascript:void(0);" class="avatar-text avatar-xs bg-warning"></a></div>
                                    <div><a href="javascript:void(0);" class="avatar-text avatar-xs bg-success"></a></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @forelse ($Top_Projects as $project)
                                <div class="professional-table-row">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="professional-mini-avatar">{{ $loop->iteration }}</span>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ \Illuminate\Support\Str::limit($project->name, 28) }}</div>
                                            <div class="fs-11 text-muted">Listing ID #{{ $project->id }}</div>
                                        </div>
                                    </div>
                                    <div class="text-end"><div class="fw-bold text-dark">{{ number_format((int) ($project->chats_count ?? 0)) }}</div><div class="fs-11 text-muted">chats</div></div>
                                </div>
                            @empty
                                <div class="text-muted">No listing chat activity recorded yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            @endisset
        </div>
    </div>
</main>
@endsection

@section('scripts')
@unless(isset($buyerChatCount))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var reportRange = document.getElementById('reportrange');
        var reportRangeField = reportRange ? reportRange.querySelector('.reportrange-picker-field') : null;
        var selectedStart = @json($selectedStartDate ?? now()->subDays(13)->toDateString());
        var selectedEnd = @json($selectedEndDate ?? now()->toDateString());

        if (reportRange && reportRangeField) {
            var updateRangeAndRedirect = function (start, end) {
                var label = start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY');
                reportRangeField.textContent = label;
                var nextUrl = new URL(window.location.href);
                nextUrl.searchParams.set('start_date', start.format('YYYY-MM-DD'));
                nextUrl.searchParams.set('end_date', end.format('YYYY-MM-DD'));
                window.location.href = nextUrl.toString();
            };

            if (window.jQuery && jQuery.fn.daterangepicker && window.moment) {
                var startMoment = moment(selectedStart, 'YYYY-MM-DD');
                var endMoment = moment(selectedEnd, 'YYYY-MM-DD');
                reportRangeField.textContent = startMoment.format('MMM D, YYYY') + ' - ' + endMoment.format('MMM D, YYYY');

                jQuery(reportRange).daterangepicker({
                    startDate: startMoment,
                    endDate: endMoment,
                    autoApply: true,
                    alwaysShowCalendars: true,
                    maxDate: moment(),
                    opens: 'left',
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 14 Days': [moment().subtract(13, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, updateRangeAndRedirect);
            } else {
                reportRange.style.cursor = 'pointer';
                reportRange.addEventListener('click', function () {
                    var startInput = window.prompt('Start date (YYYY-MM-DD):', selectedStart);
                    if (!startInput) {
                        return;
                    }
                    var endInput = window.prompt('End date (YYYY-MM-DD):', selectedEnd);
                    if (!endInput) {
                        return;
                    }
                    var nextUrl = new URL(window.location.href);
                    nextUrl.searchParams.set('start_date', startInput);
                    nextUrl.searchParams.set('end_date', endInput);
                    window.location.href = nextUrl.toString();
                });
            }
        }

        if (typeof ApexCharts === 'undefined') {
            return;
        }

        var sparkConfigs = [
            {
                selector: '#income-bar-chart',
                color: '#5b2cd7',
                gradient: '#8b5cf6',
                series: @json($DailyTotal ?? [])
            },
            {
                selector: '#expense-bar-chart',
                color: '#ef4444',
                gradient: '#f87171',
                series: @json($DailyInactive ?? [])
            },
            {
                selector: '#order-bar-chart',
                color: '#16a34a',
                gradient: '#4ade80',
                series: @json($DailyActive ?? [])
            },
            {
                selector: '#premium-bar-chart',
                color: '#d97706',
                gradient: '#fbbf24',
                series: @json($DailyPremium ?? [])
            }
        ];

        sparkConfigs.forEach(function (config) {
            var element = document.querySelector(config.selector);
            if (!element) {
                return;
            }

            var rawSeries = Array.isArray(config.series) ? config.series : [];
            var counts = rawSeries.map(function (r) { return Number(r.count) || 0; });
            var labels = rawSeries.map(function (r) { return r.full || r.date || ''; });

            if (!counts.length) {
                counts = [0, 0, 0, 0, 0, 0, 0];
                labels = ['', '', '', '', '', '', ''];
            }

            var maxVal = Math.max.apply(null, counts);
            if (maxVal === 0) {
                counts = counts.map(function (_, i) {
                    return 0.5 + Math.sin(i * 0.9) * 0.3;
                });
            }

            new ApexCharts(element, {
                chart: {
                    type: 'area',
                    height: 58,
                    sparkline: { enabled: true },
                    toolbar: { show: false },
                    animations: { enabled: true, easing: 'easeinout', speed: 600 },
                    parentHeightOffset: 0
                },
                series: [{
                    name: 'Listings',
                    data: counts.map(function (v, i) {
                        return { x: labels[i], y: v };
                    })
                }],
                colors: [config.color],
                stroke: {
                    curve: 'smooth',
                    width: 2,
                    lineCap: 'round',
                    colors: [config.color]
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0.02,
                        stops: [0, 95, 100],
                        colorStops: [
                            { offset: 0,   color: config.gradient, opacity: 0.35 },
                            { offset: 100, color: config.gradient, opacity: 0 }
                        ]
                    }
                },
                dataLabels: { enabled: false },
                markers: { size: 0, hover: { size: 3, sizeOffset: 1 } },
                tooltip: {
                    enabled: true,
                    theme: 'light',
                    fixed: { enabled: false },
                    custom: function (opts) {
                        var point = opts.w.config.series[0].data[opts.dataPointIndex] || {};
                        var label = point.x != null ? point.x : '';
                        var value = Math.round(Number(point.y) || 0);
                        return '<div style="padding:4px 8px;font-size:11px;font-weight:600;color:#0f172a;white-space:nowrap;">'
                            + label + ' <span style="color:' + config.color + ';">(' + value + ')</span>'
                            + '</div>';
                    }
                },
                grid: { show: false, padding: { left: 0, right: 0, top: 0, bottom: 0 } }
            }).render();
        });

        var chartElement = document.querySelector('#professional-dashboard-chart');
        if (!chartElement) {
            return;
        }

        var topReach        = @json($topProjectReach);
        var topPremiumReach = @json($topProjectPremiumReach);
        var topDates        = @json($topProjectDates);
        var topShortNames   = @json($topProjectShortNames);
        var topFullNames    = @json($topProjectFullNames);

        new ApexCharts(chartElement, {
            chart: {
                height: 360,
                type: 'area',
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                zoom: { enabled: false },
                animations: { enabled: true, easing: 'easeinout', speed: 700 }
            },
            series: [
                { name: 'Chats',         data: topReach },
                { name: 'Premium Chats', data: topPremiumReach }
            ],
            stroke: { curve: 'smooth', width: [3, 3], lineCap: 'round' },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.02,
                    stops: [0, 95, 100]
                }
            },
            colors: ['#5b2cd7', '#22d3ee'],
            dataLabels: {
                enabled: true,
                enabledOnSeries: [0],
                formatter: function (val, opts) {
                    if (!opts || opts.seriesIndex !== 0) {
                        return '';
                    }
                    var idx = opts.dataPointIndex;
                    return topShortNames[idx] || '';
                },
                offsetY: -10,
                style: {
                    fontSize: '10px',
                    fontWeight: 500,
                    colors: ['#5b2cd7']
                },
                background: {
                    enabled: true,
                    foreColor: '#5b2cd7',
                    padding: 3,
                    borderRadius: 4,
                    borderWidth: 0,
                    opacity: 0.9,
                    dropShadow: { enabled: false }
                }
            },
            xaxis: {
                categories: topDates,
                labels: {
                    style: { colors: '#94a3b8', fontSize: '12px', fontWeight: 500 },
                    rotate: 0,
                    hideOverlappingLabels: true
                },
                axisBorder: { show: false },
                axisTicks: { show: false },
                tooltip: { enabled: false },
                crosshairs: {
                    show: true,
                    stroke: { color: 'rgba(15, 23, 42, 0.12)', width: 1, dashArray: 3 }
                },
                title: {
                    text: 'Listing Date',
                    style: { color: '#94a3b8', fontSize: '11px', fontWeight: 500 }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#94a3b8', fontSize: '12px' },
                    formatter: function (val) { return Math.round(val); }
                }
            },
            grid: {
                borderColor: 'rgba(15, 23, 42, 0.06)',
                strokeDashArray: 4,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } },
                padding: { left: 10, right: 20, top: 10, bottom: 0 }
            },
            legend: { show: false },
            markers: {
                size: 4,
                strokeWidth: 2,
                strokeColors: '#ffffff',
                hover: { size: 6 }
            },
            tooltip: {
                theme: 'light',
                shared: true,
                intersect: false,
                x: {
                    formatter: function (val, opts) {
                        var idx = opts && opts.dataPointIndex;
                        var name = topFullNames[idx] || '';
                        var date = topDates[idx] || val;
                        return name + '  ·  ' + date;
                    }
                },
                y: {
                    formatter: function (val) {
                        var rounded = Math.round(Number(val) || 0);
                        return rounded.toLocaleString() + ' chats';
                    }
                }
            }
        }).render();
    });
</script>
@endunless
@endsection
