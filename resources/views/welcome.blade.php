@extends('app')

@section('title', 'Instituto Ulysses Guimarães — Gestão Pública')

@section('content')
{{-- Hero --}}
<div style="background: linear-gradient(135deg, #1A2B5F 0%, #243a7a 100%); padding: 4rem 0 3rem;">
    <div class="container text-center text-white">
        <div class="accent-bar mx-auto mb-3" style="background:#E8600A;"></div>
        <h1 style="font-size:2rem; font-weight:700; margin-bottom:1rem;">
            Instituto Ulysses Guimarães
        </h1>
        <p style="font-size:1rem; color:rgba(255,255,255,0.75); max-width:520px; margin:0 auto;">
            Formação e capacitação em Gestão Pública para servidores e gestores municipais.
        </p>
    </div>
</div>

<div class="container py-5">

    {{-- Cursos --}}
    <div class="mb-5">
        <div class="accent-bar"></div>
        <h2 class="fw-bold mb-4" style="color:#1A2B5F; font-size:1.3rem;">Cursos e Capacitações</h2>

        @forelse($cursos as $curso)
        <div class="card mb-3">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-2" style="color:#1A2B5F;">{{ $curso->titulo }}</h5>
                <div class="d-flex flex-wrap gap-3 mb-2">
                    <span class="text-muted small">📅
                        {{ $curso->data_inicio->format('d/m/Y') }}
                        @if($curso->data_fim->format('d/m/Y') !== $curso->data_inicio->format('d/m/Y'))
                            — {{ $curso->data_fim->format('d/m/Y') }}
                        @endif
                    </span>
                    <span class="text-muted small">📍 {{ $curso->local }}</span>
                </div>
                @if($curso->topicos)
                <p class="text-muted mb-0" style="font-size:0.875rem;">{{ $curso->topicos }}</p>
                @endif
            </div>
        </div>
        @empty
        <p class="text-muted">Nenhum curso disponível no momento.</p>
        @endforelse
    </div>

    {{-- Documentos --}}
    <div>
        <div class="accent-bar"></div>
        <h2 class="fw-bold mb-4" style="color:#1A2B5F; font-size:1.3rem;">Documentos e Materiais</h2>

        @forelse($documentos as $doc)
        <div class="card mb-3">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div class="d-flex align-items-center gap-3">
                    <span style="font-size:1.4rem; color:#E8600A;">📄</span>
                    <p class="fw-semibold mb-0" style="color:#1A2B5F;">{{ $doc->nome }}</p>
                </div>
                @if($doc->arquivo_pdf)
                <a href="{{ route('download.documento', $doc->id) }}" class="btn btn-primary btn-sm">Download</a>
                @endif
            </div>
        </div>
        @empty
        <p class="text-muted">Nenhum documento disponível no momento.</p>
        @endforelse
    </div>

</div>
@endsection
