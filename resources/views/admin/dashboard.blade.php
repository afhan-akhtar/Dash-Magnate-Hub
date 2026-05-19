@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Live metrics, trends, and recent activity across the platform.')

@push('styles')
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
    .stat-mini-icon-info    { background: rgba(14, 165, 233, 0.14); color: #0ea5e9; }
    .stat-mini-icon-dark    { background: rgba(51, 65, 85, 0.14); color: #334155; }
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
@endpush

@section('content')
    @php
        $rolePalette = [
            'user' => ['badge' => 'bg-soft-primary text-primary', 'border' => 'border-primary', 'label' => 'User'],
            'buyer' => ['badge' => 'bg-soft-success text-success', 'border' => 'border-success', 'label' => 'Buyer'],
            'seller' => ['badge' => 'bg-soft-warning text-warning', 'border' => 'border-warning', 'label' => 'Seller'],
            'capital_raiser' => ['badge' => 'bg-soft-info text-info', 'border' => 'border-info', 'label' => 'Capital raiser'],
            'broker' => ['badge' => 'bg-soft-danger text-danger', 'border' => 'border-danger', 'label' => 'Broker'],
        ];

        $contactReturnTab = static function (\App\Models\Contact $c): string {
            if ($c->type === 'News Letter Form') {
                return 'newsletter';
            }
            if (in_array($c->type, ['Contact Form', 'contact'], true)) {
                return 'contact';
            }

            return 'all';
        };
    @endphp

    <main class="nxl-container">
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title"><h5 class="m-b-10">Dashboard</h5></div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Overview</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                        <div class="dropdown">
                            <a class="btn btn-light-brand" href="#" data-bs-toggle="dropdown" data-bs-offset="0, 8" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="feather-grid me-2"></i><span>Quick links</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('admin.accounts.index') }}"><i class="feather-shield me-2"></i>Admin accounts</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.projects.index') }}"><i class="feather-briefcase me-2"></i>Listings</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.contacts.index', ['tab' => 'contact']) }}"><i class="feather-mail me-2"></i>Forms</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.professionals.index') }}"><i class="feather-users me-2"></i>Professionals</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.blogs.index') }}"><i class="feather-edit-3 me-2"></i>Blogs</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}"><i class="feather-layers me-2"></i>Categories</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.professional-questions.index') }}"><i class="feather-help-circle me-2"></i>Onboarding questions</a></li>
                            </ul>
                        </div>
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-primary">
                            <i class="feather-briefcase me-2"></i><span>Manage listings</span>
                        </a>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="main-content">
                <div class="row g-3">
                    @foreach ($statCards as $card)
                        <div class="col-xxl-4 col-lg-4 col-md-6 col-sm-6">
                            <div class="card stretch stretch-full stat-mini-card h-100">
                                <div class="card-body">
                                    <div class="stat-mini-top">
                                        <div class="stat-mini-left">
                                            <span class="stat-mini-icon stat-mini-icon-{{ $card['tone'] }}">
                                                <i class="{{ $card['icon'] }}"></i>
                                            </span>
                                            <span class="stat-mini-label">{{ $card['label'] }}</span>
                                        </div>
                                        <h3 class="stat-mini-value">{{ $card['value'] }}</h3>
                                    </div>
                                    <div class="stat-mini-divider"></div>
                                    <div class="stat-mini-bottom">
                                        <div class="stat-mini-chart" id="admin-spark-{{ $card['key'] }}"
                                             data-color="{{ $card['color'] }}"
                                             data-gradient="{{ $card['gradient'] }}"
                                             data-series='@json($card['daily'])'></div>
                                        <div class="stat-mini-meta">
                                            <span class="stat-mini-meta-highlight" style="color: {{ $card['color'] }};">{{ $card['highlight'] }}</span>
                                            <span class="stat-mini-meta-sub">{{ $card['sub'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="col-xxl-6">
                        <div class="card stretch stretch-full h-100">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <div>
                                    <h5 class="card-title mb-0">Platform growth</h5>
                                    <p class="text-muted fs-12 mb-0">New members and new listings by month (last 12 months).</p>
                                </div>
                                <a href="{{ route('admin.professionals.index') }}" class="fs-12 fw-semibold text-primary">Professionals</a>
                            </div>
                            <div class="card-body custom-card-action pt-0">
                                <div id="admin-dashboard-growth" style="min-height: 320px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-6">
                        <div class="card stretch stretch-full h-100">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <div>
                                    <h5 class="card-title mb-0">Form acquisition</h5>
                                    <p class="text-muted fs-12 mb-0">Submissions by month: contact form, newsletter, and other types.</p>
                                </div>
                                <a href="{{ route('admin.contacts.index', ['tab' => 'all']) }}" class="fs-12 fw-semibold text-primary">Open forms</a>
                            </div>
                            <div class="card-body custom-card-action pt-0">
                                <div id="admin-dashboard-acquisition" style="min-height: 320px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-6">
                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <h5 class="card-title mb-0">Recent members</h5>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('admin.professionals.index') }}" class="fs-12 fw-semibold text-primary">Professionals</a>
                                    <span class="text-muted fs-12">·</span>
                                    <a href="{{ route('admin.users.index') }}" class="fs-12 fw-semibold text-primary">Buyers &amp; members</a>
                                </div>
                            </div>
                            <div class="card-body custom-card-action p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-4">Name</th>
                                                <th>Role</th>
                                                <th>Joined</th>
                                                <th>Status</th>
                                                <th class="pe-4">Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($recentMembers as $member)
                                                @php
                                                    $palette = $rolePalette[$member->role] ?? $rolePalette['user'];
                                                    $showRoute = $member->isProfessional() ? route('admin.professionals.show', $member->id) : route('admin.users.show', $member->id);
                                                    $thumb = $member->thumbnail;
                                                    $avatarUrl = $thumb?->url ?: ($thumb?->path ? asset(ltrim(str_replace('\\', '/', $thumb->path), '/')) : null);
                                                @endphp
                                                <tr>
                                                    <td class="ps-4 position-relative">
                                                        <div class="ht-50 position-absolute start-0 top-50 translate-middle border-start border-5 {{ $palette['border'] }} rounded"></div>
                                                        <div class="hstack gap-2">
                                                            @if ($avatarUrl)
                                                                <img src="{{ $avatarUrl }}" alt="" class="rounded" width="36" height="36" style="object-fit: cover;">
                                                            @else
                                                                <span class="avatar-text avatar-sm bg-soft-secondary text-secondary">{{ \Illuminate\Support\Str::substr($member->full_name, 0, 1) }}</span>
                                                            @endif
                                                            <a href="{{ $showRoute }}" class="fw-semibold text-dark">{{ $member->full_name }}</a>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge {{ $palette['badge'] }} fw-normal">{{ $palette['label'] }}</span></td>
                                                    <td class="text-muted fs-12">{{ optional($member->created_at)->format('d M Y') ?? '—' }}</td>
                                                    <td>
                                                        <span class="badge {{ $member->status == 0 ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }} fw-normal">{{ $member->status == 0 ? 'Active' : 'Inactive' }}</span>
                                                    </td>
                                                    <td class="pe-4"><a href="mailto:{{ $member->email }}" class="text-muted fs-12 text-break">{{ $member->email }}</a></td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-center text-muted py-4">No members yet.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-6">
                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <h5 class="card-title mb-0">Recent form submissions</h5>
                                <a href="{{ route('admin.contacts.index', ['tab' => 'all']) }}" class="fs-12 fw-semibold text-primary">View all</a>
                            </div>
                            <div class="card-body custom-card-action p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-4">Type</th>
                                                <th>From</th>
                                                <th>Date</th>
                                                <th class="pe-4 text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($recentContacts as $contact)
                                                @php
                                                    $rt = $contactReturnTab($contact);
                                                @endphp
                                                <tr>
                                                    <td class="ps-4">
                                                        <span class="badge bg-soft-secondary text-secondary fw-normal text-wrap">{{ $contact->type ?? '—' }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="fw-semibold text-dark">{{ $contact->name ?: '—' }}</div>
                                                        <small class="text-muted text-break">{{ $contact->email ?? '—' }}</small>
                                                    </td>
                                                    <td class="text-muted fs-12">{{ optional($contact->created_at)->format('d M Y H:i') ?? '—' }}</td>
                                                    <td class="pe-4 text-end">
                                                        <a href="{{ route('admin.contacts.show', ['id' => $contact->id, 'return_tab' => $rt]) }}" class="btn btn-sm btn-light-brand">Open</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4" class="text-center text-muted py-4">No submissions yet.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <div>
                                    <h5 class="card-title mb-0">Recent listings</h5>
                                    <p class="text-muted fs-12 mb-0">Latest projects created in the directory.</p>
                                </div>
                                <a href="{{ route('admin.projects.index') }}" class="fs-12 fw-semibold text-primary">Open listings</a>
                            </div>
                            <div class="card-body custom-card-action p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-4">Listing</th>
                                                <th>Professional</th>
                                                <th>Added</th>
                                                <th>Status</th>
                                                <th class="pe-4 text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($recentProjects as $project)
                                                <tr>
                                                    <td class="ps-4">
                                                        <a href="{{ route('admin.projects.show', $project->id) }}" class="fw-semibold text-dark">{{ $project->name }}</a>
                                                        <div class="fs-11 text-muted">#{{ $project->ref_id ?: $project->code ?: $project->id }}</div>
                                                    </td>
                                                    <td class="text-muted fs-12">
                                                        {{ $project->professional?->full_name ?? '—' }}
                                                    </td>
                                                    <td class="text-muted fs-12">{{ optional($project->created_at)->format('d M Y') ?? '—' }}</td>
                                                    <td>
                                                        <span class="badge {{ (int) $project->status === 1 ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' }} fw-normal">
                                                            {{ (int) $project->status === 1 ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td class="pe-4 text-end">
                                                        <a href="{{ route('admin.projects.show', $project->id) }}" class="btn btn-sm btn-light-brand">View</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-center text-muted py-4">No listings yet.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        (function () {
            const cfg = {
                labels: @json($chartLabels),
                growth: @json($chartGrowth),
                acquisition: @json($chartAcquisition),
            };

            if (typeof ApexCharts === 'undefined') {
                return;
            }

            document.querySelectorAll('.stat-mini-chart').forEach(function (el) {
                var color = el.getAttribute('data-color') || '#5b2cd7';
                var gradient = el.getAttribute('data-gradient') || color;
                var rawSeries = [];
                try {
                    rawSeries = JSON.parse(el.getAttribute('data-series') || '[]');
                } catch (e) {
                    rawSeries = [];
                }
                if (!Array.isArray(rawSeries)) rawSeries = [];

                var counts = rawSeries.map(function (r) { return Number(r.count) || 0; });
                var labels = rawSeries.map(function (r) { return r.full || r.date || ''; });

                if (!counts.length) {
                    counts = [0, 0, 0, 0, 0, 0, 0];
                    labels = ['', '', '', '', '', '', ''];
                }
                if (Math.max.apply(null, counts) === 0) {
                    counts = counts.map(function (_, i) {
                        return 0.5 + Math.sin(i * 0.9) * 0.3;
                    });
                }

                new ApexCharts(el, {
                    chart: {
                        type: 'area',
                        height: 58,
                        sparkline: { enabled: true },
                        toolbar: { show: false },
                        animations: { enabled: true, easing: 'easeinout', speed: 600 },
                        parentHeightOffset: 0
                    },
                    series: [{
                        name: 'Records',
                        data: counts.map(function (v, i) { return { x: labels[i], y: v }; })
                    }],
                    colors: [color],
                    stroke: { curve: 'smooth', width: 2, lineCap: 'round', colors: [color] },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.3,
                            opacityTo: 0.02,
                            stops: [0, 95, 100],
                            colorStops: [
                                { offset: 0,   color: gradient, opacity: 0.35 },
                                { offset: 100, color: gradient, opacity: 0 }
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
                                + label + ' <span style="color:' + color + ';">(' + value + ')</span>'
                                + '</div>';
                        }
                    },
                    grid: { show: false, padding: { left: 0, right: 0, top: 0, bottom: 0 } }
                }).render();
            });

            const commonAxis = {
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { fontSize: '10px', colors: '#64748b' } },
            };

            const growthEl = document.querySelector('#admin-dashboard-growth');
            if (growthEl) {
                new ApexCharts(growthEl, {
                    chart: { height: 320, width: '100%', type: 'bar', toolbar: { show: false } },
                    colors: ['#3454D1', '#94a3b8'],
                    plotOptions: { bar: { borderRadius: 6, columnWidth: '42%' } },
                    dataLabels: { enabled: false },
                    series: cfg.growth,
                    xaxis: { categories: cfg.labels, ...commonAxis },
                    yaxis: {
                        labels: {
                            formatter: function (v) { return Math.round(v).toString(); },
                            style: { colors: '#64748b' },
                        },
                    },
                    grid: { strokeDashArray: 4, xaxis: { lines: { show: false } } },
                    legend: { position: 'top', horizontalAlign: 'left', fontSize: '12px', markers: { width: 10, height: 10, radius: 10 } },
                    tooltip: { y: { formatter: function (v) { return v + ' created'; } } },
                }).render();
            }

            const acqEl = document.querySelector('#admin-dashboard-acquisition');
            if (acqEl) {
                new ApexCharts(acqEl, {
                    chart: { height: 320, width: '100%', type: 'bar', stacked: true, toolbar: { show: false } },
                    colors: ['#3454D1', '#25b865', '#cbd5e1'],
                    plotOptions: { bar: { borderRadius: 4, columnWidth: '42%' } },
                    dataLabels: { enabled: false },
                    series: cfg.acquisition,
                    xaxis: { categories: cfg.labels, ...commonAxis },
                    yaxis: {
                        labels: {
                            formatter: function (v) { return Math.round(v).toString(); },
                            style: { colors: '#64748b' },
                        },
                    },
                    grid: { strokeDashArray: 4, xaxis: { lines: { show: false } } },
                    legend: { position: 'top', horizontalAlign: 'left', fontSize: '12px', markers: { width: 10, height: 10, radius: 10 } },
                    tooltip: { shared: true, y: { formatter: function (v) { return v; } } },
                }).render();
            }
        })();
    </script>
@endpush
