<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Certificado — {{ $nome }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <style>
        @page { size: A4 landscape }

        .sheet { position: relative !important; overflow: hidden; }

        .cert-bg {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            object-fit: fill;
            display: block;
            z-index: 0;
        }

        .cert-content { position: relative; z-index: 1; }

        .fundo {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 1.2em;
        }

        .divcentro { margin: 0 auto; }

        .nome {
            text-align: center;
            position: relative;
            top: 288px;
        }

        .titulo {
            text-align: center;
            position: relative;
            top: 324px;
            font-size: 1.3em;
            font-style: italic;
            font-weight: bold;
        }

        .data {
            text-align: center;
            position: relative;
            top: 350px;
        }

        .topico {
            text-align: justify;
            position: relative;
            top: 385px;
            margin: auto 15mm !important;
            font-size: 1em;
        }

        .participante {
            position: absolute;
            bottom: 13mm;
            left: 37mm;
            border-top: 3px solid #000;
            width: 68mm;
            text-align: center;
            padding-top: 5px;
            font-weight: bold;
            font-size: 0.75em;
            white-space: nowrap;
        }

        .instituto {
            position: absolute;
            bottom: 9.5mm;
            right: 37mm;
            border-top: 3px solid #000;
            width: 68mm;
            text-align: center;
            padding-top: 5px;
            font-weight: bold;
            font-size: 0.75em;
            white-space: nowrap;
        }

        .assinatura {
            position: absolute;
            bottom: 22mm;
            right: 41mm;
            width: 15%;
        }
    </style>
</head>
<body class="A4 landscape">
    <section class="sheet padding-10mm fundo">
        <img class="cert-bg" src="{{ asset('images/fundoCertificado.jpg') }}" />

        <div class="cert-content">
            <div class="divcentro nome">Certificamos que <b>{{ $nome }}</b> participou do curso</div>
            <div class="divcentro titulo">"{{ $titulo }}"</div>
            <div class="divcentro data">Realizado nos dias <b>{{ $data }}</b>, na cidade de <b>{{ $cidade }}</b>.</div>
            @if($topico)
            <div class="divcentro topico"><b>TÓPICOS: </b>{{ $topico }}</div>
            @endif
        </div>

        <div class="participante">Participante</div>
        <div class="instituto">Escola de Gestão Pública Ulysses Guimarães LTDA<br>CNPJ: 40.033.708/0001-63</div>
        <img class="assinatura" src="{{ asset('images/assinatura.png') }}" />
    </section>
</body>
<script>
if (!new URLSearchParams(location.search).has('capture')) window.print();
</script>
</html>
