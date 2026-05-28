@extends('app')

@section('content')
<div class="container">
    <h1>Gerenciar Documentos</h1>
    <a href="{{ route('admin.documentos.create') }}">Novo Documento</a>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <table border="1">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Ativo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        @forelse($documentos as $doc)
            <tr>
                <td>{{ $doc->nome }}</td>
                <td>{{ $doc->ativo ? 'Sim' : 'Não' }}</td>
                <td>
                    <a href="{{ route('admin.documentos.edit', $doc->id) }}">Editar</a>
                    <form action="{{ route('admin.documentos.destroy', $doc->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Deletar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="3">Nenhum documento.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
