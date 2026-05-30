<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            width: 297mm;
            height: 210mm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            @if($fundo)
            background-image: url("{{ $fundo }}");
            background-size: cover;
            background-position: center center;
            @endif
        }

        .nome {
            text-align: center;
            position: absolute;
            top: 133mm;
            width: 100%;
        }

        .titulo {
            text-align: center;
            position: absolute;
            top: 151mm;
            width: 100%;
            font-size: 15px;
            font-style: italic;
            font-weight: bold;
        }

        .data {
            text-align: center;
            position: absolute;
            top: 163mm;
            width: 100%;
        }

        .topico {
            text-align: justify;
            position: absolute;
            top: 179mm;
            left: 37mm;
            right: 37mm;
            font-size: 11px;
        }

        .assinatura {
            position: absolute;
            top: 157mm;
            left: 178mm;
            width: 47mm;
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
        }
    </style>
</head>
<body>
    <div class="nome">Certificamos que <b>{{ $nome }}</b> participou do curso</div>
    <div class="titulo">"{{ $titulo }}"</div>
    <div class="data">Realizado nos dias <b>{{ $data }}</b>, na cidade de <b>{{ $cidade }}</b>.</div>
    @if($topico)
    <div class="topico"><b>TÓPICOS: </b>{{ $topico }}</div>
    @endif
    @if($assinatura)
    <img class="assinatura" src="{{ $assinatura }}" />
    @endif
    <div class="participante">Participante</div>
    <div class="instituto">Instituto Ulysses Guimarães LTDA<br>CNPJ: 40.033.708/0001-63</div>
</body>
</html>
