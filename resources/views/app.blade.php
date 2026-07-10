<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Escola de Gestão Pública Ulysses Guimarães')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }

        :root {
            --iug-orange: #C9962D;
            --iug-navy:   #1B3A5C;
            --iug-light:  #F5F6FA;
            --iug-border: #DDE1EB;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: var(--iug-light);
            color: #333;
        }

        h1, h2, h3, h4, h5, .navbar-brand {
            font-family: 'Montserrat', sans-serif;
        }

        /* ── Navbar ── */
        .navbar {
            background: #fff !important;
            padding: 0.6rem 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-bottom: 3px solid var(--iug-orange);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-brand .brand-icon {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
        }

        .navbar-brand .brand-icon .col-row {
            display: flex;
            gap: 3px;
        }

        .navbar-brand .brand-icon .col-bar {
            width: 5px;
            background: var(--iug-orange);
            border-radius: 2px 2px 0 0;
        }

        .navbar-brand .brand-icon .col-bar:nth-child(1) { height: 18px; }
        .navbar-brand .brand-icon .col-bar:nth-child(2) { height: 24px; }
        .navbar-brand .brand-icon .col-bar:nth-child(3) { height: 20px; }

        .navbar-brand .brand-icon .col-base {
            width: 22px;
            height: 3px;
            background: var(--iug-orange);
            border-radius: 1px;
        }

        .navbar-brand .brand-text {
            line-height: 1.1;
        }

        .navbar-brand .brand-text .name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .navbar-brand .brand-text .sub {
            font-size: 0.65rem;
            color: var(--iug-orange);
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .nav-link {
            color: var(--iug-navy) !important;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.5rem 1rem !important;
            border-radius: 4px;
            transition: color 0.2s, background 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--iug-orange) !important;
            background: rgba(201,150,45,0.07);
        }

        .btn-logout-nav {
            background: none;
            border: 1px solid rgba(201,150,45,0.6);
            color: var(--iug-orange) !important;
            padding: 0.3rem 0.9rem !important;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-logout-nav:hover {
            background: var(--iug-orange);
            color: #fff !important;
            border-color: var(--iug-orange);
        }

        /* ── Page header strip ── */
        .page-header {
            background: var(--iug-navy);
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            color: #fff;
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
        }

        .page-header h1 span {
            color: var(--iug-orange);
        }

        /* ── Cards ── */
        .card {
            border: 1px solid var(--iug-border);
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }

        .card-title {
            color: var(--iug-navy);
        }

        .card-header-iug {
            background: var(--iug-navy);
            color: #fff;
            border-radius: 8px 8px 0 0 !important;
            padding: 0.75rem 1.25rem;
            font-weight: 700;
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

        .btn-navy {
            background: var(--iug-navy);
            border-color: var(--iug-navy);
            color: #fff;
            font-weight: 600;
        }

        .btn-navy:hover {
            background: #122840;
            color: #fff;
        }

        /* ── Alerts ── */
        .alert-success {
            background: #eaf6ee;
            border-color: #a8d5b5;
            color: #256235;
        }

        /* ── Table ── */
        .table thead th {
            background: var(--iug-navy);
            color: #fff;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
        }

        .table tbody tr:hover {
            background: #f0f2f8;
        }

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

        /* ── Orange accent bar ── */
        .accent-bar {
            width: 40px;
            height: 4px;
            background: var(--iug-orange);
            border-radius: 2px;
            margin-bottom: 0.75rem;
        }

        /* ── Footer ── */
        footer {
            background: var(--iug-navy);
            color: rgba(255,255,255,0.6);
            text-align: center;
            padding: 1.5rem 0;
            font-size: 0.8rem;
            margin-top: 4rem;
        }

        footer span { color: var(--iug-orange); }

        /* ── Mode switch (logged in, public side) ── */
        .mode-switch-nav {
            display: flex;
            align-items: center;
            background: rgba(27,58,92,0.08);
            border-radius: 20px;
            padding: 3px;
            gap: 2px;
        }

        .mode-switch-nav span, .mode-switch-nav a {
            padding: 4px 14px;
            border-radius: 16px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
        }

        .mode-active-site {
            background: var(--iug-navy);
            color: #fff;
        }

        .mode-inactive-nav {
            color: var(--iug-navy);
            opacity: 0.5;
            transition: opacity 0.15s;
        }

        .mode-inactive-nav:hover {
            opacity: 1;
        }
    </style>
    @yield('styles')
    @guest
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-2D8E1Y4PW1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-2D8E1Y4PW1');
    </script>
    @endguest
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="/images/logo.png" alt="Escola de Gestão Pública Ulysses Guimarães" style="height: 85px;width:auto;">
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="filter:invert(1) sepia(1) saturate(5) hue-rotate(175deg);">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-1">
                    <li class="nav-item"><a class="nav-link" href="/#sobre">Sobre</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#cursos">Cursos</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#documentos">Transparência</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#contato">Contato</a></li>
                    @auth
                        <li class="nav-item">
                            <div class="mode-switch-nav">
                                <span class="mode-active-site">Site</span>
                                <a href="{{ route('admin.dashboard') }}" class="mode-inactive-nav">Admin</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn-logout-nav">Sair</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            &copy; {{ date('Y') }} <span>Escola de Gestão Pública Ulysses Guimarães</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
