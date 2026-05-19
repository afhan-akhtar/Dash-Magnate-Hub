@extends('admin.layout')

@section('title', 'Documents')
@section('page_title', 'Uploaded Documents')
@section('page_subtitle', 'Review and download user-uploaded verification files.')

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
                                            <th>Label</th>
                                            <th>Uploader</th>
                                            <th>Type</th>
                                            <th>Upload Date</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($documents as $document)
                                            @php
                                                $rawPath = $document->path ? str_replace('\\', '/', $document->path) : '';
                                                $base = rtrim(request()->getBaseUrl(), '/');
                                                $fileUrl = $rawPath
                                                    ? ($base !== '' ? $base . '/storage/' . ltrim($rawPath, '/') : asset('storage/' . ltrim($rawPath, '/')))
                                                    : '#';
                                                $fileLabel = $document->original_name ?: ($document->collection ?: 'File #' . $document->id);
                                                $docOwner = $document->documentable;
                                            @endphp
                                            <tr class="single-item">
                                                <td>
                                                    <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="fw-bold text-dark d-block">{{ $fileLabel }}</a>
                                                    <small class="text-muted text-uppercase">{{ pathinfo($document->path ?? '', PATHINFO_EXTENSION) }}</small>
                                                </td>
                                                <td>
                                                    <span class="text-muted small">
                                                        @if ($docOwner instanceof \App\Models\User)
                                                            {{ $docOwner->full_name }}
                                                        @elseif ($docOwner !== null)
                                                            {{ $docOwner->name ?? class_basename($docOwner) . ' #' . $docOwner->getKey() }}
                                                        @else
                                                            —
                                                        @endif
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-info text-info">{{ $document->collection }}</span>
                                                    @if ($document->mime_type)
                                                        <span class="d-block text-muted small mt-1">{{ $document->mime_type }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $document->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="avatar-text avatar-md">
                                                            <i class="feather-download-cloud text-primary"></i>
                                                        </a>
                                                        <form method="POST" action="{{ route('admin.documents.destroy', $document->id) }}" class="d-inline js-admin-confirm-submit" data-confirm-title="Delete document" data-confirm-message="Remove this document permanently?">
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
                                                <td colspan="5" class="text-center py-5 text-muted">No documents found.</td>
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
