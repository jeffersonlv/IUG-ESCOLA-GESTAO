@extends('admin_layout')

@section('title', 'Gerador de Certificados')

@section('content')
<div class="admin-page-header">
    <h1>Gerador de Certificados</h1>
</div>

@php
$cursosJson = $cursos->map(fn($c) => [
    'id'     => $c->id,
    'titulo' => $c->titulo,
    'data'   => $c->data_inicio->format('d') . ' a ' . $c->data_fim->format('d \d\e F \d\e Y'),
    'cidade' => $c->local,
    'topico' => $c->topicos ?? '',
    'alunos' => $c->alunos->map(fn($a) => $a->nome_completo)->implode(';'),
])->keyBy('id');
@endphp

<div class="card p-4" style="max-width:720px;">

    {{-- Atalho: carregar de um curso --}}
    <div class="mb-4 p-3" style="background:#F0F2F8; border-radius:8px;">
        <label class="form-label fw-semibold mb-1" style="font-size:0.875rem;">Carregar dados de um curso cadastrado</label>
        <select id="cursoSelect" class="form-select form-select-sm" style="max-width:500px;">
            <option value="">— Selecione um curso para preencher automaticamente —</option>
            @foreach($cursos as $curso)
                <option value="{{ $curso->id }}">{{ $curso->titulo }} — {{ $curso->data_inicio->format('d/m/Y') }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Título do Curso <span class="text-danger">*</span></label>
        <input type="text" id="titulo" class="form-control" placeholder="A IMPLEMENTAÇÃO DE POLÍTICAS PÚBLICAS NO MUNICÍPIO">
    </div>

    <div class="row g-3 mb-3">
        <div class="col-6">
            <label class="form-label fw-semibold">Data <span class="text-danger">*</span></label>
            <input type="text" id="data" class="form-control" placeholder="05 a 08 de julho de 2022">
        </div>
        <div class="col-6">
            <label class="form-label fw-semibold">Cidade <span class="text-danger">*</span></label>
            <input type="text" id="cidade" class="form-control" placeholder="BRASÍLIA - DF">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Tópicos</label>
        <textarea id="topico" class="form-control" rows="3" placeholder="O que são Políticas Públicas; A importância da participação social..."></textarea>
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold">
            Nomes dos Participantes
            <small class="text-muted fw-normal">(separar por ponto e vírgula <code>;</code>)</small>
        </label>
        <textarea id="nomes" class="form-control" rows="8" style="font-family:monospace; font-size:0.875rem;"
                  placeholder="João da Silva;Maria Oliveira;Carlos Santos"></textarea>
    </div>

    <button onclick="gerarCertificados()" class="btn btn-primary px-5">
        🎓 Gerar Certificados
    </button>
    <p class="text-muted mt-2" style="font-size:0.8rem;">Abrirá uma aba por participante pronta para impressão.</p>
</div>

<script>
const cursosData = @json($cursosJson);

document.getElementById('cursoSelect').addEventListener('change', function() {
    const id = this.value;
    if (!id || !cursosData[id]) return;
    const c = cursosData[id];
    document.getElementById('titulo').value = c.titulo;
    document.getElementById('data').value   = c.data;
    document.getElementById('cidade').value = c.cidade;
    document.getElementById('topico').value = c.topico;
    if (c.alunos) document.getElementById('nomes').value = c.alunos;
});

function gerarCertificados() {
    const titulo = document.getElementById('titulo').value.trim();
    const data   = document.getElementById('data').value.trim();
    const cidade = document.getElementById('cidade').value.trim();
    const topico = document.getElementById('topico').value.trim();
    const nomes  = document.getElementById('nomes').value;

    if (!titulo || !data || !cidade) {
        alert('Preencha título, data e cidade.');
        return;
    }

    const lista = nomes.split(';').map(n => n.trim()).filter(n => n !== '');
    if (lista.length === 0) {
        alert('Informe ao menos um nome.');
        return;
    }

    const base = "{{ route('admin.certificados.imprimir') }}";
    lista.forEach(nome => {
        const url = base + '?nome=' + encodeURIComponent(nome)
            + '&titulo=' + encodeURIComponent(titulo)
            + '&data='   + encodeURIComponent(data)
            + '&cidade=' + encodeURIComponent(cidade)
            + '&topico=' + encodeURIComponent(topico);
        window.open(url, nome);
    });
}
</script>
@endsection
