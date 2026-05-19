@extends('admin.layout')

@section('title', 'Contact submissions')
@section('page_title', 'Forms')
@section('page_subtitle', 'Inquiries and newsletter signups captured from the site.')

@push('styles')
    <style>
        .contact-msg-preview {
            max-width: min(280px, 42vw);
            flex: 1 1 auto;
            min-width: 0;
        }
        .contact-msg-info-btn {
            width: 28px;
            height: 28px;
            padding: 0;
            border-radius: 50%;
            flex-shrink: 0;
            align-self: center;
            font-size: 0.8125rem;
            font-weight: 700;
            line-height: 1;
        }
        #adminContactMessageModal .modal-body {
            white-space: pre-wrap;
            word-break: break-word;
        }
        .nav-item .nav-link {
            color: var(--admin-sidebar-accent) !important;
        }
        .nav-item .nav-link.active, .nav-pills .show > .nav-link {
            color: #fff !important;
            background-color: var(--admin-sidebar-accent) !important;
            text-decoration: none !important;
        }
    </style>
@endpush

@section('content')
    @php
        $tab = in_array((string) ($tab ?? 'contact'), ['contact', 'newsletter', 'all'], true) ? $tab : 'contact';
        $listTotal = $contacts->count();
        $emptyCopy = match ($tab) {
            'newsletter' => 'No newsletter signups yet. New submissions will appear here.',
            'all' => 'No form submissions in the database yet.',
            default => 'No contact form messages yet. Submissions from your site will show up here.',
        };
    @endphp
    <main class="nxl-container">
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <!-- <div class="page-header-title">
                        <h5 class="m-b-10">Forms</h5>
                    </div> -->
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Forms</li>
                    </ul>
                </div>
            </div>

            <div class="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <h5 class="card-title mb-0">
                                    <i class="feather-mail me-2 text-primary"></i>Submissions
                                </h5>
                                <span class="badge bg-soft-primary text-primary">{{ $listTotal }} {{ $listTotal === 1 ? 'record' : 'records' }}</span>
                            </div>
                            <div class="card-body border-bottom bg-light-subtle py-3">
                                <ul class="nav nav-pills gap-2 mb-0 flex-wrap">
                                    <li class="nav-item">
                                        <a class="nav-link {{ $tab === 'contact' ? 'active' : '' }}"
                                           href="{{ route('admin.contacts.index', ['tab' => 'contact']) }}">Contact form</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $tab === 'newsletter' ? 'active' : '' }}"
                                           href="{{ route('admin.contacts.index', ['tab' => 'newsletter']) }}">Newsletter</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ $tab === 'all' ? 'active' : '' }}"
                                           href="{{ route('admin.contacts.index', ['tab' => 'all']) }}">All</a>
                                    </li>
                                </ul>
                            </div>

                            @if ($listTotal === 0)
                                <div class="card-body py-5">
                                    <div class="text-center text-muted mx-auto" style="max-width: 420px;">
                                        <span class="avatar-text avatar-xl bg-soft-primary text-primary mx-auto mb-3 d-inline-flex">
                                            <i class="feather-inbox fs-3"></i>
                                        </span>
                                        <h6 class="text-dark fw-semibold mb-2">Nothing to show yet</h6>
                                        <p class="small mb-0">{{ $emptyCopy }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0" id="contactsListTable">
                                            <thead>
                                                <tr>
                                                    <th>Sender</th>
                                                    <th>Subject / type</th>
                                                    <th>Message</th>
                                                    <th>Date</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($contacts as $contact)
                                                    <tr class="single-item">
                                                        <td>
                                                            <div class="fw-bold text-dark">{{ $contact->name ?: '—' }}</div>
                                                            <small class="text-muted">{{ $contact->email ?? '—' }}</small>
                                                            @if ($contact->phone)
                                                                <div class="small text-muted">{{ $contact->phone }}</div>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($contact->subject)
                                                                <span class="badge bg-soft-primary text-primary">{{ $contact->subject }}</span>
                                                            @else
                                                                <span class="badge bg-soft-secondary text-secondary">{{ $contact->type ?? '—' }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $rawMsg = (string) ($contact->message ?? '');
                                                                $displayFull = trim(strip_tags($rawMsg));
                                                                $normalized = $displayFull === '' ? '' : trim(preg_replace('/\s+/u', ' ', $displayFull));
                                                                $words = $normalized === '' ? [] : preg_split('/\s+/u', $normalized, -1, PREG_SPLIT_NO_EMPTY);
                                                                $previewWordLimit = 14;
                                                                $isTruncated = count($words) > $previewWordLimit;
                                                                $preview = $normalized === ''
                                                                    ? ''
                                                                    : ($isTruncated ? implode(' ', array_slice($words, 0, $previewWordLimit)) . '…' : $normalized);
                                                            @endphp
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span class="text-muted small contact-msg-preview d-inline-block">
                                                                    {{ $preview !== '' ? $preview : '—' }}
                                                                    @if ($displayFull !== '')
                                                                    <a
                                                                            type="button"
                                                                            class="btn btn-sm btn-light border contact-msg-info-btn js-contact-full-message"
                                                                            title="View full message"
                                                                            data-full-message="{{ e($displayFull) }}"
                                                                            aria-label="View full message"
                                                                        >
                                                                            <i class="feather-eye"></i>
                                                                        </a>
                                                                    @endif
                                                                </span>
                                                                
                                                            </div>
                                                        </td>
                                                        <td class="text-muted small">{{ $contact->created_at?->format('M d, Y') ?? '—' }}</td>
                                                        <td>
                                                            <div class="hstack gap-2 justify-content-end">
                                                                <a href="{{ route('admin.contacts.show', $contact->id) }}?{{ http_build_query(['return_tab' => $tab]) }}" class="avatar-text avatar-md" title="View">
                                                                    <i class="feather-eye text-primary"></i>
                                                                </a>
                                                                <form method="POST" action="{{ route('admin.contacts.destroy', $contact->id) }}" class="d-inline js-admin-confirm-submit" data-confirm-title="Remove submission" data-confirm-message="Remove this submission?" data-confirm-button="Yes, remove">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="tab" value="{{ $tab }}">
                                                                    <button type="submit" class="avatar-text avatar-md border-0 bg-transparent text-danger" title="Remove">
                                                                        <i class="feather-trash-2"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="adminContactMessageModal" tabindex="-1" aria-labelledby="adminContactMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminContactMessageModalLabel">Full message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-dark small" id="adminContactMessageModalBody"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const modalEl = document.getElementById('adminContactMessageModal');
            const bodyEl = document.getElementById('adminContactMessageModalBody');
            if (!modalEl || !bodyEl) {
                return;
            }
            const modal =
                typeof bootstrap !== 'undefined' && bootstrap.Modal
                    ? bootstrap.Modal.getOrCreateInstance(modalEl)
                    : null;

            document.querySelectorAll('.js-contact-full-message').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const text = btn.getAttribute('data-full-message') || '';
                    bodyEl.textContent = text;
                    if (modal) {
                        modal.show();
                    }
                });
            });
        })();
    </script>
@endpush
