<style>
    .apps-chat {
        --chat-brand: #560ce3;
        --chat-brand-soft: rgba(86, 12, 227, 0.1);
        --chat-brand-hover: #4a0bc4;
        --chat-surface: #ffffff;
        --chat-surface-muted: #f4f6fb;
        --chat-border: #e8ecf3;
        --chat-text: #0f172a;
        --chat-text-muted: #64748b;
        --chat-incoming: #ffffff;
        --chat-outgoing: #ede9fe;
        --chat-outgoing-border: #ddd6fe;
        --chat-radius: 14px;
        --chat-bubble-radius: 18px;
    }

    .apps-chat .main-content.apps-chat-layout {
        align-items: stretch;
        min-height: calc(100vh - 140px);
        background: var(--chat-surface-muted);
    }

    .apps-chat .content-sidebar {
        background: var(--chat-surface);
        border-right: 1px solid var(--chat-border);
    }

    .apps-chat .content-sidebar-header {
        padding: 1.25rem 1.25rem 1rem;
        border-bottom: 1px solid var(--chat-border);
    }

    .apps-chat .chat-inbox-search-wrap {
        padding: 0.85rem 1rem;
        background: var(--chat-surface);
        border-bottom: 1px solid var(--chat-border);
    }

    .apps-chat .chat-inbox-search {
        border-radius: 999px;
        border: 1px solid var(--chat-border);
        background: var(--chat-surface-muted);
        padding-left: 2.5rem;
        font-size: 0.875rem;
    }

    .apps-chat .chat-inbox-search:focus {
        border-color: var(--chat-brand);
        box-shadow: 0 0 0 3px var(--chat-brand-soft);
        background: var(--chat-surface);
    }

    .apps-chat .chat-inbox-search-icon {
        position: absolute;
        left: 1.6rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--chat-text-muted);
        pointer-events: none;
        z-index: 2;
    }

    /* Inbox list items */
    .apps-chat .chat-inbox-item {
        display: flex;
        gap: 0.85rem;
        padding: 0.95rem 1rem;
        text-decoration: none;
        border-bottom: 1px solid var(--chat-border);
        transition: background-color 0.15s ease, border-color 0.15s ease;
        position: relative;
    }

    .apps-chat .chat-inbox-item:hover {
        background: var(--chat-surface-muted);
    }

    .apps-chat .chat-inbox-item.chat-item-active {
        background: linear-gradient(90deg, rgba(86, 12, 227, 0.12) 0%, rgba(86, 12, 227, 0.04) 100%);
        border-left: 3px solid var(--chat-brand);
        padding-left: calc(1rem - 3px);
    }

    .apps-chat .chat-inbox-item.chat-item-active::before {
        content: none;
    }

    .apps-chat .chat-inbox-item__avatar {
        flex-shrink: 0;
        position: relative;
    }

    .apps-chat .chat-inbox-item__body {
        flex: 1;
        min-width: 0;
    }

    .apps-chat .chat-inbox-item__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.5rem;
        margin-bottom: 0.15rem;
    }

    .apps-chat .chat-inbox-item__title {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--chat-text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .apps-chat .chat-inbox-item__time {
        font-size: 0.6875rem;
        font-weight: 500;
        color: var(--chat-text-muted);
        white-space: nowrap;
        flex-shrink: 0;
        text-transform: none;
        letter-spacing: 0;
    }

    .apps-chat .chat-inbox-item__subtitle {
        font-size: 0.75rem;
        color: var(--chat-text-muted);
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 0;
    }

    .apps-chat .chat-inbox-item__subtitle > i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 14px;
        width: 14px !important;
        height: 14px !important;
        min-width: 14px;
        margin: 0;
    }

    .apps-chat .chat-inbox-item__subtitle > i svg {
        width: 14px !important;
        height: 14px !important;
        stroke-width: 2;
    }

    .apps-chat .chat-inbox-item__subtitle > span {
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        line-height: 1.35;
    }

    .apps-chat .chat-inbox-item__preview {
        font-size: 0.8125rem;
        color: var(--chat-text-muted);
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.45;
    }

    .apps-chat .chat-inbox-item--unread .chat-inbox-item__title,
    .apps-chat .chat-inbox-item--unread .chat-inbox-item__preview {
        color: var(--chat-text);
        font-weight: 600;
    }

    .apps-chat .chat-inbox-item__badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1.25rem;
        height: 1.25rem;
        padding: 0 0.4rem;
        border-radius: 999px;
        font-size: 0.625rem;
        font-weight: 700;
        background: var(--chat-brand);
        color: #fff;
        margin-top: 0.15rem;
        flex-shrink: 0;
    }

    .apps-chat .chat-inbox-empty {
        padding: 2.5rem 1.5rem;
        text-align: center;
        color: var(--chat-text-muted);
    }

    .apps-chat .chat-inbox-empty i {
        font-size: 2rem;
        opacity: 0.45;
        margin-bottom: 0.75rem;
    }

    /* Avatars */
    .apps-chat .avatar-image,
    .apps-chat .chat-inbox-item .avatar-image,
    .apps-chat .single-chat-item .avatar-image,
    .apps-chat .chat-thread-header .avatar-image {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 50% !important;
        overflow: hidden !important;
        flex-shrink: 0;
    }

    .apps-chat .chat-inbox-item .avatar-image {
        width: 48px;
        height: 48px;
        min-width: 48px;
        min-height: 48px;
    }

    .apps-chat .chat-thread-header .avatar-image {
        width: 44px;
        height: 44px;
        min-width: 44px;
        min-height: 44px;
    }

    .apps-chat .single-chat-item .avatar-image {
        width: 36px;
        height: 36px;
        min-width: 36px;
        min-height: 36px;
    }

    .apps-chat .avatar-image img {
        width: 100% !important;
        height: 100% !important;
        max-width: none !important;
        object-fit: cover !important;
        border-radius: 50% !important;
        display: block;
    }

    .apps-chat .chat-inbox-item .avatar-text,
    .apps-chat .chat-thread-header .avatar-text,
    .apps-chat .single-chat-item .avatar-text:not(.file-download):not(.avatar-xxl) {
        border-radius: 50% !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .apps-chat .chat-inbox-item .avatar-text {
        width: 48px;
        height: 48px;
        font-size: 1rem;
    }

    /* Thread header */
    .apps-chat .chat-thread-header {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--chat-border);
        background: var(--chat-surface);
    }

    .apps-chat .chat-thread-header__name {
        font-size: 1rem;
        font-weight: 700;
        color: var(--chat-text);
        margin: 0;
    }

    .apps-chat .chat-thread-header__context {
        font-size: 0.8125rem;
        color: var(--chat-text-muted);
        margin: 0.15rem 0 0;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.35rem 0.5rem;
        line-height: 1.4;
    }

    .apps-chat .chat-thread-header__context > i,
    .apps-chat .chat-thread-header__context .chat-thread-header__listing-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 14px;
        width: 14px !important;
        height: 14px !important;
        margin: 0 !important;
    }

    .apps-chat .chat-thread-header__context > i svg {
        width: 14px !important;
        height: 14px !important;
    }

    .apps-chat .chat-thread-header__context a {
        color: var(--chat-brand);
        text-decoration: none;
        font-weight: 500;
    }

    .apps-chat .chat-thread-header__context a:hover {
        text-decoration: underline;
    }

    /* Messages area */
    .apps-chat .content-area.chat-content-panel {
        display: flex;
        flex-direction: column;
        min-height: 0;
        flex: 1 1 auto;
        background: var(--chat-surface-muted);
    }

    .apps-chat #chat-panel-body {
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
    }

    .apps-chat .chat-messages-scroll {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        padding: 1.25rem 1.5rem;
        background:
            radial-gradient(circle at 20% 10%, rgba(86, 12, 227, 0.04) 0%, transparent 45%),
            radial-gradient(circle at 80% 90%, rgba(14, 165, 233, 0.04) 0%, transparent 40%),
            var(--chat-surface-muted);
    }

    .apps-chat .single-chat-item {
        margin-bottom: 1.25rem;
        max-width: min(100%, 640px);
    }

    .apps-chat .single-chat-item--outgoing {
        margin-left: auto;
    }

    .apps-chat .single-chat-item--incoming {
        margin-right: auto;
    }

    .apps-chat .chat-message-meta {
        display: flex;
        flex-direction: column;
        gap: 0.1rem;
        min-width: 0;
    }

    .apps-chat .single-chat-item--outgoing .chat-message-meta {
        align-items: flex-end;
        text-align: right;
    }

    .apps-chat .chat-message-meta__name {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--chat-text);
    }

    .apps-chat .chat-message-meta__time {
        font-size: 0.6875rem;
        color: var(--chat-text-muted);
    }

    .apps-chat .chat-bubble-stack {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        align-items: flex-start;
    }

    .apps-chat .single-chat-item--outgoing .chat-bubble-stack {
        align-items: flex-end;
    }

    .apps-chat .chat-bubble {
        max-width: 100%;
        padding: 0.65rem 0.9rem;
        border-radius: var(--chat-bubble-radius);
        font-size: 0.9375rem;
        line-height: 1.5;
        word-break: break-word;
    }

    .apps-chat .chat-bubble--text {
        background: var(--chat-incoming);
        border: 1px solid var(--chat-border);
        color: var(--chat-text);
        border-bottom-left-radius: 6px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .apps-chat .single-chat-item--outgoing .chat-bubble--text {
        background: var(--chat-outgoing);
        border-color: var(--chat-outgoing-border);
        border-bottom-left-radius: var(--chat-bubble-radius);
        border-bottom-right-radius: 6px;
    }

    .apps-chat .chat-bubble--text p {
        margin: 0;
    }

    .apps-chat .chat-attachment-row {
        background: var(--chat-incoming) !important;
        border-radius: 12px !important;
        overflow: hidden;
    }

    .apps-chat .single-chat-item--outgoing .chat-attachment-row {
        background: var(--chat-incoming) !important;
    }

    .apps-chat .chat-empty-thread {
        min-height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .apps-chat .chat-empty-thread__icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--chat-brand-soft);
        color: var(--chat-brand);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 1rem;
    }

    .apps-chat .chat-empty-panel {
        min-height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background: var(--chat-surface-muted);
    }

    /* Composer */
    .apps-chat .chat-composer-wrap {
        flex-shrink: 0;
        background: var(--chat-surface);
        border-top: 1px solid var(--chat-border);
        z-index: 5;
        box-shadow: 0 -8px 24px rgba(15, 23, 42, 0.06);
    }

    .apps-chat .chat-composer-form {
        min-height: 59px;
        padding: 0.5rem 0.65rem;
        gap: 0.35rem;
    }

    .apps-chat .chat-composer-inner {
        flex: 1;
        display: flex;
        align-items: flex-end;
        gap: 0.35rem;
        background: var(--chat-surface-muted);
        border: 1px solid var(--chat-border);
        border-radius: 999px;
        padding: 0.25rem 0.25rem 0.25rem 0.5rem;
    }

    .apps-chat .chat-composer-inner:focus-within {
        border-color: var(--chat-brand);
        box-shadow: 0 0 0 3px var(--chat-brand-soft);
        background: var(--chat-surface);
    }

    .apps-chat .chat-message-input {
        resize: none;
        overflow-y: hidden;
        line-height: 1.45;
        max-height: 120px;
        border: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        padding: 0.55rem 0.35rem !important;
        font-size: 0.9375rem;
    }

    .apps-chat .chat-composer-btn {
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background 0.15s ease, color 0.15s ease;
    }

    .apps-chat .chat-composer-btn--attach {
        background: transparent;
        color: var(--chat-text-muted);
    }

    .apps-chat .chat-composer-btn--attach:hover {
        background: rgba(15, 23, 42, 0.06);
        color: var(--chat-text);
    }

    .apps-chat .chat-composer-btn--send {
        background: var(--chat-brand);
        color: #fff;
    }

    .apps-chat .chat-composer-btn--send:hover {
        background: var(--chat-brand-hover);
        color: #fff;
    }

    .apps-chat .chat-composer-btn--send:disabled {
        opacity: 0.5;
    }

    .apps-chat .chat-send-loader {
        position: absolute;
        inset: 0;
        z-index: 25;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(2px);
    }

    .apps-chat .chat-send-loader.is-active {
        display: flex;
    }

    .apps-chat .chat-attachment-preview {
        border-bottom: 1px solid var(--chat-border);
        background: var(--chat-surface-muted);
    }

    .apps-chat .chat-attachment-preview-header {
        padding: 0.5rem 0.75rem 0.35rem;
    }

    .apps-chat .chat-attachment-label {
        font-size: 0.6875rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--chat-text-muted);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .apps-chat .chat-attachment-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0 0.75rem 0.65rem;
        max-height: 140px;
        overflow-y: auto;
    }

    .apps-chat .chat-file-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        max-width: min(100%, 280px);
        padding: 0.35rem;
        background: var(--chat-surface);
        border: 1px solid var(--chat-border);
        border-radius: 10px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
    }

    .apps-chat .chat-file-chip-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.625rem;
        font-weight: 700;
    }

    .apps-chat .chat-file-chip-icon--image { background: #e8f4fd; color: #0d6efd; }
    .apps-chat .chat-file-chip-icon--pdf { background: #fde8e8; color: #dc3545; }
    .apps-chat .chat-file-chip-icon--doc { background: #e8eefd; color: #3949ab; }
    .apps-chat .chat-file-chip-icon--default { background: #f0f2f5; color: #495057; }

    .apps-chat .chat-file-chip-name {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--chat-text);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .apps-chat .chat-file-chip-meta { font-size: 0.6875rem; color: var(--chat-text-muted); }

    .apps-chat .chat-file-chip-remove {
        width: 28px;
        height: 28px;
        border: none;
        border-radius: 6px;
        background: transparent;
        color: #adb5bd;
    }

    .apps-chat .btn-chat-attachments-clear {
        font-size: 0.6875rem;
        font-weight: 600;
    }

    .apps-chat .chat-panel-loader {
        position: absolute;
        inset: 0;
        z-index: 30;
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 0.75rem;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(2px);
    }

    .apps-chat .chat-panel-loader.is-active {
        display: flex;
    }

    .apps-chat .chat-content-panel {
        position: relative;
    }

    .apps-chat a.chat-attachment-download {
        cursor: pointer;
    }

    .apps-chat .js-chat-image-preview {
        color: var(--chat-brand);
    }

    .apps-chat .badge.bg-primary {
        background-color: var(--chat-brand) !important;
    }

    /* Dark mode */
    html.app-skin-dark .apps-chat {
        --chat-surface: #121a2d;
        --chat-surface-muted: #0f172a;
        --chat-border: #1e293b;
        --chat-text: #f1f5f9;
        --chat-text-muted: #94a3b8;
        --chat-incoming: #1e293b;
        --chat-outgoing: #312e81;
        --chat-outgoing-border: #4c1d95;
        --chat-brand-soft: rgba(139, 92, 246, 0.2);
    }

    html.app-skin-dark .apps-chat .chat-inbox-item:hover {
        background: rgba(255, 255, 255, 0.04);
    }

    html.app-skin-dark .apps-chat .chat-inbox-item.chat-item-active {
        background: linear-gradient(90deg, rgba(139, 92, 246, 0.22) 0%, rgba(139, 92, 246, 0.06) 100%);
    }

    html.app-skin-dark .apps-chat .chat-bubble--text {
        color: #f1f5f9;
    }

    html.app-skin-dark .apps-chat .chat-composer-wrap,
    html.app-skin-dark .apps-chat .chat-thread-header,
    html.app-skin-dark .apps-chat .content-sidebar,
    html.app-skin-dark .apps-chat .content-sidebar-header {
        background: #121a2d;
    }

    html.app-skin-dark .apps-chat .chat-send-loader,
    html.app-skin-dark .apps-chat .chat-panel-loader {
        background: rgba(15, 23, 42, 0.9);
    }

    html.app-skin-dark .apps-chat .chat-inbox-search {
        background: #0f172a;
        color: #f1f5f9;
        border-color: #334155;
    }

    html.app-skin-dark .apps-chat .chat-composer-inner {
        background: #0f172a;
        border-color: #334155;
    }
</style>
