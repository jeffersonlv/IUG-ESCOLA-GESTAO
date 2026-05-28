@extends('admin_layout')

@section('title', 'Editar Palestrante')

@section('content')
<div class="admin-page-header">
    <h1>Editar <span>Palestrante</span></h1>
</div>

<div class="card p-4" style="max-width:600px;">
    <div class="accent-bar"></div>
    <form action="{{ route('admin.palestrantes.update', $palestrante->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                   value="{{ old('nome', $palestrante->nome) }}" required>
            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Breve Descrição</label>
            <textarea name="descricao" rows="4" class="form-control @error('descricao') is-invalid @enderror">{{ old('descricao', $palestrante->descricao) }}</textarea>
            @error('descricao')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Foto <small class="text-muted">(deixe em branco para manter atual)</small></label>
            @if($palestrante->foto)
            <div class="mb-2">
                <img src="{{ Storage::url('palestrantes/' . $palestrante->foto) }}" alt="{{ $palestrante->nome }}"
                     style="width:80px; height:80px; object-fit:cover; border-radius:50%; border:3px solid #DDE1EB;">
            </div>
            @endif
            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror"
                   accept="image/jpg,image/jpeg,image/png,image/webp">
            @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4 form-check">
            <input type="checkbox" name="ativo" value="1" class="form-check-input" id="ativo" {{ $palestrante->ativo ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="ativo">Ativo</label>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">Salvar Alterações</button>
            <a href="{{ route('admin.palestrantes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
