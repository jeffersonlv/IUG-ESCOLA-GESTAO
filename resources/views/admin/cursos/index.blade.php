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

{{-- RESULTADO DE BUSCA --}}
@if($q)
<div class="card">
    <table class="table mb-0">
        <thead><tr>
            <th>Título</th><th>Data Início</th><th>Local</th><th>Status</th><th>Ativo</th><th style="width:140px;">Ações</th>
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
                        <span class="badge" style="background:#1A2B5F;">Próximo</span>
                    @endif
                </td>
                <td>
                    @if($curso->ativo)<span class="badge" style="background:#1A2B5F;">Sim</span>
                    @else<span class="badge bg-secondary">Não</span>@endif
                </td>
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
            <tr><td colspan="6" class="text-center text-muted py-4">Nenhum curso encontrado.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@else

{{-- PRÓXIMOS / EM ANDAMENTO --}}
<h6 class="text-uppercase fw-bold mb-2" style="color:#1A2B5F; font-size:0.75rem; letter-spacing:1px;">Próximos e em Andamento</h6>
<div class="card mb-4">
    <table class="table mb-0">
        <thead><tr>
            <th>Título</th><th>Data Início</th><th>Local</th><th>Ativo</th><th style="width:140px;">Ações</th>
        </tr></thead>
        <tbody>
        @forelse($proximos as $curso)
            <tr>
                <td class="fw-semibold">{{ $curso->titulo }}</td>
                <td>{{ $curso->data_inicio->format('d/m/Y') }}</td>
                <td>{{ $curso->local }}</td>
                <td>
                    @if($curso->ativo)<span class="badge" style="background:#1A2B5F;">Sim</span>
                    @else<span class="badge bg-secondary">Não</span>@endif
                </td>
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
            <tr><td colspan="5" class="text-center text-muted py-3" style="font-size:0.875rem;">Nenhum curso próximo.</td></tr>
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
            <th>Título</th><th>Data</th><th>Local</th><th>Ativo</th><th style="width:140px;">Ações</th>
        </tr></thead>
        <tbody>
        @foreach($passados as $curso)
            <tr style="opacity:0.7;">
                <td class="fw-semibold">{{ $curso->titulo }}</td>
                <td class="text-muted" style="font-size:0.875rem;">{{ $curso->data_inicio->format('d/m/Y') }}</td>
                <td>{{ $curso->local }}</td>
                <td>
                    @if($curso->ativo)<span class="badge" style="background:#1A2B5F;">Sim</span>
                    @else<span class="badge bg-secondary">Não</span>@endif
                </td>
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
@endif

@endif
@endsection
