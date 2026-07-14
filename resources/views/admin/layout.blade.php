<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 240px;
            --sidebar-w-collapsed: 68px;
            --sidebar-bg: #eff6ff; /* Bright pastel blue background */
            --sidebar-hover: #dbeafe; /* Pastel blue hover */
            --sidebar-active: #1d4ed8; /* Strong blue active background */
            --sidebar-text: #334155; /* Dark grey for navigation text */
            --sidebar-text-active: #ffffff;
            --accent: #3b82f6;
            --accent-dark: #1d4ed8;
            --surface: #ffffff;
            --bg: #f8fafc;
            --border: #e2e8f0;
            --text: #1e293b;
            --text-muted: #64748b;
            --danger: #ef4444;
            --danger-bg: #fef2f2;
            --success: #22c55e;
            --success-bg: #f0fdf4;
            --warning: #f59e0b;
            --warning-bg: #fffbeb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            border-right: 1px solid #dbeafe;
            transition: width 0.18s ease;
            overflow: visible;
        }

        .sidebar-toggle-btn {
            position: absolute;
            top: 26px;
            right: -13px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid #dbeafe;
            box-shadow: 0 2px 5px rgba(15,23,42,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 101;
            color: #1d4ed8;
            padding: 0;
        }

        .sidebar-toggle-btn svg {
            width: 14px;
            height: 14px;
            transition: transform 0.18s ease;
        }

        body.sidebar-collapsed .sidebar-toggle-btn svg {
            transform: rotate(180deg);
        }

        body.sidebar-collapsed .sidebar { width: var(--sidebar-w-collapsed); }
        body.sidebar-collapsed .main { margin-left: var(--sidebar-w-collapsed); }

        body.sidebar-collapsed .nav-section-label,
        body.sidebar-collapsed .nav-text,
        body.sidebar-collapsed .user-name,
        body.sidebar-collapsed .user-email,
        body.sidebar-collapsed .logout-text,
        body.sidebar-collapsed .sidebar-brand-full {
            display: none;
        }

        body.sidebar-collapsed .nav-item {
            justify-content: center;
            padding: 10px 0;
            gap: 0;
        }

        body.sidebar-collapsed .nav-item.active::before {
            border-radius: 0;
        }

        body.sidebar-collapsed .sidebar-footer .user-info {
            justify-content: center;
            margin-bottom: 10px;
        }

        body.sidebar-collapsed .logout-btn {
            padding: 8px;
        }

        .sidebar-brand-compact {
            display: none;
            width: 48px;
            height: 48px;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .sidebar-brand-compact img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        body.sidebar-collapsed .sidebar-brand-compact { display: flex; }

        .sidebar-brand {
            padding: 16px 20px;
            border-bottom: 1px solid #dbeafe;
            display: flex;
            align-items: center;
            gap: 12px;
            height: 84px;
        }

        .sidebar-brand img {
            height: 36px;
            object-fit: contain;
        }

        .sidebar-brand-text {
            display: none; /* Deleting text "NYS Performance Hub" and badge from sidebar */
        }

        .sidebar-nav {
            padding: 16px 0;
            flex: 1;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            color: #3b82f6;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 8px 20px 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            border-radius: 0;
            transition: background 0.15s, color 0.15s;
            position: relative;
        }

        .nav-item:hover {
            background: var(--sidebar-hover);
            color: #1e293b;
        }

        .nav-item.active {
            background: var(--sidebar-active);
            color: var(--sidebar-text-active);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: #60a5fa;
            border-radius: 0 2px 2px 0;
        }

        .nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            opacity: 0.8;
        }

        .nav-item.active .nav-icon { opacity: 1; }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid #dbeafe;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-email {
            font-size: 11px;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .logout-btn {
            width: 100%;
            padding: 8px 12px;
            background: transparent;
            border: 1px solid #cbd5e1;
            color: #475569;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 500;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .logout-btn:hover {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .badge-role-admin {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            font-size: 12px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
        }

        /* ── Main ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.18s ease;
        }

        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 16px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            height: 84px;
        }

        .page-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text);
        }

        .page-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .public-link {
            font-size: 12.5px;
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            border: 1px solid #dbeafe;
            border-radius: 6px;
            background: #eff6ff;
            transition: all 0.15s;
        }

        .public-link:hover {
            background: #dbeafe;
            color: var(--accent-dark);
        }

        .content {
            padding: 28px;
            flex: 1;
        }

        /* ── Alerts ── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13.5px;
            margin-bottom: 20px;
        }

        .alert-success { background: var(--success-bg); color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error   { background: var(--danger-bg);  color: #b91c1c; border: 1px solid #fecaca; }
        .alert-warning { background: var(--warning-bg); color: #92400e; border: 1px solid #fde68a; }

        /* ── Cards ── */
        .card {
            background: var(--surface);
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
        }

        .card-body { padding: 22px; }

        /* ── Stats ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
        }

        .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            margin-top: 6px;
            color: var(--text);
        }

        .stat-value.blue   { color: var(--accent-dark); }
        .stat-value.green  { color: #16a34a; }
        .stat-value.orange { color: #d97706; }
        .stat-value.red    { color: var(--danger); }
        .stat-value.slate  { color: #64748b; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        th {
            padding: 11px 14px;
            text-align: left;
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }

        /* ── Badges ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
        }

        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-yellow { background: #fef9c3; color: #92400e; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-slate  { background: #f1f5f9; color: #475569; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            font-family: 'Inter', sans-serif;
            transition: all 0.15s;
            white-space: nowrap;
        }

        .btn-primary { background: var(--accent-dark); color: #fff; }
        .btn-primary:hover { background: #1e40af; }

        .btn-success { background: #16a34a; color: #fff; }
        .btn-success:hover { background: #15803d; }

        .btn-warning { background: #d97706; color: #fff; }
        .btn-warning:hover { background: #b45309; }

        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #dc2626; }

        .btn-outline {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border);
        }
        .btn-outline:hover { background: #f8fafc; color: var(--text); }

        .btn-outline-danger {
            background: transparent;
            color: var(--danger);
            border: 1px solid #fecaca;
        }
        .btn-outline-danger:hover { background: var(--danger-bg); }

        .btn-sm { padding: 5px 11px; font-size: 12px; }

        .btn-actions { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }

        /* ── Forms ── */
        .form-grid { display: grid; gap: 18px; }
        .form-grid-2 { grid-template-columns: 1fr 1fr; }

        .form-group { display: flex; flex-direction: column; gap: 5px; }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .form-label .required { color: var(--danger); margin-left: 2px; }
        .form-label .optional { color: var(--text-muted); font-weight: 400; font-size: 12px; margin-left: 4px; }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 13.5px;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: #fff;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-textarea { resize: vertical; min-height: 90px; }

        .form-hint { font-size: 12px; color: var(--text-muted); }
        .form-error { font-size: 12px; color: var(--danger); }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-top: 4px;
        }

        /* ── Pagination ── */
        .pagination {
            display: flex;
            gap: 4px;
            align-items: center;
            justify-content: center;
            padding: 16px 22px;
            border-top: 1px solid var(--border);
        }

        .pagination a, .pagination span {
            padding: 6px 11px;
            border-radius: 6px;
            font-size: 13px;
            text-decoration: none;
            color: var(--text-muted);
            border: 1px solid var(--border);
        }

        .pagination a:hover { background: #f8fafc; color: var(--text); }
        .pagination .active { background: var(--accent-dark); color: #fff; border-color: var(--accent-dark); }

        /* ── Filter bar ── */
        .filter-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: flex-end;
            padding: 16px 22px;
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
        }

        .filter-bar .form-group { min-width: 160px; flex: 1; }
        .filter-bar .form-label { font-size: 12px; }

        /* ── Modal ── */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 200;
            align-items: center;
            justify-content: center;
        }

        .modal-backdrop.open { display: flex; }

        .modal {
            background: #fff;
            border-radius: 12px;
            padding: 28px;
            max-width: 420px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .modal h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .modal p { font-size: 13.5px; color: var(--text-muted); margin-bottom: 20px; }

        .modal-actions { display: flex; gap: 10px; justify-content: flex-end; }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-state svg { width: 48px; height: 48px; margin: 0 auto 12px; opacity: 0.3; display: block; }
        .empty-state h3 { font-size: 15px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .empty-state p { font-size: 13px; }
    </style>
    @stack('styles')
</head>
<body>

<script>
    if (localStorage.getItem('sidebarCollapsed') === '1') {
        document.body.classList.add('sidebar-collapsed');
    }
</script>

<!-- ── Sidebar ── -->
<aside class="sidebar">
    <button type="button" class="sidebar-toggle-btn" onclick="toggleSidebar()" title="Collapse/Expand sidebar">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

    <div class="sidebar-brand" style="justify-content: center;">
        <img src="{{ asset('img/logo-pemi.png') }}" alt="Logo PEMI" class="sidebar-brand-full" style="max-height: 40px; width: auto; margin: 0 auto;">
        <div class="sidebar-brand-compact">
            <img src="{{ asset('img/logo-pemi-icon.png') }}" alt="Logo">
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="nav-text">Dashboard</span>
        </a>

        <div class="nav-section-label" style="margin-top:8px;">Data Review</div>
        <a href="{{ route('admin.data.index') }}"
           class="nav-item {{ request()->routeIs('admin.data.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span class="nav-text">Review & Revise Data</span>
        </a>

        <div class="nav-section-label" style="margin-top:8px;">Administration</div>
        <a href="{{ route('admin.pic.index') }}"
           class="nav-item {{ request()->routeIs('admin.pic.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span class="nav-text">Manage PIC Accounts</span>
        </a>

        <a href="{{ route('admin.group.index') }}"
           class="nav-item {{ request()->routeIs('admin.group.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <span class="nav-text">Manage Groups</span>
        </a>

        <div class="nav-section-label" style="margin-top:8px;">Links</div>
        <a href="{{ route('progress-achievement') }}" class="nav-item" target="_blank">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            <span class="nav-text">Public View</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-email">{{ auth()->user()->email }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" data-confirm="Are you sure you want to logout?" data-confirm-title="Logout" data-confirm-btn="Logout">
            @csrf
            <button type="submit" class="logout-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="logout-text">Logout</span>
            </button>
        </form>
    </div>
</aside>

<!-- ── Main ── -->
<div class="main">
    <div class="topbar">
        <div>
            <div class="page-title">@yield('page-title', 'Dashboard')</div>
            @hasSection('page-subtitle')
                <div class="page-subtitle">@yield('page-subtitle')</div>
            @endif
        </div>

        <!-- Progress Achievement Title in the center of the topbar -->
        <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:2px; justify-content:center; padding:0 20px;">
            <div style="display:flex; align-items:center; gap:8px;">
                <svg style="width:22px; height:22px; min-width:22px; padding:3px; border-radius:6px; background:linear-gradient(135deg, #1d4ed8, #3b82f6); color:#ffffff; flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v18h18" />
                    <path d="M18.5 8l-5 5-3-3-4 4" />
                </svg>
                <span style="font-size:20px; font-weight:800; letter-spacing:0.3px; background:linear-gradient(90deg, #1d4ed8, #3b82f6); -webkit-background-clip:text; -webkit-text-fill-color:transparent; white-space:nowrap;">Progress Achievement</span>
            </div>
            <div style="width:40px; height:3px; border-radius:999px; background:linear-gradient(90deg, #1d4ed8, #3b82f6);"></div>
        </div>

        <div class="topbar-right">
            <span class="badge-role-admin">Admin</span>
            @yield('topbar-actions')
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>

@stack('scripts')

<!-- ── Custom Confirmation Modal ── -->
<div id="confirmModal" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;">
    <!-- Backdrop -->
    <div id="confirmBackdrop" onclick="closeConfirmModal()" style="position:absolute;inset:0;background:rgba(15,23,42,0.45);backdrop-filter:blur(3px);"></div>
    <!-- Dialog -->
    <div id="confirmDialog" style="position:relative;background:#fff;border-radius:16px;box-shadow:0 20px 60px rgba(15,23,42,0.25);padding:28px 30px;max-width:400px;width:calc(100% - 40px);margin:auto;transform:scale(0.94) translateY(-8px);transition:transform 0.2s ease, opacity 0.2s ease;opacity:0;">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
            <div id="confirmIconWrap" style="width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg id="confirmIcon" width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></svg>
            </div>
            <div>
                <div id="confirmTitle" style="font-size:16px;font-weight:700;color:#1e293b;"></div>
            </div>
        </div>
        <div id="confirmMsg" style="font-size:14px;color:#475569;line-height:1.6;margin-bottom:22px;padding-left:58px;margin-top:-12px;"></div>
        <div style="display:flex;justify-content:flex-end;gap:10px;">
            <button onclick="closeConfirmModal()" type="button" style="padding:9px 20px;background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:all 0.15s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">Cancel</button>
            <button id="confirmOkBtn" type="button" style="padding:9px 22px;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:all 0.15s;color:#fff;"></button>
        </div>
    </div>
</div>

<script>
(function() {
    window.toggleSidebar = function() {
        document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebarCollapsed', document.body.classList.contains('sidebar-collapsed') ? '1' : '0');
    };

    let _pendingAction = null;

    window.openConfirmModal = function(opts) {
        // opts: { title, message, btnText, type: 'danger'|'info'|'warning', onConfirm }
        const type = opts.type || 'danger';
        const colors = {
            danger:  { bg: '#fef2f2', icon: '#ef4444', btn: '#ef4444', btnHover: '#dc2626', path: 'M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z' },
            warning: { bg: '#fffbeb', icon: '#f59e0b', btn: '#f59e0b', btnHover: '#d97706', path: 'M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z' },
            info:    { bg: '#eff6ff', icon: '#3b82f6', btn: '#1d4ed8', btnHover: '#1e40af', path: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
        };
        const c = colors[type] || colors.info;

        document.getElementById('confirmTitle').textContent    = opts.title || 'Confirm Action';
        document.getElementById('confirmMsg').textContent      = opts.message || 'Are you sure?';
        const btn = document.getElementById('confirmOkBtn');
        btn.textContent = opts.btnText || 'Confirm';
        btn.style.background = c.btn;
        btn.onmouseover = () => { btn.style.background = c.btnHover; };
        btn.onmouseout  = () => { btn.style.background = c.btn; };

        const iconWrap = document.getElementById('confirmIconWrap');
        iconWrap.style.background = c.bg;
        const icon = document.getElementById('confirmIcon');
        icon.style.stroke = c.icon;
        icon.innerHTML = `<path d="${c.path}"/>`;

        _pendingAction = opts.onConfirm;
        btn.onclick = function() {
            closeConfirmModal();
            if (_pendingAction) _pendingAction();
        };

        const modal   = document.getElementById('confirmModal');
        const dialog  = document.getElementById('confirmDialog');
        modal.style.display = 'flex';
        requestAnimationFrame(() => {
            dialog.style.transform = 'scale(1) translateY(0)';
            dialog.style.opacity   = '1';
        });
    };

    window.closeConfirmModal = function() {
        const modal  = document.getElementById('confirmModal');
        const dialog = document.getElementById('confirmDialog');
        dialog.style.transform = 'scale(0.94) translateY(-8px)';
        dialog.style.opacity   = '0';
        setTimeout(() => { modal.style.display = 'none'; }, 180);
    };

    // ── Auto-intercept forms with data-confirm ──
    document.addEventListener('submit', function(e) {
        const form = e.target;
        const msg  = form.dataset.confirm;
        if (!msg) return;
        e.preventDefault();
        openConfirmModal({
            title:    form.dataset.confirmTitle || 'Confirm Action',
            message:  msg,
            btnText:  form.dataset.confirmBtn   || 'Confirm',
            type:     form.dataset.confirmType  || 'danger',
            onConfirm: () => { form.removeAttribute('data-confirm'); form.submit(); },
        });
    }, true);

    // ── Auto-intercept links with data-confirm ──
    document.addEventListener('click', function(e) {
        const el = e.target.closest('[data-confirm-link]');
        if (!el) return;
        e.preventDefault();
        const href = el.href || el.dataset.href;
        openConfirmModal({
            title:    el.dataset.confirmTitle || 'Confirm Action',
            message:  el.dataset.confirmLink,
            btnText:  el.dataset.confirmBtn   || 'Confirm',
            type:     el.dataset.confirmType  || 'danger',
            onConfirm: () => { window.location.href = href; },
        });
    });
})();
</script>
</body>
</html>