@extends('app')

@section('content')
<div class="container">
    <h1>Contato</h1>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <form action="{{ route('contato.store') }}" method="POST">
        @csrf
        <div>
            <label>Nome:</label>
            <input type="text" name="nome" required>
            @error('nome') <span>{{ $message }}</span> @enderror
        </div>
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
            @error('email') <span>{{ $message }}</span> @enderror
        </div>
        <div>
            <label>Mensagem:</label>
            <textarea name="mensagem" required></textarea>
            @error('mensagem') <span>{{ $message }}</span> @enderror
        </div>
        <input type="hidden" name="website">
        <button type="submit">Enviar</button>
    </form>
</div>
@endsection
