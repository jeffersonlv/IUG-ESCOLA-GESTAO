@extends('app')

@section('content')
<div class="container">
    <h1>Novo Documento</h1>
    <form action="{{ route('admin.documentos.store') }}" method="POST">
        @csrf
        <div>
            <label>Nome:</label>
            <input type="text" name="nome" required>
        </div>
        <div>
            <label>Arquivo PDF:</label>
            <input type="text" name="arquivo_pdf" required>
        </div>
        <div>
            <label>Ativo:</label>
            <input type="checkbox" name="ativo" value="1" checked>
        </div>
        <button type="submit">Criar</button>
    </form>
</div>
@endsection
