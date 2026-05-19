@extends('admin.layout')

@section('title', 'Subscribers')
@section('page_title', 'Subscribers')
@section('page_subtitle', 'Email list for newsletter subscriptions.')

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
                                            <th>Email Address</th>
                                            <th>Subscription Date</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($subscribers as $subscriber)
                                            <tr class="single-item">
                                                <td><span class="fw-bold text-dark">{{ $subscriber->email }}</span></td>
                                                <td>{{ $subscriber->created_at?->format('F d, Y') ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <form method="POST" action="{{ route('admin.subscribers.destroy', $subscriber->id) }}" class="d-inline js-admin-confirm-submit" data-confirm-title="Remove subscriber" data-confirm-message="Remove this subscriber?" data-confirm-button="Yes, remove">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="avatar-text avatar-md border-0 bg-transparent text-danger">
                                                                <i class="feather-trash-2"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-5 text-muted">No subscribers found.</td>
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
