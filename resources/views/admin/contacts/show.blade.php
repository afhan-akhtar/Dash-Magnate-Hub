@extends('admin.layout')

@section('title', 'Submission — ' . ($contact->name ?: $contact->email ?: 'View'))
@section('page_title', 'Submission details')
@section('page_subtitle', $contact->name ?: $contact->email ?: 'Newsletter signup')

@push('styles')
    <style>
        .ac-shell { padding-bottom: 2rem; }
        .ac-card {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 12px 40px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            margin-bottom: 1.25rem;
        }
        .ac-card-hd {
            padding: 0.85rem 1.25rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            font-size: 0.6875rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--admin-sidebar-accent);
            background: linear-gradient(90deg, rgba(52, 84, 209, 0.06) 0%, #fff 50%);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .ac-card-bd { padding: 1.15rem 1.35rem 1.35rem; }
        .ac-meta {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.75rem;
        }
        .ac-meta-item {
            padding: 0.75rem 0.9rem;
            border-radius: 0.65rem;
            border: 1px solid rgba(15, 23, 42, 0.06);
            background: #fafbfd;
        }
        .ac-meta-label {
            font-size: 0.625rem;
            font-weight: 800;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 0.25rem;
        }
        .ac-meta-val {
            font-size: 0.9rem;
            font-weight: 600;
            color: #0f172a;
            word-break: break-word;
        }
        .ac-message {
            font-size: 0.9375rem;
            color: #475569;
            line-height: 1.7;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .ac-foot {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
        }
        .ac-foot i { margin-right: 0.35rem; color: var(--admin-sidebar-accent) }
    </style>
@endpush

@section('content')
    @php
        $returnTab = in_array((string) request('return_tab'), ['contact', 'newsletter', 'all'], true)
            ? request('return_tab')
            : 'contact';
    @endphp
    <main class="nxl-container">
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Submission details</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.contacts.index', ['tab' => $returnTab]) }}">Forms</a></li>
                        <li class="breadcrumb-item">#{{ $contact->id }}</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <div class="page-header-right-items">
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                            <a href="{{ route('admin.contacts.index', ['tab' => $returnTab]) }}" class="btn btn-light-brand">
                                <i class="feather-arrow-left me-2"></i>
                                <span>Back to list</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-content ac-shell">
                <div class="row">
                    <div class="col-12">

                        <div class="ac-card">
                            <div class="ac-card-hd">
                                <i class="feather-info"></i> Summary
                            </div>
                            <div class="ac-card-bd">
                                <div class="ac-meta">
                                    <div class="ac-meta-item">
                                        <div class="ac-meta-label">Type</div>
                                        <div class="ac-meta-val">{{ $contact->type ?? '—' }}</div>
                                    </div>
                                    <div class="ac-meta-item">
                                        <div class="ac-meta-label">Name</div>
                                        <div class="ac-meta-val">{{ $contact->name ?: '—' }}</div>
                                    </div>
                                    <div class="ac-meta-item">
                                        <div class="ac-meta-label">Email</div>
                                        <div class="ac-meta-val">{{ $contact->email ?? '—' }}</div>
                                    </div>
                                    <div class="ac-meta-item">
                                        <div class="ac-meta-label">Phone</div>
                                        <div class="ac-meta-val">{{ $contact->phone ?: '—' }}</div>
                                    </div>
                                    <div class="ac-meta-item">
                                        <div class="ac-meta-label">Subject</div>
                                        <div class="ac-meta-val">{{ $contact->subject ?: '—' }}</div>
                                    </div>
                                    <div class="ac-meta-item">
                                        <div class="ac-meta-label">Reference</div>
                                        <div class="ac-meta-val text-break">{{ $contact->code ?: '—' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ac-card">
                            <div class="ac-card-hd">
                                <i class="feather-message-square"></i> Message
                            </div>
                            <div class="ac-card-bd">
                                @if ($contact->message)
                                    <div class="ac-message">{{ $contact->message }}</div>
                                @else
                                    <p class="text-muted mb-0">No message body for this submission.</p>
                                @endif
                            </div>
                        </div>

                        <div class="ac-card mb-0">
                            <div class="ac-card-bd">
                                <div class="ac-foot">
                                    <span><i class="feather-calendar"></i>Received {{ $contact->created_at?->format('M d, Y — h:i A') ?? '—' }}</span>
                                    @if ($contact->updated_at && $contact->updated_at->ne($contact->created_at))
                                        <span><i class="feather-refresh-cw"></i>Updated {{ $contact->updated_at->format('M d, Y — h:i A') }}</span>
                                    @endif
                                </div>
                                <div class="mt-3 pt-3 border-top">
                                    <form method="POST" action="{{ route('admin.contacts.destroy', $contact->id) }}" class="d-inline js-admin-confirm-submit" data-confirm-title="Remove submission" data-confirm-message="Remove this submission from the list?" data-confirm-button="Yes, remove">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="tab" value="{{ $returnTab }}">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="feather-trash-2 me-1"></i> Remove submission
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
