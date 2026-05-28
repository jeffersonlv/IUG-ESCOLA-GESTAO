@extends('app')

@section('content')
<div class="container">
    <h1>Configurações do Site</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.config.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Texto Sobre:</label>
            <textarea name="sobre_texto" rows="4" class="form-control">{{ $configs['sobre_texto'] ?? '' }}</textarea>
            @error('sobre_texto') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Público Alvo:</label>
            <input type="text" name="publico_alvo" class="form-control" value="{{ $configs['publico_alvo'] ?? '' }}">
            @error('publico_alvo') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Endereço:</label>
            <input type="text" name="endereco" class="form-control" value="{{ $configs['endereco'] ?? '' }}">
            @error('endereco') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Telefone:</label>
            <input type="tel" name="telefone" class="form-control" value="{{ $configs['telefone'] ?? '' }}">
            @error('telefone') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>WhatsApp:</label>
            <input type="tel" name="whatsapp" class="form-control" value="{{ $configs['whatsapp'] ?? '' }}">
            @error('whatsapp') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="{{ $configs['email'] ?? '' }}">
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Banner Título:</label>
            <input type="text" name="banner_titulo" class="form-control" value="{{ $configs['banner_titulo'] ?? '' }}">
            @error('banner_titulo') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Banner Subtítulo:</label>
            <textarea name="banner_subtitulo" rows="2" class="form-control">{{ $configs['banner_subtitulo'] ?? '' }}</textarea>
            @error('banner_subtitulo') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Texto Transparência:</label>
            <textarea name="transparencia_texto" rows="4" class="form-control">{{ $configs['transparencia_texto'] ?? '' }}</textarea>
            @error('transparencia_texto') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Salvar Configurações</button>
    </form>
</div>
@endsection
