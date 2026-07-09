@extends('admin_layout')

@section('title', 'Cursos')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1>Cursos</h1>
    <a href="{{ route('admin.cursos.create') }}" class="btn btn-primary btn-sm">+ Novo Curso</a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif

<form method="GET" class="mb-4 d-flex gap-2" style="max-width:400px;">
    <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Buscar por título ou local...">
    <button class="btn btn-sm btn-outline-primary px-3">Buscar</button>
    @if($q)<a href="{{ route('admin.cursos.index') }}" class="btn btn-sm btn-outline-secondary">✕</a>@endif
</form>

@php
    function cursoSortUrl($col, $sort, $dir) {
        return request()->fullUrlWithQuery(['sort' => $col, 'dir' => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc']);
    }
    function cursoSortIcon($col, $sort, $dir) {
        return $sort === $col ? ($dir === 'asc' ? ' ▲' : ' ▼') : ' ⇅';
    }
@endphp

{{-- RESULTADO DE BUSCA --}}
@if($q)
<div class="card">
    <table class="table mb-0">
        <thead><tr>
            <th><a href="{{ cursoSortUrl('titulo', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Título{!! cursoSortIcon('titulo', $sort, $dir) !!}</a></th>
            <th><a href="{{ cursoSortUrl('data_inicio', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Data Início{!! cursoSortIcon('data_inicio', $sort, $dir) !!}</a></th>
            <th><a href="{{ cursoSortUrl('local', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Local{!! cursoSortIcon('local', $sort, $dir) !!}</a></th>
            <th>Status</th><th>Ativo</th><th style="width:70px; text-align:center;">Flyer DL</th><th style="width:140px;">Ações</th>
        </tr></thead>
        <tbody>
        @forelse($cursos as $curso)
            <tr @if($curso->data_fim->isPast()) style="opacity:0.65;" @endif>
                <td class="fw-semibold">{{ $curso->titulo }}</td>
                <td>{{ $curso->data_inicio->format('d/m/Y') }}</td>
                <td>{{ $curso->local }}</td>
                <td>
                    @if($curso->data_fim->isPast())
                        <span class="badge bg-secondary">Realizado</span>
                    @else
                        <span class="badge" style="background:#1B3A5C;">Próximo</span>
                    @endif
                </td>
                <td>
                    @if($curso->ativo)<span class="badge" style="background:#1B3A5C;">Sim</span>
                    @else<span class="badge bg-secondary">Não</span>@endif
                </td>
                <td style="text-align:center; font-size:0.875rem;">{{ $curso->flyer_downloads ?? 0 }}</td>
                <td>
                    <a href="{{ route('admin.cursos.edit', $curso->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Deletar este curso?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Del</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-4">Nenhum curso encontrado.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@else

{{-- PRÓXIMOS / EM ANDAMENTO --}}
<h6 class="text-uppercase fw-bold mb-2" style="color:#1B3A5C; font-size:0.75rem; letter-spacing:1px;">Próximos e em Andamento</h6>
<div class="card mb-4">
    <table class="table mb-0">
        <thead><tr>
            <th><a href="{{ cursoSortUrl('titulo', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Título{!! cursoSortIcon('titulo', $sort, $dir) !!}</a></th>
            <th><a href="{{ cursoSortUrl('data_inicio', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Data Início{!! cursoSortIcon('data_inicio', $sort, $dir) !!}</a></th>
            <th><a href="{{ cursoSortUrl('local', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Local{!! cursoSortIcon('local', $sort, $dir) !!}</a></th>
            <th>Status</th><th>Ativo</th><th style="width:70px; text-align:center;">Flyer DL</th><th style="width:140px;">Ações</th>
        </tr></thead>
        <tbody>
        @forelse($proximos as $curso)
            @php
                $hoje  = now()->startOfDay();
                $ini   = $curso->data_inicio->startOfDay();
                $fim   = $curso->data_fim->startOfDay();
                $emAndamento = $ini <= $hoje && $fim >= $hoje;
                $diasInicia  = $hoje->diffInDays($ini, false);
            @endphp
            <tr>
                <td class="fw-semibold">{{ $curso->titulo }}</td>
                <td>{{ $curso->data_inicio->format('d/m/Y') }}</td>
                <td>{{ $curso->local }}</td>
                <td>
                    @if($emAndamento)
                        <span class="badge" style="background:#C9962D;">Em andamento</span>
                    @else
                        <span class="badge bg-primary">Começa em {{ $diasInicia }} dia{{ $diasInicia != 1 ? 's' : '' }}</span>
                    @endif
                </td>
                <td>
                    @if($curso->ativo)<span class="badge" style="background:#1B3A5C;">Sim</span>
                    @else<span class="badge bg-secondary">Não</span>@endif
                </td>
                <td style="text-align:center; font-size:0.875rem;">{{ $curso->flyer_downloads ?? 0 }}</td>
                <td>
                    <a href="{{ route('admin.cursos.edit', $curso->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Deletar este curso?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Del</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-3" style="font-size:0.875rem;">Nenhum curso próximo.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- REALIZADOS --}}
@if($passados->count())
<h6 class="text-uppercase fw-bold mb-2" style="color:#888; font-size:0.75rem; letter-spacing:1px;">Realizados</h6>
<div class="card">
    <table class="table mb-0">
        <thead><tr>
            <th><a href="{{ cursoSortUrl('titulo', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Título{!! cursoSortIcon('titulo', $sort, $dir) !!}</a></th>
            <th><a href="{{ cursoSortUrl('data_inicio', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Data{!! cursoSortIcon('data_inicio', $sort, $dir) !!}</a></th>
            <th><a href="{{ cursoSortUrl('local', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Local{!! cursoSortIcon('local', $sort, $dir) !!}</a></th>
            <th>Status</th><th>Ativo</th><th style="width:70px; text-align:center;">Flyer DL</th><th style="width:140px;">Ações</th>
        </tr></thead>
        <tbody>
        @foreach($passados as $curso)
            @php $diasTerminou = $curso->data_fim->startOfDay()->diffInDays(now()->startOfDay()); @endphp
            <tr style="opacity:0.7;">
                <td class="fw-semibold">{{ $curso->titulo }}</td>
                <td class="text-muted" style="font-size:0.875rem;">{{ $curso->data_inicio->format('d/m/Y') }}</td>
                <td>{{ $curso->local }}</td>
                <td><span class="badge bg-secondary">Terminou há {{ $diasTerminou }} dia{{ $diasTerminou != 1 ? 's' : '' }}</span></td>
                <td>
                    @if($curso->ativo)<span class="badge" style="background:#1B3A5C;">Sim</span>
                    @else<span class="badge bg-secondary">Não</span>@endif
                </td>
                <td style="text-align:center; font-size:0.875rem;">{{ $curso->flyer_downloads ?? 0 }}</td>
                <td>
                    <a href="{{ route('admin.cursos.edit', $curso->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Deletar este curso?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Del</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
    @if($passados->hasPages())
    <div class="mt-3">{{ $passados->appends(['q' => $q])->links() }}</div>
    @endif
@endif

@endif
@endsection
