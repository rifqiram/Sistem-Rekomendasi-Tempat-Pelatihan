<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Admin Panel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-width: 230px;
            --topbar-height: 56px;
            --color-primary: #5E5CE6;
            --color-primary-light: #EEF2FF;
            --color-primary-hover: #4F46E5;
            --color-bg: #F3F4F6;
            --color-surface: #FFFFFF;
            --color-border: #E5E7EB;
            --color-border-light: #F3F4F6;
            --color-text: #111827;
            --color-text-secondary: #6B7280;
            --color-text-muted: #9CA3AF;
            --color-danger: #EF4444;
            --color-danger-light: #FEF2F2;
            --color-warning: #F59E0B;
            --color-warning-light: #FFFBEB;
            --color-success: #10B981;
            --color-success-light: #ECFDF5;
            --color-info: #3B82F6;
            --color-info-light: #EFF6FF;
            --radius-sm: 6px;
            --radius: 10px;
            --radius-lg: 14px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
            --shadow: 0 2px 8px rgba(0,0,0,0.09);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.1);
        }

        html, body { height: 100%; font-family: 'Inter', sans-serif; font-size: 14px; color: var(--color-text); }
        body { background: var(--color-bg); min-height: 100vh; }

        /* ============================
           SIDEBAR OVERLAY (Mobile)
        ============================ */
        .sidebar-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.45);
            z-index: 199; opacity: 0; visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.show { opacity: 1; visibility: visible; }

        /* ============================
           LAYOUT
        ============================ */
        .admin-wrapper { display: flex; min-height: 100vh; }

        /* ============================
           SIDEBAR
        ============================ */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--color-surface);
            border-right: 1px solid var(--color-border);
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 200;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            transition: transform 0.3s ease;
        }

        /* Logo / Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 20px;
            height: var(--topbar-height);
            border-bottom: 1px solid var(--color-border);
            text-decoration: none;
            flex-shrink: 0;
        }
        .brand-logo {
            width: 30px; height: 30px;
            background: var(--color-primary);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 14px; flex-shrink: 0;
        }
        .brand-text { font-weight: 700; font-size: 16px; color: var(--color-text); letter-spacing: -0.3px; }

        /* Nav */
        .sidebar-nav { flex: 1; padding: 12px 10px; }
        .nav-group { margin-bottom: 4px; }
        .nav-group-label {
            font-size: 10.5px; font-weight: 600; letter-spacing: 0.08em;
            text-transform: uppercase; color: var(--color-text-muted);
            padding: 8px 10px 4px; display: block;
        }

        .nav-item { list-style: none; }
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: var(--radius-sm);
            color: var(--color-text-secondary); text-decoration: none;
            font-weight: 500; font-size: 13.5px;
            transition: all 0.15s ease; cursor: pointer;
            white-space: nowrap;
        }
        .nav-link:hover { background: var(--color-bg); color: var(--color-text); }
        .nav-link.active { background: var(--color-primary-light); color: var(--color-primary); font-weight: 600; }
        .nav-link .nav-icon { width: 18px; text-align: center; font-size: 14px; flex-shrink: 0; }
        .nav-link p { margin: 0; }

        .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid var(--color-border);
            flex-shrink: 0;
        }
        .logout-btn {
            display: flex; align-items: center; gap: 10px;
            width: 100%; padding: 8px 10px; border-radius: var(--radius-sm);
            background: transparent; border: none;
            color: var(--color-text-secondary); font-size: 13.5px; font-weight: 500;
            cursor: pointer; transition: all 0.15s ease; font-family: inherit;
        }
        .logout-btn:hover { background: var(--color-danger-light); color: var(--color-danger); }

        /* ============================
           MAIN
        ============================ */
        .admin-main {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ============================
           TOPBAR
        ============================ */
        .admin-topbar {
            height: var(--topbar-height);
            background: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            display: flex; align-items: center;
            padding: 0 24px;
            gap: 16px;
            position: sticky; top: 0; z-index: 100;
        }
        .hamburger-btn {
            display: none; align-items: center; justify-content: center;
            width: 34px; height: 34px; border: none; background: transparent;
            color: var(--color-text); font-size: 18px; cursor: pointer;
            border-radius: var(--radius-sm); transition: background 0.15s;
        }
        .hamburger-btn:hover { background: var(--color-bg); }
        .topbar-search {
            flex: 1; max-width: 360px;
            position: relative;
        }
        .topbar-search input {
            width: 100%;
            padding: 7px 12px 7px 34px;
            background: var(--color-bg);
            border: 1px solid var(--color-border);
            border-radius: 20px;
            font-size: 13px; color: var(--color-text);
            outline: none; font-family: inherit;
            transition: border-color 0.2s;
        }
        .topbar-search input:focus { border-color: var(--color-primary); background: #fff; }
        .topbar-search input::placeholder { color: var(--color-text-muted); }
        .topbar-search .search-icon {
            position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
            color: var(--color-text-muted); font-size: 12px;
        }
        .topbar-spacer { flex: 1; }
        .topbar-actions { display: flex; align-items: center; gap: 4px; }
        .topbar-icon-btn {
            width: 34px; height: 34px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: none; background: transparent; cursor: pointer;
            color: var(--color-text-secondary); font-size: 15px;
            transition: background 0.15s ease; position: relative;
        }
        .topbar-icon-btn:hover { background: var(--color-bg); color: var(--color-text); }
        .topbar-badge {
            position: absolute; top: 3px; right: 3px;
            width: 16px; height: 16px; border-radius: 50%;
            background: var(--color-danger); color: #fff;
            font-size: 9px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }
        .topbar-divider { width: 1px; height: 22px; background: var(--color-border); margin: 0 6px; }
        .topbar-user {
            display: flex; align-items: center; gap: 8px;
            padding: 4px 8px 4px 4px; border-radius: 20px;
            cursor: pointer; transition: background 0.15s; border: none; background: transparent;
            font-family: inherit;
        }
        .topbar-user:hover { background: var(--color-bg); }
        .user-avatar-top {
            width: 30px; height: 30px; border-radius: 50%;
            background: var(--color-primary);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 12px; font-weight: 700;
        }
        .user-name-top { font-size: 13px; font-weight: 600; color: var(--color-text); }

        /* Breadcrumb */
        .page-breadcrumb {
            display: flex; align-items: center;
            padding: 14px 24px;
            gap: 6px;
            font-size: 12.5px; color: var(--color-text-secondary);
        }
        .page-breadcrumb .current { color: var(--color-text); font-weight: 500; }
        .page-breadcrumb i { font-size: 10px; }

        /* ============================
           CONTENT
        ============================ */
        .admin-content { flex: 1; padding: 0 24px 28px; }

        /* Page header */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px; padding-bottom: 16px;
            border-bottom: 1px solid var(--color-border);
        }
        .page-header h1 {
            font-size: 20px; font-weight: 700; color: var(--color-text);
            letter-spacing: -0.3px;
        }
        .page-header p { font-size: 13px; color: var(--color-text-secondary); margin-top: 2px; }

        /* ============================
           CARDS
        ============================ */
        .card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 18px;
            border-bottom: 1px solid var(--color-border);
        }
        .card-title { font-size: 14px; font-weight: 600; color: var(--color-text); }
        .card-body { padding: 18px; }
        .card-body.p-0 { padding: 0; }

        /* Stat Cards */
        .stat-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            padding: 20px;
            display: flex; align-items: center; gap: 16px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
        .stat-icon {
            width: 48px; height: 48px; border-radius: var(--radius);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
        }
        .stat-icon.info { background: var(--color-info-light); color: var(--color-info); }
        .stat-icon.success { background: var(--color-success-light); color: var(--color-success); }
        .stat-icon.warning { background: var(--color-warning-light); color: var(--color-warning); }
        .stat-icon.danger { background: var(--color-danger-light); color: var(--color-danger); }
        .stat-body { flex: 1; min-width: 0; }
        .stat-value { font-size: 26px; font-weight: 700; color: var(--color-text); line-height: 1; }
        .stat-label { font-size: 12.5px; color: var(--color-text-secondary); margin-top: 4px; }

        /* ============================
           TABLES
        ============================ */
        .table { width: 100%; border-collapse: collapse; }
        .table th {
            padding: 10px 16px;
            background: var(--color-bg);
            font-size: 11.5px; font-weight: 600;
            color: var(--color-text-secondary);
            text-transform: uppercase; letter-spacing: 0.06em;
            border-bottom: 1px solid var(--color-border);
            text-align: left; white-space: nowrap;
        }
        .table td {
            padding: 11px 16px;
            font-size: 13.5px; color: var(--color-text);
            border-bottom: 1px solid var(--color-border-light);
        }
        .table tbody tr:hover { background: #FAFBFF; }
        .table tbody tr:last-child td { border-bottom: none; }
        .table-responsive { overflow-x: auto; }
        .table-fixed { table-layout: fixed; word-wrap: break-word; }
        .text-nowrap { white-space: nowrap; }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11.5px; font-weight: 600;
        }
        .badge-success { background: var(--color-success-light); color: var(--color-success); }
        .badge-warning { background: var(--color-warning-light); color: var(--color-warning); }
        .badge-danger { background: var(--color-danger-light); color: var(--color-danger); }
        .badge-info { background: var(--color-info-light); color: var(--color-info); }
        .badge-gray { background: var(--color-bg); color: var(--color-text-secondary); }

        /* ============================
           FORMS
        ============================ */
        .form-section {
            margin-bottom: 24px;
        }
        .form-section-title {
            font-size: 13px; font-weight: 600; color: var(--color-text);
            margin-bottom: 12px; padding-bottom: 8px;
            border-bottom: 1px solid var(--color-border);
        }
        .form-group { margin-bottom: 14px; }
        .form-group label {
            display: block; margin-bottom: 5px;
            font-size: 12.5px; font-weight: 500; color: var(--color-text-secondary);
        }
        .form-group label .required { color: var(--color-danger); margin-left: 2px; }
        .form-control {
            width: 100%; padding: 8px 12px;
            background: #fff; border: 1px solid var(--color-border);
            border-radius: var(--radius-sm); color: var(--color-text); font-size: 13.5px;
            transition: border-color 0.15s, box-shadow 0.15s;
            outline: none; font-family: inherit;
        }
        .form-control:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(94,92,230,0.1); }
        .form-control::placeholder { color: var(--color-text-muted); }
        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 80px; }

        .form-check { display: flex; align-items: center; gap: 8px; }
        .form-check-input {
            width: 16px; height: 16px; accent-color: var(--color-primary); cursor: pointer;
        }
        .form-check-label { font-size: 13.5px; color: var(--color-text); cursor: pointer; }

        /* ============================
           BUTTONS
        ============================ */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 8px 16px; border-radius: var(--radius-sm);
            border: 1px solid transparent;
            font-size: 13.5px; font-weight: 600; cursor: pointer;
            transition: all 0.15s ease; text-decoration: none; font-family: inherit;
            line-height: 1.4;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: none; }
        .btn-primary {
            background: var(--color-primary); color: #fff;
            border-color: var(--color-primary);
        }
        .btn-primary:hover { background: var(--color-primary-hover); border-color: var(--color-primary-hover); }
        .btn-secondary {
            background: #fff; color: var(--color-text-secondary);
            border-color: var(--color-border);
        }
        .btn-secondary:hover { background: var(--color-bg); color: var(--color-text); }
        .btn-danger {
            background: #fff; color: var(--color-danger);
            border-color: #FCA5A5;
        }
        .btn-danger:hover { background: var(--color-danger-light); border-color: var(--color-danger); }
        .btn-warning {
            background: #fff; color: var(--color-warning);
            border-color: #FCD34D;
        }
        .btn-warning:hover { background: var(--color-warning-light); border-color: var(--color-warning); }
        .btn-info {
            background: #fff; color: var(--color-info);
            border-color: #93C5FD;
        }
        .btn-info:hover { background: var(--color-info-light); border-color: var(--color-info); }
        .btn-sm { padding: 5px 12px; font-size: 12.5px; border-radius: 5px; }
        .btn-block { width: 100%; }

        /* ============================
           ALERTS
        ============================ */
        .alert { padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13.5px; display: flex; align-items: center; gap: 8px; }
        .alert-danger { background: var(--color-danger-light); border: 1px solid #FCA5A5; color: #B91C1C; }

        /* ============================
           CONFIRM DELETE MODAL
        ============================ */
        .confirm-overlay {
            position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,0.45);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
            backdrop-filter: blur(2px);
        }
        .confirm-overlay.active { opacity: 1; visibility: visible; }
        .confirm-modal {
            background: var(--color-surface);
            border-radius: var(--radius-lg);
            box-shadow: 0 20px 60px rgba(0,0,0,0.18);
            padding: 32px 28px 24px;
            width: 100%; max-width: 420px;
            text-align: center;
            transform: scale(0.92) translateY(10px);
            transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1);
        }
        .confirm-overlay.active .confirm-modal { transform: scale(1) translateY(0); }
        .confirm-icon {
            width: 60px; height: 60px; border-radius: 50%;
            background: var(--color-danger-light);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            font-size: 26px; color: var(--color-danger);
        }
        .confirm-title {
            font-size: 17px; font-weight: 700; color: var(--color-text);
            margin-bottom: 8px; letter-spacing: -0.3px;
        }
        .confirm-message {
            font-size: 13.5px; color: var(--color-text-secondary);
            line-height: 1.6; margin-bottom: 24px;
        }
        .confirm-actions {
            display: flex; gap: 10px; justify-content: center;
        }
        .confirm-actions .btn { min-width: 110px; padding: 9px 20px; }
        .confirm-actions .btn-confirm-danger {
            background: var(--color-danger); color: #fff;
            border-color: var(--color-danger);
        }
        .confirm-actions .btn-confirm-danger:hover { background: #DC2626; border-color: #DC2626; }
        .confirm-actions .btn-confirm-cancel {
            background: #fff; color: var(--color-text-secondary);
            border: 1px solid var(--color-border);
        }
        .confirm-actions .btn-confirm-cancel:hover { background: var(--color-bg); color: var(--color-text); }

        /* ============================
           ERROR / INFO MODAL
        ============================ */
        .error-overlay {
            position: fixed; inset: 0; z-index: 10000;
            background: rgba(0,0,0,0.45);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
            backdrop-filter: blur(2px);
        }
        .error-overlay.active { opacity: 1; visibility: visible; }
        .error-modal {
            background: var(--color-surface);
            border-radius: var(--radius-lg);
            box-shadow: 0 20px 60px rgba(0,0,0,0.18);
            padding: 32px 28px 24px;
            width: 100%; max-width: 400px;
            text-align: center;
            transform: scale(0.92) translateY(10px);
            transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1);
        }
        .error-overlay.active .error-modal { transform: scale(1) translateY(0); }
        .error-icon {
            width: 60px; height: 60px; border-radius: 50%;
            background: var(--color-warning-light);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            font-size: 26px; color: var(--color-warning);
        }
        .error-title {
            font-size: 17px; font-weight: 700; color: var(--color-text);
            margin-bottom: 8px; letter-spacing: -0.3px;
        }
        .error-message {
            font-size: 13.5px; color: var(--color-text-secondary);
            line-height: 1.6; margin-bottom: 24px;
        }

        /* ============================
           GRID UTILITIES
        ============================ */
        .row { display: flex; flex-wrap: wrap; margin: 0 -8px; }
        .col-12 { width: 100%; padding: 0 8px; }
        .col-6 { width: 50%; padding: 0 8px; }
        .col-md-3 { width: 25%; padding: 0 8px; }
        .col-md-4 { width: 33.333%; padding: 0 8px; }
        .col-md-6 { width: 50%; padding: 0 8px; }
        .col-lg-3 { width: 25%; padding: 0 8px; }
        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 16px; }
        .mb-4 { margin-bottom: 20px; }
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 16px; }
        .d-none { display: none !important; }
        .d-flex { display: flex; }
        .align-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .text-muted { color: var(--color-text-secondary); }
        .text-small { font-size: 12.5px; }
        .font-medium { font-weight: 500; }
        .font-semibold { font-weight: 600; }

        /* Input group */
        .input-group { display: flex; align-items: stretch; }
        .input-group .form-control { border-radius: var(--radius-sm) 0 0 var(--radius-sm); }
        .input-group-append { display: flex; }
        .input-group-text {
            background: var(--color-bg); border: 1px solid var(--color-border);
            border-left: none; border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
            padding: 0 12px; color: var(--color-text-muted); display: flex; align-items: center;
        }

        /* Stats grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--color-border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #D1D5DB; }

        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .col-lg-3 { width: 50%; }
        }
        @media (max-width: 768px) {
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.open { transform: translateX(0); box-shadow: var(--shadow-md); }
            .admin-main { margin-left: 0; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .col-md-4, .col-md-6, .col-lg-3 { width: 100%; }
            .hamburger-btn { display: flex !important; }
        }
        @media (max-width: 500px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="admin-wrapper">
        @yield('content')
    </div>

    {{-- ===== ERROR MODAL ===== --}}
    <div class="error-overlay" id="errorOverlay" role="alertdialog" aria-modal="true" aria-labelledby="errorTitle">
        <div class="error-modal">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="error-title" id="errorTitle">Tidak Dapat Dihapus</div>
            <p class="error-message" id="errorMessage">Terjadi kesalahan.</p>
            <div class="confirm-actions">
                <button class="btn btn-primary" onclick="closeErrorModal()" style="min-width:120px;">
                    <i class="fas fa-check"></i> Mengerti
                </button>
            </div>
        </div>
    </div>

    {{-- ===== CONFIRM DELETE MODAL ===== --}}
    <div class="confirm-overlay" id="confirmOverlay" role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
        <div class="confirm-modal">
            <div class="confirm-icon">
                <i class="fas fa-trash-alt"></i>
            </div>
            <div class="confirm-title" id="confirmTitle">Hapus Data</div>
            <p class="confirm-message" id="confirmMessage">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="confirm-actions">
                <button class="btn btn-confirm-cancel" id="confirmCancelBtn" onclick="closeConfirmModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button class="btn btn-confirm-danger" id="confirmOkBtn">
                    <i class="fas fa-trash"></i> Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        window.apiBase = window.location.origin + '{{ request()->getBaseUrl() }}/api';
        window.getApiToken = function () {
            return localStorage.getItem('api_token');
        };
        window.setApiToken = function (token) {
            localStorage.setItem('api_token', token);
        };
        window.clearApiToken = function () {
            localStorage.removeItem('api_token');
            localStorage.removeItem('api_user');
        };
        window.getApiUser = function () {
            try {
                return JSON.parse(localStorage.getItem('api_user')) || null;
            } catch (err) {
                return null;
            }
        };
        window.setApiUser = function (user) {
            localStorage.setItem('api_user', JSON.stringify(user));
        };

        window.showErrorModal = function (title, message) {
            document.getElementById('errorTitle').textContent = title || 'Tidak Dapat Dihapus';
            document.getElementById('errorMessage').textContent = message || 'Terjadi kesalahan.';
            document.getElementById('errorOverlay').classList.add('active');
        };

        window.closeErrorModal = function () {
            document.getElementById('errorOverlay').classList.remove('active');
        };

        /* ===== GLOBAL CONFIRM MODAL ===== */
        window._confirmResolve = null;

        window.showConfirmModal = function (title, message) {
            return new Promise(function (resolve) {
                window._confirmResolve = resolve;
                document.getElementById('confirmTitle').textContent = title || 'Hapus Data';
                document.getElementById('confirmMessage').textContent = message || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
                document.getElementById('confirmOverlay').classList.add('active');

                document.getElementById('confirmOkBtn').onclick = function () {
                    var res = window._confirmResolve;
                    window._confirmResolve = null;
                    document.getElementById('confirmOverlay').classList.remove('active');
                    if (res) res(true);
                };
            });
        };

        window.closeConfirmModal = function () {
            document.getElementById('confirmOverlay').classList.remove('active');
            if (window._confirmResolve) {
                window._confirmResolve(false);
                window._confirmResolve = null;
            }
        };

        // Close modal when clicking backdrop
        document.addEventListener('DOMContentLoaded', function () {
            const overlay = document.getElementById('confirmOverlay');
            if (overlay) {
                overlay.addEventListener('click', function (e) {
                    if (e.target === overlay) closeConfirmModal();
                });
            }
            const errorOverlay = document.getElementById('errorOverlay');
            if (errorOverlay) {
                errorOverlay.addEventListener('click', function (e) {
                    if (e.target === errorOverlay) closeErrorModal();
                });
            }

            // Sidebar Toggle Logic
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const sidebar = document.getElementById('adminSidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (hamburgerBtn && sidebar && sidebarOverlay) {
                hamburgerBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('open');
                    sidebarOverlay.classList.toggle('show');
                });
                sidebarOverlay.addEventListener('click', function () {
                    sidebar.classList.remove('open');
                    sidebarOverlay.classList.remove('show');
                });
                
                // Close sidebar on link click (mobile)
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth <= 768) {
                            sidebar.classList.remove('open');
                            sidebarOverlay.classList.remove('show');
                        }
                    });
                });
            }
        });

        // ESC key closes modal
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeConfirmModal();
                closeErrorModal();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
