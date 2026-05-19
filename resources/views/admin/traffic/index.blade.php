@extends('admin.layout')

@section('title', 'Traffic')
@section('page_title', 'Traffic Logs')
@section('page_subtitle', 'Review recent visitor traffic and daily site statistics.')

@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            <!-- [ page-header ] start -->
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Proposal</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">Proposal</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex d-md-none">
                            <a href="javascript:void(0)" class="page-header-right-close-toggle">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Back</span>
                            </a>
                        </div>
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                            <a href="javascript:void(0);" class="btn btn-icon btn-light-brand" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                <i class="feather-bar-chart"></i>
                            </a>
                            <div class="dropdown">
                                <a class="btn btn-icon btn-light-brand" data-bs-toggle="dropdown" data-bs-offset="0, 12" data-bs-auto-close="outside">
                                    <i class="feather-filter"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:void(0);" class="dropdown-item">
                                        <i class="feather-eye me-3"></i>
                                        <span>All</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-md-none d-flex align-items-center">
                        <a href="javascript:void(0)" class="page-header-right-open-toggle">
                            <i class="feather-align-right fs-20"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div id="collapseOne" class="accordion-collapse collapse page-header-collapse">
                <div class="accordion-body pb-2">
                    <div class="row">
                        @foreach($stats as $date => $count)
                            <div class="col-xxl-3 col-md-6">
                                <div class="card stretch stretch-full">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="hstack gap-3">
                                                <div class="avatar-text avatar-sm bg-soft-primary text-primary">
                                                    <i class="feather-calendar"></i>
                                                </div>
                                                <div>
                                                    <span class="fs-12 text-muted d-block">{{ $date }}</span>
                                                    <span class="fs-16 fw-bold d-block text-dark">{{ $count }} visits</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($loop->iteration >= 4) @break @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- [ page-header ] end -->
            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="proposalList">
                                        <thead>
                                            <tr>
                                                <th>IP Address</th>
                                                <th>Location (Country/City)</th>
                                                <th>Date (D/M/Y)</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($logs as $log)
                                                <tr class="single-item">
                                                    <td>
                                                        <span class="fw-bold text-dark">{{ $log->ip }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="hstack gap-2">
                                                            <span class="badge bg-soft-secondary text-secondary">{{ $log->country }}</span>
                                                            <span class="text-muted small">{{ $log->city }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-muted small">
                                                            {{ sprintf('%02d', $log->day) }}/{{ sprintf('%02d', $log->month) }}/{{ $log->year }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <a href="javascript:void(0);" class="avatar-text avatar-md">
                                                                <i class="feather feather-eye"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-5 text-muted">No traffic logs found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </main>
@endsection
