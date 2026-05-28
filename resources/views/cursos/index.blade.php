@extends('app')

@section('content')
<div class="container">
    <h1>Cursos</h1>
    <ul>
    @forelse($cursos as $curso)
        <li><a href="{{ route('cursos.show', $curso->id) }}">{{ $curso->titulo }}</a></li>
    @empty
        <p>Nenhum curso disponível.</p>
    @endforelse
    </ul>
</div>
@endsection
