<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Certificado — {{ $nome }}</title>
    <style>
        @page { size: A4 landscape; margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            width: 297mm;
            height: 210mm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            overflow: hidden;
        }
        .bg {
            position: absolute;
            top: 0; left: 0;
            width: 297mm;
            height: 210mm;
            z-index: 0;
        }
        .nome {
            position: absolute;
            top: 133mm;
            width: 100%;
            text-align: center;
            z-index: 1;
        }
        .titulo {
            position: absolute;
            top: 151mm;
            width: 100%;
            text-align: center;
            font-size: 15px;
            font-style: italic;
            font-weight: bold;
            z-index: 1;
        }
        .data {
            position: absolute;
            top: 163mm;
            width: 100%;
            text-align: center;
            z-index: 1;
        }
        .topico {
            position: absolute;
            top: 179mm;
            left: 37mm;
            right: 37mm;
            text-align: justify;
            font-size: 11px;
            z-index: 1;
        }
        .assinatura {
            position: absolute;
            top: 157mm;
            left: 178mm;
            width: 47mm;
            z-index: 1;
        }
        .participante {
            position: absolute;
            top: 196mm;
            left: 37mm;
            border-top: 2px solid #000;
            width: 78mm;
            text-align: center;
            padding-top: 2px;
            font-weight: bold;
            font-size: 10px;
            z-index: 1;
        }
        .instituto {
            position: absolute;
            top: 196mm;
            right: 37mm;
            border-top: 2px solid #000;
            width: 78mm;
            text-align: center;
            padding-top: 2px;
            font-weight: bold;
            font-size: 10px;
            z-index: 1;
        }
    </style>
</head>
<body>
    @if(!empty($fundo))<img class="bg" src="{{ $fundo }}" alt="">@endif
    <div class="nome">Certificamos que <b>{{ $nome }}</b> participou do curso</div>
    <div class="titulo">"{{ $titulo }}"</div>
    <div class="data">Realizado nos dias <b>{{ $data }}</b>, na cidade de <b>{{ $cidade }}</b>.</div>
    @if(!empty($topico))<div class="topico"><b>TÓPICOS: </b>{{ $topico }}</div>@endif
    @if(!empty($assinatura))<img class="assinatura" src="{{ $assinatura }}" alt="">@endif
    <div class="participante">Participante</div>
    <div class="instituto">Instituto Ulysses Guimarães LTDA<br>CNPJ: 40.033.708/0001-63</div>
    @if(!empty($isPrint))<script>window.print();</script>@endif
</body>
</html>
