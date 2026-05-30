<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Certificado — {{ $nome }}</title>
    <style>
        /* paper-css inlined — A4 landscape */
        @page { size: A4 landscape; margin: 0; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: white; }
        .sheet {
            overflow: hidden;
            position: relative;
            page-break-after: always;
            width: 297mm;
            height: 210mm;
        }
        .padding-10mm { padding: 10mm; }

        /* fundo via img absoluta */
        .bg {
            position: absolute;
            top: 0; left: 0;
            width: 297mm;
            height: 210mm;
            z-index: 0;
        }

        /* conteúdo — mesmos valores do imprimir original */
        .fundo {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 1.2em;
        }
        .divcentro { margin: 0 auto; }

        .nome {
            text-align: center;
            position: relative;
            top: 288px;
            z-index: 1;
        }
        .titulo {
            text-align: center;
            position: relative;
            top: 324px;
            font-size: 1.3em;
            font-style: italic;
            font-weight: bold;
            z-index: 1;
        }
        .data {
            text-align: center;
            position: relative;
            top: 350px;
            z-index: 1;
        }
        .topico {
            text-align: justify;
            position: relative;
            top: 385px;
            margin: auto 140px !important;
            font-size: 1em;
            z-index: 1;
        }
        .participante {
            position: relative;
            top: 481px;
            left: 140px;
            border-top: 3px solid #000;
            width: 295px;
            text-align: center;
            padding-top: 5px;
            font-weight: bold;
            font-size: 0.75em;
            z-index: 1;
        }
        .instituto {
            position: relative;
            top: 456px;
            right: 140px;
            border-top: 3px solid #000;
            width: 295px;
            text-align: center;
            padding-top: 5px;
            font-weight: bold;
            float: right;
            font-size: 0.75em;
            z-index: 1;
        }
        .assinatura {
            position: relative;
            top: 392px;
            left: 672px;
            width: 18%;
            z-index: 1;
        }
    </style>
</head>
<body class="A4 landscape">
    <section class="sheet padding-10mm fundo">
        @if(!empty($fundo))<img class="bg" src="{{ $fundo }}" alt="">@endif
        <div class="divcentro nome">Certificamos que <b>{{ $nome }}</b> participou do curso</div>
        <div class="divcentro titulo">"{{ $titulo }}"</div>
        <div class="divcentro data">Realizado nos dias <b>{{ $data }}</b>, na cidade de <b>{{ $cidade }}</b>.</div>
        @if(!empty($topico))
        <div class="divcentro topico"><b>TÓPICOS: </b>{{ $topico }}</div>
        @endif
        <div class="participante">Participante</div>
        <div class="instituto">Instituto Ulysses Guimarães LTDA<br>CNPJ: 40.033.708/0001-63</div>
        @if(!empty($assinatura))<img class="assinatura" src="{{ $assinatura }}" alt="">@endif
    </section>
    @if(!empty($isPrint))<script>window.print();</script>@endif
</body>
</html>
