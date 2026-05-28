<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Instituto Ulysses Guimarães</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, #1A2B5F 0%, #243a7a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 12px;
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-brand { text-align: center; margin-bottom: 2rem; }
        .login-brand .name {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            color: #1A2B5F;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .login-brand .sub {
            font-size: 0.7rem;
            color: #E8600A;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .accent-bar {
            width: 40px; height: 4px;
            background: #E8600A;
            border-radius: 2px;
            margin: 0.5rem auto 1.5rem;
        }
        .form-label { font-weight: 600; color: #1A2B5F; font-size: 0.875rem; }
        .form-control:focus {
            border-color: #E8600A;
            box-shadow: 0 0 0 0.2rem rgba(232,96,10,0.2);
        }
        .btn-login {
            background: #E8600A;
            border: none;
            color: #fff;
            width: 100%;
            padding: 0.65rem;
            font-weight: 600;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: background 0.2s;
        }
        .btn-login:hover { background: #c9530a; }
        .brand-icon { display: flex; flex-direction: column; align-items: center; gap: 2px; margin-bottom: 0.5rem; }
        .col-row { display: flex; gap: 3px; }
        .col-bar { width: 6px; background: #E8600A; border-radius: 2px 2px 0 0; }
        .col-bar:nth-child(1) { height: 20px; }
        .col-bar:nth-child(2) { height: 28px; }
        .col-bar:nth-child(3) { height: 22px; }
        .col-base { width: 26px; height: 4px; background: #E8600A; border-radius: 1px; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-brand">
            <img src="/images/logo.svg" alt="Instituto Ulysses Guimarães" style="height:64px; width:auto; margin-bottom:1rem;">
            <div class="accent-bar"></div>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:0.875rem;">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                       placeholder="admin@institutoulyssesguimaraes.com.br" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label">Senha</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-login">Entrar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
