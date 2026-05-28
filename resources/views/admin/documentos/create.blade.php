@extends('admin_layout')

@section('title', 'Novo Documento')

@section('content')
<div class="admin-page-header">
    <h1>Novo <span>Documento</span></h1>
</div>

<div class="card p-4" style="max-width:600px;">
    <div class="accent-bar"></div>
    <form action="{{ route('admin.documentos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                   value="{{ old('nome') }}" required>
            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Arquivo PDF <small class="text-muted">(máx 10MB)</small></label>
            <input type="file" name="arquivo_pdf" class="form-control @error('arquivo_pdf') is-invalid @enderror"
                   accept=".pdf" required>
            @error('arquivo_pdf')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Data de Vencimento <small class="text-muted">(opcional — documento não aparece no site após esta data)</small></label>
            <input type="date" name="data_vencimento" class="form-control @error('data_vencimento') is-invalid @enderror"
                   value="{{ old('data_vencimento') }}" style="max-width:200px;">
            @error('data_vencimento')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4 form-check">
            <input type="checkbox" name="ativo" value="1" class="form-check-input" id="ativo" checked>
            <label class="form-check-label fw-semibold" for="ativo">Ativo (visível no site)</label>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">Criar Documento</button>
            <a href="{{ route('admin.documentos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
