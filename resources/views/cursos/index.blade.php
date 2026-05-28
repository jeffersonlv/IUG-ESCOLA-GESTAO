@extends('app')

@section('title', 'Cursos — Instituto Ulysses Guimarães')

@section('content')
<div class="mb-4">
    <h1>Cursos</h1>
</div>

@forelse($cursos as $curso)
<div class="card mb-4">
    <div class="card-body">
        <h3 class="card-title fw-bold">{{ $curso->titulo }}</h3>

        <div class="row mb-2">
            <div class="col-auto">
                <strong>Data:</strong>
                {{ $curso->data_inicio->format('d/m/Y') }}
                @if($curso->data_fim->format('d/m/Y') !== $curso->data_inicio->format('d/m/Y'))
                    — {{ $curso->data_fim->format('d/m/Y') }}
                @endif
            </div>
            <div class="col-auto">
                <strong>Local:</strong> {{ $curso->local }}
            </div>
        </div>

        @if($curso->topicos)
        <div class="mt-2">
            <strong>Tópicos:</strong>
            <p class="mb-0">{{ $curso->topicos }}</p>
        </div>
        @endif
    </div>
</div>
@empty
<p class="text-muted">Nenhum curso disponível.</p>
@endforelse
@endsection
