@extends('app')

@section('content')
<div class="container">
    <h1>Novo Curso</h1>
    <form action="{{ route('admin.cursos.store') }}" method="POST">
        @csrf
        <div>
            <label>Título:</label>
            <input type="text" name="titulo" required>
        </div>
        <div>
            <label>Data Início:</label>
            <input type="datetime-local" name="data_inicio" required>
        </div>
        <div>
            <label>Data Fim:</label>
            <input type="datetime-local" name="data_fim" required>
        </div>
        <div>
            <label>Local:</label>
            <input type="text" name="local" required>
        </div>
        <div>
            <label>Ativo:</label>
            <input type="checkbox" name="ativo" value="1" checked>
        </div>
        <button type="submit">Criar</button>
    </form>
</div>
@endsection
