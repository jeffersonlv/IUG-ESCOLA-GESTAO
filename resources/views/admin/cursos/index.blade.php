@extends('app')

@section('content')
<div class="container">
    <h1>Gerenciar Cursos</h1>
    <a href="{{ route('admin.cursos.create') }}">Novo Curso</a>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <table border="1">
        <thead>
            <tr>
                <th>Título</th>
                <th>Data</th>
                <th>Ativo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        @forelse($cursos as $curso)
            <tr>
                <td>{{ $curso->titulo }}</td>
                <td>{{ $curso->data_inicio->format('d/m/Y') }}</td>
                <td>{{ $curso->ativo ? 'Sim' : 'Não' }}</td>
                <td>
                    <a href="{{ route('admin.cursos.edit', $curso->id) }}">Editar</a>
                    <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Deletar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">Nenhum curso.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
