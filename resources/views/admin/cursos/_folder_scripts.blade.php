<script>
const GERAR_PDF_URL = @json(route('admin.cursos.gerar-folder-pdf'));
const CSRF_TOKEN    = @json(csrf_token());

const DIAS_PT = ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'];
const MESES_PT = ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];

// ── Carrega dados salvos ──────────────────────────────────────────────────
(function() {
    try {
        const prog = {!! $programacaoInicial !!};
        if (Array.isArray(prog)) prog.forEach(d => adicionarDia(d));
    } catch(e) {}

})();

// ── Auto-gera dias ao mudar datas ─────────────────────────────────────────
document.getElementById('data_inicio').addEventListener('change', tentarGerarDias);
document.getElementById('data_fim').addEventListener('change', tentarGerarDias);

function tentarGerarDias() {
    const inicio = document.getElementById('data_inicio').value;
    const fim    = document.getElementById('data_fim').value;
    if (!inicio || !fim) return;

    const container = document.getElementById('programacao-container');
    if (container.children.length > 0) return; // não sobrescreve se já tem dias

    const d1 = new Date(inicio + 'T00:00:00');
    const d2 = new Date(fim    + 'T00:00:00');
    if (d2 < d1) return;

    for (let d = new Date(d1); d <= d2; d.setDate(d.getDate() + 1)) {
        const dia_semana = DIAS_PT[d.getDay()];
        const data       = String(d.getDate()).padStart(2,'0') + '/' + String(d.getMonth()+1).padStart(2,'0');
        const tipo       = (d.getTime() === d1.getTime()) ? 'credenciamento'
                         : (d.getTime() === d2.getTime()) ? 'encerramento'
                         : 'palestra';
        adicionarDia({ dia_semana, data, horario: '', tipo, temas: [] });
    }
}

// ── Serializa antes do submit ─────────────────────────────────────────────
document.getElementById('curso-form').addEventListener('submit', serializarDados);

function serializarDados() {
    const dias = [];
    document.querySelectorAll('.dia-row').forEach(function(row) {
        const tipoVal  = row.querySelector('.dia-tipo').value;
        const customEl = row.querySelector('.dia-tipo-custom');
        dias.push({
            dia_semana:   row.querySelector('.dia-semana').value,
            data:         row.querySelector('.dia-data').value,
            horario:      row.querySelector('.dia-horario').value,
            tipo:         tipoVal,
            tipo_label:   (tipoVal === 'personalizado' && customEl) ? customEl.value : '',
            temas:        row.querySelector('.dia-temas').value.split('\n').map(t => t.trim()).filter(t => t),
        });
    });
    document.getElementById('programacao-json').value = JSON.stringify(dias);
}

// ── Adicionar dia ─────────────────────────────────────────────────────────
function adicionarDia(dados) {
    const container = document.getElementById('programacao-container');
    const div = document.createElement('div');
    div.className = 'dia-row border rounded p-3 mb-2 bg-light position-relative';

    const tipo       = (dados && dados.tipo)       ? dados.tipo       : 'palestra';
    const tipoCustom = (dados && dados.tipo_label)  ? dados.tipo_label : '';

    div.innerHTML = `
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2"
                onclick="this.closest('.dia-row').remove()" title="Remover dia"></button>
        <div class="row g-2">
            <div class="col-md-3">
                <input class="form-control form-control-sm dia-semana" placeholder="Dia da semana"
                       value="${esc(dados && dados.dia_semana ? dados.dia_semana : '')}">
            </div>
            <div class="col-md-2">
                <input class="form-control form-control-sm dia-data" placeholder="Ex: 15/07"
                       value="${esc(dados && dados.data ? dados.data : '')}">
            </div>
            <div class="col-md-3">
                <input class="form-control form-control-sm dia-horario" placeholder="Ex: 08:00 às 12:00"
                       value="${esc(dados && dados.horario ? dados.horario : '')}">
            </div>
            <div class="col-md-4">
                <select class="form-select form-select-sm dia-tipo" onchange="toggleCustomTipo(this)">
                    <option value="palestra"${sel(tipo,'palestra')}>Palestra</option>
                    <option value="credenciamento"${sel(tipo,'credenciamento')}>Credenciamento</option>
                    <option value="encerramento"${sel(tipo,'encerramento')}>Encerramento</option>
                    <option value="personalizado"${sel(tipo,'personalizado')}>Personalizado...</option>
                </select>
                <input type="text" class="form-control form-control-sm dia-tipo-custom mt-1"
                       placeholder="Ex: Mesa Redonda"
                       style="display:${tipo === 'personalizado' ? 'block' : 'none'};"
                       value="${esc(tipoCustom)}">
            </div>
            <div class="col-12">
                <textarea class="form-control form-control-sm dia-temas" rows="3"
                          placeholder="Um tema por linha...">${esc(dados && dados.temas ? dados.temas.join('\n') : '')}</textarea>
            </div>
        </div>`;
    container.appendChild(div);
}

function toggleCustomTipo(select) {
    const input = select.closest('.col-md-4').querySelector('.dia-tipo-custom');
    input.style.display = select.value === 'personalizado' ? 'block' : 'none';
}

// ── Gerar Folder PDF ──────────────────────────────────────────────────────
async function gerarFolderPDF() {
    serializarDados();
    const btn = document.getElementById('btn-gerar-pdf');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Gerando...';

    const folderIds = Array.from(
        document.querySelectorAll('[name="palestrantes[]"]:checked')
    ).map(el => el.value);

    const dados = {
        titulo:                  document.querySelector('[name=titulo]').value,
        data_inicio:             document.querySelector('[name=data_inicio]').value,
        data_fim:                document.querySelector('[name=data_fim]').value,
        local:                   document.querySelector('[name=local]').value,
        investimento:            document.querySelector('[name=investimento]').value,
        carga_horaria:           document.querySelector('[name=carga_horaria]').value,
        publico_alvo:            document.querySelector('[name=publico_alvo]').value,
        programacao:             JSON.parse(document.getElementById('programacao-json').value || '[]'),
        folder_palestrante_ids:  folderIds,
    };

    try {
        const resp = await fetch(GERAR_PDF_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify(dados),
        });
        const result = await resp.json();

        if (result.success) {
            document.getElementById('folder-pdf-gerado').value = result.path;
            document.getElementById('pdf-status').style.display = '';
            document.getElementById('pdf-preview-link').href = result.url;
            btn.innerHTML = '<i class="fas fa-check me-2"></i>PDF Gerado!';
            btn.classList.replace('btn-outline-danger', 'btn-success');
        } else {
            alert('Erro ao gerar PDF: ' + (result.message || 'Erro desconhecido'));
            resetBtn(btn);
        }
    } catch(e) {
        alert('Erro de conexão: ' + e.message);
        resetBtn(btn);
    }
}

function resetBtn(btn) {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-magic me-2"></i>Gerar Folder PDF';
}

// ── Busca nas listas de palestrantes ─────────────────────────────────────
['busca-pal-folder', 'busca-palestrantes'].forEach(function(id) {
    const el = document.getElementById(id);
    if (!el) return;
    const listId = id === 'busca-pal-folder' ? 'lista-pal-folder' : 'lista-palestrantes';
    const itemClass = id === 'busca-pal-folder' ? '.pal-folder-item' : '.palestrante-item';
    el.addEventListener('input', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('#' + listId + ' ' + itemClass).forEach(function(item) {
            item.style.display = item.dataset.nome.includes(val) ? '' : 'none';
        });
    });
});

// ── Helpers ───────────────────────────────────────────────────────────────
function esc(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function sel(atual, val) { return atual === val ? ' selected' : ''; }
</script>
