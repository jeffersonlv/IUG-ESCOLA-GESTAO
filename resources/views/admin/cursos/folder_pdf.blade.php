<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<style>
    @page { margin: 0; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        color: #1a1a1a;
        width: 210mm;
        height: 297mm;
    }
    /* Fundo de página inteira */
    .bg-img {
        position: absolute;
        top: 0; left: 0;
        width: 210mm;
        height: 297mm;
        z-index: 0;
    }
    /* Área de conteúdo — empurrada à direita para limpar os pilares do fundo
       e abaixo para limpar o logo do fundo */
    .wrap {
        position: absolute;
        top: 0; left: 0;
        z-index: 2;
        width: 210mm;
        padding: 55mm 8mm 12mm 49mm;
    }

    /* ── Público-alvo (caixa azul, topo-direita, abaixo do logo) ── */
    /* Instagram no extremo topo-direito */
    .instagram-top {
        position: absolute;
        top: 7mm;
        right: 8mm;
        text-align: right;
        font-size: 9pt;
        line-height: 1.3;
        color: #14245c;
        z-index: 3;
    }
    .instagram-top b { display: block; }

    .badge-publico {
        position: absolute;
        top: 31mm;
        right: 8mm;
        width: 54mm;
        background: #3f7fd6;
        color: #fff;
        font-size: 7.5pt;
        line-height: 1.35;
        padding: 2.5mm 3mm;
        border-radius: 1.5mm;
        z-index: 3;
    }
    .badge-publico b { font-weight: bold; }

    /* ── Bloco de título central ── */
    .titulo-bloco { text-align: center; margin-bottom: 6mm; }
    .local-badge {
        display: inline-block;
        background: #fff100;
        color: #14245c;
        font-weight: bold;
        font-size: 14pt;
        padding: 0.5mm 4mm;
        text-decoration: underline;
        margin-bottom: 3mm;
    }
    .titulo-evento {
        color: #14245c;
        font-weight: bold;
        font-size: 15pt;
        line-height: 1.2;
        margin-bottom: 1.5mm;
    }
    .titulo-data {
        color: #14245c;
        font-weight: bold;
        font-size: 13pt;
    }

    /* ── Colunas ── */
    table.cols { width: 100%; border-collapse: collapse; }
    td.col-left  { width: 56%; vertical-align: top; padding-right: 4mm; }
    td.col-right { width: 44%; vertical-align: top; }

    /* Programação (texto corrido como o folder real) */
    .prog-dia {
        color: #14245c;
        font-weight: bold;
        font-size: 9.5pt;
        margin-top: 2.5mm;
    }
    .prog-temas {
        font-size: 9pt;
        line-height: 1.4;
        margin-bottom: 1mm;
    }

    /* Bloco de contato/banco */
    .info { font-size: 9pt; line-height: 1.45; margin-top: 3mm; }
    .info .lbl { font-weight: bold; }
    .info a { color: #14245c; text-decoration: none; }

    /* Coluna direita */
    .right-titulo {
        color: #14245c;
        font-weight: bold;
        font-size: 10pt;
        margin-bottom: 1.5mm;
    }
    .right-instagram { font-size: 9pt; margin-bottom: 5mm; line-height: 1.3; }
    .pal-item { margin-bottom: 4mm; width: 18.7mm; }   /* 110% da foto (17mm) */
    .pal-foto {
        width: 17mm;
        height: 17mm;
        border-radius: 1mm;
        object-fit: cover;
        margin-bottom: 1mm;
    }
    .pal-nome  {
        font-weight: bold; font-size: 7pt; color: #14245c; line-height: 1.15;
        word-wrap: break-word; overflow-wrap: break-word;
    }
    .pal-cargo {
        font-size: 6pt; color: #333; font-style: italic; line-height: 1.15;
        word-wrap: break-word; overflow-wrap: break-word;
    }

    .obs {
        font-size: 7.5pt;
        color: #c0392b;
        font-weight: bold;
        margin-top: 5mm;
        line-height: 1.35;
    }
</style>
</head>
<body>
@php
    $d = $dados;
    $whats = $configs['whatsapp'] ?? $configs['telefone'] ?? '';
    $email = $configs['email'] ?? 'contato@institutoulyssesguimaraes.com.br';
    $fmt = function ($v) {
        try { return \Carbon\Carbon::parse($v); } catch (\Exception $e) { return null; }
    };
    $di = !empty($d['data_inicio']) ? $fmt($d['data_inicio']) : null;
    $df = !empty($d['data_fim'])    ? $fmt($d['data_fim'])    : null;
    $meses = [1=>'janeiro',2=>'fevereiro',3=>'março',4=>'abril',5=>'maio',6=>'junho',
              7=>'julho',8=>'agosto',9=>'setembro',10=>'outubro',11=>'novembro',12=>'dezembro'];
@endphp

@if(!empty($bgBase64))
<img class="bg-img" src="{{ $bgBase64 }}" alt="">
@endif

{{-- Instagram (extremo topo-direito) --}}
<div class="instagram-top"><b>Instagram:</b>@institutoulyssesguimaraes</div>

{{-- Público-alvo --}}
@if(!empty($d['publico_alvo']))
<div class="badge-publico"><b>Público-Alvo:</b> {{ $d['publico_alvo'] }}</div>
@endif

<div class="wrap">

    {{-- Título --}}
    <div class="titulo-bloco">
        @if(!empty($d['local']))
        <div class="local-badge">{{ strtoupper($d['local']) }}</div>
        @endif
        <div class="titulo-evento">{{ strtoupper($d['titulo']) }}</div>
        @if($di && $df)
        <div class="titulo-data">
            de {{ $di->day }} a {{ $df->day }} de {{ $meses[$df->month] }} de {{ $df->year }}
        </div>
        @endif
    </div>

    <table class="cols">
        <tr>
            {{-- Coluna esquerda: programação + contato --}}
            <td class="col-left">
                @if(!empty($d['programacao']))
                    @foreach($d['programacao'] as $dia)
                    @php
                        $tipoLabel = $dia['tipo_label']
                            ?? ucfirst(str_replace('palestra','Palestra',$dia['tipo'] ?? ''));
                        $tipoMap = [
                            'palestra' => 'Palestra',
                            'credenciamento' => 'Credenciamento',
                            'encerramento' => 'Encerramento',
                        ];
                        if (empty($dia['tipo_label'])) {
                            $tipoLabel = $tipoMap[$dia['tipo'] ?? ''] ?? '';
                        }
                    @endphp
                    <div class="prog-dia">
                        {{ $dia['dia_semana'] ?? '' }}@if(!empty($dia['data'])): {{ $dia['data'] }}@endif
                        @if(!empty($dia['horario'])) — Horário: {{ $dia['horario'] }}@endif
                        @if($tipoLabel) — {{ $tipoLabel }}@endif
                    </div>
                    @if(!empty($dia['temas']))
                    <div class="prog-temas">
                        @foreach($dia['temas'] as $tema){{ $tema }}<br>@endforeach
                    </div>
                    @endif
                    @endforeach
                @endif

                <div class="info">
                    <span class="lbl">Contato</span><br>
                    @if($whats)Telefone: {{ $whats }} (WhatsApp)<br>@endif
                    @if(!empty($d['investimento']))<span class="lbl">Investimento:</span> {{ $d['investimento'] }}<br>@endif
                    @if(!empty($d['carga_horaria']))<span class="lbl">Carga Horária:</span> {{ $d['carga_horaria'] }}<br>@endif
                    <br>
                    <span class="lbl">Dados Bancários:</span><br>
                    Banco do Brasil<br>
                    Agência: 2901-7<br>
                    Conta Corrente: 51010-6<br>
                    Instituto Ulysses Guimarães Ltda.<br>
                    CNPJ: 40.033.708/0001-63<br>
                    <span class="lbl">E-mail:</span> {{ $email }}<br>
                    <span class="lbl">Site:</span> institutoulyssesguimaraes.com.br/cursos<br>
                    @if(!empty($d['local']))<span class="lbl">Local:</span> {{ $d['local'] }}@endif
                </div>

                <div class="obs">
                    Obs.: O Instituto Ulysses Guimarães se reserva no direito de cancelar os
                    eventos, não se responsabilizando pela viagem sem inscrição antecipada.
                </div>
            </td>

            {{-- Coluna direita: instagram + palestrantes --}}
            <td class="col-right">
                @if(!empty($d['folder_palestrantes']))
                <div class="right-titulo">Palestrantes:</div>
                @foreach($d['folder_palestrantes'] as $p)
                <div class="pal-item">
                    @if(!empty($p['foto']))<img class="pal-foto" src="{{ $p['foto'] }}" alt="">@endif
                    <div class="pal-nome">{{ $p['nome'] ?? '' }}</div>
                    @if(!empty($p['cargo']))<div class="pal-cargo">{{ $p['cargo'] }}</div>@endif
                </div>
                @endforeach
                @endif
            </td>
        </tr>
    </table>

</div>
</body>
</html>
