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

<form method="GET" class="mb-3 d-flex gap-2" style="max-width:400px;">
    <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Buscar por título ou local...">
    <button class="btn btn-sm btn-outline-primary px-3">Buscar</button>
    @if($q)<a href="{{ route('admin.cursos.index') }}" class="btn btn-sm btn-outline-secondary">✕</a>@endif
</form>

<div class="card">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Título</th>
                <th>Data Início</th>
                <th>Local</th>
                <th>Ativo</th>
                <th style="width:140px;">Ações</th>
            </tr>
        </thead>
        <tbody>
        @forelse($cursos as $curso)
            <tr>
                <td class="fw-semibold">{{ $curso->titulo }}</td>
                <td>{{ $curso->data_inicio->format('d/m/Y') }}</td>
                <td>{{ $curso->local }}</td>
                <td>
                    @if($curso->ativo)
                        <span class="badge" style="background:#1A2B5F;">Sim</span>
                    @else
                        <span class="badge bg-secondary">Não</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.cursos.edit', $curso->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Deletar este curso?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Del</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Nenhum curso encontrado.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@if($cursos->hasPages())
<div class="mt-3">{{ $cursos->links() }}</div>
@endif
@endsection
