@extends('admin_layout')

@section('title', 'Mensagens')

@section('content')
<div class="admin-page-header">
    <h1>Mensagens</h1>
</div>

@if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif

<div class="card">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Lido</th>
                <th style="width:140px;">Ações</th>
            </tr>
        </thead>
        <tbody>
        @forelse($mensagens as $msg)
            <tr>
                <td class="fw-semibold">{{ $msg->nome }}</td>
                <td>{{ $msg->email }}</td>
                <td>
                    @if($msg->lido)
                        <span class="badge bg-secondary">Lido</span>
                    @else
                        <span class="badge" style="background:#C9962D;">Novo</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.mensagens.show', $msg->id) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                    <form action="{{ route('admin.mensagens.destroy', $msg->id) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Deletar esta mensagem?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Del</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center text-muted py-4">Nenhuma mensagem.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
