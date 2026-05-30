@extends('admin_layout')

@section('title', 'Editar Template')

@section('content')
<div class="container-fluid mt-4">
    <div class="mb-4">
        <h1 class="h3">Editor de Template: {{ $template->nome }}</h1>
    </div>

    <div class="row" style="height: calc(100vh - 180px);">
        <!-- Canvas Editor -->
        <div class="col-md-9 border-end" style="overflow:auto;">
            <div id="canvas-container" style="position:relative; margin:20px; background:#f5f5f5; border:1px solid #ddd;">
                <canvas id="editor-canvas" style="border:1px solid #999; background:#fff;"></canvas>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-3" style="overflow-y:auto; padding:15px; background:#f9f9f9;">
            <h5>Controles</h5>
            <hr>

            <!-- Adicionar blocos -->
            <div class="mb-4">
                <h6>Adicionar Bloco</h6>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-texto">
                        <i class="fas fa-font"></i> Texto
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-imagem">
                        <i class="fas fa-image"></i> Imagem
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-campo">
                        <i class="fas fa-database"></i> Campo Dinâmico
                    </button>
                </div>
            </div>

            <hr>

            <!-- Propriedades do bloco selecionado -->
            <div id="block-properties" style="display:none;">
                <h6>Propriedades do Bloco</h6>

                <div class="mb-2">
                    <small class="text-muted">ID: <span id="prop-id">-</span></small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo</label>
                    <input type="text" id="prop-tipo" class="form-control form-control-sm" disabled>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label">X (mm)</label>
                        <input type="number" id="prop-x" class="form-control form-control-sm" step="0.5">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Y (mm)</label>
                        <input type="number" id="prop-y" class="form-control form-control-sm" step="0.5">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label">Largura (mm)</label>
                        <input type="number" id="prop-w" class="form-control form-control-sm" step="0.5" min="5">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Altura (mm)</label>
                        <input type="number" id="prop-h" class="form-control form-control-sm" step="0.5" min="5">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Z-Index</label>
                    <input type="number" id="prop-z" class="form-control form-control-sm" min="0">
                </div>

                <!-- Texto -->
                <div id="props-texto" style="display:none;">
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Conteúdo</label>
                        <textarea id="prop-conteudo" class="form-control form-control-sm" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tamanho (pt)</label>
                        <input type="number" id="prop-font-size" class="form-control form-control-sm" min="6" max="72" value="12">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cor</label>
                        <input type="color" id="prop-color" class="form-control form-control-sm" value="#000000">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alinhamento</label>
                        <select id="prop-align" class="form-control form-control-sm">
                            <option value="left">Esquerda</option>
                            <option value="center">Centro</option>
                            <option value="right">Direita</option>
                        </select>
                    </div>

                    <div class="form-check mb-2">
                        <input type="checkbox" id="prop-bold" class="form-check-input">
                        <label class="form-check-label" for="prop-bold">Negrito</label>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" id="prop-italic" class="form-check-input">
                        <label class="form-check-label" for="prop-italic">Itálico</label>
                    </div>
                </div>

                <!-- Campo Dinâmico -->
                <div id="props-campo" style="display:none;">
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Campo</label>
                        <select id="prop-campo" class="form-control form-control-sm">
                            <option value="">-- Selecione --</option>
                            @if($template->tipo === 'flyer')
                                <option value="@{{titulo}}">@{{titulo}} — Título</option>
                                <option value="@{{numero_seminario}}">@{{numero_seminario}} — Número Seminário</option>
                                <option value="@{{data_inicio}}">@{{data_inicio}} — Data Início</option>
                                <option value="@{{data_fim}}">@{{data_fim}} — Data Fim</option>
                                <option value="@{{local}}">@{{local}} — Local</option>
                                <option value="@{{publico_alvo}}">@{{publico_alvo}} — Público-Alvo</option>
                                <option value="@{{investimento}}">@{{investimento}} — Investimento</option>
                                <option value="@{{carga_horaria}}">@{{carga_horaria}} — Carga Horária</option>
                            @else
                                <option value="@{{nome_aluno}}">@{{nome_aluno}} — Nome Aluno</option>
                                <option value="@{{titulo_curso}}">@{{titulo_curso}} — Título Curso</option>
                                <option value="@{{data_curso}}">@{{data_curso}} — Data Curso</option>
                                <option value="@{{cidade_curso}}">@{{cidade_curso}} — Cidade</option>
                                <option value="@{{topicos}}">@{{topicos}} — Tópicos</option>
                            @endif
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tamanho (pt)</label>
                        <input type="number" id="prop-campo-font-size" class="form-control form-control-sm" min="6" max="72" value="12">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cor</label>
                        <input type="color" id="prop-campo-color" class="form-control form-control-sm" value="#000000">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alinhamento</label>
                        <select id="prop-campo-align" class="form-control form-control-sm">
                            <option value="left">Esquerda</option>
                            <option value="center">Centro</option>
                            <option value="right">Direita</option>
                        </select>
                    </div>
                </div>

                <hr>
                <button type="button" class="btn btn-sm btn-danger w-100" id="btn-delete-block">
                    <i class="fas fa-trash"></i> Deletar Bloco
                </button>
            </div>

            <hr>

            <!-- Ações -->
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-success" id="btn-save">
                    <i class="fas fa-save"></i> Salvar Layout
                </button>
                <a href="{{ route('admin.templates.preview', $template->id) }}" target="_blank" class="btn btn-info">
                    <i class="fas fa-eye"></i> Preview PDF
                </a>
                <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
<script>
const SCALE_RATIO = 2; // 1mm = 2px no canvas
const TEMPLATE_WIDTH_MM = {{ $template->largura_mm }};
const TEMPLATE_HEIGHT_MM = {{ $template->altura_mm }};
const CANVAS_WIDTH = TEMPLATE_WIDTH_MM * SCALE_RATIO;
const CANVAS_HEIGHT = TEMPLATE_HEIGHT_MM * SCALE_RATIO;
const TEMPLATE_TIPO = '{{ $template->tipo }}';

let canvas;
let currentLayout = {!! json_encode($template->layout ?? ['blocks' => []]) !!};
let selectedObject = null;
let blockIdCounter = 0;

function init() {
    canvas = new fabric.Canvas('editor-canvas', {
        width: CANVAS_WIDTH,
        height: CANVAS_HEIGHT,
        backgroundColor: '#fff',
    });

    canvas.on('object:added', handleObjectAdded);
    canvas.on('object:modified', handleObjectModified);
    canvas.on('selection:created', handleSelection);
    canvas.on('selection:updated', handleSelection);
    canvas.on('selection:cleared', handleSelectionCleared);

    loadLayout();
    setupEventListeners();
}

function loadLayout() {
    if (!currentLayout.blocks || currentLayout.blocks.length === 0) return;

    currentLayout.blocks.forEach(block => {
        addBlockToCanvas(block);
        blockIdCounter = Math.max(blockIdCounter, parseInt(block.id.replace('blk_', '')) || 0);
    });
}

function addBlockToCanvas(blockData) {
    const x = (blockData.x_mm || 0) * SCALE_RATIO;
    const y = (blockData.y_mm || 0) * SCALE_RATIO;
    const w = (blockData.w_mm || 50) * SCALE_RATIO;
    const h = (blockData.h_mm || 20) * SCALE_RATIO;

    let obj;
    if (blockData.tipo === 'texto' || blockData.tipo === 'campo') {
        const label = blockData.tipo === 'campo'
            ? (blockData.campo || 'Campo')
            : (blockData.conteudo || 'Texto');
        obj = new fabric.Textbox(label, {
            left: x,
            top: y,
            width: w,
            height: h,
            fontSize: (blockData.font_size || 12) * (2/3),
            fill: blockData.color || '#000',
            textAlign: blockData.align || 'left',
            fontWeight: blockData.bold ? 'bold' : 'normal',
            fontStyle: blockData.italic ? 'italic' : 'normal',
        });
    } else if (blockData.tipo === 'imagem') {
        obj = new fabric.Rect({
            left: x,
            top: y,
            width: w,
            height: h,
            fill: '#e0e0e0',
            stroke: '#999',
            strokeDasharray: [5, 5],
        });
        obj.custom = { tipo: 'imagem' };
    } else {
        obj = new fabric.Rect({
            left: x,
            top: y,
            width: w,
            height: h,
            fill: 'rgba(100,200,255,0.3)',
            stroke: '#0066cc',
        });
    }

    obj.custom = blockData;
    obj.setControlsVisibility({
        ml: true, mr: true, mt: true, mb: true,
        bl: true, br: true, tl: true, tr: true,
        mtr: true, removeIcon: false,
    });

    canvas.add(obj);
}

function setupEventListeners() {
    document.getElementById('btn-add-texto').addEventListener('click', () => addTexto());
    document.getElementById('btn-add-imagem').addEventListener('click', () => addImagem());
    document.getElementById('btn-add-campo').addEventListener('click', () => addCampo());
    document.getElementById('btn-save').addEventListener('click', saveLayout);
    document.getElementById('btn-delete-block').addEventListener('click', deleteSelectedBlock);

    // Propriedades
    ['prop-x', 'prop-y', 'prop-w', 'prop-h', 'prop-z'].forEach(id => {
        document.getElementById(id).addEventListener('change', updateBlockFromForm);
    });

    document.getElementById('prop-conteudo').addEventListener('change', updateBlockFromForm);
    document.getElementById('prop-font-size').addEventListener('change', updateBlockFromForm);
    document.getElementById('prop-color').addEventListener('change', updateBlockFromForm);
    document.getElementById('prop-align').addEventListener('change', updateBlockFromForm);
    document.getElementById('prop-bold').addEventListener('change', updateBlockFromForm);
    document.getElementById('prop-italic').addEventListener('change', updateBlockFromForm);

    document.getElementById('prop-campo').addEventListener('change', updateBlockFromForm);
    document.getElementById('prop-campo-font-size').addEventListener('change', updateBlockFromForm);
    document.getElementById('prop-campo-color').addEventListener('change', updateBlockFromForm);
    document.getElementById('prop-campo-align').addEventListener('change', updateBlockFromForm);
}

function addTexto() {
    blockIdCounter++;
    const texto = new fabric.Textbox('Novo texto', {
        left: 100, top: 100, width: 150, height: 40,
        fontSize: 16,
    });
    texto.custom = {
        id: 'blk_' + blockIdCounter,
        tipo: 'texto',
        conteudo: 'Novo texto',
        font_size: 16,
        color: '#000',
        align: 'left',
        bold: false,
        italic: false,
        font_family: 'DejaVu Sans',
    };
    canvas.add(texto);
    canvas.setActiveObject(texto);
    canvas.renderAll();
}

function addImagem() {
    blockIdCounter++;
    const img = new fabric.Rect({
        left: 100, top: 100, width: 100, height: 100,
        fill: '#e0e0e0',
        stroke: '#999',
        strokeDasharray: [5, 5],
    });
    img.custom = {
        id: 'blk_' + blockIdCounter,
        tipo: 'imagem',
        imagem: '',
        x_mm: 50,
        y_mm: 50,
        w_mm: 50,
        h_mm: 50,
    };
    canvas.add(img);
    canvas.setActiveObject(img);
    canvas.renderAll();
}

function addCampo() {
    blockIdCounter++;
    const campo = new fabric.Textbox('@{{campo}}', {
        left: 100, top: 100, width: 150, height: 40,
        fontSize: 14,
    });
    campo.custom = {
        id: 'blk_' + blockIdCounter,
        tipo: 'campo',
        campo: '@{{titulo}}',
        font_size: 14,
        color: '#000',
        align: 'left',
    };
    canvas.add(campo);
    canvas.setActiveObject(campo);
    canvas.renderAll();
}

function handleSelection(e) {
    selectedObject = e.selected[0];
    showBlockProperties();
}

function handleSelectionCleared() {
    selectedObject = null;
    document.getElementById('block-properties').style.display = 'none';
}

function showBlockProperties() {
    if (!selectedObject || !selectedObject.custom) return;

    const data = selectedObject.custom;
    const props = document.getElementById('block-properties');
    props.style.display = 'block';

    document.getElementById('prop-id').textContent = data.id || '-';
    document.getElementById('prop-tipo').value = data.tipo || '-';

    const x_mm = (selectedObject.left / SCALE_RATIO).toFixed(1);
    const y_mm = (selectedObject.top / SCALE_RATIO).toFixed(1);
    const w_mm = (selectedObject.width / SCALE_RATIO).toFixed(1);
    const h_mm = (selectedObject.height / SCALE_RATIO).toFixed(1);

    document.getElementById('prop-x').value = x_mm;
    document.getElementById('prop-y').value = y_mm;
    document.getElementById('prop-w').value = w_mm;
    document.getElementById('prop-h').value = h_mm;
    document.getElementById('prop-z').value = data.z || 0;

    // Mostrar apenas os painéis relevantes
    document.getElementById('props-texto').style.display = (data.tipo === 'texto' || data.tipo === 'campo') ? 'block' : 'none';
    document.getElementById('props-campo').style.display = data.tipo === 'campo' ? 'block' : 'none';

    if (data.tipo === 'texto') {
        document.getElementById('prop-conteudo').value = data.conteudo || '';
        document.getElementById('prop-font-size').value = data.font_size || 12;
        document.getElementById('prop-color').value = data.color || '#000000';
        document.getElementById('prop-align').value = data.align || 'left';
        document.getElementById('prop-bold').checked = data.bold || false;
        document.getElementById('prop-italic').checked = data.italic || false;
    } else if (data.tipo === 'campo') {
        document.getElementById('prop-campo').value = data.campo || '';
        document.getElementById('prop-campo-font-size').value = data.font_size || 12;
        document.getElementById('prop-campo-color').value = data.color || '#000000';
        document.getElementById('prop-campo-align').value = data.align || 'left';
    }
}

function updateBlockFromForm() {
    if (!selectedObject || !selectedObject.custom) return;

    const data = selectedObject.custom;
    data.x_mm = parseFloat(document.getElementById('prop-x').value) || 0;
    data.y_mm = parseFloat(document.getElementById('prop-y').value) || 0;
    data.w_mm = parseFloat(document.getElementById('prop-w').value) || 50;
    data.h_mm = parseFloat(document.getElementById('prop-h').value) || 20;
    data.z = parseInt(document.getElementById('prop-z').value) || 0;

    if (data.tipo === 'texto') {
        data.conteudo = document.getElementById('prop-conteudo').value;
        data.font_size = parseInt(document.getElementById('prop-font-size').value) || 12;
        data.color = document.getElementById('prop-color').value;
        data.align = document.getElementById('prop-align').value;
        data.bold = document.getElementById('prop-bold').checked;
        data.italic = document.getElementById('prop-italic').checked;

        selectedObject.set({
            text: data.conteudo,
            fontSize: (data.font_size * 2) / 3,
            fill: data.color,
            textAlign: data.align,
            fontWeight: data.bold ? 'bold' : 'normal',
            fontStyle: data.italic ? 'italic' : 'normal',
        });
    } else if (data.tipo === 'campo') {
        data.campo = document.getElementById('prop-campo').value;
        data.font_size = parseInt(document.getElementById('prop-campo-font-size').value) || 12;
        data.color = document.getElementById('prop-campo-color').value;
        data.align = document.getElementById('prop-campo-align').value;

        selectedObject.set({
            text: data.campo,
            fontSize: (data.font_size * 2) / 3,
            fill: data.color,
            textAlign: data.align,
        });
    }

    selectedObject.set({
        left: data.x_mm * SCALE_RATIO,
        top: data.y_mm * SCALE_RATIO,
        width: data.w_mm * SCALE_RATIO,
        height: data.h_mm * SCALE_RATIO,
    });

    canvas.renderAll();
}

function deleteSelectedBlock() {
    if (!selectedObject) return;
    canvas.remove(selectedObject);
    selectedObject = null;
    document.getElementById('block-properties').style.display = 'none';
    canvas.renderAll();
}

function handleObjectAdded(e) {
    if (!e.target.custom) {
        e.target.custom = {
            id: 'blk_' + (++blockIdCounter),
            tipo: 'texto',
        };
    }
}

function handleObjectModified(e) {
    if (e.target.custom) {
        e.target.custom.x_mm = (e.target.left / SCALE_RATIO).toFixed(1);
        e.target.custom.y_mm = (e.target.top / SCALE_RATIO).toFixed(1);
        e.target.custom.w_mm = (e.target.getScaledWidth() / SCALE_RATIO).toFixed(1);
        e.target.custom.h_mm = (e.target.getScaledHeight() / SCALE_RATIO).toFixed(1);
    }
}

function saveLayout() {
    const blocks = canvas.getObjects().map(obj => {
        if (obj.custom) {
            return {
                ...obj.custom,
                x_mm: parseFloat((obj.left / SCALE_RATIO).toFixed(1)),
                y_mm: parseFloat((obj.top / SCALE_RATIO).toFixed(1)),
                w_mm: parseFloat((obj.getScaledWidth() / SCALE_RATIO).toFixed(1)),
                h_mm: parseFloat((obj.getScaledHeight() / SCALE_RATIO).toFixed(1)),
            };
        }
        return null;
    }).filter(Boolean);

    const layoutJSON = JSON.stringify({ blocks });

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.templates.update", $template->id) }}';

    const layoutInput = document.createElement('input');
    layoutInput.type = 'hidden';
    layoutInput.name = 'layout';
    layoutInput.value = layoutJSON;

    const nameInput = document.createElement('input');
    nameInput.type = 'hidden';
    nameInput.name = 'nome';
    nameInput.value = '{{ $template->nome }}';

    const larguraInput = document.createElement('input');
    larguraInput.type = 'hidden';
    larguraInput.name = 'largura_mm';
    larguraInput.value = '{{ $template->largura_mm }}';

    const alturaInput = document.createElement('input');
    alturaInput.type = 'hidden';
    alturaInput.name = 'altura_mm';
    alturaInput.value = '{{ $template->altura_mm }}';

    const orientacaoInput = document.createElement('input');
    orientacaoInput.type = 'hidden';
    orientacaoInput.name = 'orientacao';
    orientacaoInput.value = '{{ $template->orientacao }}';

    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = '{{ csrf_token() }}';

    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'PUT';

    form.appendChild(layoutInput);
    form.appendChild(nameInput);
    form.appendChild(larguraInput);
    form.appendChild(alturaInput);
    form.appendChild(orientacaoInput);
    form.appendChild(tokenInput);
    form.appendChild(methodInput);

    document.body.appendChild(form);
    form.submit();
}

document.addEventListener('DOMContentLoaded', init);
</script>
@endsection
