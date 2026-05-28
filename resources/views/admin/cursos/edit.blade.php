@extends('app')

@section('content')
<div class="container">
    <h1>Editar Curso</h1>
    <form action="{{ route('admin.cursos.update', $curso->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label>Título:</label>
            <input type="text" name="titulo" value="{{ $curso->titulo }}" required>
        </div>
        <div>
            <label>Data Início:</label>
            <input type="datetime-local" name="data_inicio" value="{{ $curso->data_inicio->format('Y-m-d\TH:i') }}" required>
        </div>
        <div>
            <label>Data Fim:</label>
            <input type="datetime-local" name="data_fim" value="{{ $curso->data_fim->format('Y-m-d\TH:i') }}" required>
        </div>
        <div>
            <label>Local:</label>
            <input type="text" name="local" value="{{ $curso->local }}" required>
        </div>
        <div>
            <label>Ativo:</label>
            <input type="checkbox" name="ativo" value="1" {{ $curso->ativo ? 'checked' : '' }}>
        </div>
        <button type="submit">Atualizar</button>
    </form>
</div>
@endsection
