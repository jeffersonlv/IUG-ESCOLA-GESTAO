@extends('admin_layout')

@section('title', 'Novo Template')

@section('content')
<div class="container-fluid mt-4">
    <div class="mb-4">
        <h1 class="h3">Novo Template</h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.templates.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <select name="tipo" id="tipo" class="form-control @error('tipo') is-invalid @enderror" required>
                                <option value="">-- Selecione --</option>
                                <option value="flyer" @if(old('tipo') === 'flyer') selected @endif>Flyer/Folder</option>
                                <option value="certificado" @if(old('tipo') === 'certificado') selected @endif>Certificado</option>
                            </select>
                            @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Template</label>
                            <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror"
                                   value="{{ old('nome') }}" required>
                            @error('nome') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="largura_mm" class="form-label">Largura (mm)</label>
                                    <input type="number" name="largura_mm" id="largura_mm"
                                           class="form-control @error('largura_mm') is-invalid @enderror"
                                           value="{{ old('largura_mm', 210) }}" min="50" max="1000" required>
                                    @error('largura_mm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="altura_mm" class="form-label">Altura (mm)</label>
                                    <input type="number" name="altura_mm" id="altura_mm"
                                           class="form-control @error('altura_mm') is-invalid @enderror"
                                           value="{{ old('altura_mm', 297) }}" min="50" max="1000" required>
                                    @error('altura_mm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="orientacao" class="form-label">Orientação</label>
                            <select name="orientacao" id="orientacao" class="form-control @error('orientacao') is-invalid @enderror" required>
                                <option value="portrait" @if(old('orientacao') === 'portrait') selected @endif>Portrait</option>
                                <option value="landscape" @if(old('orientacao') === 'landscape') selected @endif>Landscape</option>
                            </select>
                            @error('orientacao') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="fundo" class="form-label">Imagem de Fundo</label>
                            <input type="file" name="fundo" id="fundo" class="form-control @error('fundo') is-invalid @enderror"
                                   accept="image/*">
                            <small class="text-muted">Deixe em branco para preencher depois no editor</small>
                            @error('fundo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="ativo" id="ativo" class="form-check-input" value="1"
                                   @if(old('ativo')) checked @endif>
                            <label for="ativo" class="form-check-label">Template Ativo</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Criar Template</button>
                            <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
