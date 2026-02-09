<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>NOTA CREDITO</title>

    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            text-align: left;
        }

        body {
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1 {
            font-size: 15pt;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 12pt;
        }

        p {
            /* margin-bottom: 20px; */
            line-height: 25px;
            font-size: 12pt;
            text-align: justify;
        }

        strong {
            font-size: 12pt;
        }

        table {
            width: 100%;
            text-align: left;
            border-spacing: 0;
            margin-bottom: 10px;
            /* border: 1px solid rgb(0, 0, 0); */
            font-size: 12pt;
        }

        thead {
            background-color: #fdfdfd;
            font-size: 10px;
        }

        th,
        td {
            padding: 6px;
            font-size: 9px;
            margin: 0;
            padding: 0;
        }

        strong {
            font-size: 9px;
        }

    </style>

</head>
<body>
    @php
    function cabecalho($classe, $header1, $header2){
    switch ($classe) {
    case '10º Classe':
    case '10ª Classe':
    return $header2;
    break;

    case '11º Classe':
    case '11ª Classe':
    return $header2;
    break;

    case '12º Classe':
    case '12ª Classe':
    return $header2;
    break;

    case '13º Classe':
    case '13ª Classe':
    return $header2;
    break;

    default:
    return $header1;
    break;
    }
    }
    @endphp

    <header style="position: absolute;top: 0;right: 10px;left: 10px;">
        <table>
            <tr>
                <td rowspan="">
                    <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 70px;width: 70px;">
                </td>
                <td style="text-align: right">
                    <span>Pág: 1/1</span> <br> <br>
                    {{ $pagamento->data_at }} <br>
                    ORGINAL
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0;">
                    <strong>@php echo cabecalho($classe->classes, $escola->cabecalho1, $escola->cabecalho2); @endphp</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Endereço:</strong> {{ $escola->endereco }}
                </td>
                <td>DADOS CLIENTES</td>
            </tr>
            <tr>
                <td>
                    <strong>NIF:</strong> {{ $escola->documento }}
                </td>
                <td style="border-top: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px;">
                    <strong style="font-size: 9px">{{ $estudante->nome }} {{ $estudante->sobre_nome }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Telefone:</strong> {{ $escola->telefone1 }}
                </td>
                <td style="border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>NIF:</strong> {{ $estudante->bilheite ?? '--- --- ---' }}
                </td>
            </tr>
            <tr>
                <td>

                </td>
                <td style="border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>Nº MATRICULA: </strong> {{ $matricula->numero_estudante ?? '--- --- ---' }}
                </td>
            </tr>

            <tr>
                <td>

                </td>
                <td style="border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>TURMA: </strong> {{ $turma->turma ?? '--- --- ---' }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>E-mail:</strong> {{ $escola->site }}
                </td>
                <td style="border-bottom: #eaeaea 1px solid;border-right: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>TEL:</strong> {{ $estudante->telefone_estudante ?? '--- --- ---' }}
                </td>
            </tr>

        </table>
    </header>

    <main style="position: absolute;top: 230px;right: 10px;left: 10px;">
        <table>
            <tr>
                <td style="font-size: 13px"><strong>Luanda-Angola</strong></td>
                <td style="text-align: right">OPERADOR: {{ $pagamento->operador->nome }} <br> _______________________</td>
            </tr>

            <tr>
                @if ($pagamento->tipo_factura == 'FP')
                <td style="font-size: 13px;padding: 1px 0"><strong>FACTURA PRÓ-FORMA</strong></td>
                @endif

                @if ($pagamento->tipo_factura == 'FT')
                <td style="font-size: 13px;padding: 1px 0"><strong>FACTURA</strong></td>
                @endif

                @if ($pagamento->tipo_factura == 'FR')
                <td style="font-size: 13px;padding: 1px 0"><strong>FACTURA RECIBO</strong></td>
                <td style="font-size: 9px;padding: 1px 0;text-align: right">FORMA PAGAMENTO: {{ $pagamento->tipo_pagamento }}</td>
                @endif
            </tr>
        </table>

        <table>
            <tr>
                @if ($pagamento->convertido_factura == "Y")
                <td style="font-size: 13px"><strong>ANULAÇÃO REFERENTE À {{ $pagamento->numeracao_proforma }}</strong></td>
                @else
                <td style="font-size: 13px"><strong>{{ $pagamento->next_factura }}</strong></td>
                @endif
                <td style="text-align: right"><strong>{{ $pagamento->next_factura }}</strong></td>
            </tr>


        </table>
        @php
        $numero = 0;
        @endphp
        <table style="width: 100%" class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
            <thead style="border-bottom: 1px dashed #000;x">
                <tr>
                    <th style="padding: 2px 0">N.º</th>
                    <th>Descrição</th>
                    <th>Valor Unitário</th>
                    <th>Qtd</th>
                    <th>Un.</th>
                    <th>Desc. %</th>
                    <th>Taxa. %</th>
                    <th style="text-align: right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalhes as $item)
                @php
                $numero++;
                @endphp
                <tr>
                    <td style="padding: 2px 0">{{ $numero }}</td>
                    <td>{{ $item->mes }}</td>
                    <td>{{ number_format($item->preco, 2, ',', '.')  }} Kz</td>
                    <td>{{ number_format( $item->quantidade, 1, ',', '.') }}</td>
                    <td>un</td>
                    <td>{{ number_format( 0, 1, ',', '.') }}</td>
                    <td>{{ number_format( 0, 1, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format( $item->preco * $item->quantidade, 2, ',', '.') }} KZ</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>

    <footer style="position: absolute;bottom: 0;right: 10px;left: 10px;">
        <table style="border: 2px solid #000;padding: 10px">
            <tbody>
                <tr>
                    <td>Observação: <strong>{{ $pagamento->motivo }}</strong></td>
                    <td style="text-align: right;padding: 1px 0;"><strong>Total Factura:</strong> {{ number_format($pagamento->valor, '2', ',', '.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: right;padding: 1px 0;"><strong>Desconto:</strong> {{ number_format($pagamento->desconto, '2', ',', '.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: right;padding: 1px 0;"><strong>IVA:</strong> {{ number_format(0, '2', ',', '.') }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: right;padding: 1px 0;"><strong>Total pagar:</strong> {{ number_format($pagamento->valor , '2', ',', '.') }}</td>
                </tr>

                <tr>
                    <td colspan="2" style="padding: 1px 0;text-align: center">Total Por Extenso: <strong>{{ $pagamento->valor_extenso }}</strong> </td>
                </tr>



            </tbody>
        </table>
        <table>
            <tbody>
                <tr>
                    <td style="padding: 1px 0;">DXQO-Processado por programa válido nº 469/AGT/2024</td>
                    <td></td>
                </tr>

                <tr>
                    <td style="padding: 1px 0; text-align: center" colspan="2">Software de gestão escolar, desenvolvido pela {{ env('APP_NAME') }}</td>
                </tr>
            </tbody>
        </table>
    </footer>

</body>
</html>
