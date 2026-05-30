@extends('app')

@section('title', 'Cursos — Instituto Ulysses Guimarães')

@section('content')
<div class="page-header">
    <div class="container">
        <h1><span>Cursos</span> e Capacitações</h1>
    </div>
</div>

<div class="container pb-5">
    @forelse($cursos as $curso)
    <div class="card mb-4">
        <div class="card-body p-4">
            <div class="accent-bar"></div>
            <h3 class="card-title fw-bold mb-3">{{ $curso->titulo }}</h3>

            <div class="d-flex flex-wrap gap-3 mb-3">
                <div class="d-flex align-items-center gap-1 text-muted small">
                    <span style="color:#E8600A;">📅</span>
                    <span>
                        {{ $curso->data_inicio->format('d/m/Y') }}
                        @if($curso->data_fim->format('d/m/Y') !== $curso->data_inicio->format('d/m/Y'))
                            — {{ $curso->data_fim->format('d/m/Y') }}
                        @endif
                    </span>
                </div>
                <div class="d-flex align-items-center gap-1 text-muted small">
                    <span style="color:#E8600A;">📍</span>
                    <span>{{ $curso->local }}</span>
                </div>
            </div>

            @if($curso->topicos)
            <div class="mt-2 pt-2 border-top">
                <p class="fw-semibold text-navy mb-1" style="font-size:0.875rem; color:#1A2B5F;">Tópicos abordados:</p>
                <p class="mb-0 text-muted" style="font-size:0.9rem;">{{ $curso->topicos }}</p>
            </div>
            @endif

            @if($curso->folder_pdf)
            @php $folderUrl = '/storage/' . $curso->folder_pdf; @endphp
            <div class="mt-3 pt-3 border-top">
                <div style="width:100%; height:280px; overflow:hidden; border:1px solid #dde1eb; border-radius:6px;">
                    <iframe src="{{ $folderUrl }}" style="width:100%; height:100%; border:none;" loading="lazy"></iframe>
                </div>
                <div class="mt-2">
                    <a href="{{ $folderUrl }}" target="_blank" download
                       class="btn btn-block w-100"
                       style="background:#E8600A; color:#fff; font-weight:700; border:none; padding:10px;">
                        ⬇ Download Flyer
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-5 text-muted">
        <p>Nenhum curso disponível no momento.</p>
    </div>
    @endforelse
</div>
@endsection
