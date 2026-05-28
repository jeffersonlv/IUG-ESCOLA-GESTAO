@extends('admin_layout')

@section('title', 'Editar Documento')

@section('content')
<div class="admin-page-header">
    <h1>Editar <span>Documento</span></h1>
</div>

<div class="card p-4" style="max-width:600px;">
    <div class="accent-bar"></div>
    <form action="{{ route('admin.documentos.update', $documento->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                   value="{{ old('nome', $documento->nome) }}" required>
            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Arquivo PDF <small class="text-muted">(deixe em branco para manter atual)</small></label>
            @if($documento->arquivo_pdf)
                <p class="text-muted small mb-1">Atual: {{ $documento->arquivo_pdf }}</p>
            @endif
            <input type="file" name="arquivo_pdf" class="form-control @error('arquivo_pdf') is-invalid @enderror" accept=".pdf">
            @error('arquivo_pdf')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Data de Vencimento <small class="text-muted">(opcional — documento não aparece no site após esta data)</small></label>
            <input type="date" name="data_vencimento" class="form-control @error('data_vencimento') is-invalid @enderror"
                   value="{{ old('data_vencimento', $documento->data_vencimento?->format('Y-m-d')) }}" style="max-width:200px;">
            @error('data_vencimento')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @if($documento->vencido)
                <div class="text-danger small mt-1">⚠️ Este documento está vencido e não aparece no site.</div>
            @endif
        </div>
        <div class="mb-4 form-check">
            <input type="checkbox" name="ativo" value="1" class="form-check-input" id="ativo" {{ $documento->ativo ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="ativo">Ativo (visível no site)</label>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">Salvar Alterações</button>
            <a href="{{ route('admin.documentos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
