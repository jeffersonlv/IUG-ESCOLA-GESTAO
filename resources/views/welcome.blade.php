@extends('app')

@section('title', 'Instituto Ulysses Guimarães — Gestão Pública')

@section('content')
{{-- Hero Banner --}}
<div style="width:100%; max-height:418px; overflow:hidden; line-height:0;">
    <img src="/images/banner.jpg" alt="Instituto Ulysses Guimarães" style="width:100%; object-fit:cover; display:block;">
</div>

<div class="container py-5">

    {{-- Sobre --}}
    @if(!empty($configs['sobre_texto']))
    <div class="mb-5" id="sobre">
        <div class="accent-bar"></div>
        <h2 class="fw-bold mb-3" style="color:#1A2B5F; font-size:1.3rem;">Sobre o Instituto</h2>
        <p class="text-muted" style="font-size:1rem; max-width:760px;">{{ $configs['sobre_texto'] }}</p>
        @if(!empty($configs['publico_alvo']))
        <p class="mb-0"><strong style="color:#1A2B5F;">Público-alvo:</strong> <span class="text-muted">{{ $configs['publico_alvo'] }}</span></p>
        @endif
    </div>
    @endif

    {{-- Cursos --}}
    <div class="mb-5" id="cursos">
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
    <div id="documentos">
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
