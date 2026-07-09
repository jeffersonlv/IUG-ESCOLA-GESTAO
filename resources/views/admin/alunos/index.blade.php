@extends('admin_layout')

@section('title', 'Alunos')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1>Alunos</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.alunos.lote') }}" class="btn btn-outline-primary btn-sm">+ Lote</a>
        <a href="{{ route('admin.alunos.create') }}" class="btn btn-primary btn-sm">+ Novo Aluno</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif

<form method="GET" class="mb-3 d-flex gap-2" style="max-width:400px;">
    <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Buscar por nome, cidade ou estado...">
    <button class="btn btn-sm btn-outline-primary px-3">Buscar</button>
    @if($q)<a href="{{ route('admin.alunos.index') }}" class="btn btn-sm btn-outline-secondary">✕</a>@endif
</form>

<div class="card">
    <table class="table mb-0">
        @php
            function alunoSortUrl($col, $sort, $dir) {
                return request()->fullUrlWithQuery(['sort' => $col, 'dir' => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc']);
            }
            function alunoSortIcon($col, $sort, $dir) {
                return $sort === $col ? ($dir === 'asc' ? ' ▲' : ' ▼') : ' ⇅';
            }
        @endphp
        <thead>
            <tr>
                <th><a href="{{ alunoSortUrl('nome_completo', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Nome Completo{!! alunoSortIcon('nome_completo', $sort, $dir) !!}</a></th>
                <th>
                    <a href="{{ alunoSortUrl('cidade', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Cidade{!! alunoSortIcon('cidade', $sort, $dir) !!}</a>
                    /
                    <a href="{{ alunoSortUrl('estado', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Estado{!! alunoSortIcon('estado', $sort, $dir) !!}</a>
                </th>
                <th>Cursos</th>
                <th style="width:140px;">Ações</th>
            </tr>
        </thead>
        <tbody>
        @forelse($alunos as $aluno)
            <tr>
                <td class="fw-semibold align-middle">{{ $aluno->nome_completo }}</td>
                <td class="align-middle text-muted" style="font-size:0.875rem;">{{ $aluno->cidade }} — {{ $aluno->estado }}</td>
                <td class="align-middle">
                    @php $cursos = $aluno->cursos @endphp
                    @if($cursos->count())
                        <span class="badge" style="background:#1B3A5C;">{{ $cursos->count() }} curso(s)</span>
                    @else
                        <span class="text-muted" style="font-size:0.8rem;">—</span>
                    @endif
                </td>
                <td class="align-middle">
                    <a href="{{ route('admin.alunos.edit', $aluno->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    <form action="{{ route('admin.alunos.destroy', $aluno->id) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Deletar este aluno?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Del</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">Nenhum aluno cadastrado.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@if($alunos->hasPages())
<div class="mt-3">{{ $alunos->links() }}</div>
@endif
@endsection
