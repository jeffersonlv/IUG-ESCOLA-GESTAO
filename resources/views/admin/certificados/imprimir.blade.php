<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Certificado — {{ $nome }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page { size: A4 portrait; margin: 0; }

        html, body {
            width: 210mm;
            height: 297mm;
            overflow: hidden;
            background: #fff;
        }

        .cert {
            position: relative;
            width: 210mm;
            height: 297mm;
            font-family: Arial, Helvetica, sans-serif;
            overflow: hidden;
        }

        .cert-bg {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Conteúdo começa após o "Certificado" do fundo (~42% do topo) */
        .cert-body {
            position: absolute;
            top: 42%;
            left: 0; right: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 18mm;
            gap: 5mm;
            text-align: center;
        }

        .cert-intro {
            font-size: 12pt;
            line-height: 1.5;
        }

        .cert-titulo {
            font-size: 13pt;
            font-weight: bold;
            line-height: 1.4;
            margin-top: 1mm;
        }

        .cert-data {
            font-size: 11pt;
            line-height: 1.5;
        }

        .cert-topico {
            font-size: 9.5pt;
            text-align: justify;
            line-height: 1.5;
            margin-top: 2mm;
        }

        .cert-footer {
            position: absolute;
            bottom: 18mm;
            left: 18mm;
            right: 18mm;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
        }

        .cert-ass-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2mm;
        }

        .cert-ass-block img {
            height: 14mm;
            object-fit: contain;
        }

        .cert-line {
            border-top: 2px solid #000;
            padding-top: 1.5mm;
            text-align: center;
            font-size: 7.5pt;
            font-weight: bold;
            width: 50mm;
        }
    </style>
</head>
<body>
<div class="cert">
    <img class="cert-bg" src="{{ asset('images/fundoCertificado.jpg') }}" />

    <div class="cert-body">
        <div class="cert-intro">Certificamos que <b>{{ $nome }}</b> participou do curso</div>
        <div class="cert-titulo">"{{ $titulo }}"</div>
        <div class="cert-data">Realizado nos dias <b>{{ $data }}</b>,<br>na cidade de <b>{{ $cidade }}</b>.</div>
        @if($topico)
        <div class="cert-topico"><b>TÓPICOS: </b>{{ $topico }}</div>
        @endif
    </div>

    <div class="cert-footer">
        <div class="cert-ass-block">
            <div class="cert-line">Participante</div>
        </div>
        <div class="cert-ass-block">
            <img src="{{ asset('images/assinatura.png') }}" />
            <div class="cert-line">Instituto Ulysses Guimarães LTDA<br>CNPJ: 40.033.708/0001-63</div>
        </div>
    </div>
</div>
<script>
if (!new URLSearchParams(location.search).has('capture')) window.print();
</script>
</body>
</html>
