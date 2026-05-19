@extends('admin.layout')

@section('title', 'Email templates')
@section('page_title', 'Email templates')

@push('styles')
    <style>
        .et-summary {
            border-radius: 1rem;
            border: 1px solid rgba(52, 84, 209, 0.1);
            background:
                radial-gradient(ellipse 120% 80% at 100% 0%, rgba(52, 84, 209, 0.07), transparent 50%),
                linear-gradient(180deg, #fbfcff 0%, #ffffff 100%);
            box-shadow: 0 14px 42px rgba(15, 23, 42, 0.06);
            padding: 1.25rem 1.35rem 1.35rem;
            margin-bottom: 1.5rem;
        }
    </style>
@endpush

@section('content')
    <main class="nxl-container">
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Email templates</li>
                    </ul>
                </div>
            </div>

            <div class="main-content">
                <div class="row">
                    <div class="col-12">
                        <div class="et-summary">
                            <h6 class="mb-1 text-uppercase text-muted fw-semibold" style="letter-spacing: 0.06em; font-size: 0.8125rem;">Template library</h6>
                            <p class="mb-0 text-muted">List of all the email templates used by the app. You can edit templates. </p>
                        </div>

                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <h5 class="card-title mb-0">
                                    <i class="feather-mail me-2 text-primary"></i>All email templates
                                </h5>
                                <span class="badge bg-soft-primary text-primary">{{ $templates->count() }} {{ $templates->count() === 1 ? 'template' : 'templates' }}</span>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-4">View</th>
                                                <th>Filename</th>
                                                <th>Lines</th>
                                                <th>Size</th>
                                                <th>Last updated</th>
                                                <th class="text-end pe-4">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($templates as $template)
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="fw-semibold text-dark">{{ $template['view'] }}</div>
                                                    </td>
                                                    <td class="text-muted">{{ $template['filename'] }}</td>
                                                    <td class="text-muted">{{ number_format($template['line_count']) }}</td>
                                                    <td class="text-muted">{{ $template['size_kb'] }} KB</td>
                                                    <td class="text-muted">{{ $template['updated_at'] }}</td>
                                                    <td class="text-end pe-4">
                                                        <a href="{{ route('admin.email-templates.edit', $template['key']) }}" class="btn btn-sm btn-light-brand">
                                                            <i class="feather-edit-3 me-1"></i>
                                                            <span>Edit</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5 text-muted">
                                                        <i class="feather-inbox fs-3 d-block mb-2 opacity-50"></i>
                                                        No email templates found in <code>resources/views/mail</code>.
                                                    </td>
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
        </div>
    </main>
@endsection

