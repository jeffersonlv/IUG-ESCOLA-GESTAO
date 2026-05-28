@extends('admin_layout')

@section('title', 'Editar Curso')

@section('content')
<div class="admin-page-header">
    <h1>Editar <span>Curso</span></h1>
</div>

<div class="card p-4" style="max-width:760px;">
    <div class="accent-bar"></div>
    <form action="{{ route('admin.cursos.update', $curso->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror"
                   value="{{ old('titulo', $curso->titulo) }}" required>
            @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Data Início</label>
                <input type="date" name="data_inicio" class="form-control @error('data_inicio') is-invalid @enderror"
                       value="{{ old('data_inicio', $curso->data_inicio->format('Y-m-d')) }}" required>
                @error('data_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Data Fim</label>
                <input type="date" name="data_fim" class="form-control @error('data_fim') is-invalid @enderror"
                       value="{{ old('data_fim', $curso->data_fim->format('Y-m-d')) }}" required>
                @error('data_fim')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Local / Cidade</label>
            <input type="text" name="local" class="form-control @error('local') is-invalid @enderror"
                   value="{{ old('local', $curso->local) }}" required>
            @error('local')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Tópicos</label>
            <textarea name="topicos" rows="4" class="form-control @error('topicos') is-invalid @enderror">{{ old('topicos', $curso->topicos) }}</textarea>
            @error('topicos')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">PDF do Curso <small class="text-muted">(deixe em branco para manter atual)</small></label>
            @if($curso->arquivo_pdf)
                <p class="text-muted small mb-1">Atual: {{ $curso->arquivo_pdf }}
                    <a href="{{ Storage::url('cursos/' . $curso->arquivo_pdf) }}" target="_blank" class="ms-2" style="color:#E8600A;">Ver PDF</a>
                </p>
            @endif
            <input type="file" name="arquivo_pdf" class="form-control @error('arquivo_pdf') is-invalid @enderror" accept=".pdf">
            @error('arquivo_pdf')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        @if($palestrantes->count())
        <div class="mb-3">
            <label class="form-label">Palestrantes</label>
            <input type="text" id="busca-palestrantes" class="form-control form-control-sm mb-2" placeholder="Buscar palestrante...">
            <div class="border rounded p-3" style="max-height:240px; overflow-y:auto;" id="lista-palestrantes">
                @foreach($palestrantes as $p)
                <div class="palestrante-item mb-2" data-nome="{{ strtolower($p->nome) }} {{ strtolower($p->descricao) }}">
                    <div class="d-flex align-items-start gap-2">
                        <input type="checkbox" name="palestrantes[]" value="{{ $p->id }}"
                               class="form-check-input mt-1" id="pe{{ $p->id }}"
                               {{ $curso->palestrantes->contains($p->id) ? 'checked' : '' }}>
                        <label for="pe{{ $p->id }}" style="cursor:pointer;">
                            <span class="fw-semibold d-block" style="font-size:0.875rem;">{{ $p->nome }}</span>
                            @if($p->descricao)<span class="text-muted" style="font-size:0.8rem;">{{ $p->descricao }}</span>@endif
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        <div class="mb-3">
            <label class="form-label">Ordem</label>
            <input type="number" name="ordem" class="form-control" value="{{ old('ordem', $curso->ordem) }}" min="0" max="999" style="max-width:120px;">
        </div>
        <div class="mb-4 form-check">
            <input type="checkbox" name="ativo" value="1" class="form-check-input" id="ativo" {{ $curso->ativo ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="ativo">Ativo (visível no site)</label>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">Salvar Alterações</button>
            <a href="{{ route('admin.cursos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
