@extends('professional.dashboard.layouts.app')

@php
    use Illuminate\Support\Str;

    $isProfessional = $isProfessional ?? true;
@endphp

@section('title', 'MagnateHub || Chats')
@section('page_title', 'Chats')
@section('page_summary', 'Review conversations, inspect listing enquiries, and reply from the professional workspace.')

@section('styles')
@include('professional.dashboard.partials.chat-styles')
@endsection

@section('content')
<main class="nxl-container apps-container apps-chat">

    <div class="nxl-content without-header nxl-full-content">


        <div class="main-content d-flex apps-chat-layout">
            <div class="content-sidebar content-sidebar-xl" data-scrollbar-target="#psScrollbarInit">
                <div class="content-sidebar-header bg-white sticky-top hstack justify-content-between">
                    <div>
                        <h4 class="fw-bolder mb-0">Inbox</h4>
                        <div class="fs-12 text-muted mt-1">{{ $conversations->count() }} conversation{{ $conversations->count() === 1 ? '' : 's' }}</div>
                    </div>
                    <a href="javascript:void(0);" class="app-sidebar-close-trigger d-flex">
                        <i class="feather-x"></i>
                    </a>
                </div>

                <div class="content-sidebar-body">
                    <div class="chat-inbox-search-wrap position-relative">
                        <i class="feather-search chat-inbox-search-icon"></i>
                        <input id="chat-search-input" type="search" class="form-control chat-inbox-search w-100" placeholder="Search by name or listing…" autocomplete="off">
                    </div>

                    <div class="content-sidebar-items" id="chat-conversation-list">
                        @forelse ($conversations as $conversation)
                            @php
                                $conversationProject = $conversation->project;
                                $listingName = $conversationProject?->name ?: 'Listing enquiry';
                                $listingAvatar = optional($conversationProject?->thumbnail)->url;
                                if (blank($listingAvatar) && filled($conversationProject?->card)) {
                                    $listingAvatar = $conversationProject->card;
                                }
                                $counterpartyUser = $isProfessional ? $conversation->user : $conversation->professional;
                                $counterpartyProfileUrl = optional($counterpartyUser?->document('profile')->first())->url;
                                $conversationAvatar = filled($counterpartyProfileUrl)
                                    ? $counterpartyProfileUrl
                                    : $listingAvatar;
                                $conversationAvatarLabel = $isProfessional
                                    ? ($counterpartyUser?->name ?: $listingName)
                                    : $listingName;
                                $counterpartyName = $counterpartyUser?->name
                                    ?: ($isProfessional ? 'Buyer' : 'Seller');
                                $inboxPrimaryTitle = $isProfessional ? $counterpartyName : $listingName;
                                $inboxSecondaryTitle = $isProfessional
                                    ? $listingName
                                    : $counterpartyName;

                                if ($isProfessional) {
                                    $isSelected = ($selectedUserId ?? 0) === (int) $conversation->user_id && $selectedProjectId === (int) $conversation->project_id;
                                    $isUnread = (int) $conversation->send === 1 && (int) $conversation->status === 0;
                                    $routeParams = ['user_id' => $conversation->user_id, 'project_id' => $conversation->project_id];
                                } else {
                                    $isSelected = ($selectedProfessionalId ?? 0) === (int) $conversation->professional_id && $selectedProjectId === (int) $conversation->project_id;
                                    $isUnread = (int) $conversation->send === 0 && (int) $conversation->status === 0;
                                    $routeParams = ['user_id' => $conversation->professional_id, 'project_id' => $conversation->project_id];
                                }
                                $preview = Str::limit($conversation->message ?: 'Open conversation', 80);
                            @endphp
                            <a
                                href="{{ route('professional.chat', $routeParams) }}"
                                data-search="{{ strtolower(trim(($inboxPrimaryTitle ?? '') . ' ' . ($inboxSecondaryTitle ?? '') . ' ' . ($preview ?? ''))) }}"
                                class="chat-inbox-item single-item text-decoration-none {{ $isSelected ? 'chat-item-active' : '' }} {{ $isUnread ? 'chat-inbox-item--unread' : '' }}"
                            >
                                <div class="chat-inbox-item__avatar">
                                    @if ($conversationAvatar)
                                        <div class="avatar-image">
                                            <img src="{{ $conversationAvatar }}" alt="{{ $inboxPrimaryTitle }}">
                                        </div>
                                    @else
                                        <div class="avatar-text bg-primary text-white">{{ strtoupper(Str::substr($conversationAvatarLabel, 0, 1)) }}</div>
                                    @endif
                                </div>
                                <div class="chat-inbox-item__body">
                                    <div class="chat-inbox-item__top">
                                        <span class="chat-inbox-item__title">{{ $inboxPrimaryTitle }}</span>
                                        <span class="chat-inbox-item__time">{{ optional($conversation->created_at)->diffForHumans() }}</span>
                                    </div>
                                    <div class="chat-inbox-item__subtitle">
                                        <i class="feather-{{ $isProfessional ? 'briefcase' : 'user' }}"></i>
                                        <span class="text-truncate">{{ $inboxSecondaryTitle }}</span>
                                    </div>
                                    <p class="chat-inbox-item__preview">{{ $preview }}</p>
                                </div>
                                @if ($isUnread)
                                    <span class="chat-inbox-item__badge" aria-label="Unread">New</span>
                                @endif
                            </a>
                        @empty
                            <div class="chat-inbox-empty" id="chat-empty-state">
                                <i class="feather-inbox d-block"></i>
                                <div class="fw-semibold mb-1">No conversations yet</div>
                                <div class="fs-12">@if ($isProfessional)When a buyer enquires on your listing, it will appear here.@elseYour conversations about listings will appear here.@endif</div>
                            </div>
                        @endforelse
                        <div class="p-4 text-center text-muted d-none" id="chat-no-search-result">
                            No conversations match your search.
                        </div>
                    </div>
                </div>
            </div>

            <div id="chat-content-panel" class="content-area chat-content-panel" data-scrollbar-target="#psScrollbarInit" data-chat-path="{{ parse_url(route('professional.chat'), PHP_URL_PATH) }}">
                <div id="chat-panel-load-error" class="chat-panel-load-error alert alert-danger d-none mx-3 mt-2 mb-0 py-2 px-3" role="alert"></div>
                <div id="chat-panel-loader" class="chat-panel-loader" role="status" aria-live="polite" aria-hidden="true">
                    <div class="spinner-border text-primary" role="presentation"></div>
                    <span class="fs-13 text-muted fw-medium">Loading conversation…</span>
                </div>
                <div id="chat-panel-body">
                    @include('professional.dashboard.partials.chat-panel-body', [
                        'conversations' => $conversations,
                        'messages' => $messages,
                        'selectedConversation' => $selectedConversation,
                        'selectedUserId' => $selectedUserId ?? 0,
                        'selectedProjectId' => $selectedProjectId ?? 0,
                        'selectedProfessionalId' => $selectedProfessionalId ?? 0,
                        'isProfessional' => $isProfessional,
                    ])
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="chat-image-preview-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-truncate" id="chat-image-preview-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center bg-light">
                <img id="chat-image-preview-src" src="" alt="Image preview" class="img-fluid rounded-3 border">
            </div>
        </div>
    </div>
</div>

@include('professional.dashboard.partials.footer')
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function scrollChatToBottom() {
            var container = document.getElementById('chat-message-list');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }

        function resizeChatMessageInput(textarea) {
            if (!textarea) {
                return;
            }
            textarea.style.height = 'auto';
            var maxHeight = 180;
            var nextHeight = Math.min(textarea.scrollHeight, maxHeight);
            textarea.style.height = nextHeight + 'px';
            textarea.style.overflowY = textarea.scrollHeight > maxHeight ? 'auto' : 'hidden';
        }

        function bindComposerInputBehavior(scope) {
            var root = scope || document;
            var textarea = root.querySelector('#chat-message-input');
            if (!textarea || textarea.dataset.autosizeBound === '1') {
                return;
            }

            textarea.dataset.autosizeBound = '1';
            resizeChatMessageInput(textarea);
            textarea.addEventListener('input', function () {
                resizeChatMessageInput(textarea);
            });
        }

        var chatImagePreviewModalEl = document.getElementById('chat-image-preview-modal');
        var chatImagePreviewTitleEl = document.getElementById('chat-image-preview-title');
        var chatImagePreviewSrcEl = document.getElementById('chat-image-preview-src');
        var chatImagePreviewModal = (chatImagePreviewModalEl && window.bootstrap && window.bootstrap.Modal)
            ? window.bootstrap.Modal.getOrCreateInstance(chatImagePreviewModalEl)
            : null;
        var localPreviewObjectUrl = null;

        function releaseLocalPreviewObjectUrl() {
            if (localPreviewObjectUrl) {
                URL.revokeObjectURL(localPreviewObjectUrl);
                localPreviewObjectUrl = null;
            }
        }

        function openChatImagePreview(src, title, isObjectUrl) {
            if (!chatImagePreviewModal || !chatImagePreviewSrcEl || !src) {
                return;
            }
            releaseLocalPreviewObjectUrl();
            if (isObjectUrl) {
                localPreviewObjectUrl = src;
            }
            chatImagePreviewSrcEl.src = src;
            chatImagePreviewSrcEl.alt = title || 'Image preview';
            if (chatImagePreviewTitleEl) {
                chatImagePreviewTitleEl.textContent = title || 'Image Preview';
            }
            chatImagePreviewModal.show();
        }

        if (chatImagePreviewModalEl) {
            chatImagePreviewModalEl.addEventListener('hidden.bs.modal', function () {
                if (chatImagePreviewSrcEl) {
                    chatImagePreviewSrcEl.src = '';
                }
                releaseLocalPreviewObjectUrl();
            });
        }

        scrollChatToBottom();
        bindComposerInputBehavior(document);

        var panelRoot = document.getElementById('chat-content-panel');
        var panelBody = document.getElementById('chat-panel-body');
        var panelLoader = document.getElementById('chat-panel-loader');
        var panelLoadError = document.getElementById('chat-panel-load-error');
        var conversationList = document.getElementById('chat-conversation-list');
        var realtimePollingTimer = null;
        var realtimeRefreshInFlight = false;

        function setPanelLoading(active) {
            if (panelLoader) {
                panelLoader.classList.toggle('is-active', active);
                panelLoader.setAttribute('aria-hidden', active ? 'false' : 'true');
            }
        }

        function hidePanelLoadError() {
            if (panelLoadError) {
                panelLoadError.textContent = '';
                panelLoadError.classList.add('d-none');
            }
        }

        function showPanelLoadError(msg) {
            if (panelLoadError) {
                panelLoadError.textContent = msg;
                panelLoadError.classList.remove('d-none');
            }
        }

        function highlightConversationByUrl(url) {
            if (!conversationList) {
                return;
            }
            try {
                var target = new URL(url, window.location.origin);
                var uid = target.searchParams.get('user_id') || '';
                var pid = target.searchParams.get('project_id') || '';
                conversationList.querySelectorAll('a.single-item').forEach(function (a) {
                    var au = new URL(a.getAttribute('href'), window.location.origin);
                    var match = (au.searchParams.get('user_id') || '') === uid && (au.searchParams.get('project_id') || '') === pid;
                    a.classList.toggle('chat-item-active', match);
                });
            } catch (e) {}
        }

        function findConversationItemByUrl(url) {
            if (!conversationList) {
                return null;
            }
            try {
                var target = new URL(url, window.location.origin);
                var uid = target.searchParams.get('user_id') || '';
                var pid = target.searchParams.get('project_id') || '';
                var matched = null;
                conversationList.querySelectorAll('a.single-item').forEach(function (a) {
                    if (matched) {
                        return;
                    }
                    var au = new URL(a.getAttribute('href'), window.location.origin);
                    if ((au.searchParams.get('user_id') || '') === uid && (au.searchParams.get('project_id') || '') === pid) {
                        matched = a;
                    }
                });
                return matched;
            } catch (e) {
                return null;
            }
        }

        function updateConversationListPreview(url, previewText) {
            var item = findConversationItemByUrl(url);
            if (!item) {
                return;
            }

            var safePreview = (previewText || '').trim();
            if (!safePreview) {
                safePreview = 'Open conversation';
            }

            var previewNode = item.querySelector('.chat-inbox-item__preview');
            if (previewNode) {
                previewNode.textContent = safePreview;
            }

            var timeNode = item.querySelector('.chat-inbox-item__time');
            if (timeNode) {
                timeNode.textContent = 'Just now';
            }

            var titleNode = item.querySelector('.chat-inbox-item__title');
            var subtitleNode = item.querySelector('.chat-inbox-item__subtitle span');
            var title = titleNode ? titleNode.textContent.trim() : '';
            var subtitle = subtitleNode ? subtitleNode.textContent.trim() : '';
            item.setAttribute('data-search', (title + ' ' + subtitle + ' ' + safePreview).toLowerCase().trim());
            item.classList.add('chat-inbox-item--unread');
        }

        function loadConversationFromUrl(url, options) {
            options = options || {};
            if (!panelBody) {
                return;
            }
            hidePanelLoadError();
            setPanelLoading(true);
            var fetchUrl = new URL(url, window.location.origin);
            fetchUrl.searchParams.set('panel', '1');

            fetch(fetchUrl.toString(), {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(function (res) {
                    return res.text().then(function (text) {
                        var data = {};
                        if (text) {
                            try {
                                data = JSON.parse(text);
                            } catch (err) {
                                data = { _parseError: true };
                            }
                        }
                        return { ok: res.ok, status: res.status, data: data };
                    });
                })
                .then(function (r) {
                    setPanelLoading(false);
                    if (r.ok && r.data && r.data.success && r.data.html) {
                        panelBody.innerHTML = r.data.html;
                        scrollChatToBottom();
                        bindComposerInputBehavior(panelBody);
                        highlightConversationByUrl(url);
                        ensureRealtimePolling();
                        if (!options.suppressHistory && window.history && window.history.pushState) {
                            var cleanUrl = new URL(url, window.location.origin);
                            cleanUrl.searchParams.delete('panel');
                            window.history.pushState({}, '', cleanUrl.pathname + cleanUrl.search);
                        }
                        return;
                    }
                    var msg = (r.data && r.data.message) ? r.data.message : 'Could not load conversation.';
                    if (r.data && r.data._parseError) {
                        msg = 'Could not load conversation.';
                    }
                    if (r.status === 419) {
                        msg = 'Session expired. Refresh the page.';
                    }
                    showPanelLoadError(msg);
                })
                .catch(function () {
                    setPanelLoading(false);
                    showPanelLoadError('Network error. Check your connection and try again.');
                });
        }

        function shouldPollActiveConversation() {
            if (!panelRoot || !panelBody) {
                return false;
            }
            var messageList = document.getElementById('chat-message-list');
            if (!messageList) {
                return false;
            }
            var url = new URL(window.location.href);
            return Boolean(url.searchParams.get('user_id') && url.searchParams.get('project_id'));
        }

        function refreshActiveConversationMessages() {
            if (!shouldPollActiveConversation() || realtimeRefreshInFlight) {
                return;
            }

            realtimeRefreshInFlight = true;

            var fetchUrl = new URL(window.location.href);
            fetchUrl.searchParams.set('panel', '1');

            fetch(fetchUrl.toString(), {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(function (res) {
                    return res.json();
                })
                .then(function (payload) {
                    if (!payload || !payload.success || !payload.html) {
                        return;
                    }

                    var parserWrap = document.createElement('div');
                    parserWrap.innerHTML = payload.html;

                    var nextMessageList = parserWrap.querySelector('#chat-message-list');
                    var currentMessageList = document.getElementById('chat-message-list');
                    if (!nextMessageList || !currentMessageList) {
                        return;
                    }

                    var nextLastId = String(nextMessageList.dataset.lastMessageId || '0');
                    var currentLastId = String(currentMessageList.dataset.lastMessageId || '0');
                    if (nextLastId === currentLastId) {
                        return;
                    }
                    var nextPreview = String(nextMessageList.dataset.lastMessagePreview || '').trim();

                    var userNearBottom = (currentMessageList.scrollHeight - currentMessageList.scrollTop - currentMessageList.clientHeight) < 120;
                    panelBody.innerHTML = payload.html;
                    bindComposerInputBehavior(panelBody);
                    updateConversationListPreview(window.location.href, nextPreview);

                    if (userNearBottom) {
                        scrollChatToBottom();
                    }
                })
                .catch(function () {
                    // Silent fail for realtime polling; manual chat interactions remain usable.
                })
                .finally(function () {
                    realtimeRefreshInFlight = false;
                });
        }

        function ensureRealtimePolling() {
            if (realtimePollingTimer) {
                window.clearInterval(realtimePollingTimer);
                realtimePollingTimer = null;
            }
            if (!shouldPollActiveConversation()) {
                return;
            }
            realtimePollingTimer = window.setInterval(function () {
                if (document.hidden) {
                    return;
                }
                refreshActiveConversationMessages();
            }, 4000);
        }

        if (conversationList && panelBody && panelRoot) {
            conversationList.addEventListener('click', function (e) {
                var a = e.target.closest && e.target.closest('a.single-item');
                if (!a || !conversationList.contains(a)) {
                    return;
                }
                if (e.defaultPrevented) {
                    return;
                }
                if (e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
                    return;
                }
                var targetHref = a.getAttribute('href');
                if (!targetHref || targetHref.indexOf('javascript:') === 0) {
                    return;
                }
                try {
                    var next = new URL(targetHref, window.location.origin);
                    var cur = new URL(window.location.href);
                    if ((next.searchParams.get('user_id') || '') === (cur.searchParams.get('user_id') || '')
                        && (next.searchParams.get('project_id') || '') === (cur.searchParams.get('project_id') || '')) {
                        e.preventDefault();
                        return;
                    }
                } catch (err) {}
                e.preventDefault();
                loadConversationFromUrl(targetHref);
            });
        }

        window.addEventListener('popstate', function () {
            if (!panelBody || !panelRoot) {
                return;
            }
            var chatPath = panelRoot.getAttribute('data-chat-path') || '';
            if (chatPath && window.location.pathname !== chatPath) {
                return;
            }
            loadConversationFromUrl(window.location.href, { suppressHistory: true });
        });

        window.addEventListener('beforeunload', function () {
            if (realtimePollingTimer) {
                window.clearInterval(realtimePollingTimer);
            }
        });
        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) {
                refreshActiveConversationMessages();
            }
        });
        window.addEventListener('focus', function () {
            refreshActiveConversationMessages();
        });

        var searchInput = document.getElementById('chat-search-input');
        var noSearchResult = document.getElementById('chat-no-search-result');
        var emptyState = document.getElementById('chat-empty-state');
        if (searchInput && conversationList) {
            var items = conversationList.querySelectorAll('a.single-item');
            searchInput.addEventListener('input', function () {
                var q = (searchInput.value || '').toLowerCase().trim();
                var visibleCount = 0;
                items.forEach(function (item) {
                    var hay = (item.getAttribute('data-search') || '').toLowerCase();
                    var show = q === '' || hay.indexOf(q) !== -1;
                    item.classList.toggle('d-none', !show);
                    if (show) {
                        visibleCount++;
                    }
                });
                if (noSearchResult) {
                    noSearchResult.classList.toggle('d-none', q === '' || visibleCount > 0);
                }
                if (emptyState && items.length > 0) {
                    emptyState.classList.add('d-none');
                }
            });
        }

        function formatFileSize(bytes) {
            if (!bytes && bytes !== 0) {
                return '';
            }
            if (bytes < 1024) {
                return bytes + ' B';
            }
            if (bytes < 1048576) {
                return (bytes / 1024).toFixed(1) + ' KB';
            }
            return (bytes / 1048576).toFixed(1) + ' MB';
        }

        function fileChipStyle(file) {
            var t = (file.type || '').toLowerCase();
            var n = (file.name || '').toLowerCase();
            var label = 'FILE';
            var cls = 'chat-file-chip-icon--default';
            if (t.indexOf('image/') === 0) {
                label = 'IMG';
                cls = 'chat-file-chip-icon--image';
            } else if (t === 'application/pdf' || n.endsWith('.pdf')) {
                label = 'PDF';
                cls = 'chat-file-chip-icon--pdf';
            } else if (t.indexOf('word') !== -1 || n.endsWith('.doc') || n.endsWith('.docx')) {
                label = 'DOC';
                cls = 'chat-file-chip-icon--doc';
            } else {
                var dot = n.lastIndexOf('.');
                if (dot !== -1 && dot < n.length - 1) {
                    label = n.slice(dot + 1, dot + 5).toUpperCase();
                    if (label.length > 4) {
                        label = label.slice(0, 4);
                    }
                }
            }
            return { cls: cls, label: label };
        }

        function getFileInput() {
            return document.getElementById('chat-attachments');
        }

        function rebuildFileInputWithoutIndex(fileInput, removedIndex) {
            if (!fileInput) {
                return;
            }
            var dt = new DataTransfer();
            var files = fileInput.files;
            for (var i = 0; i < files.length; i++) {
                if (i !== removedIndex) {
                    dt.items.add(files[i]);
                }
            }
            fileInput.files = dt.files;
        }

        function clearAllAttachments() {
            var fileInput = getFileInput();
            if (fileInput) {
                fileInput.value = '';
            }
            renderAttachmentPreview();
        }

        function inferFileNameFromUrl(url) {
            try {
                var cleanUrl = (url || '').split('?')[0].split('#')[0];
                var parts = cleanUrl.split('/');
                return decodeURIComponent(parts[parts.length - 1] || 'attachment');
            } catch (e) {
                return 'attachment';
            }
        }

        function extractFileNameFromContentDisposition(headerValue) {
            if (!headerValue) {
                return '';
            }
            var utfMatch = headerValue.match(/filename\*=UTF-8''([^;]+)/i);
            if (utfMatch && utfMatch[1]) {
                try {
                    return decodeURIComponent(utfMatch[1].trim());
                } catch (e) {}
            }
            var basicMatch = headerValue.match(/filename="?([^"]+)"?/i);
            return basicMatch && basicMatch[1] ? basicMatch[1].trim() : '';
        }

        function forceDownloadFromAnchor(anchorEl) {
            var href = anchorEl ? anchorEl.getAttribute('href') : '';
            if (!href) {
                return;
            }
            var preferredName = (anchorEl.getAttribute('download') || '').trim();

            fetch(href, { credentials: 'same-origin' })
                .then(function (res) {
                    if (!res.ok) {
                        throw new Error('Download request failed');
                    }
                    return Promise.all([
                        res.blob(),
                        Promise.resolve(res.headers.get('content-disposition') || ''),
                    ]);
                })
                .then(function (payload) {
                    var blob = payload[0];
                    var contentDisposition = payload[1];
                    var serverName = extractFileNameFromContentDisposition(contentDisposition);
                    var fileName = preferredName || serverName || inferFileNameFromUrl(href);
                    var blobUrl = URL.createObjectURL(blob);
                    var tempLink = document.createElement('a');
                    tempLink.href = blobUrl;
                    tempLink.download = fileName || 'attachment';
                    document.body.appendChild(tempLink);
                    tempLink.click();
                    document.body.removeChild(tempLink);
                    URL.revokeObjectURL(blobUrl);
                })
                .catch(function () {
                    // Fallback if blob download is blocked by server/cors.
                    window.open(href, '_blank', 'noopener,noreferrer');
                });
        }

        function renderAttachmentPreview() {
            var fileInput = getFileInput();
            var attachmentPreview = document.getElementById('chat-attachment-preview');
            var attachmentChips = document.getElementById('chat-attachment-chips');
            var attachmentCount = document.getElementById('chat-attachment-count');
            var attachmentsClearBtn = document.getElementById('chat-attachments-clear');
            if (!fileInput || !attachmentPreview || !attachmentChips) {
                return;
            }
            attachmentChips.innerHTML = '';
            var files = fileInput.files;
            var n = files ? files.length : 0;

            if (attachmentCount) {
                attachmentCount.textContent = String(n);
            }
            if (attachmentsClearBtn) {
                attachmentsClearBtn.classList.toggle('d-none', n === 0);
            }

            if (n === 0) {
                attachmentPreview.classList.add('d-none');
                return;
            }

            attachmentPreview.classList.remove('d-none');

            for (var i = 0; i < n; i++) {
                (function (index) {
                    var file = files[index];
                    var spec = fileChipStyle(file);
                    var chip = document.createElement('div');
                    chip.className = 'chat-file-chip';
                    chip.setAttribute('role', 'group');
                    chip.setAttribute('aria-label', file.name);

                    var iconWrap = document.createElement('div');
                    iconWrap.className = 'chat-file-chip-icon ' + spec.cls;
                    iconWrap.textContent = spec.label;

                    var body = document.createElement('div');
                    body.className = 'chat-file-chip-body';
                    var nameEl = document.createElement('div');
                    nameEl.className = 'chat-file-chip-name';
                    nameEl.textContent = file.name;
                    nameEl.title = file.name;
                    var meta = document.createElement('div');
                    meta.className = 'chat-file-chip-meta';
                    meta.textContent = formatFileSize(file.size);
                    body.appendChild(nameEl);
                    body.appendChild(meta);

                    var fileType = (file.type || '').toLowerCase();
                    if (fileType.indexOf('image/') === 0) {
                        var previewBtn = document.createElement('button');
                        previewBtn.type = 'button';
                        previewBtn.className = 'btn btn-link p-0 mt-1 text-primary fs-11 text-start';
                        previewBtn.textContent = 'Preview image';
                        previewBtn.addEventListener('click', function () {
                            var localSrc = URL.createObjectURL(file);
                            openChatImagePreview(localSrc, file.name || 'Attachment', true);
                        });
                        body.appendChild(previewBtn);
                    }

                    var removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'chat-file-chip-remove';
                    removeBtn.setAttribute('aria-label', 'Remove ' + file.name);
                    removeBtn.appendChild(document.createTextNode('\u00D7'));
                    removeBtn.addEventListener('click', function () {
                        rebuildFileInputWithoutIndex(getFileInput(), index);
                        renderAttachmentPreview();
                    });

                    chip.appendChild(iconWrap);
                    chip.appendChild(body);
                    chip.appendChild(removeBtn);
                    attachmentChips.appendChild(chip);
                })(i);
            }
        }

        if (panelRoot) {
            panelRoot.addEventListener('change', function (e) {
                if (e.target && e.target.id === 'chat-attachments') {
                    renderAttachmentPreview();
                }
            });
            panelRoot.addEventListener('click', function (e) {
                var downloadLink = e.target.closest && e.target.closest('.chat-attachment-download');
                if (downloadLink && panelRoot.contains(downloadLink)) {
                    e.preventDefault();
                    e.stopPropagation();
                    forceDownloadFromAnchor(downloadLink);
                    return;
                }

                var clearBtn = e.target.closest && e.target.closest('#chat-attachments-clear');
                if (clearBtn && panelRoot.contains(clearBtn)) {
                    e.preventDefault();
                    clearAllAttachments();
                }

                var imagePreviewBtn = e.target.closest && e.target.closest('.js-chat-image-preview');
                if (imagePreviewBtn && panelRoot.contains(imagePreviewBtn)) {
                    e.preventDefault();
                    openChatImagePreview(
                        imagePreviewBtn.getAttribute('data-image-url'),
                        imagePreviewBtn.getAttribute('data-image-name') || 'Attachment',
                        false
                    );
                }
            });
            panelRoot.addEventListener('submit', function (e) {
                var form = e.target && typeof e.target.closest === 'function' ? e.target.closest('#chat-compose-form') : null;
                if (!form || !panelRoot.contains(form) || form.id !== 'chat-compose-form') {
                    return;
                }
                e.preventDefault();

                var sendLoader = document.getElementById('chat-send-loader');
                var sendBtn = document.getElementById('chat-send-btn');
                var formErrorBox = document.getElementById('chat-form-error');
                var formErrorText = formErrorBox ? formErrorBox.querySelector('.chat-form-error-text') : null;

                function setChatSending(sending) {
                    if (sendLoader) {
                        sendLoader.classList.toggle('is-active', sending);
                        sendLoader.setAttribute('aria-hidden', sending ? 'false' : 'true');
                    }
                    if (sendBtn) {
                        sendBtn.disabled = sending;
                    }
                }

                function hideChatFormError() {
                    if (formErrorBox && formErrorText) {
                        formErrorText.textContent = '';
                        formErrorBox.classList.add('d-none');
                    }
                }

                function showChatFormError(msg) {
                    if (formErrorBox && formErrorText) {
                        formErrorText.textContent = msg;
                        formErrorBox.classList.remove('d-none');
                    }
                }

                hideChatFormError();

                var tokenInput = form.querySelector('input[name="_token"]');
                var token = tokenInput ? tokenInput.value : '';

                setChatSending(true);

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    }
                })
                    .then(function (res) {
                        return res.text().then(function (text) {
                            var data = {};
                            if (text) {
                                try {
                                    data = JSON.parse(text);
                                } catch (err) {
                                    data = { _parseError: true, _raw: text };
                                }
                            }
                            return { ok: res.ok, status: res.status, data: data };
                        });
                    })
                    .then(function (r) {
                        if (r.ok && r.data && r.data.success && r.data.redirect) {
                            window.location.href = r.data.redirect;
                            return;
                        }

                        setChatSending(false);

                        var msg = (r.data && r.data.message) ? r.data.message : 'Could not send message.';
                        if (r.data && r.data.errors) {
                            var parts = [];
                            Object.keys(r.data.errors).forEach(function (k) {
                                (r.data.errors[k] || []).forEach(function (x) {
                                    parts.push(x);
                                });
                            });
                            if (parts.length) {
                                msg = parts.join(' ');
                            }
                        }
                        if (r.data && r.data._parseError && r.status >= 500) {
                            msg = 'Something went wrong. Please refresh and try again.';
                        }
                        if (r.status === 419) {
                            msg = 'Session expired. Refresh the page and try again.';
                        }
                        showChatFormError(msg);
                    })
                    .catch(function () {
                        setChatSending(false);
                        showChatFormError('Network error. Check your connection and try again.');
                    });
            });
        }

        ensureRealtimePolling();
    });
</script>
@endsection


