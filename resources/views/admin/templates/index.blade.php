@extends('admin_layout')

@section('title', 'Templates')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Templates de Flyer & Certificado</h1>
        <a href="{{ route('admin.templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Template
        </a>
    </div>

    @if($templates->isEmpty())
        <div class="alert alert-info">Nenhum template criado ainda.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Dimensões</th>
                        <th>Ativo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $t)
                    <tr>
                        <td><strong>{{ $t->nome }}</strong></td>
                        <td>
                            @if($t->tipo === 'flyer')
                                <span class="badge bg-info">Flyer</span>
                            @else
                                <span class="badge bg-success">Certificado</span>
                            @endif
                        </td>
                        <td>{{ $t->largura_mm }}×{{ $t->altura_mm }}mm ({{ ucfirst($t->orientacao) }})</td>
                        <td>
                            @if($t->ativo)
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-secondary">Inativo</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.templates.edit', $t->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('admin.templates.preview', $t->id) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Preview
                            </a>
                            <form action="{{ route('admin.templates.destroy', $t->id) }}" method="post" style="display:inline;">
                                @csrf @method('delete')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Deletar?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
