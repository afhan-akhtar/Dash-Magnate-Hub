<style>
    /* ── Seller listing form — premium layout ── */
    .listing-form-premium {
        --listing-brand: #6366f1;
        --listing-brand-dark: #4f46e5;
        --listing-brand-soft: #eef2ff;
        --listing-surface: #ffffff;
        --listing-muted: #64748b;
        --listing-border: #e2e8f0;
        --listing-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        --listing-shadow-lg: 0 12px 40px rgba(99, 102, 241, 0.12);
        --listing-radius: 16px;
        --listing-radius-sm: 12px;
    }

    .listing-form-premium .card-body {
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.75rem 1.5rem 2rem;
    }

    @media (min-width: 992px) {
        .listing-form-premium .card-body {
            padding: 2rem 2.25rem 2.5rem;
        }
    }

    /* Hero intro */
    .listing-capital-hero {
        background: linear-gradient(135deg, #0e7490 0%, #4f46e5 48%, #7c3aed 100%);
    }

    .listing-broker-hero {
        background: linear-gradient(135deg, #b91c1c 0%, #dc2626 48%, #ea580c 100%);
    }

    .listing-seller-hero {
        position: relative;
        overflow: hidden;
        border-radius: var(--listing-radius);
        padding: 1.5rem 1.5rem 1.5rem 1.25rem;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 48%, #a855f7 100%);
        color: #fff;
        box-shadow: var(--listing-shadow-lg);
        margin-bottom: 0.5rem;
    }

    .listing-seller-hero::before {
        content: "";
        position: absolute;
        top: -40%;
        right: -10%;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        pointer-events: none;
    }

    .listing-seller-hero::after {
        content: "";
        position: absolute;
        bottom: -30%;
        left: 20%;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        pointer-events: none;
    }

    .listing-seller-hero__inner {
        position: relative;
        z-index: 1;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .listing-seller-hero__icon {
        flex-shrink: 0;
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .listing-seller-hero__title {
        margin: 0 0 0.35rem;
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: #ffffff !important;
    }

    .listing-seller-hero h2,
    .listing-seller-hero .listing-seller-hero__title {
        color: #ffffff !important;
    }

    .listing-seller-hero__text {
        margin: 0;
        font-size: 0.9rem;
        line-height: 1.6;
        opacity: 0.92;
        max-width: 52rem;
    }

    .listing-seller-hero__chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .listing-seller-hero__chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.28);
    }

    /* Essentials panel (category, location) */
    .listing-essentials-panel {
        background: var(--listing-surface);
        border: 1px solid var(--listing-border);
        border-radius: var(--listing-radius);
        padding: 1.35rem 1.35rem 0.5rem;
        box-shadow: var(--listing-shadow);
    }

    .listing-essentials-panel__label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--listing-brand-dark);
    }

    .listing-essentials-panel__label i {
        font-size: 1rem;
    }

    .listing-essentials-panel .listing-field-card {
        box-shadow: none;
        border: 1px dashed #e2e8f0;
        background: #fafbfc;
    }

    .listing-essentials-row {
        --bs-gutter-x: 1rem;
    }

    .listing-essentials-row > [class*="col-"] {
        min-width: 0;
    }

    .listing-meta-field {
        margin-bottom: 0;
    }

    .listing-meta-field--region .form-control,
    .listing-essentials-row .listing-meta-field .form-control {
        width: 100%;
    }

    .listing-meta-field .form-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: #1e293b;
        margin-bottom: 0.45rem;
    }

    /* Uniform required asterisk (labels + field titles) */
    .listing-form-premium .listing-required-mark {
        color: #ef4444 !important;
        font-size: 0.875rem !important;
        font-weight: 600;
        line-height: 1;
        margin-left: 0.1rem;
        vertical-align: baseline;
        display: inline;
    }

    .listing-form-premium .listing-field-card__title .listing-required-mark {
        font-size: 0.875rem !important;
        font-weight: 600;
    }

    /* Section headers */
    .listing-section-wrap {
        margin-top: 0.25rem;
    }

    .listing-section-panel {
        background: var(--listing-surface);
        border: 1px solid var(--listing-border);
        border-radius: var(--listing-radius);
        padding: 1.5rem 1.25rem 0.25rem;
        box-shadow: var(--listing-shadow);
        margin-bottom: 0.25rem;
    }

    @media (min-width: 768px) {
        .listing-section-panel {
            padding: 1.75rem 1.5rem 0.5rem;
        }
    }

    .listing-section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.35rem;
        padding-bottom: 1.15rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .listing-section-header__number {
        flex-shrink: 0;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        color: var(--listing-brand);
        background: var(--listing-brand-soft);
        padding: 0.4rem 0.55rem;
        border-radius: 8px;
        line-height: 1;
    }

    .listing-section-header__icon {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(145deg, #6366f1, #8b5cf6);
        color: #fff;
        font-size: 1.15rem;
        box-shadow: 0 6px 16px rgba(99, 102, 241, 0.35);
    }

    .listing-section-header__title {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.02em;
        line-height: 1.25;
    }

    .listing-section-header__subtitle {
        margin: 0.2rem 0 0;
        font-size: 0.8125rem;
        color: var(--listing-muted);
        line-height: 1.45;
    }

    /* Field cards */
    .listing-field-card {
        position: relative;
        background: #fff;
        border: 1px solid #e8ecf4;
        border-radius: var(--listing-radius-sm);
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        display: flex;
        flex-direction: column;
    }

    .listing-field-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #6366f1, #a78bfa);
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .listing-field-card:hover {
        border-color: #c7d2fe;
        box-shadow: 0 8px 28px rgba(99, 102, 241, 0.1);
        transform: translateY(-2px);
    }

    .listing-field-card:hover::before,
    .listing-field-card:focus-within::before {
        opacity: 1;
    }

    .listing-field-card:focus-within {
        border-color: #a5b4fc;
        box-shadow: 0 8px 28px rgba(99, 102, 241, 0.14);
    }

    .listing-field-card__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 1.1rem 1.15rem 0;
    }

    .listing-field-card__title {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.02em;
        line-height: 1.3;
    }

    .listing-field-card__badge {
        flex-shrink: 0;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }

    .listing-field-card__badge--required {
        color: #b91c1c;
        background: #fef2f2;
        border: 1px solid #fecaca;
    }

    .listing-field-card__guide {
        padding: 0 1.15rem 0.85rem;
        flex: 1;
    }

    .listing-field-guide__desc {
        font-size: 0.875rem;
        line-height: 1.65;
        color: #475569;
        margin: 0 0 0.75rem;
    }

    /* Callouts */
    .listing-callout {
        display: flex;
        gap: 0.65rem;
        align-items: flex-start;
        padding: 0.7rem 0.85rem;
        border-radius: 10px;
        margin-bottom: 0.55rem;
    }

    .listing-callout:last-child {
        margin-bottom: 0;
    }

    .listing-callout__icon {
        flex-shrink: 0;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .listing-callout__text {
        margin: 0;
        font-size: 0.8125rem;
        line-height: 1.55;
        flex: 1;
    }

    .listing-callout--example {
        background: linear-gradient(135deg, #faf5ff 0%, #f5f3ff 100%);
        border: 1px solid #e9d5ff;
    }

    .listing-callout--example .listing-callout__icon {
        background: #ede9fe;
        color: #7c3aed;
    }

    .listing-callout--example .listing-callout__text,
    .listing-callout--example .listing-callout__text em,
    .listing-callout--example .listing-callout__text strong {
        color: #6d28d9;
        font-style: italic;
    }

    .listing-callout--example .listing-callout__text strong {
        font-weight: 700;
        font-style: italic;
    }

    .listing-callout--tip {
        background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
        border: 1px solid #bbf7d0;
    }

    .listing-callout--tip .listing-callout__icon {
        background: #d1fae5;
        color: #059669;
    }

    .listing-callout--tip .listing-callout__text {
        color: #334155;
    }

    .listing-field-guide__tip-label {
        color: #059669;
        font-weight: 700;
    }

    .listing-field-card__input-area {
        padding: 0 1.15rem 1.15rem;
        margin-top: auto;
        border-top: 1px solid #f1f5f9;
        padding-top: 1rem;
        background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
    }

    .listing-field-card__input,
    .listing-form-premium .form-control {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: 0.9rem;
        padding: 0.6rem 0.85rem;
        background: #fff;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .listing-field-card__input:focus,
    .listing-form-premium .form-control:focus {
        border-color: var(--listing-brand);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    .listing-form-premium textarea.form-control {
        min-height: 110px;
        line-height: 1.55;
        resize: vertical;
    }

    /* Description block */
    .listing-description-card {
        background: #fff;
        border: 1px solid #e8ecf4;
        border-radius: var(--listing-radius-sm);
        padding: 1.25rem 1.15rem;
        box-shadow: var(--listing-shadow);
    }

    .listing-description-card .listing-field-card__header {
        padding: 0 0 0.75rem;
    }

    .listing-description-card .listing-field-card__input-area {
        border-top: none;
        padding: 0;
        background: transparent;
    }

    /* Sticky footer */
    .listing-form-sticky-footer {
        position: sticky;
        bottom: 0;
        z-index: 20;
        margin: 1.5rem -1.5rem -1.75rem;
        padding: 1rem 1.5rem;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(12px);
        border-top: 1px solid #e2e8f0;
        box-shadow: 0 -8px 32px rgba(15, 23, 42, 0.06);
    }

    @media (min-width: 992px) {
        .listing-form-sticky-footer {
            margin-left: -2.25rem;
            margin-right: -2.25rem;
            margin-bottom: -2.5rem;
            padding: 1.1rem 2.25rem;
        }
    }

    .listing-form-sticky-footer .btn-listing-save {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        padding: 0.65rem 1.75rem;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .listing-form-sticky-footer .btn-listing-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.5);
        background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
    }

    .listing-form-footer-link {
        color: #4f46e5;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        white-space: nowrap;
    }

    .listing-form-footer-link:hover {
        color: #4338ca;
        text-decoration: underline;
    }

    .card:has(.listing-form-premium) > .card-header {
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
    }

    @media (min-width: 992px) {
        .card:has(.listing-form-premium) > .card-header {
            padding: 1.35rem 2.25rem;
        }
    }

    .listing-plan-limit-badge {
        background: #eef2ff;
        color: #4338ca;
        border: 1px solid #e0e7ff;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.45rem 0.75rem;
    }

    @include('professional.dashboard.partials.dashboard-premium-dark-styles')
</style>
