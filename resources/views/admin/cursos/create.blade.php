@extends('admin_layout')

@section('title', 'Novo Curso')

@section('content')
<div class="admin-page-header">
    <h1>Novo <span>Curso</span></h1>
</div>

<div class="card p-4" style="max-width:900px;">
    <div class="accent-bar"></div>
    <form action="{{ route('admin.cursos.store') }}" method="POST" enctype="multipart/form-data" id="curso-form">
        @csrf

        {{-- Título --}}
        <div class="mb-3">
            <label class="form-label">Título do Curso</label>
            <input type="text" name="titulo" class="form-control @error('titulo') is-invalid @enderror"
                   value="{{ old('titulo') }}" required>
            @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Datas --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Data Início</label>
                <input type="date" name="data_inicio" id="data_inicio"
                       class="form-control @error('data_inicio') is-invalid @enderror"
                       value="{{ old('data_inicio') }}" required>
                @error('data_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Data Fim</label>
                <input type="date" name="data_fim" id="data_fim"
                       class="form-control @error('data_fim') is-invalid @enderror"
                       value="{{ old('data_fim') }}" required>
                @error('data_fim')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Local, Investimento, Carga --}}
        <div class="row mb-3">
            <div class="col-md-5">
                <label class="form-label">Local / Cidade</label>
                <input type="text" name="local" class="form-control @error('local') is-invalid @enderror"
                       value="{{ old('local', 'Brasília - DF') }}" required>
                @error('local')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Investimento</label>
                <input type="text" name="investimento" class="form-control"
                       value="{{ old('investimento', 'R$1.490,00 por participante') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Carga Horária</label>
                <input type="text" name="carga_horaria" class="form-control"
                       placeholder="Ex: 10h/aulas"
                       value="{{ old('carga_horaria') }}">
            </div>
        </div>

        {{-- Público-alvo --}}
        <div class="mb-3">
            <label class="form-label">Público-Alvo</label>
            <input type="text" name="publico_alvo" class="form-control"
                   value="{{ old('publico_alvo', $configs['publico_alvo'] ?? 'Vereadores, Assessores, Prefeitos e Servidores Públicos') }}">
        </div>

        {{-- Tópicos --}}
        <div class="mb-3">
            <label class="form-label">Tópicos <small class="text-muted">(usados no certificado)</small></label>
            <textarea name="topicos" rows="3" class="form-control @error('topicos') is-invalid @enderror"
                      placeholder="Ex: Gestão Pública Municipal; Lei de Responsabilidade Fiscal; Licitações...">{{ old('topicos') }}</textarea>
            @error('topicos')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <hr class="my-4">
        <h6 class="text-uppercase fw-bold mb-3" style="color:#1A2B5F; font-size:0.75rem; letter-spacing:1px;">
            Programação do Folder
        </h6>

        {{-- Programação por dia --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Programação por Dia</label>
            <div class="text-muted small mb-2">
                <i class="fas fa-info-circle me-1"></i>Preencha as datas acima para gerar os dias automaticamente.
            </div>
            <div id="programacao-container"></div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="adicionarDia()">
                <i class="fas fa-plus me-1"></i>Adicionar Dia
            </button>
            <input type="hidden" name="programacao" id="programacao-json" value="[]">
        </div>

        {{-- Palestrantes --}}
        @if($palestrantes->count())
        <div class="mb-4">
            <label class="form-label fw-semibold">Palestrantes</label>
            <small class="text-muted d-block mb-2">Selecionados aparecem no curso e no folder PDF.</small>
            <input type="text" id="busca-palestrantes" class="form-control form-control-sm mb-2" placeholder="Buscar palestrante...">
            <div class="border rounded p-3" style="max-height:220px; overflow-y:auto;" id="lista-palestrantes">
                @foreach($palestrantes as $p)
                <div class="palestrante-item mb-2" data-nome="{{ strtolower($p->nome) }}">
                    <div class="d-flex align-items-start gap-2">
                        <input type="checkbox" name="palestrantes[]" value="{{ $p->id }}"
                               class="form-check-input mt-1" id="pc{{ $p->id }}"
                               {{ in_array($p->id, old('palestrantes', [])) ? 'checked' : '' }}>
                        <label for="pc{{ $p->id }}" style="cursor:pointer;">
                            <span class="fw-semibold d-block" style="font-size:0.875rem;">{{ $p->nome }}</span>
                            @if($p->descricao)<span class="text-muted" style="font-size:0.8rem;">{{ $p->descricao }}</span>@endif
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <p class="text-muted small mb-4">Nenhum palestrante cadastrado. <a href="{{ route('admin.palestrantes.create') }}">Cadastrar</a></p>
        @endif

        <hr class="my-4">

        {{-- Upload PDF --}}
        <div class="mb-3">
            <label class="form-label">PDF do Curso <small class="text-muted">(máx 10MB — ou gere abaixo)</small></label>
            <input type="file" name="arquivo_pdf" class="form-control @error('arquivo_pdf') is-invalid @enderror" accept=".pdf">
            @error('arquivo_pdf')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4 form-check">
            <input type="checkbox" name="ativo" value="1" class="form-check-input" id="ativo" checked>
            <label class="form-check-label fw-semibold" for="ativo">Ativo (visível no site)</label>
        </div>

        {{-- Folder PDF gerado --}}
        <input type="hidden" name="folder_pdf_gerado" id="folder-pdf-gerado" value="">
        <div id="pdf-status" class="mb-3" style="display:none;">
            <span class="text-success fw-semibold">
                <i class="fas fa-check-circle me-1"></i>Folder PDF gerado — será salvo ao criar o curso.
            </span>
            <a id="pdf-preview-link" href="#" target="_blank" class="ms-2 small">Visualizar</a>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <button type="submit" class="btn btn-primary px-4">Criar Curso</button>
            <button type="button" class="btn btn-outline-danger px-4" id="btn-gerar-pdf" onclick="gerarFolderPDF()">
                <i class="fas fa-magic me-2"></i>Gerar Folder PDF
            </button>
            <a href="{{ route('admin.cursos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
@include('admin.cursos._folder_scripts', ['programacaoInicial' => '[]', 'folderPalestrantesIds' => '[]'])
@endsection
