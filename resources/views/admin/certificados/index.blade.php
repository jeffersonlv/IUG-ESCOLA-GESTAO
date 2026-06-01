@extends('admin_layout')

@section('title', 'Gerador de Certificados')

@section('content')
<div class="admin-page-header">
    <h1>Gerador de Certificados</h1>
</div>

@php
$_meses = ['janeiro','fevereiro','março','abril','maio','junho','julho','agosto','setembro','outubro','novembro','dezembro'];
$cursosJson = $cursos->map(fn($c) => [
    'id'         => $c->id,
    'slug'       => \Str::slug($c->titulo . '-' . $c->id),
    'titulo'     => $c->titulo,
    'data'       => $c->data_inicio->format('d') . ' a ' . $c->data_fim->format('d') . ' de ' . $_meses[$c->data_fim->month - 1] . ' de ' . $c->data_fim->year,
    'cidade'     => $c->local,
    'topico'     => $c->topicos ?? '',
    'alunos_raw' => $c->alunos->map(fn($a) =>
        $a->nome_completo . ($a->cidade || $a->estado ? ';' . trim($a->cidade . '-' . $a->estado, '-') : '')
    )->implode("\n"),
])->keyBy('id');
@endphp

<div class="row g-4">
    {{-- Formulário --}}
    <div class="col-lg-7">
        <div class="card p-4">

            <div class="mb-4 p-3" style="background:#F0F2F8; border-radius:8px;">
                <label class="form-label fw-semibold mb-1" style="font-size:0.875rem;">Carregar curso cadastrado</label>
                <select id="cursoSelect" class="form-select form-select-sm">
                    <option value="">— Selecione para preencher automaticamente —</option>
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
                    Participantes
                    <small class="text-muted fw-normal">(um por linha — <code>Nome Completo;Cidade-UF</code>)</small>
                </label>
                <textarea id="nomes" class="form-control" rows="10" style="font-family:monospace; font-size:0.82rem;"
                          placeholder="João da Silva;Brasília-DF&#10;Maria Santos;São Paulo-SP&#10;Carlos Oliveira"></textarea>
            </div>

            <div class="d-flex gap-2">
                <button id="btnSalvar" onclick="gerarESalvar()" class="btn btn-primary px-4">
                    <span id="btnSalvarTxt">Gerar e Salvar PDFs</span>
                    <span id="btnSalvarLoad" class="spinner-border spinner-border-sm ms-1 d-none"></span>
                </button>
                <button onclick="abrirAbas()" class="btn btn-outline-secondary px-4">Visualizar (abas)</button>
            </div>
        </div>
    </div>

    {{-- Downloads --}}
    <div class="col-lg-5">
        <div id="downloadPanel" class="d-none">
            <div class="card p-4">
                <h5 class="mb-3">Downloads</h5>

                <div id="downloadInfo" class="mb-3 text-muted" style="font-size:0.875rem;"></div>

                <div class="d-flex gap-2 mb-3" id="btnsBatch"></div>

                <div id="listaDownloads" style="max-height:400px; overflow-y:auto;"></div>
            </div>
        </div>

        <div id="erroPanel" class="d-none">
            <div class="alert alert-danger" id="erroMsg"></div>
        </div>
    </div>
</div>

<style>
/* Replica paper-css A4 landscape @ 96dpi com padding-10mm */
#certPreview {
    position: fixed; left: -9999px; top: 0;
    width: 1123px; height: 794px;
    overflow: hidden;
    background: #fff;
}
#certPreview .cert-wrap {
    position: relative;
    width: 1123px; height: 794px;
    padding: 38px; /* 10mm @ 96dpi */
    box-sizing: border-box;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 19.2px; /* 1.2em base (paper-css 16px × 1.2) */
    overflow: hidden;
}
#certPreview .cert-bg {
    position: absolute; top: 0; left: 0;
    width: 100%; height: 100%;
    object-fit: fill;
    display: block; z-index: 0;
}
#certPreview .cert-content { position: relative; z-index: 1; }
#certPreview .divcentro { margin: 0 auto; }
#certPreview .nome   { text-align: center; position: relative; top: 288px; }
#certPreview .titulo { text-align: center; position: relative; top: 324px; font-size: 1.3em; font-style: italic; font-weight: bold; }
#certPreview .data   { text-align: center; position: relative; top: 350px; }
#certPreview .topico { text-align: justify; position: relative; top: 385px; margin: 0 57px !important; font-size: 1em; }
#certPreview .participante {
    position: absolute; bottom: 55px; left: 178px;
    border-top: 3px solid #000; width: 295px;
    text-align: center; padding-top: 5px; font-weight: bold; font-size: 0.75em;
}
#certPreview .instituto {
    position: absolute; bottom: 55px; right: 178px;
    border-top: 3px solid #000; width: 295px;
    text-align: center; padding-top: 5px; font-weight: bold; font-size: 0.75em;
}
#certPreview .assinatura {
    position: absolute; bottom: 63px; right: 320px; width: 18%;
}
</style>

<div id="certPreview">
    <div class="cert-wrap">
        <img class="cert-bg" id="certBg" src="" />
        <div class="cert-content">
            <div class="divcentro nome"   id="certNome"></div>
            <div class="divcentro titulo" id="certTitulo"></div>
            <div class="divcentro data"   id="certData"></div>
            <div class="divcentro topico" id="certTopico"></div>
        </div>

        <div class="participante">Participante</div>
        <div class="instituto">Instituto Ulysses Guimarães LTDA<br>CNPJ: 40.033.708/0001-63</div>
        <img class="assinatura" id="certAss" src="" />
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
const cursosData  = @json($cursosJson);
const fundoB64    = @json($fundoB64);
const assB64      = @json($assB64);
const uploadUrl   = "{{ route('admin.certificados.uploadPdf') }}";
const imprimirUrl = "{{ route('admin.certificados.imprimir') }}";
const zipUrl      = "{{ route('admin.certificados.zip') }}";
const csrfToken   = "{{ csrf_token() }}";

// Pré-carrega imagens no preview oculto
document.getElementById('certBg').src  = fundoB64;
document.getElementById('certAss').src = assB64;

// Restaurar downloads após reload
(function() {
    try {
        const saved = localStorage.getItem('iug_cert_downloads');
        if (saved) mostrarDownloads(JSON.parse(saved));
    } catch(e) {}
})();

document.getElementById('cursoSelect').addEventListener('change', function() {
    const id = this.value;
    if (!id || !cursosData[id]) return;
    const c = cursosData[id];
    document.getElementById('titulo').value = c.titulo;
    document.getElementById('data').value   = c.data;
    document.getElementById('cidade').value = c.cidade;
    document.getElementById('topico').value = c.topico;
    document.getElementById('nomes').value  = c.alunos_raw;
    document.getElementById('downloadPanel').classList.add('d-none');
    document.getElementById('erroPanel').classList.add('d-none');
});

function parsearAlunos() {
    const raw = document.getElementById('nomes').value;
    return raw.split('\n')
        .map(l => l.trim()).filter(l => l !== '')
        .map(l => {
            const parts = l.split(';');
            const nome  = parts[0].trim();
            const loc   = (parts[1] || '').trim();
            const locParts = loc.split('-');
            const estado = locParts.length > 1 ? locParts.pop().trim() : '';
            const cidadeAluno = locParts.join('-').trim();
            return { nome, cidade_aluno: cidadeAluno, estado_aluno: estado };
        })
        .filter(a => a.nome !== '');
}

async function aguardarImagem(img) {
    if (img.complete && img.naturalWidth > 0) return;
    return new Promise((res, rej) => {
        img.onload  = res;
        img.onerror = rej;
    });
}

async function capturarCertificadoPDF(aluno, titulo, data, cidade, topico) {
    document.getElementById('certNome').innerHTML   = 'Certificamos que <b>' + aluno.nome + '</b> participou do curso';
    document.getElementById('certTitulo').textContent = '“' + titulo + '”';
    document.getElementById('certData').innerHTML   = 'Realizado nos dias <b>' + data + '</b>, na cidade de <b>' + cidade + '</b>.';
    const topicoEl = document.getElementById('certTopico');
    if (topico) { topicoEl.innerHTML = '<b>TÓPICOS: </b>' + topico; topicoEl.style.display = ''; }
    else         { topicoEl.innerHTML = ''; topicoEl.style.display = 'none'; }

    // Garante que imagens carregaram
    await Promise.all([
        aguardarImagem(document.getElementById('certBg')),
        aguardarImagem(document.getElementById('certAss')),
    ]);
    await new Promise(r => setTimeout(r, 150)); // repaint

    const wrap = document.querySelector('#certPreview .cert-wrap');
    const canvas = await html2canvas(wrap, {
        scale: 2,
        useCORS: false,
        allowTaint: true,
        logging: false,
        width:  1123,
        height: 794,
    });

    return canvas.toDataURL('image/jpeg', 0.92);
}

async function gerarESalvar() {
    const titulo = document.getElementById('titulo').value.trim();
    const data   = document.getElementById('data').value.trim();
    const cidade = document.getElementById('cidade').value.trim();
    const topico = document.getElementById('topico').value.trim();
    const alunos = parsearAlunos();

    if (!titulo || !data || !cidade) { alert('Preencha título, data e cidade.'); return; }
    if (alunos.length === 0) { alert('Informe ao menos um participante.'); return; }

    const cursoSlug = document.getElementById('cursoSelect').value
        ? cursosData[document.getElementById('cursoSelect').value]?.slug
        : titulo.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '');

    const btnTxt  = document.getElementById('btnSalvarTxt');
    const btnLoad = document.getElementById('btnSalvarLoad');
    const btn     = document.getElementById('btnSalvar');
    btn.disabled  = true;
    btnLoad.classList.remove('d-none');
    document.getElementById('downloadPanel').classList.add('d-none');
    document.getElementById('erroPanel').classList.add('d-none');

    const gerados = [];

    try {
        for (let i = 0; i < alunos.length; i++) {
            const a = alunos[i];
            btnTxt.textContent = `Gerando ${i + 1}/${alunos.length}…`;

            const imgBase64 = await capturarCertificadoPDF(a, titulo, data, cidade, topico);

            const locSlug = [a.estado_aluno, a.cidade_aluno, a.nome]
                .filter(Boolean).join('_')
                .toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '');
            const filename = locSlug + '.pdf';

            const resp = await fetch(uploadUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ img_base64: imgBase64, filename, curso_slug: cursoSlug }),
            });

            const json = await resp.json();
            if (!resp.ok || !json.ok) throw new Error(json.message || 'Erro ao salvar PDF.');

            gerados.push({
                nome:         a.nome,
                cidade_aluno: a.cidade_aluno,
                estado_aluno: a.estado_aluno,
                arquivo:      json.arquivo,
                url_download: json.url_download,
            });
        }

        mostrarDownloads({ curso_slug: cursoSlug, gerados });

    } catch (e) {
        document.getElementById('erroPanel').classList.remove('d-none');
        document.getElementById('erroMsg').textContent = e.message;
    } finally {
        btnTxt.textContent = 'Gerar e Salvar PDFs';
        btnLoad.classList.add('d-none');
        btn.disabled = false;
    }
}

function mostrarDownloads(json) {
    localStorage.setItem('iug_cert_downloads', JSON.stringify(json));

    const panel = document.getElementById('downloadPanel');
    panel.classList.remove('d-none');

    document.getElementById('downloadInfo').textContent =
        json.gerados.length + ' certificado(s) gerado(s). Disponíveis por 30 dias.';

    // Botões batch
    const cidades = [...new Set(json.gerados.map(g => g.cidade_aluno).filter(c => c))];
    const btnsBatch = document.getElementById('btnsBatch');
    btnsBatch.innerHTML = '';

    const btnTodos = document.createElement('a');
    btnTodos.href = zipUrl + '?curso=' + encodeURIComponent(json.curso_slug);
    btnTodos.className = 'btn btn-sm btn-success';
    btnTodos.textContent = 'ZIP Todos';
    btnsBatch.appendChild(btnTodos);

    cidades.forEach(c => {
        const btn = document.createElement('a');
        btn.href = zipUrl + '?curso=' + encodeURIComponent(json.curso_slug) + '&cidade=' + encodeURIComponent(c);
        btn.className = 'btn btn-sm btn-outline-success';
        btn.textContent = 'ZIP ' + c;
        btnsBatch.appendChild(btn);
    });

    // Lista individual
    const lista = document.getElementById('listaDownloads');
    lista.innerHTML = '';

    json.gerados.forEach(g => {
        const row = document.createElement('div');
        row.className = 'd-flex justify-content-between align-items-center py-1 border-bottom';
        row.style.fontSize = '0.82rem';

        const info = document.createElement('span');
        info.textContent = g.nome + (g.cidade_aluno ? ' — ' + g.cidade_aluno + (g.estado_aluno ? '-' + g.estado_aluno : '') : '');

        const link = document.createElement('a');
        link.href = g.url_download;
        link.className = 'btn btn-xs btn-outline-primary ms-2';
        link.style.fontSize = '0.75rem';
        link.style.padding = '2px 8px';
        link.textContent = 'Baixar';

        row.appendChild(info);
        row.appendChild(link);
        lista.appendChild(row);
    });
}

function abrirAbas() {
    const titulo = document.getElementById('titulo').value.trim();
    const data   = document.getElementById('data').value.trim();
    const cidade = document.getElementById('cidade').value.trim();
    const topico = document.getElementById('topico').value.trim();
    const alunos = parsearAlunos();

    if (!titulo || !data || !cidade) { alert('Preencha título, data e cidade.'); return; }
    if (alunos.length === 0) { alert('Informe ao menos um participante.'); return; }

    alunos.forEach(a => {
        const url = imprimirUrl
            + '?nome='   + encodeURIComponent(a.nome)
            + '&titulo=' + encodeURIComponent(titulo)
            + '&data='   + encodeURIComponent(data)
            + '&cidade=' + encodeURIComponent(cidade)
            + '&topico=' + encodeURIComponent(topico);
        window.open(url, a.nome);
    });
}
</script>
@endsection
