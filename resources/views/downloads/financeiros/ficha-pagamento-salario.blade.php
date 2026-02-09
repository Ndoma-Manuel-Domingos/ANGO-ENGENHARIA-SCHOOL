<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de pagamento de Salário</title>

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
            max-width: 800px;
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

    
    <header style="position: absolute;top: 30px;right: 5px;left: 15px;padding: 15px">
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
                <td style="padding: 5px 0;font-size: 15px"> {{ $escola->nome }} </td>
            </tr>
            
            <tr>
                <td>
                    <strong>Endereço:</strong> {{ $escola->endereco }}
                </td>
            </tr>
            
            <tr>
                <td>
                    <strong>NIF:</strong> {{ $escola->documento }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Telefone:</strong> {{ $escola->telefone1 }}
                </td>
            </tr>
            
            <tr>
                <td>
                    <strong>E-mail:</strong> {{ $escola->site }}
                </td>
            </tr>
            <tr>
                <td style="font-size: 9px"><strong>Luanda-Angola</strong></td>
            </tr>
        </table>
        
        
    </header>

    <main style="position: absolute;top: 230px;right: 30px;left: 30px;">
        <table>
            <tr>
                <td colspan="4" style="padding-bottom: 10px">DADOS CLIENTES</td>
            </tr>
            
            <tr>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">Nome</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">N.º Mecan.</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">N.º Benef.</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">N.º Contrib.</td>
            </tr>
            
            <tr>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">
                    <strong style="font-size: 9px">{{ $funcionario->nome }} {{ $funcionario->sobre_nome }}</strong>
                </td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">
                    <strong style="font-size: 9px">{{ $funcionario->id }}</strong>
                </td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">
                    <strong style="font-size: 9px">{{ $funcionario->codigo }}</strong>
                </td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">
                    <strong style="font-size: 9px">{{ $funcionario->bilheite }}</strong>
                </td>
            </tr>
        </table>
        
        
        <table style="margin-top: 50px;border-top: 2px solid #000">

            <tr>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">Período</th>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">Data Fecho</th>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;">Tempo Semanal</th>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;">Tempo Mensal</th>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;">Tempos em Faltas</th>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;">Tempos Dados</th>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">Departamento</th>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">Cargo</th>
            </tr>
            
            <tr>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">
                    @foreach ($detalhes as $item)
                        {{ $item->mes }}
                    @endforeach
                </td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">
                    {{ $pagamento->data_at }}
                </td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;">
                    {{ number_format($pagamento->total_tempos_semanal, 2, ',', '.') }}
                </td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;">
                    {{ number_format($pagamento->total_tempos_mensal, 2, ',', '.') }}
                </td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;">
                    {{ number_format($pagamento->faltas, 2, ',', '.') }}
                </td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;">
                    {{ number_format($pagamento->presenca, 2, ',', '.') }}
                </td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">
                    {{ $contrato->departamento->departamento ?? "" }}
                </td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">
                    {{ $contrato->cargos->cargo ?? "" }}
                </td>
            </tr>
        </table>
        
        
        <table style="margin-top: 50px;border-top: 2px solid #000">

            <tr>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">Cód.</th>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">Data</th>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;">Descrição</th>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;">Remunerações</th>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;">Descontos</th>
            </tr>
            
            <tr>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px">R01</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px"></td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px">Vencimento</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px">{{ number_format($pagamento->salario_bruto, 2, ',', '.') }}</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px"></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px">R12</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px"></td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px">Subsídio Alimentação - Valor Fixo</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px">{{ number_format($pagamento->subcidio_alimentacao, 2, ',', '.') }}</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px"></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px">R93</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px"></td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px">Subsídio de Transporte Mensal</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px">{{ number_format($pagamento->subcidio_transporte, 2, ',', '.') }}</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px"></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px">D01</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px"></td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px">Segurança Social (3%)</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px"></td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px">{{ number_format($pagamento->inss, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px">D02</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px"></td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px">IRT (19%)</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px"></td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px">{{ number_format($pagamento->irt, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px">D05</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px"></td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: left;font-size: 9px">Outros Descontos</td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px"></td>
                <td style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px">{{ number_format($pagamento->desconto, 2, ',', '.') }}</td>
            </tr>
           
        </table>
        
    </main>

    <footer style="position: absolute;bottom: 30px;right: 30px;left: 30px;">
        {{-- <table style="margin-top: 50px;border-top: 2px solid #000">
            <tbody>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px" colspan="3">Total</th>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px;width: 150px">{{ number_format($pagamento->valor2, 2, ',', '.') }}</th>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 9px;width: 150px">{{ number_format($pagamento->valor2, 2, ',', '.') }}</th>
            </tbody>
        </table>
         --}}
        <table style="margin-top: 50px;border-top: 2px solid #000">
            <tbody>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 16px" colspan="4">Total Pago ( AKZ )</th>
                <th style="border-bottom: 1px solid #e7e7e7;padding: 3px;padding-bottom: 10px;text-align: right;font-size: 16px;width: 150px" >{{ number_format($pagamento->valor2, 2, ',', '.') }}</th>
            </tbody>
        </table>
    
        <table style="margin-bottom: 50px;border-top: 2px solid #000">
            <tbody>
                <tr>
                    <th>Declaro que recebi a quantia constante neste recibo</th>
                    <td style="text-align: right;padding: 1px 0;"> </td>
                </tr>
            </tbody>
        </table>
    </footer>


</body>
</html>
