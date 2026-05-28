@extends('app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dashboard Admin</h1>
    <span class="text-muted">{{ auth()->user()->name }}</span>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <a href="{{ route('admin.cursos.index') }}" class="card text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Cursos</h5>
                <p class="card-text text-muted small">Gerenciar cursos</p>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('admin.documentos.index') }}" class="card text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Documentos</h5>
                <p class="card-text text-muted small">Gerenciar documentos</p>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('admin.mensagens.index') }}" class="card text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Mensagens</h5>
                <p class="card-text text-muted small">Ver mensagens de contato</p>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('admin.config.index') }}" class="card text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Configurações</h5>
                <p class="card-text text-muted small">Configurações do sistema</p>
            </div>
        </a>
    </div>
</div>
@endsection
