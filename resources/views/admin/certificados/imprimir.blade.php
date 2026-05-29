<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Certificado — {{ $nome }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <style>
        @page { size: A4 landscape }

        .fundo {
            background: url('/images/fundoCertificado.jpg') center center / cover no-repeat;
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
            margin: auto 140px !important;
            font-size: 1em;
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
        }

        .assinatura {
            position: relative;
            top: 392px;
            left: 672px;
            width: 18%;
        }
    </style>
</head>
<body class="A4 landscape">
    <section class="sheet padding-10mm fundo">
        <div class="divcentro nome">Certificamos que <b>{{ $nome }}</b> participou do curso</div>
        <div class="divcentro titulo">"{{ $titulo }}"</div>
        <div class="divcentro data">Realizado nos dias <b>{{ $data }}</b>, na cidade de <b>{{ $cidade }}</b>.</div>
        @if($topico)
        <div class="divcentro topico"><b>TÓPICOS: </b>{{ $topico }}</div>
        @endif
        <div class="participante">Participante</div>
        <div class="instituto">Instituto Ulysses Guimarães LTDA<br>CNPJ: 40.033.708/0001-63</div>
        <img class="assinatura" src="/images/assinatura.png" />
    </section>
</body>
<script>window.print();</script>
</html>
