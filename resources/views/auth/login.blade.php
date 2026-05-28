@extends('app')

@section('content')
<div class="login-container">
    <h1>Login</h1>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
            @error('email') <span>{{ $message }}</span> @enderror
        </div>
        <div>
            <label>Senha:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Entrar</button>
    </form>
</div>
@endsection
