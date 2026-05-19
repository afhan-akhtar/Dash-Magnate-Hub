<style>
    .pricing-page-premium .main-content {
        background: linear-gradient(165deg, #f8fafc 0%, #eef2ff 35%, #f1f5f9 100%);
        padding: 0.5rem 0 2.5rem;
        border-radius: 0 0 20px 20px;
    }

    .pricing-page-premium .page-header {
        margin-bottom: 0.5rem;
    }

    /* Hero */
    .pricing-hero {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        padding: 1.75rem 2rem;
        margin-bottom: 1.75rem;
        background: linear-gradient(125deg, #3730a3 0%, #5b21b6 38%, #7c3aed 68%, #a855f7 100%);
        color: #fff;
        box-shadow: 0 20px 50px rgba(79, 70, 229, 0.35);
    }

    .pricing-hero::before,
    .pricing-hero::after {
        content: "";
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
    }

    .pricing-hero::before {
        top: -60%;
        right: -8%;
        width: 380px;
        height: 380px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, transparent 70%);
    }

    .pricing-hero::after {
        bottom: -40%;
        left: 5%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.06);
    }

    .pricing-hero__inner {
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
    }

    .pricing-hero__left {
        display: flex;
        gap: 1.15rem;
        align-items: flex-start;
        flex: 1;
        min-width: 260px;
    }

    .pricing-hero__badge {
        flex-shrink: 0;
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.28);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        backdrop-filter: blur(8px);
    }

    .pricing-hero__title {
        margin: 0 0 0.4rem;
        font-size: 1.5rem;
        font-weight: 800;
        color: #ffffff !important;
        letter-spacing: -0.03em;
        line-height: 1.2;
    }

    .pricing-hero__text {
        margin: 0 0 0.85rem;
        font-size: 0.9rem;
        opacity: 0.9;
        max-width: 32rem;
        line-height: 1.6;
    }

    .pricing-hero__chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .pricing-hero__chip {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.3rem 0.65rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.22);
    }

    .pricing-hero__btn {
        background: #fff !important;
        color: #5b21b6 !important;
        border: none !important;
        font-weight: 700;
        font-size: 0.875rem;
        padding: 0.7rem 1.4rem;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        white-space: nowrap;
    }

    .pricing-hero__btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
        color: #4c1d95 !important;
    }

    /* Section label */
    .pricing-section-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0 0 1rem;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #6366f1;
    }

    .pricing-section-label i {
        font-size: 0.95rem;
    }

    /* Stat cards */
    .pricing-stat-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(15, 23, 42, 0.08);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        height: 100%;
        background: #fff;
        position: relative;
    }

    .pricing-stat-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .pricing-stat-card--primary::before {
        background: linear-gradient(90deg, #6366f1, #8b5cf6);
    }

    .pricing-stat-card--success::before {
        background: linear-gradient(90deg, #10b981, #34d399);
    }

    .pricing-stat-card--warning::before {
        background: linear-gradient(90deg, #f59e0b, #fbbf24);
    }

    .pricing-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
    }

    .pricing-stat-card__body {
        padding: 1.5rem 1.45rem 0.5rem;
    }

    .pricing-stat-card__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
    }

    .pricing-stat-card__icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .pricing-stat-card--primary .pricing-stat-card__icon {
        background: linear-gradient(145deg, #eef2ff, #c7d2fe);
        color: #4f46e5;
    }

    .pricing-stat-card--success .pricing-stat-card__icon {
        background: linear-gradient(145deg, #ecfdf5, #a7f3d0);
        color: #059669;
    }

    .pricing-stat-card--warning .pricing-stat-card__icon {
        background: linear-gradient(145deg, #fffbeb, #fde68a);
        color: #d97706;
    }

    .pricing-stat-card__label {
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
        margin-bottom: 0.35rem;
    }

    .pricing-stat-card__value {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.03em;
        line-height: 1.2;
    }

    .pricing-stat-card__foot {
        padding: 0.9rem 1.45rem 1.2rem;
        font-size: 0.8125rem;
        color: #64748b;
        background: linear-gradient(180deg, transparent, #fafbfc);
        border-top: 1px solid #f1f5f9;
        margin: 0;
    }

    /* Panels */
    .pricing-panel {
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(15, 23, 42, 0.06);
        background: #fff;
        height: 100%;
        overflow: hidden;
    }

    .pricing-panel__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.2rem 1.4rem;
        background: linear-gradient(180deg, #fff 0%, #fafbfc 100%);
        border-bottom: 1px solid #f1f5f9;
    }

    .pricing-panel__header-left {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .pricing-panel__header-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        background: linear-gradient(145deg, #eef2ff, #e0e7ff);
        color: #4f46e5;
    }

    .pricing-panel__title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.02em;
    }

    .pricing-panel__subtitle {
        font-size: 0.75rem;
        color: #94a3b8;
        margin: 0.15rem 0 0;
    }

    .pricing-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .pricing-status-pill::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
        animation: pricing-pulse 2s ease infinite;
    }

    @keyframes pricing-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .pricing-active-plan {
        background: linear-gradient(145deg, #faf5ff 0%, #f5f3ff 45%, #eef2ff 100%);
        border: 1px solid #ddd6fe;
        border-radius: 16px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .pricing-active-plan::after {
        content: "";
        position: absolute;
        top: -30px;
        right: -30px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(139, 92, 246, 0.08);
    }

    .pricing-active-plan__head {
        position: relative;
        z-index: 1;
        margin-bottom: 1.25rem;
    }

    .pricing-active-plan__eyebrow {
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #7c3aed;
        margin-bottom: 0.35rem;
    }

    .pricing-active-plan__name {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 0.25rem;
        letter-spacing: -0.02em;
    }

    .pricing-active-plan__price-tag {
        display: inline-flex;
        align-items: baseline;
        gap: 0.15rem;
        margin-top: 0.5rem;
        padding: 0.35rem 0.75rem;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #e9d5ff;
        box-shadow: 0 2px 8px rgba(124, 58, 237, 0.08);
    }

    .pricing-active-plan__price-tag .currency {
        font-size: 0.9rem;
        font-weight: 700;
        color: #7c3aed;
    }

    .pricing-active-plan__price-tag .amount {
        font-size: 1.5rem;
        font-weight: 800;
        color: #5b21b6;
        letter-spacing: -0.03em;
    }

    .pricing-metric {
        background: #fff;
        border: 1px solid #e8ecf4;
        border-radius: 14px;
        padding: 1rem 1.1rem;
        height: 100%;
        transition: all 0.2s ease;
        position: relative;
        z-index: 1;
    }

    .pricing-metric:hover {
        border-color: #c4b5fd;
        box-shadow: 0 6px 16px rgba(99, 102, 241, 0.08);
        transform: translateY(-1px);
    }

    .pricing-metric__icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        background: #f8fafc;
        color: #64748b;
    }

    .pricing-metric__label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        margin-bottom: 0.3rem;
    }

    .pricing-metric__value {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
    }

    .pricing-metric__value--live {
        color: #059669;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Snapshot */
    .pricing-snapshot-card {
        background: #fff;
        border: 1px solid #e8ecf4;
        border-radius: 16px;
        padding: 1.35rem 1.15rem;
        height: 100%;
        text-align: center;
        transition: all 0.22s ease;
        position: relative;
        overflow: hidden;
    }

    .pricing-snapshot-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 60%, rgba(99, 102, 241, 0.03) 100%);
        pointer-events: none;
    }

    .pricing-snapshot-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(99, 102, 241, 0.1);
        border-color: #c7d2fe;
    }

    .pricing-snapshot-card__icon {
        width: 44px;
        height: 44px;
        margin: 0 auto 0.75rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
    }

    .pricing-snapshot-card--total .pricing-snapshot-card__icon {
        background: linear-gradient(145deg, #eef2ff, #e0e7ff);
        color: #4f46e5;
    }

    .pricing-snapshot-card--active .pricing-snapshot-card__icon {
        background: linear-gradient(145deg, #ecfdf5, #d1fae5);
        color: #059669;
    }

    .pricing-snapshot-card--expired .pricing-snapshot-card__icon {
        background: linear-gradient(145deg, #f8fafc, #f1f5f9);
        color: #64748b;
    }

    .pricing-snapshot-card__label {
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
    }

    .pricing-snapshot-card__value {
        font-size: 2rem;
        font-weight: 800;
        margin: 0.4rem 0;
        letter-spacing: -0.04em;
        line-height: 1;
    }

    .pricing-snapshot-card--total .pricing-snapshot-card__value { color: #4f46e5; }
    .pricing-snapshot-card--active .pricing-snapshot-card__value { color: #059669; }
    .pricing-snapshot-card--expired .pricing-snapshot-card__value { color: #64748b; }

    .pricing-snapshot-card__desc {
        font-size: 0.75rem;
        color: #94a3b8;
        line-height: 1.45;
        position: relative;
        z-index: 1;
    }

    /* Empty states */
    .pricing-empty {
        text-align: center;
        padding: 2.75rem 1.5rem;
        background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
    }

    .pricing-empty__icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1.1rem;
        border-radius: 50%;
        background: linear-gradient(145deg, #eef2ff, #e0e7ff);
        color: #6366f1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.65rem;
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.15);
    }

    /* Upgrade CTA */
    .pricing-upgrade-cta {
        border-radius: 18px;
        padding: 1.35rem 1.5rem;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4c1d95 100%);
        color: #fff;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        box-shadow: 0 12px 36px rgba(49, 46, 129, 0.35);
        margin-top: 0.5rem;
    }

    .pricing-upgrade-cta__title {
        font-size: 1.05rem;
        font-weight: 700;
        margin: 0 0 0.25rem;
        color: #fff !important;
    }

    .pricing-upgrade-cta__text {
        margin: 0;
        font-size: 0.85rem;
        opacity: 0.85;
    }

    .pricing-upgrade-cta .pricing-cta-btn,
    .pricing-hero__btn {
        background: #fff !important;
        background-image: none !important;
        color: #5b21b6 !important;
        font-weight: 700;
        border: none !important;
    }

    .pricing-upgrade-cta .pricing-cta-btn {
        border-radius: 10px;
        padding: 0.55rem 1.2rem;
    }

    .pricing-upgrade-cta .pricing-cta-btn:hover,
    .pricing-hero__btn:hover {
        color: #4c1d95 !important;
        background: #f8fafc !important;
    }

    /* Table */
    .pricing-table-wrap {
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #e8ecf4;
        margin: 0 1.25rem 1.25rem;
    }

    .pricing-page-premium .pricing-table thead th {
        background: linear-gradient(180deg, #f8fafc, #f1f5f9);
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 1.1rem;
    }

    .pricing-page-premium .pricing-table tbody td {
        padding: 1.05rem 1.1rem;
        vertical-align: middle;
        border-color: #f1f5f9;
        font-size: 0.9rem;
    }

    .pricing-page-premium .pricing-table tbody tr {
        transition: background 0.15s ease;
    }

    .pricing-page-premium .pricing-table tbody tr:hover {
        background: linear-gradient(90deg, #fafbff, #fff);
    }

    .pricing-page-premium .pricing-table tbody tr.pricing-row--active {
        background: linear-gradient(90deg, #f5f3ff 0%, #fff 100%);
    }

    .pricing-plan-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .pricing-badge-active {
        background: linear-gradient(135deg, #10b981, #059669) !important;
        border: none;
        padding: 0.45em 0.9em;
        font-weight: 700;
        font-size: 0.72rem;
        letter-spacing: 0.02em;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .pricing-badge-expired {
        background: #f1f5f9 !important;
        color: #64748b !important;
        font-weight: 600;
        font-size: 0.72rem;
        border: 1px solid #e2e8f0;
    }

    @media (max-width: 767.98px) {
        .pricing-hero {
            padding: 1.35rem 1.25rem;
        }

        .pricing-hero__title {
            font-size: 1.25rem;
        }

        .pricing-hero__btn {
            width: 100%;
            justify-content: center;
        }
    }

    @include('professional.dashboard.partials.dashboard-premium-dark-styles')
</style>
