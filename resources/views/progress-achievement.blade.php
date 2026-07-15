<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} - Progress Achievement</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, "Segoe UI", Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #1f2937;
        }

        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 10px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            /* NEVER wrap — always one row */
            flex-wrap: nowrap;
            overflow: hidden;
        }

        .navbar-left {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
        }

        .navbar-logo {
            height: 52px;
            object-fit: contain;
            display: block;
        }

        .navbar-center {
            flex: 1 1 auto;
            min-width: 0;
            /* allow shrinking */
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            overflow: hidden;
        }

        .navbar-center .title-row {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            justify-content: center;
        }

        .navbar-center .title-icon {
            width: 28px;
            height: 28px;
            min-width: 28px;
            padding: 5px;
            border-radius: 8px;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: #ffffff;
            flex-shrink: 0;
        }

        .navbar-center h1 {
            font-size: 24px;
            margin: 0;
            font-weight: 800;
            letter-spacing: 0.3px;
            background: linear-gradient(90deg, #1d4ed8, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            white-space: nowrap;
        }

        .navbar-center .title-underline {
            width: 56px;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, #1d4ed8, #3b82f6);
        }

        .navbar-right {
            flex: 0 0 auto;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
        }

        .badge-public {
            background-color: #69f0ae;
            color: #064e3b;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-login {
            color: #4b5563;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            border-radius: 50%;
            transition: all 0.2s;
            text-decoration: none;
            background: transparent;
            border: none;
            cursor: pointer;
        }

        .btn-login:hover {
            background-color: #f3f4f6;
            color: #111827;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 24px 30px 60px;
        }

        .filter-bar {
            background: #ffffff;
            border-radius: 10px;
            padding: 16px 20px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: flex-end;
            margin-bottom: 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
            min-width: 150px;
        }

        .filter-group label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .action-bar {
            display: flex;
            gap: 10px;
            margin-left: auto;
            align-self: center;
        }

        .dropdown-wrap {
            position: relative;
        }

        .btn-action {
            display: flex;
            align-items: center;
            gap: 7px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            border-radius: 999px;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.28);
            transition: transform 0.15s, box-shadow 0.15s, filter 0.15s;
        }

        .btn-action.secondary {
            background: #ffffff;
            color: #2563eb;
            border: 1.5px solid #dbeafe;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .btn-action:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.32);
        }

        .btn-action.secondary:hover {
            background: #eff6ff;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.12);
        }

        .btn-action svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .btn-action .chevron {
            width: 11px;
            height: 11px;
            margin-left: 1px;
            opacity: 0.85;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 200px;
            background: #ffffff;
            border: 1px solid #eef0f3;
            border-radius: 12px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.14);
            padding: 6px;
            z-index: 40;
        }

        .dropdown-menu.open {
            display: block;
        }

        .dropdown-menu button {
            display: flex;
            align-items: center;
            gap: 11px;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 8px 8px;
            font-size: 13.5px;
            font-weight: 500;
            color: #374151;
            border-radius: 8px;
            cursor: pointer;
        }

        .dropdown-menu button:hover {
            background: #f3f4f6;
        }

        .dropdown-menu .menu-icon {
            width: 26px;
            height: 26px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dropdown-menu .menu-icon svg {
            width: 14px;
            height: 14px;
        }

        .menu-icon.excel {
            background: #dcfce7;
            color: #16a34a;
        }

        .menu-icon.pdf {
            background: #fee2e2;
            color: #dc2626;
        }

        .menu-icon.image {
            background: #dbeafe;
            color: #2563eb;
        }

        .dropdown-menu .menu-divider {
            height: 1px;
            background: #f0f1f3;
            margin: 4px 4px;
        }

        .dropdown-menu .menu-note {
            padding: 4px 8px 2px;
            font-size: 11px;
            line-height: 1.4;
            color: #9ca3af;
        }

        @media print {

            .filter-bar,
            .action-bar,
            .top-section,
            .modal-overlay {
                display: none !important;
            }
        }

        /* Choices.js Theme Overrides */
        .choices {
            margin-bottom: 0;
            width: 100%;
        }

        .choices__inner {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 4px 16px;
            min-height: 42px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
        }

        .is-focused .choices__inner,
        .is-open .choices__inner {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #eff6ff;
            color: #1d4ed8;
        }

        .summary-bar {
            display: flex;
            gap: 16px;
            margin-bottom: 0;
            flex-wrap: wrap;
        }

        .summary-card {
            flex: 1;
            min-width: 140px;
            background: #ffffff;
            border-radius: 10px;
            padding: 12px 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            border-left: 4px solid #d1d5db;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .summary-card.achieve {
            border-left-color: #16a34a;
        }

        .summary-card.non-achieve {
            border-left-color: #dc2626;
        }

        .summary-card.no-data {
            border-left-color: #9ca3af;
        }

        .summary-card .num {
            font-size: 24px;
            font-weight: 700;
            line-height: 1.1;
        }

        .summary-card .label {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.2;
        }

        .top-section {
            display: flex;
            gap: 20px;
            margin-bottom: 24px;
            align-items: stretch;
            justify-content: flex-start;
        }

        .left-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .right-panel {
            flex: 0 0 350px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            position: relative;
        }

        .chart-container {
            position: absolute;
            top: 8px;
            bottom: 8px;
            left: 12px;
            right: 12px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
        }

        .item-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 14px 18px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            cursor: pointer;
            border-top: 4px solid #d1d5db;
            transition: transform 0.12s ease;
            min-width: 0;
            /* allow flex children to shrink */
            overflow: hidden;
        }

        .item-card:hover {
            transform: translateY(-2px);
        }

        .item-card.kurang {
            border-top-color: #dc2626;
        }

        .item-card.tercapai {
            border-top-color: #16a34a;
        }

        .item-card.melampaui {
            border-top-color: #16a34a;
        }

        .item-card.tidak_ada_data {
            border-top-color: #9ca3af;
            opacity: 0.85;
        }

        .item-card .group-tag {
            font-size: 11px;
            color: #9ca3af;
            margin-bottom: 2px;
        }

        /* item name — no min-height so badge sits right below */
        .item-name-center {
            text-align: center;
            font-weight: 600;
            font-size: 12.5px;
            margin-bottom: 6px;
            line-height: 1.35;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Badge row: badge on the left, emoji on the right */
        .badge-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            gap: 8px;
        }

        /* Badge box: transparent bg, only label text neutral gray */
        .badge-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1px;
            padding: 6px 16px;
            border-radius: 8px;
            border: 1.5px solid #e5e7eb;
            background: transparent;
            flex: 1;
        }

        .badge-box .badge-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6b7280;
            /* always gray — no color tinting */
        }

        /* Only the number gets a color */
        .badge-box .badge-pct {
            font-size: 22px;
            font-weight: 800;
            line-height: 1.1;
        }

        .kurang .badge-box .badge-pct {
            color: #dc2626;
        }

        .tercapai .badge-box .badge-pct {
            color: #16a34a;
        }

        .melampaui .badge-box .badge-pct {
            color: #16a34a;
        }

        .tidak_ada_data .badge-box .badge-pct {
            color: #9ca3af;
        }

        /* Emoji at top-right, bigger */
        .face-top {
            width: 36px;
            height: 36px;
            flex-shrink: 0;
        }

        .bottom-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .values-left {
            font-size: 11px;
            color: #6b7280;
            line-height: 1.7;
        }

        /* Right side: trend arrow + delta pct (bigger, right-aligned) */
        .indicator-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .trend-arrow {
            display: flex;
            align-items: center;
            gap: 3px;
            font-size: 13px;
            font-weight: 800;
        }

        .trend-arrow svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .trend-arrow.up {
            color: #16a34a;
        }

        .trend-arrow.down {
            color: #dc2626;
        }

        .trend-arrow.gold {
            color: #16a34a;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.45);
            align-items: center;
            justify-content: center;
            z-index: 50;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px;
            width: 90%;
            max-width: 640px;
            max-height: 85vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .modal-header h2 {
            font-size: 18px;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: #6b7280;
            line-height: 1;
        }

        table.detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            font-size: 13px;
        }

        table.detail-table th,
        table.detail-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #eee;
            text-align: right;
        }

        table.detail-table th:first-child,
        table.detail-table td:first-child {
            text-align: left;
        }

        /* ---- Responsive: layout only (navbar always stays 1 row) ---- */
        @media (max-width: 1200px) {
            .navbar {
                padding: 10px 20px;
            }

            .container {
                padding: 20px 20px 60px;
            }
        }

        @media (max-width: 900px) {
            .badge-public {
                font-size: 12px;
                padding: 5px 10px;
            }
        }

        @media (max-width: 700px) {

            /* Hide the eye icon text, keep icon only */
            .badge-label-text {
                display: none;
            }

            .badge-public {
                padding: 6px 8px;
            }

            .navbar-logo {
                height: 38px;
            }

            .navbar-center .title-icon {
                width: 20px;
                height: 20px;
                min-width: 20px;
            }
        }

        @media (max-width: 1024px) {
            .top-section {
                flex-direction: column;
                align-items: stretch;
            }

            .right-panel {
                flex: none;
                width: 100%;
                max-width: 380px;
                height: 240px;
                margin: 0 auto;
            }

            .chart-container {
                position: absolute;
                top: 8px;
                bottom: 8px;
                left: 12px;
                right: 12px;
            }
        }

        @media (max-width: 768px) {
            .filter-bar {
                gap: 12px;
            }

            .summary-bar {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 10px;
            }

            .summary-card {
                min-width: 130px;
                padding: 10px 12px;
            }

            .grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 520px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('img/logo-pemi.png') }}" alt="Logo" class="navbar-logo" />
        </div>

        <div class="navbar-center">
            <div class="title-row">
                <svg class="title-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v18h18" />
                    <path d="M18.5 8l-5 5-3-3-4 4" />
                </svg>
                <h1>Progress Achievement</h1>
            </div>
            <div class="title-underline"></div>
        </div>

        <div class="navbar-right export-hide">
            <div class="badge-public">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <span class="badge-label-text">{{ auth()->check() ? auth()->user()->name : 'Public View' }}</span>
            </div>

            @if(auth()->check())
                <form action="{{ Route::has('logout') ? route('logout') : url('/logout') }}" method="POST"
                    style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-login" title="Logout">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </form>
            @else
                <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="btn-login" title="Login">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </a>
            @endif
        </div>
    </div>

    <div class="container">

        <div class="top-section">
            <div class="left-panel">
                <form class="filter-bar" method="GET" action="{{ route('progress-achievement') }}" id="filterForm">
                    <div class="filter-group">
                        <label>Period</label>
                        <select name="periode" onchange="document.getElementById('filterForm').submit()">
                            @foreach ($availablePeriods as $p)
                                <option value="{{ $p->format('Y-m-d') }}" {{ $periode === $p->format('Y-m-d') ? 'selected' : '' }}>
                                    {{ $p->format('F Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Group</label>
                        <select name="group_id" onchange="document.getElementById('filterForm').submit()">
                            <option value="all" {{ $groupId === 'all' ? 'selected' : '' }}>All Groups</option>
                            @foreach ($groups as $g)
                                <option value="{{ $g->id }}" {{ (string) $groupId === (string) $g->id ? 'selected' : '' }}>
                                    Group {{ $g->kode_group }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Status</label>
                        <select name="status" onchange="document.getElementById('filterForm').submit()">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                            <option value="achieve" {{ $status === 'achieve' ? 'selected' : '' }}>Achieve</option>
                            <option value="non_achieve" {{ $status === 'non_achieve' ? 'selected' : '' }}>Non-Achieve
                            </option>
                            <option value="tidak_ada_data" {{ $status === 'tidak_ada_data' ? 'selected' : '' }}>No Data
                            </option>
                        </select>
                    </div>

                    <div class="action-bar export-hide">
                        <div class="dropdown-wrap">
                            <button type="button" class="btn-action" onclick="toggleExportMenu('downloadMenu')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Download
                                <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                            <div class="dropdown-menu" id="downloadMenu">
                                <button type="button" onclick="downloadExcel()">
                                    <span class="menu-icon excel">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                            <line x1="3" y1="9" x2="21" y2="9"></line>
                                            <line x1="3" y1="15" x2="21" y2="15"></line>
                                            <line x1="9" y1="3" x2="9" y2="21"></line>
                                            <line x1="15" y1="3" x2="15" y2="21"></line>
                                        </svg>
                                    </span>
                                    Excel (.xlsx)
                                </button>
                                <button type="button" onclick="downloadPdf()">
                                    <span class="menu-icon pdf">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                            </path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                        </svg>
                                    </span>
                                    PDF
                                </button>
                                <button type="button" onclick="downloadImage()">
                                    <span class="menu-icon image">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </span>
                                    Image (PNG)
                                </button>
                            </div>
                        </div>

                        <div class="dropdown-wrap">
                            <button type="button" class="btn-action secondary" onclick="toggleExportMenu('printMenu')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                    <path
                                        d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2">
                                    </path>
                                    <rect x="6" y="14" width="12" height="8"></rect>
                                </svg>
                                Print
                                <svg class="chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                            <div class="dropdown-menu" id="printMenu">
                                <button type="button" onclick="printPdf()">
                                    <span class="menu-icon pdf">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                            </path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                        </svg>
                                    </span>
                                    PDF
                                </button>
                                <button type="button" onclick="printImage()">
                                    <span class="menu-icon image">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </span>
                                    Image
                                </button>
                                <button type="button" onclick="printExcelNotice()">
                                    <span class="menu-icon excel">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                            <line x1="3" y1="9" x2="21" y2="9"></line>
                                            <line x1="3" y1="15" x2="21" y2="15"></line>
                                            <line x1="9" y1="3" x2="9" y2="21"></line>
                                            <line x1="15" y1="3" x2="15" y2="21"></line>
                                        </svg>
                                    </span>
                                    Excel
                                </button>
                                <div class="menu-divider"></div>
                                <div class="menu-note">Excel will be downloaded first — print it manually from
                                    Excel.</div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="summary-bar">
                    <div class="summary-card achieve">
                        <div>
                            <div class="num">{{ $totalAchieve }}</div>
                            <div class="label">Achieve Items (including over-achieve)</div>
                        </div>
                        <div style="opacity: 0.15; color: #16a34a; flex-shrink: 0;">
                            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="summary-card non-achieve">
                        <div>
                            <div class="num">{{ $totalNonAchieve }}</div>
                            <div class="label">Non-Achieve Items</div>
                        </div>
                        <div style="opacity: 0.15; color: #dc2626; flex-shrink: 0;">
                            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                        </div>
                    </div>
                    <div class="summary-card no-data">
                        <div>
                            <div class="num">{{ $totalNoData }}</div>
                            <div class="label">No Data Yet</div>
                        </div>
                        <div style="opacity: 0.15; color: #9ca3af; flex-shrink: 0;">
                            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-panel">
                <div class="chart-container">
                    <canvas id="summaryPieChart"></canvas>
                </div>
            </div>
        </div>

        @if (count($items) === 0)
            <div class="empty-state">No data available for the selected filters.</div>
        @else
            <div class="grid">
                @foreach ($items as $item)
                    @php
                        $isNoData = $item['kategori'] === 'tidak_ada_data';
                        $trendPct = $item['trend_pct'] ?? null;
                        $trendNaik = $item['trend_naik'] ?? null;
                        $hasTrend = !$isNoData && $trendPct !== null;
                        $trendClass = $trendNaik ? 'up' : 'down';
                    @endphp
                    <div class="item-card {{ $item['kategori'] }}"
                        onclick="openDetail({{ $item['metric_id'] }}, '{{ addslashes($item['nama_item']) }}')">

                        {{-- Row 1: Group tag --}}
                        <div class="group-tag">Group {{ $item['kode_group'] }}</div>

                        {{-- Row 2: Item name (no min-height) --}}
                        <div class="item-name-center">{{ $item['nama_item'] }}</div>

                        {{-- Row 3: Badge (left) + Emoji (right, bigger) --}}
                        <div class="badge-row">
                            <div class="badge-box">
                                <span class="badge-label">
                                    @if ($item['kategori'] === 'kurang') Non-Achieve
                                    @elseif ($item['kategori'] === 'tercapai') Achieve
                                    @elseif ($item['kategori'] === 'melampaui') Over-Achieve
                                    @else No Data
                                    @endif
                                </span>
                                <span class="badge-pct">
                                    {{ $isNoData ? '—' : $item['persen_achievement'] . '%' }}
                                </span>
                            </div>

                            {{-- Emoji at top-right, enlarged --}}
                            @if ($isNoData)
                                <svg class="face-top" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" fill="#9ca3af" />
                                    <circle cx="9" cy="10" r="1.2" fill="#fff" />
                                    <circle cx="15" cy="10" r="1.2" fill="#fff" />
                                    <line x1="8" y1="15" x2="16" y2="15" stroke="#fff" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                            @elseif ($item['kategori'] === 'kurang')
                                <svg class="face-top" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" fill="#dc2626" />
                                    <circle cx="9" cy="10" r="1.2" fill="#fff" />
                                    <circle cx="15" cy="10" r="1.2" fill="#fff" />
                                    <path d="M8 16c1-1.5 2.5-2 4-2s3 0.5 4 2" stroke="#fff" stroke-width="1.5" fill="none"
                                        stroke-linecap="round" />
                                </svg>
                            @elseif ($item['kategori'] === 'tercapai')
                                <svg class="face-top" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" fill="#16a34a" />
                                    <circle cx="9" cy="10" r="1.2" fill="#fff" />
                                    <circle cx="15" cy="10" r="1.2" fill="#fff" />
                                    <path d="M8 14c1 1.5 2.5 2 4 2s3-0.5 4-2" stroke="#fff" stroke-width="1.5" fill="none"
                                        stroke-linecap="round" />
                                </svg>
                            @else
                                <svg class="face-top" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" fill="#16a34a" />
                                    <path d="M8 9.5c0-0.5 0.4-1 1-1s1 0.5 1 1M14 9.5c0-0.5 0.4-1 1-1s1 0.5 1 1" stroke="#fff"
                                        stroke-width="1.4" stroke-linecap="round" />
                                    <path d="M7.5 13.5c1.2 2 2.8 2.8 4.5 2.8s3.3-0.8 4.5-2.8" stroke="#fff" stroke-width="1.6"
                                        fill="none" stroke-linecap="round" />
                                </svg>
                            @endif
                        </div>

                        {{-- Row 4: Target/Actual (left) + trend arrow with delta % (right, bigger) --}}
                        <div class="bottom-row">
                            <div class="values-left">
                                <div>Target:
                                    {{ $item['nilai_target'] !== null ? number_format($item['nilai_target'], 2) . ' ' . $item['satuan'] : '-' }}
                                </div>
                                <div>Actual:
                                    {{ $item['nilai_actual'] !== null ? number_format($item['nilai_actual'], 2) . ' ' . $item['satuan'] : '-' }}
                                </div>
                            </div>
                            <div class="indicator-right">
                                @if ($isNoData)
                                    <span style="font-size: 12px; color: #9ca3af;">No report yet</span>
                                @elseif (!$hasTrend)
                                    <span style="font-size: 12px; color: #9ca3af;">vs last month: -</span>
                                @else
                                    <div class="trend-arrow {{ $trendClass }}">
                                        @if ($trendNaik)
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                                <polyline points="17 6 23 6 23 12"></polyline>
                                            </svg>
                                        @else
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                                <polyline points="17 18 23 18 23 12"></polyline>
                                            </svg>
                                        @endif
                                        <span>{{ $trendNaik ? '+' : '-' }}{{ abs($trendPct) }}%</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-box">
            <div class="modal-header">
                <h2 id="modalTitle">Item Details</h2>
                <button class="modal-close" onclick="closeDetail()">&times;</button>
            </div>
            <canvas id="detailChart" height="140"></canvas>
            <table class="detail-table" id="detailTable">
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>Target</th>
                        <th>Actual</th>
                    </tr>
                </thead>
                <tbody id="detailTableBody"></tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const selects = document.querySelectorAll('.filter-group select');
            selects.forEach(select => {
                new Choices(select, {
                    searchEnabled: false,
                    itemSelectText: '',
                    shouldSort: false,
                });
            });

            const achieveCount = {{ $totalAchieve }};
            const nonAchieveCount = {{ $totalNonAchieve }};
            const noDataCount = {{ $totalNoData }};

            if (achieveCount > 0 || nonAchieveCount > 0 || noDataCount > 0) {
                new Chart(document.getElementById('summaryPieChart'), {
                    type: 'pie',
                    plugins: [ChartDataLabels],
                    data: {
                        labels: ['Achieve', 'Non-Achieve', 'No Data'],
                        datasets: [{
                            data: [achieveCount, nonAchieveCount, noDataCount],
                            backgroundColor: ['#16a34a', '#dc2626', '#9ca3af'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: 4
                        },
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 12,
                                    font: { size: 12 }
                                }
                            },
                            datalabels: {
                                color: '#ffffff',
                                font: {
                                    weight: 'bold',
                                    size: 16
                                },
                                formatter: (value, context) => {
                                    if (value === 0) return '';
                                    let total = context.dataset.data.reduce((acc, curr) => acc + curr, 0);
                                    return Math.round((value / total) * 100) + "%";
                                }
                            }
                        }
                    }
                });
            }
        });

        let detailChartInstance = null;

        function openDetail(metricId, namaItem) {
            document.getElementById('modalTitle').textContent = namaItem;
            document.getElementById('modalOverlay').classList.add('active');

            fetch(`/progress-achievement/${metricId}/detail`)
                .then(res => res.json())
                .then(data => {
                    const labels = data.history.map(h => h.periode);
                    const targets = data.history.map(h => h.target);
                    const actuals = data.history.map(h => h.actual);

                    if (detailChartInstance) {
                        detailChartInstance.destroy();
                    }

                    detailChartInstance = new Chart(document.getElementById('detailChart'), {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                { label: 'Target', data: targets, backgroundColor: '#93c5fd' },
                                { label: 'Actual', data: actuals, backgroundColor: '#3b82f6' }
                            ]
                        },
                        options: { responsive: true, scales: { y: { beginAtZero: false } } }
                    });

                    const tbody = document.getElementById('detailTableBody');
                    tbody.innerHTML = '';
                    data.history.forEach(h => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `<td>${h.periode}</td><td>${h.target ?? '-'}</td><td>${h.actual ?? '-'}</td>`;
                        tbody.appendChild(tr);
                    });
                });
        }

        function closeDetail() {
            document.getElementById('modalOverlay').classList.remove('active');
        }

        // ─── Export: Download & Print (Excel / PDF / Image) ────────────────────
        function toggleExportMenu(id) {
            document.querySelectorAll('.dropdown-menu').forEach(function (el) {
                if (el.id !== id) el.classList.remove('open');
            });
            document.getElementById(id).classList.toggle('open');
        }

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.dropdown-wrap')) {
                document.querySelectorAll('.dropdown-menu').forEach(function (el) {
                    el.classList.remove('open');
                });
            }
        });

        function exportQueryString() {
            const params = new URLSearchParams({
                periode: '{{ $periode }}',
                group_id: '{{ $groupId }}',
                status: '{{ $status }}',
            });
            return params.toString();
        }

        function downloadExcel() {
            window.location.href = '{{ route('progress-achievement.export.excel') }}?' + exportQueryString();
        }

        function downloadPdf() {
            window.location.href = '{{ route('progress-achievement.export.pdf') }}?' + exportQueryString();
        }

        function captureFullAsCanvas(callback) {
            html2canvas(document.body, {
                backgroundColor: '#f4f6f9',
                scale: 2,
                useCORS: true,
                ignoreElements: (el) => el.classList && el.classList.contains('export-hide')
            }).then(callback);
        }

        function downloadImage() {
            captureFullAsCanvas(function (canvas) {
                const link = document.createElement('a');
                link.download = 'progress-achievement-{{ $periode }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }

        function printPdf() {
            // The PDF opens in a new tab — use the print button in the PDF viewer
            window.open('{{ route('progress-achievement.export.pdf') }}?' + exportQueryString(), '_blank');
        }

        function printImage() {
            captureFullAsCanvas(function (canvas) {
                const dataUrl = canvas.toDataURL('image/png');
                const printWindow = window.open('', '_blank');
                printWindow.document.write(
                    '<html><head><title>Print Progress Achievement</title></head>' +
                    '<body style="margin:0"><img src="' + dataUrl +
                    '" style="width:100%" onload="window.print()"></body></html>'
                );
                printWindow.document.close();
            });
        }

        function printExcelNotice() {
            alert('Excel files can\'t be printed directly from the browser. The file will be downloaded — please print it manually from Excel.');
            downloadExcel();
        }
    </script>

</body>

</html>