@extends('admin_layout')

@section('title', 'Novo Aluno')

@section('content')
<div class="admin-page-header d-flex justify-content-between align-items-center">
    <h1>Novo Aluno</h1>
    <a href="{{ route('admin.alunos.index') }}" class="btn btn-sm btn-outline-secondary">← Voltar</a>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-3">{{ $errors->first() }}</div>
@endif

<div class="card p-4" style="max-width:640px;">
    <form method="POST" action="{{ route('admin.alunos.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Nome Completo <span class="text-danger">*</span></label>
            <input type="text" name="nome_completo" class="form-control" value="{{ old('nome_completo') }}" required>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-8">
                <label class="form-label fw-semibold">Cidade <span class="text-danger">*</span></label>
                <input type="text" name="cidade" class="form-control" value="{{ old('cidade') }}" required>
            </div>
            <div class="col-4">
                <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                <select name="estado" class="form-select" required>
                    <option value="">UF</option>
                    @foreach($estados as $uf)
                        <option value="{{ $uf }}" {{ old('estado') === $uf ? 'selected' : '' }}>{{ $uf }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Cursos realizados</label>
            <div style="max-height:240px; overflow-y:auto; border:1px solid #DDE1EB; border-radius:6px; padding:0.75rem;">
                @forelse($cursos as $curso)
                <div class="form-check mb-1">
                    <input class="form-check-input" type="checkbox" name="cursos[]"
                           value="{{ $curso->id }}" id="curso_{{ $curso->id }}"
                           {{ in_array($curso->id, old('cursos', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="curso_{{ $curso->id }}" style="font-size:0.875rem;">
                        <strong>{{ $curso->titulo }}</strong>
                        <span class="text-muted"> — {{ $curso->data_inicio->format('d/m/Y') }}</span>
                    </label>
                </div>
                @empty
                    <p class="text-muted mb-0" style="font-size:0.875rem;">Nenhum curso cadastrado.</p>
                @endforelse
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Aluno</button>
    </form>
</div>
@endsection
