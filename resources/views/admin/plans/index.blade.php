@extends('admin.layout')

@section('title', 'Plans')
@section('page_title', 'Plans')
@section('page_subtitle', 'Review and update subscription plans.')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <!-- <div class="page-header-title">
                    <h5 class="m-b-10">Proposal</h5>
                </div> -->
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
                    <div class="col-xxl-3 col-md-6">
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="javascript:void(0);" class="fw-bold d-block">
                                        <span class="d-block">Paid</span>
                                        <span class="fs-20 fw-bold d-block">78/100</span>
                                    </a>
                                    <div class="progress-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                            <th>Professional</th>
                                            <th>Plan</th>
                                            <th>Limits</th>
                                            <th>Expiry</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($plans as $plan)
                                            <tr class="single-item">
                                                <td class="fw-bold">{{ $plan->professional?->full_name ?? '—' }}</td>
                                                <td>
                                                    @php
                                                        $planInfo = \App\Helpers\SubscriptionHelper::getPlanInfo($plan->type);
                                                    @endphp
                                                    <span class="badge bg-soft-info text-info">{{ $planInfo['name'] ?? ('Type ' . $plan->type) }}</span>
                                                </td>
                                                <td>
                                                    <form method="POST" action="{{ route('admin.plans.update', $plan->id) }}" class="d-flex align-items-center gap-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="number" name="listing_limit" value="{{ $plan->listing_limit }}" class="form-control form-control-sm wd-60">
                                                        <button type="submit" class="btn btn-sm btn-light-brand p-1"><i class="feather-save"></i></button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <div class="text-muted small">
                                                        @if ($plan->expiry && today()->isAfter($plan->expiry))
                                                            <span class="text-danger">Expired</span>
                                                        @else
                                                            {{ $plan->expiry?->format('M d, Y') ?? 'N/A' }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <form method="POST" action="{{ route('admin.plans.update', $plan->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                            <option value="1" {{ $plan->status ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ !$plan->status ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </form>
                                                </td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <form method="POST" action="{{ route('admin.plans.destroy', $plan->id) }}" class="d-inline js-admin-confirm-submit" data-confirm-title="Remove plan" data-confirm-message="Remove this plan entry?" data-confirm-button="Yes, remove">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="avatar-text avatar-md border-0 bg-transparent text-danger">
                                                                <i class="feather feather-trash-2"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">No subscription records found.</td>
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
