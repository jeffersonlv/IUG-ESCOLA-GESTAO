@extends('admin_layout')

@section('title', 'Palestrantes')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1>Palestrantes</h1>
    <a href="{{ route('admin.palestrantes.create') }}" class="btn btn-primary btn-sm">+ Novo Palestrante</a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif

<form method="GET" class="mb-3 d-flex gap-2" style="max-width:400px;">
    <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Buscar por nome ou descrição...">
    <button class="btn btn-sm btn-outline-primary px-3">Buscar</button>
    @if($q)<a href="{{ route('admin.palestrantes.index') }}" class="btn btn-sm btn-outline-secondary">✕</a>@endif
</form>

<div class="card">
    <table class="table mb-0">
        @php
            function palSortUrl($col, $sort, $dir) {
                return request()->fullUrlWithQuery(['sort' => $col, 'dir' => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc']);
            }
            function palSortIcon($col, $sort, $dir) {
                return $sort === $col ? ($dir === 'asc' ? ' ▲' : ' ▼') : ' ⇅';
            }
        @endphp
        <thead>
            <tr>
                <th style="width:60px;">Foto</th>
                <th><a href="{{ palSortUrl('nome', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Nome{!! palSortIcon('nome', $sort, $dir) !!}</a></th>
                <th>Descrição</th>
                <th><a href="{{ palSortUrl('ativo', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Ativo{!! palSortIcon('ativo', $sort, $dir) !!}</a></th>
                <th style="width:140px;">Ações</th>
            </tr>
        </thead>
        <tbody>
        @forelse($palestrantes as $p)
            <tr>
                <td>
                    @if($p->foto)
                        <img src="{{ $p->foto_url }}" alt="{{ $p->nome }}"
                             style="width:44px; height:44px; object-fit:cover; border-radius:50%;">
                    @else
                        <div style="width:44px; height:44px; border-radius:50%; background:#DDE1EB; display:flex; align-items:center; justify-content:center; color:#aaa; font-size:1.2rem;">👤</div>
                    @endif
                </td>
                <td class="fw-semibold align-middle">{{ $p->nome }}</td>
                <td class="text-muted align-middle" style="font-size:0.875rem;">{{ Str::limit($p->descricao, 80) }}</td>
                <td class="align-middle">
                    @if($p->ativo)
                        <span class="badge" style="background:#1B3A5C;">Sim</span>
                    @else
                        <span class="badge bg-secondary">Não</span>
                    @endif
                </td>
                <td class="align-middle">
                    <a href="{{ route('admin.palestrantes.edit', $p->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    <form action="{{ route('admin.palestrantes.destroy', $p->id) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Deletar este palestrante?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Del</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Nenhum palestrante encontrado.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

@if($palestrantes->hasPages())
<div class="mt-3">{{ $palestrantes->links() }}</div>
@endif
@endsection
