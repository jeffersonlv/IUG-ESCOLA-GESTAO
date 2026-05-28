@extends('app')

@section('content')
<div class="container">
    <h1>{{ $curso->titulo }}</h1>
    <p><strong>Data:</strong> {{ $curso->data_inicio->format('d/m/Y') }} - {{ $curso->data_fim->format('d/m/Y') }}</p>
    <p><strong>Local:</strong> {{ $curso->local }}</p>
    <a href="{{ route('cursos.index') }}">Voltar</a>
</div>
@endsection
