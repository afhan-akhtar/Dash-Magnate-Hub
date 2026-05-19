@php
    use Illuminate\Support\Str;

    if ($isProfessional) {
        $selectedProject = optional($selectedConversation)->project ?? ($messages->isNotEmpty() ? $messages->first()->project : null);
    } else {
        $selectedProject = optional($selectedConversation)->project ?? ($messages->isNotEmpty() ? $messages->first()->project : null);
    }

    $listingName = $selectedProject?->name ?: 'Listing enquiry';
    $listingAvatar = optional($selectedProject?->thumbnail)->url;
    if (blank($listingAvatar) && filled($selectedProject?->card)) {
        $listingAvatar = $selectedProject->card;
    }

    $currentProfessional = auth()->user();
    $counterparty = $isProfessional
        ? (optional($selectedConversation)->user ?? null)
        : (optional($selectedConversation)->professional ?? null);
    $counterpartyProfileUrl = optional($counterparty?->document('profile')->first())->url;
    $currentProfessionalProfileUrl = optional($currentProfessional?->document('profile')->first())->url;

    $headerAvatar = filled($counterpartyProfileUrl) ? $counterpartyProfileUrl : $listingAvatar;
    $headerLabel = $counterparty?->name ?: $listingName;
    $lastMessage = $messages->last();
    $lastMessagePreview = trim((string) optional($lastMessage)->message);
    if ($lastMessagePreview === '' && $lastMessage && $lastMessage->documents->isNotEmpty()) {
        $lastMessagePreview = 'Attachment';
    }
    if ($lastMessagePreview === '') {
        $lastMessagePreview = 'Open conversation';
    }
@endphp

@if ($selectedConversation || (($selectedUserId ?? 0) && $selectedProjectId) || (($selectedProfessionalId ?? 0) && $selectedProjectId))
    <div class="chat-thread-header sticky-top flex-shrink-0">
        <div class="page-header-left hstack gap-3">
            <a href="javascript:void(0);" class="app-sidebar-open-trigger d-lg-none">
                <i class="feather-align-left fs-20"></i>
            </a>
            <div class="d-flex align-items-center gap-3 min-w-0">
                @if ($headerAvatar)
                    <div class="avatar-image">
                        <img src="{{ $headerAvatar }}" alt="{{ $headerLabel }}">
                    </div>
                @else
                    <div class="avatar-text bg-primary text-white">{{ strtoupper(Str::substr($headerLabel, 0, 1)) }}</div>
                @endif
                <div class="min-w-0">
                    <h2 class="chat-thread-header__name mb-0 text-truncate">{{ $headerLabel }}</h2>
                    <p class="chat-thread-header__context mb-0 text-truncate">
                        @if ($isProfessional && $selectedProject)
                            <a href="{{ route('professional.listings.show', $selectedProject->id) }}">{{ $listingName }}</a>
                            <span class="mx-1">•</span>
                            Listing #{{ $selectedProject->id }}
                        @else
                            <i class="feather-briefcase chat-thread-header__listing-icon" aria-hidden="true"></i>
                            <span class="text-truncate">{{ $listingName }}</span>
                            @if ($selectedProject)
                                <span class="mx-1">•</span>Listing #{{ $selectedProject->id }}
                            @endif
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div
        class="content-area-body chat-messages-scroll"
        id="chat-message-list"
        data-last-message-id="{{ $messages->last()?->id ?? 0 }}"
        data-last-message-preview="{{ $lastMessagePreview }}"
    >
        @forelse ($messages as $message)
            @php
                $isOutgoing = $isProfessional ? ((int) $message->send === 0) : ((int) $message->send === 1);

                if ($isProfessional) {
                    if ($isOutgoing) {
                        $messageUserName = $currentProfessional?->name ?: 'You';
                        $messageAvatar = filled($currentProfessionalProfileUrl) ? $currentProfessionalProfileUrl : null;
                    } else {
                        $messageUserName = $message->user?->name ?: ($counterparty?->name ?: $listingName);
                        $messageAvatar = optional($message->user?->document('profile')->first())->url
                            ?: (filled($counterpartyProfileUrl) ? $counterpartyProfileUrl : null);
                    }
                } else {
                    if ($isOutgoing) {
                        $messageUserName = $message->user?->name ?: 'You';
                        $messageAvatar = optional($message->user?->document('profile')->first())->url;
                    } else {
                        $messageUserName = $message->professional?->name ?: ($counterparty?->name ?: $listingName);
                        $messageAvatar = optional($message->professional?->document('profile')->first())->url
                            ?: (filled($counterpartyProfileUrl) ? $counterpartyProfileUrl : null);
                    }
                }

                if (blank($messageAvatar)) {
                    $messageAvatar = $listingAvatar;
                }
            @endphp

            <div class="single-chat-item {{ $isOutgoing ? 'single-chat-item--outgoing' : 'single-chat-item--incoming' }}">
                <div class="d-flex {{ $isOutgoing ? 'flex-row-reverse' : '' }} align-items-center gap-2 mb-2">
                    @if ($messageAvatar)
                        <div class="avatar-image">
                            <img src="{{ $messageAvatar }}" alt="{{ $messageUserName }}">
                        </div>
                    @else
                        <div class="avatar-text {{ $isOutgoing ? 'bg-dark text-white' : 'bg-primary text-white' }}">
                            {{ strtoupper(Str::substr($messageUserName, 0, 1)) }}
                        </div>
                    @endif
                    <div class="chat-message-meta {{ $isOutgoing ? 'text-end' : '' }}">
                        <span class="chat-message-meta__name">{{ $messageUserName }}</span>
                        <span class="chat-message-meta__time">{{ optional($message->created_at)->format('d M Y, h:i A') }}</span>
                    </div>
                </div>

                <div class="chat-bubble-stack">
                    @if (filled($message->message))
                        <div class="chat-bubble chat-bubble--text">
                            <p>{{ $message->message }}</p>
                        </div>
                    @endif

                    @foreach ($message->documents as $document)
                        @php
                            $attachmentUrl = trim((string) ($document->url ?? ''));
                            if ($attachmentUrl === '' && filled($document->path)) {
                                $attachmentUrl = '/storage/' . str_replace('\\', '/', ltrim($document->path, '/'));
                            }
                            if ($attachmentUrl !== '' && ! preg_match('#^(https?:)?//#i', $attachmentUrl)) {
                                $attachmentUrl = url($attachmentUrl);
                            }
                            $downloadName = $document->original_name ? basename($document->original_name) : 'attachment';
                            $downloadName = preg_replace('/[\r\n\0]/', '', $downloadName) ?: 'attachment';
                            $attachmentMime = strtolower((string) ($document->mime_type ?? ''));
                            $attachmentExt = strtolower(pathinfo((string) ($document->original_name ?? ''), PATHINFO_EXTENSION));
                            $isImageAttachment = str_starts_with($attachmentMime, 'image/')
                                || in_array($attachmentExt, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'], true);
                        @endphp
                        <div class="chat-bubble chat-bubble--attachment">
                            <div class="d-flex align-items-center justify-content-between bg-white border rounded-3 chat-attachment-row">
                                <div class="d-flex align-items-center overflow-hidden">
                                    @if ($attachmentUrl !== '')
                                        <a href="{{ $attachmentUrl }}" target="_blank" rel="noopener noreferrer" class="p-3 d-flex align-items-center border-end text-decoration-none" style="min-width: 56px; min-height: 56px;">
                                            <i class="feather-paperclip"></i>
                                        </a>
                                        <div class="d-block ms-3 overflow-hidden pe-3 py-2">
                                            <a href="{{ $attachmentUrl }}" target="_blank" rel="noopener noreferrer" class="fs-13 fw-semibold text-dark d-block text-truncate text-decoration-none">{{ $document->original_name ?: 'Attachment' }}</a>
                                            <small class="text-muted">{{ $document->mime_type ?: 'File' }}</small>
                                            @if ($isImageAttachment)
                                                <div class="mt-1">
                                                    <button
                                                        type="button"
                                                        class="btn btn-link p-0 fs-11 js-chat-image-preview"
                                                        data-image-url="{{ $attachmentUrl }}"
                                                        data-image-name="{{ $document->original_name ?: 'Attachment' }}"
                                                    >
                                                        Preview image
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="p-3 d-flex align-items-center border-end text-muted" style="min-width: 56px;"><i class="feather-paperclip"></i></span>
                                        <div class="d-block ms-3 overflow-hidden pe-3 py-2">
                                            <span class="fs-13 fw-semibold text-muted d-block text-truncate">{{ $document->original_name ?: 'Attachment' }}</span>
                                            <small class="text-muted">Unavailable</small>
                                        </div>
                                    @endif
                                </div>
                                @if ($attachmentUrl !== '')
                                    <div class="d-flex align-items-center p-3 border-start">
                                        <a
                                            href="{{ $attachmentUrl }}"
                                            download="{{ $downloadName }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="chat-composer-btn chat-composer-btn--attach chat-attachment-download"
                                            title="Download {{ $document->original_name ?: 'attachment' }}"
                                        >
                                            <i class="feather-download"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="chat-empty-thread">
                <div class="text-center text-muted">
                    <div class="chat-empty-thread__icon mx-auto">
                        <i class="feather-message-circle"></i>
                    </div>
                    <h5 class="fw-semibold text-dark mb-2">Start the conversation</h5>
                    <p class="mb-0 fs-13">Send a message below to connect about this listing.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="chat-composer-wrap">
        <div id="chat-send-loader" class="chat-send-loader" role="status" aria-live="polite" aria-hidden="true">
            <div class="d-flex flex-column align-items-center gap-2">
                <div class="spinner-border text-primary" role="presentation"></div>
                <span class="fs-12 text-muted fw-medium">Sending…</span>
            </div>
        </div>
        <div id="chat-form-error" class="alert alert-danger d-none mx-3 mt-2 mb-0 py-2 px-3" role="alert">
            <span class="chat-form-error-text"></span>
        </div>
        <div id="chat-attachment-preview" class="chat-attachment-preview d-none" aria-live="polite">
            <div class="chat-attachment-preview-header d-flex align-items-center justify-content-between gap-2">
                <span class="chat-attachment-label">
                    <i class="feather-paperclip" style="width:14px;height:14px;"></i>
                    <span>Attachments</span>
                    <span id="chat-attachment-count" class="badge bg-primary rounded-pill">0</span>
                </span>
                <button type="button" id="chat-attachments-clear" class="btn btn-link btn-chat-attachments-clear text-danger d-none">
                    Clear all
                </button>
            </div>
            <div id="chat-attachment-chips" class="chat-attachment-chips"></div>
        </div>
        <form id="chat-compose-form" method="POST" action="{{ route('professional.chat.send') }}" enctype="multipart/form-data" class="chat-composer-form d-flex align-items-end bg-white">
            @csrf
            @if ($isProfessional)
                <input type="hidden" name="user_id" value="{{ $selectedUserId ?? 0 }}">
            @else
                <input type="hidden" name="professional_id" value="{{ $selectedProfessionalId ?? 0 }}">
            @endif
            <input type="hidden" name="project_id" value="{{ $selectedProjectId }}">

            <div class="chat-composer-inner flex-grow-1">
                <label class="chat-composer-btn chat-composer-btn--attach mb-0" for="chat-attachments" data-bs-toggle="tooltip" title="Attach files">
                    <i class="feather-paperclip"></i>
                </label>
                <input id="chat-attachments" type="file" name="attachments[]" class="d-none" multiple>
                <textarea
                    id="chat-message-input"
                    class="form-control chat-message-input flex-grow-1"
                    name="message"
                    rows="1"
                    placeholder="Write a message…"
                    autocomplete="off"
                >{{ old('message') }}</textarea>
                <button type="submit" id="chat-send-btn" class="chat-composer-btn chat-composer-btn--send" data-bs-toggle="tooltip" title="Send message">
                    <i class="feather-send"></i>
                </button>
            </div>
        </form>
    </div>
@else
    <div class="chat-empty-panel">
        <div class="text-center" style="max-width: 420px;">
            <div class="chat-empty-thread__icon mx-auto">
                <i class="feather-message-square"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Select a conversation</h4>
            <p class="text-muted mb-0 fs-13">Pick a conversation from the inbox to read messages and send a reply.</p>
        </div>
    </div>
@endif
