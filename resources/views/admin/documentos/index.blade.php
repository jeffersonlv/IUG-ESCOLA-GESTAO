@extends('admin_layout')

@section('title', 'Documentos')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1>Documentos</h1>
    <a href="{{ route('admin.documentos.create') }}" class="btn btn-primary btn-sm">+ Novo Documento</a>
</div>

@if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif

<div class="card">
    <table class="table mb-0">
        @php
            function docSortUrl($col, $sort, $dir) {
                return request()->fullUrlWithQuery(['sort' => $col, 'dir' => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc']);
            }
            function docSortIcon($col, $sort, $dir) {
                return $sort === $col ? ($dir === 'asc' ? ' ▲' : ' ▼') : ' ⇅';
            }
        @endphp
        <thead>
            <tr>
                <th><a href="{{ docSortUrl('nome', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Nome{!! docSortIcon('nome', $sort, $dir) !!}</a></th>
                <th><a href="{{ docSortUrl('data_vencimento', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Vencimento{!! docSortIcon('data_vencimento', $sort, $dir) !!}</a></th>
                <th><a href="{{ docSortUrl('ativo', $sort, $dir) }}" style="color:inherit;text-decoration:none;">Ativo{!! docSortIcon('ativo', $sort, $dir) !!}</a></th>
                <th style="width:80px; text-align:center;">Downloads</th>
                <th style="width:140px;">Ações</th>
            </tr>
        </thead>
        <tbody>
        @forelse($documentos as $doc)
            <tr @if($doc->vencido) style="opacity:0.6;" @endif>
                <td class="fw-semibold">
                    {{ $doc->nome }}
                    @if($doc->vencido)
                        <span class="badge bg-danger ms-1">Vencido</span>
                    @endif
                </td>
                <td>
                    @if($doc->data_vencimento)
                        @php $dias = now()->startOfDay()->diffInDays($doc->data_vencimento->startOfDay(), false); @endphp
                        <span class="{{ $doc->vencido ? 'text-danger fw-semibold' : 'text-muted' }}" style="font-size:0.875rem;">
                            {{ $doc->data_vencimento->format('d/m/Y') }}
                        </span><br>
                        <small class="{{ $doc->vencido ? 'text-danger' : ($dias <= 30 ? 'text-warning fw-semibold' : 'text-muted') }}">
                            @if($doc->vencido)
                                vencido há {{ abs($dias) }}d
                            @elseif($dias === 0)
                                vence hoje
                            @else
                                vence em {{ $dias }}d
                            @endif
                        </small>
                    @else
                        <span class="text-muted" style="font-size:0.875rem;">—</span>
                    @endif
                </td>
                <td>
                    @if($doc->ativo)
                        <span class="badge" style="background:#1A2B5F;">Sim</span>
                    @else
                        <span class="badge bg-secondary">Não</span>
                    @endif
                </td>
                <td style="text-align:center; font-size:0.875rem;">
                    {{ $doc->downloads ?? 0 }}
                </td>
                <td>
                    <a href="{{ route('admin.documentos.edit', $doc->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    <form action="{{ route('admin.documentos.destroy', $doc->id) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Deletar este documento?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Del</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="text-center text-muted py-4">Nenhum documento cadastrado.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
