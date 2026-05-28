@extends('app')

@section('content')
<div class="container">
    <h1>Mensagens</h1>

    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <table border="1">
        <thead>
            <tr>
                <th>De</th>
                <th>Email</th>
                <th>Lido</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        @forelse($mensagens as $msg)
            <tr>
                <td>{{ $msg->nome }}</td>
                <td>{{ $msg->email }}</td>
                <td>{{ $msg->lido ? '✓' : '✗' }}</td>
                <td>
                    <a href="{{ route('admin.mensagens.show', $msg->id) }}">Ver</a>
                    <form action="{{ route('admin.mensagens.destroy', $msg->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Deletar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">Nenhuma mensagem.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
