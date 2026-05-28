@extends('app')

@section('content')
<div class="container">
    <h1>Mensagem de {{ $mensagem->nome }}</h1>
    <p><strong>Email:</strong> {{ $mensagem->email }}</p>
    <p><strong>Mensagem:</strong></p>
    <p>{{ $mensagem->mensagem }}</p>
    <a href="{{ route('admin.mensagens.index') }}">Voltar</a>
</div>
@endsection
