<style>
    .report-detail {
        --detail-primary: #2563eb;
        --detail-ink: #0f172a;
        --detail-muted: #64748b;
        --detail-line: #e2e8f0;
    }

    .report-detail__hero {
        padding: 1.6rem;
        border-radius: 1.1rem;
        color: #fff;
        background:
            radial-gradient(circle at 90% 10%, rgba(255, 255, 255, .18), transparent 24%),
            linear-gradient(125deg, #0f172a, #1e40af);
        box-shadow: 0 18px 42px rgba(30, 64, 175, .16);
    }

    .report-detail__hero-row,
    .report-detail__actions,
    .report-detail__section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .report-detail__title {
        font-size: 1.7rem;
        font-weight: 700;
    }

    .report-detail__subtitle {
        margin-top: .35rem;
        color: rgba(255, 255, 255, .7);
    }

    .report-detail__button {
        display: inline-flex;
        min-height: 40px;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        padding: .6rem .9rem;
        border: 1px solid rgba(255, 255, 255, .22);
        border-radius: .7rem;
        color: #fff;
        background: rgba(255, 255, 255, .1);
    }

    .report-detail__button--light {
        border-color: #fff;
        color: #1d4ed8;
        background: #fff;
    }

    .report-detail__filter {
        display: flex;
        flex-wrap: wrap;
        align-items: end;
        gap: .65rem;
        margin-top: 1.2rem;
    }

    .report-detail__filter label {
        display: block;
        margin-bottom: .35rem;
        color: rgba(255, 255, 255, .7);
        font-size: .72rem;
        font-weight: 600;
    }

    .report-detail__filter input {
        min-height: 40px;
        padding: .55rem .75rem;
        border: 1px solid rgba(255, 255, 255, .25);
        border-radius: .7rem;
        outline: none;
        color: #fff;
        background: rgba(15, 23, 42, .35);
        color-scheme: dark;
    }

    .report-detail__metrics {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.25rem;
    }

    .report-detail__metric {
        padding: 1rem;
        border: 1px solid var(--detail-line);
        border-radius: .9rem;
        background: #fff;
        box-shadow: 0 9px 24px rgba(15, 23, 42, .045);
    }

    .report-detail__metric-value {
        color: var(--detail-ink);
        font-size: 1.4rem;
        font-weight: 700;
    }

    .report-detail__metric-label {
        margin-top: .3rem;
        color: var(--detail-muted);
        font-size: .78rem;
    }

    .report-detail__metric-difference {
        display: inline-flex;
        margin-top: .55rem;
        padding: .25rem .5rem;
        border-radius: 999px;
        color: #b45309;
        background: #fef3c7;
        font-size: .7rem;
        font-weight: 700;
    }

    .report-detail__section {
        overflow: hidden;
        margin-top: 1.25rem;
        border: 1px solid var(--detail-line);
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 10px 28px rgba(15, 23, 42, .045);
    }

    .report-detail__section-header {
        padding: 1rem 1.15rem;
        border-bottom: 1px solid var(--detail-line);
    }

    .report-detail__section-title {
        color: var(--detail-ink);
        font-size: .95rem;
        font-weight: 700;
    }

    .report-detail__section-copy {
        margin-top: .2rem;
        color: var(--detail-muted);
        font-size: .75rem;
    }

    .report-detail__table-wrap {
        overflow-x: auto;
    }

    .report-detail__table {
        width: 100%;
        border-collapse: collapse;
        white-space: nowrap;
    }

    .report-detail__table th {
        padding: .75rem 1rem;
        color: var(--detail-muted);
        background: #f8fafc;
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .035em;
        text-align: left;
        text-transform: uppercase;
    }

    .report-detail__table td {
        padding: .85rem 1rem;
        border-top: 1px solid #f1f5f9;
        color: #334155;
        font-size: .8rem;
    }

    .report-detail__table tbody tr:hover {
        background: #f8fafc;
    }

    .report-detail__table .is-numeric {
        text-align: right;
        font-variant-numeric: tabular-nums;
    }

    .report-detail__user {
        display: flex;
        align-items: center;
        gap: .65rem;
        color: var(--detail-ink);
        font-weight: 600;
    }

    .report-detail__avatar {
        display: inline-flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        border-radius: .7rem;
        color: #1d4ed8;
        background: #dbeafe;
        font-size: .7rem;
    }

    .report-detail__rank {
        display: inline-flex;
        width: 28px;
        height: 28px;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        color: #475569;
        background: #f1f5f9;
        font-weight: 700;
    }

    .report-detail__status {
        display: inline-flex;
        padding: .25rem .55rem;
        border-radius: 999px;
        font-size: .68rem;
        font-weight: 700;
    }

    .report-detail__status--success {
        color: #047857;
        background: #d1fae5;
    }

    .report-detail__status--pending {
        color: #b45309;
        background: #fef3c7;
    }

    .report-detail__status--failed {
        color: #b91c1c;
        background: #fee2e2;
    }

    .report-detail__link {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        color: var(--detail-primary);
        font-weight: 600;
    }

    .report-detail__empty {
        padding: 2.6rem 1rem !important;
        color: var(--detail-muted) !important;
        text-align: center !important;
    }

    @media (max-width: 900px) {
        .report-detail__metrics {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .report-detail__hero-row,
        .report-detail__actions {
            align-items: stretch;
            flex-direction: column;
        }

        .report-detail__metrics {
            grid-template-columns: 1fr;
        }

        .report-detail__filter,
        .report-detail__filter > div,
        .report-detail__filter input,
        .report-detail__button {
            width: 100%;
        }
    }
</style>
