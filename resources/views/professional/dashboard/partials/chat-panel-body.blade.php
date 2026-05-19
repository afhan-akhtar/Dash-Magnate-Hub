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
    <div class="content-area-header sticky-top flex-shrink-0 bg-white">
        <div class="page-header-left hstack gap-4">
            <a href="javascript:void(0);" class="app-sidebar-open-trigger">
                <i class="feather-align-left fs-20"></i>
            </a>
            <div class="d-flex align-items-center gap-3">
                @if ($headerAvatar)
                    <div class="avatar-image">
                        <img src="{{ $headerAvatar }}" alt="{{ $headerLabel }}">
                    </div>
                @else
                    <div class="avatar-text bg-primary text-white rounded-circle">{{ strtoupper(Str::substr($headerLabel, 0, 1)) }}</div>
                @endif
                <div>
                    <div class="fw-bold d-flex align-items-center">{{ $headerLabel }}</div>
                    <div class="fs-12 text-muted mt-1">
                        {{ $listingName }}
                        @if ($selectedProject)
                            <span class="mx-2">•</span>
                            Listing #{{ $selectedProject->id }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="page-header-right ms-auto">
            <div class="d-flex align-items-center justify-content-center gap-2">
                @if(false)
                <a href="{{ route('professional.listings.index') }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Open Listings">
                    <i class="feather-briefcase"></i>
                </a>
                <a href="{{ route('professional.settings') }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Profile Settings">
                    <i class="feather-settings"></i>
                </a>
                @endif
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

            <div class="single-chat-item mb-5">
                <div class="d-flex {{ $isOutgoing ? 'flex-row-reverse' : '' }} align-items-center gap-3 mb-3">
                    @if ($messageAvatar)
                        <div class="avatar-image">
                            <img src="{{ $messageAvatar }}" alt="{{ $messageUserName }}">
                        </div>
                    @else
                        <div class="avatar-text {{ $isOutgoing ? 'bg-dark text-white' : 'bg-primary text-white' }} rounded-circle">
                            {{ strtoupper(Str::substr($messageUserName, 0, 1)) }}
                        </div>
                    @endif
                    <div class="d-flex {{ $isOutgoing ? 'flex-row-reverse' : '' }} align-items-center gap-2">
                        <span class="fw-semibold text-dark">{{ $messageUserName }}</span>
                        <span class="wd-5 ht-5 bg-gray-400 rounded-circle"></span>
                        <span class="fs-11 text-muted">{{ optional($message->created_at)->format('d M Y, h:i A') }}</span>
                    </div>
                </div>

                <div class="wd-500 p-3 rounded-5 bg-gray-200 {{ $isOutgoing ? 'ms-auto' : '' }}">
                    @if (filled($message->message))
                        <p class="py-2 px-3 rounded-5 bg-white mb-{{ $message->documents->isNotEmpty() ? '3' : '0' }}">{{ $message->message }}</p>
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
                        <div class="mb-{{ $loop->last ? '0' : '3' }} d-flex align-items-center justify-content-between bg-white border rounded-3 chat-attachment-row">
                            <div class="d-flex align-items-center overflow-hidden">
                                @if ($attachmentUrl !== '')
                                    <a href="{{ $attachmentUrl }}" target="_blank" rel="noopener noreferrer" class="p-3 d-flex align-items-center border-end wd-70 ht-70 text-decoration-none" download>
                                        <i class="feather-paperclip"></i>
                                    </a>
                                    <div class="d-block ms-3 overflow-hidden pe-3">
                                        <a href="{{ $attachmentUrl }}" target="_blank" rel="noopener noreferrer" class="fs-13 fw-700 text-dark d-block text-truncate">{{ $document->original_name ?: 'Attachment' }}</a>
                                        <small class="fw-300 text-dark text-uppercase">{{ $document->mime_type ?: 'File' }}</small>
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
                                    <span class="p-3 d-flex align-items-center border-end wd-70 ht-70 text-muted" title="File unavailable"><i class="feather-paperclip"></i></span>
                                    <div class="d-block ms-3 overflow-hidden pe-3">
                                        <span class="fs-13 fw-700 text-muted d-block text-truncate">{{ $document->original_name ?: 'Attachment' }}</span>
                                        <small class="fw-300 text-muted text-uppercase">Unavailable</small>
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex align-items-center p-3 border-start">
                                @if ($attachmentUrl !== '')
                                    <a
                                        href="{{ $attachmentUrl }}"
                                        download="{{ $downloadName }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="avatar-text file-download chat-attachment-download"
                                        title="Download {{ $document->original_name ?: 'attachment' }}"
                                        style="pointer-events: auto;"
                                    >
                                        <i class="feather-download"></i>
                                    </a>
                                @else
                                    <span class="avatar-text file-download text-muted" style="opacity: 0.5;" title="File unavailable"><i class="feather-download"></i></span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="h-100 d-flex align-items-center justify-content-center py-5">
                <div class="text-center text-muted">
                    <i class="feather-message-square fs-1 d-block mb-3"></i>
                    No messages yet. Start the conversation below.
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
        <form id="chat-compose-form" method="POST" action="{{ route('professional.chat.send') }}" enctype="multipart/form-data" class="chat-composer-form d-flex align-items-stretch justify-content-between bg-white">
            @csrf
            @if ($isProfessional)
                <input type="hidden" name="user_id" value="{{ $selectedUserId ?? 0 }}">
            @else
                <input type="hidden" name="professional_id" value="{{ $selectedProfessionalId ?? 0 }}">
            @endif
            <input type="hidden" name="project_id" value="{{ $selectedProjectId }}">

            <div class="d-flex align-items-stretch">
                <label class="border-end border-gray-5 mb-0 c-pointer d-flex align-items-center" for="chat-attachments">
                    <span class="wd-60 d-flex align-items-center justify-content-center" data-bs-toggle="tooltip" title="Attach files (multiple)" style="min-height: 59px">
                        <i class="feather-paperclip"></i>
                    </span>
                </label>
                <input id="chat-attachments" type="file" name="attachments[]" class="d-none" multiple>
            </div>

            <textarea
                id="chat-message-input"
                class="form-control border-0 chat-message-input"
                name="message"
                rows="1"
                placeholder="Type your message here..."
                autocomplete="off"
                style="padding-top: 20px;"
            >{{ old('message') }}</textarea>

            <button type="submit" id="chat-send-btn" class="border-start border-gray-5 send-message bg-transparent border-0">
                <span class="wd-60 d-flex align-items-center justify-content-center" data-bs-toggle="tooltip" title="Send Message" style="min-height: 59px">
                    <i class="feather-send"></i>
                </span>
            </button>
        </form>
    </div>
@else
    <div class="h-100 d-flex align-items-center justify-content-center p-5 bg-white">
        <div class="text-center" style="max-width: 420px;">
            <div class="avatar-text avatar-xxl bg-soft-primary text-primary mx-auto mb-4">
                <i class="feather-message-square"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">No chat selected</h4>
            <p class="text-muted mb-0">Choose a conversation from the left to review listing enquiries and reply from your professional dashboard.</p>
        </div>
    </div>
@endif
