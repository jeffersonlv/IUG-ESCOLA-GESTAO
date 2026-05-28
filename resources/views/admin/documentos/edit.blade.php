@extends('app')

@section('content')
<div class="container">
    <h1>Editar Documento</h1>
    <form action="{{ route('admin.documentos.update', $documento->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label>Nome:</label>
            <input type="text" name="nome" value="{{ $documento->nome }}" required>
        </div>
        <div>
            <label>Arquivo PDF:</label>
            <input type="text" name="arquivo_pdf" value="{{ $documento->arquivo_pdf }}" required>
        </div>
        <div>
            <label>Ativo:</label>
            <input type="checkbox" name="ativo" value="1" {{ $documento->ativo ? 'checked' : '' }}>
        </div>
        <button type="submit">Atualizar</button>
    </form>
</div>
@endsection
