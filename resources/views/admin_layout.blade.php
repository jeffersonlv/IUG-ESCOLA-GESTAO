<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Escola de Gestão Pública Ulysses Guimarães</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --iug-orange: #C9962D;
            --iug-navy:   #1B3A5C;
            --iug-sidebar: #102438;
            --iug-light:  #F0F2F8;
            --iug-border: #DDE1EB;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Open Sans', sans-serif;
            background: var(--iug-light);
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Montserrat', sans-serif;
        }

        /* ── Top bar ── */
        .admin-topbar {
            background: var(--iug-navy);
            height: 56px;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            flex: 1;
        }

        .brand-icon { display: flex; flex-direction: column; align-items: center; gap: 2px; }
        .col-row { display: flex; gap: 3px; }
        .col-bar { width: 4px; background: var(--iug-orange); border-radius: 2px 2px 0 0; }
        .col-bar:nth-child(1) { height: 14px; }
        .col-bar:nth-child(2) { height: 19px; }
        .col-bar:nth-child(3) { height: 16px; }
        .col-base { width: 18px; height: 3px; background: var(--iug-orange); border-radius: 1px; }

        .topbar-brand .brand-name {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 0.8rem;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── Switch site/admin ── */
        .mode-switch {
            display: flex;
            align-items: center;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 3px;
            gap: 2px;
        }

        .mode-switch a, .mode-switch span {
            padding: 4px 14px;
            border-radius: 16px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .mode-switch .mode-inactive {
            color: rgba(255,255,255,0.6);
        }

        .mode-switch .mode-inactive:hover {
            color: #fff;
        }

        .mode-switch .mode-active-site {
            background: #fff;
            color: var(--iug-navy);
        }

        .mode-switch .mode-active-admin {
            background: var(--iug-orange);
            color: #fff;
        }

        /* ── Topbar right ── */
        .topbar-user {
            color: rgba(255,255,255,0.7);
            font-size: 0.8rem;
        }

        .btn-topbar-logout {
            background: none;
            border: 1px solid rgba(255,255,255,0.25);
            color: rgba(255,255,255,0.7);
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-topbar-logout:hover {
            border-color: var(--iug-orange);
            color: var(--iug-orange);
        }

        /* ── Layout wrapper ── */
        .admin-wrapper {
            display: flex;
            flex: 1;
        }

        /* ── Sidebar ── */
        .admin-sidebar {
            width: 220px;
            background: var(--iug-sidebar);
            flex-shrink: 0;
            padding: 1.5rem 0;
        }

        .sidebar-section {
            padding: 0 0.75rem 1rem;
        }

        .sidebar-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: rgba(255,255,255,0.35);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 0 0.75rem;
            margin-bottom: 0.4rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.55rem 0.75rem;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.15s;
            margin-bottom: 2px;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }

        .sidebar-link.active {
            background: var(--iug-orange);
            color: #fff;
        }

        .sidebar-link .icon {
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        /* ── Main content ── */
        .admin-main {
            flex: 1;
            padding: 2rem;
            overflow-x: auto;
        }

        /* ── Page header ── */
        .admin-page-header {
            margin-bottom: 1.5rem;
        }

        .admin-page-header h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--iug-navy);
            margin: 0;
        }

        .admin-page-header h1 span { color: var(--iug-orange); }

        /* ── Cards / Tables ── */
        .card {
            border: 1px solid var(--iug-border);
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }

        .table thead th {
            background: var(--iug-navy);
            color: #fff;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
        }

        .table tbody tr:hover { background: #f0f2f8; }

        /* ── Forms ── */
        .form-label {
            font-weight: 600;
            color: var(--iug-navy);
            font-size: 0.875rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--iug-orange);
            box-shadow: 0 0 0 0.2rem rgba(201,150,45,0.2);
        }

        /* ── Buttons ── */
        .btn-primary {
            background: var(--iug-orange);
            border-color: var(--iug-orange);
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #A87B22;
            border-color: #A87B22;
        }

        .btn-outline-primary {
            color: var(--iug-orange);
            border-color: var(--iug-orange);
            font-weight: 600;
        }

        .btn-outline-primary:hover {
            background: var(--iug-orange);
            border-color: var(--iug-orange);
        }

        /* ── Accent bar ── */
        .accent-bar {
            width: 36px;
            height: 4px;
            background: var(--iug-orange);
            border-radius: 2px;
            margin-bottom: 1rem;
        }

        /* ── Alerts ── */
        .alert-success {
            background: #eaf6ee;
            border-color: #a8d5b5;
            color: #256235;
        }

        /* ── Responsive sidebar ── */
        @media (max-width: 768px) {
            .admin-sidebar { display: none; }
            .admin-main { padding: 1rem; }
        }

        /* ── Mobile offcanvas sidebar ── */
        .offcanvas-sidebar {
            width: 240px;
            background: var(--iug-sidebar);
        }

        .offcanvas-sidebar .offcanvas-header {
            background: var(--iug-navy);
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .offcanvas-sidebar .offcanvas-body {
            padding: 1rem 0.75rem;
        }

        .btn-hamburger {
            background: none;
            border: none;
            color: rgba(255,255,255,0.8);
            cursor: pointer;
            padding: 4px;
            display: none;
        }

        @media (max-width: 768px) {
            .btn-hamburger { display: flex; align-items: center; }
        }
    </style>
    @yield('styles')
</head>
<body>

{{-- Top Bar --}}
<div class="admin-topbar">
    <button class="btn-hamburger me-2" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>

    <a href="{{ route('admin.dashboard') }}" class="topbar-brand">
        <img src="/images/logo.png" alt="Escola de Gestão Pública Ulysses Guimarães" style="height:36px; width:auto; filter:brightness(0) invert(1);">
    </a>

    <div class="mode-switch">
        <a href="/" class="mode-inactive">Site</a>
        <span class="mode-active-admin">Admin</span>
    </div>

    <div class="d-flex align-items-center gap-3 ms-auto">
        <span class="topbar-user d-none d-md-block">{{ auth()->user()->name }}</span>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button class="btn-topbar-logout">Sair</button>
        </form>
    </div>
</div>

{{-- Mobile Offcanvas Sidebar --}}
<div class="offcanvas offcanvas-start offcanvas-sidebar" tabindex="-1" id="sidebarMobile">
    <div class="offcanvas-header">
        <img src="/images/logo.png" alt="Escola de Gestão Pública Ulysses Guimarães" style="height:32px; filter:brightness(0) invert(1);">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div class="sidebar-label">Menu</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><span class="icon">🏠</span> Dashboard</a>
        <a href="{{ route('admin.cursos.index') }}" class="sidebar-link {{ request()->routeIs('admin.cursos.*') ? 'active' : '' }}"><span class="icon">🎓</span> Cursos</a>
        <a href="{{ route('admin.documentos.index') }}" class="sidebar-link {{ request()->routeIs('admin.documentos.*') ? 'active' : '' }}"><span class="icon">📄</span> Documentos</a>
        <a href="{{ route('admin.palestrantes.index') }}" class="sidebar-link {{ request()->routeIs('admin.palestrantes.*') ? 'active' : '' }}"><span class="icon">🎤</span> Palestrantes</a>
        <a href="{{ route('admin.alunos.index') }}" class="sidebar-link {{ request()->routeIs('admin.alunos.*') ? 'active' : '' }}"><span class="icon">👨‍🎓</span> Alunos</a>
        <a href="{{ route('admin.certificados.index') }}" class="sidebar-link {{ request()->routeIs('admin.certificados.*') ? 'active' : '' }}"><span class="icon">🎓</span> Certificados</a>
        <div class="sidebar-label mt-3">Sistema</div>
        <a href="{{ route('admin.config.index') }}" class="sidebar-link {{ request()->routeIs('admin.config.*') ? 'active' : '' }}"><span class="icon">⚙️</span> Configurações</a>
    </div>
</div>

<div class="admin-wrapper">
    {{-- Sidebar --}}
    <aside class="admin-sidebar">
        <div class="sidebar-section">
            <div class="sidebar-label">Menu</div>
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icon">🏠</span> Dashboard
            </a>
            <a href="{{ route('admin.cursos.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.cursos.*') ? 'active' : '' }}">
                <span class="icon">🎓</span> Cursos
            </a>
            <a href="{{ route('admin.documentos.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.documentos.*') ? 'active' : '' }}">
                <span class="icon">📄</span> Documentos
            </a>
            <a href="{{ route('admin.palestrantes.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.palestrantes.*') ? 'active' : '' }}">
                <span class="icon">🎤</span> Palestrantes
            </a>
            <a href="{{ route('admin.alunos.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.alunos.*') ? 'active' : '' }}">
                <span class="icon">👨‍🎓</span> Alunos
            </a>
            <a href="{{ route('admin.certificados.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.certificados.*') ? 'active' : '' }}">
                <span class="icon">🎓</span> Certificados
            </a>
        </div>
        <div class="sidebar-section">
            <div class="sidebar-label">Sistema</div>
            <a href="{{ route('admin.config.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.config.*') ? 'active' : '' }}">
                <span class="icon">⚙️</span> Configurações
            </a>
        </div>
    </aside>

    {{-- Main --}}
    <main class="admin-main">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.getElementById('busca-palestrantes');
    if (!input) return;
    input.addEventListener('input', function () {
        var val = this.value.toLowerCase();
        document.querySelectorAll('.palestrante-item').forEach(function (item) {
            item.style.display = item.dataset.nome.includes(val) ? '' : 'none';
        });
    });
});
</script>
@yield('scripts')
</body>
</html>
