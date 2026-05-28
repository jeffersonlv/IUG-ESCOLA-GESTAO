@extends('admin_layout')

@section('title', 'Configurações')

@section('content')
<div class="admin-page-header">
    <h1>Configurações do <span>Site</span></h1>
</div>

@if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif

<div class="card p-4" style="max-width:760px;">
    <div class="accent-bar"></div>
    <form action="{{ route('admin.config.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Texto Sobre</label>
            <textarea name="sobre_texto" rows="4" class="form-control">{{ $configs['sobre_texto'] ?? '' }}</textarea>
            @error('sobre_texto')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Público Alvo</label>
            <input type="text" name="publico_alvo" class="form-control" value="{{ $configs['publico_alvo'] ?? '' }}">
            @error('publico_alvo')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Endereço <small class="text-muted">(uma linha por campo)</small></label>
                <textarea name="endereco" rows="5" class="form-control" placeholder="Rua: Q SDE QUADRA 01...&#10;COMPLEMENTO: APT 102...&#10;CEP: 72.145-105&#10;BAIRRO: SETOR DE DESENVOLVIMENTO...&#10;BRASÍLIA - DF">{{ $configs['endereco'] ?? '' }}</textarea>
            </div>
            <div class="col-md-3">
                <label class="form-label">Telefone</label>
                <input type="tel" name="telefone" class="form-control" value="{{ $configs['telefone'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">WhatsApp</label>
                <input type="tel" name="whatsapp" class="form-control" value="{{ $configs['whatsapp'] ?? '' }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Email de Contato</label>
            <input type="email" name="email" class="form-control" value="{{ $configs['email'] ?? '' }}">
        </div>

        <hr class="my-4">

        <div class="mb-3">
            <label class="form-label">Banner — Título</label>
            <input type="text" name="banner_titulo" class="form-control" value="{{ $configs['banner_titulo'] ?? '' }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Banner — Subtítulo</label>
            <textarea name="banner_subtitulo" rows="2" class="form-control">{{ $configs['banner_subtitulo'] ?? '' }}</textarea>
        </div>

        <div class="mb-4">
            <label class="form-label">Texto Transparência</label>
            <textarea name="transparencia_texto" rows="4" class="form-control">{{ $configs['transparencia_texto'] ?? '' }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary px-4">Salvar Configurações</button>
    </form>
</div>
@endsection
