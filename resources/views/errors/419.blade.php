<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessão Expirada — Escola de Gestão Pública Ulysses Guimarães</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background: #F0F2F8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            max-width: 420px;
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: none;
            padding: 2.5rem;
            text-align: center;
        }
        .icon { font-size: 3rem; margin-bottom: 1rem; }
        h1 { font-family: 'Montserrat', sans-serif; font-weight: 700; color: #1B3A5C; font-size: 1.4rem; }
        p { color: #666; font-size: 0.9rem; }
        .btn-primary { background: #C9962D; border-color: #C9962D; font-weight: 600; }
        .btn-primary:hover { background: #A87B22; border-color: #A87B22; }
        .btn-outline-secondary { font-weight: 600; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">⏱</div>
        <h1>Sessão Expirada</h1>
        <p class="mb-4">Sua sessão expirou ou o token de segurança é inválido. Faça login novamente para continuar.</p>

        @auth
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary w-100 mb-2">Ir para o Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">Fazer Login</a>
        @endauth
        <a href="/" class="btn btn-outline-secondary w-100">Voltar ao Site</a>
    </div>
</body>
</html>
