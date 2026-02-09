<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RECIBO</title>

    <style>
        * {
            padding: 0;
            margin: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            padding: 20px;
            position: relative;
        }

        .header,
        .footer {
            text-align: center;
        }

        .company-info,
        .client-info,
        .invoice-details {
            margin-top: 10px;
        }

        .table {
            width: 95%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table-taxas {
            width: 75%;
            border-collapse: collapse;
            margin-top: 40px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .table th {
            background-color: #f0f0f0;
        }

        .total {
            text-align: right;
            padding-right: 10px;
        }

        .a6 {
            width: 8.5cm;
            padding: 10px;
            border: 1px dashed #000;
            margin-top: 30px;
            position: relative;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 70px;
            color: rgba(0, 0, 0, 0.1); /* cinza claro com transparência */
            font-weight: bold;
            white-space: nowrap;
            pointer-events: none; /* não atrapalha clique/seleção */
            z-index: 0;
        }
        
        .watermark-a6 {
            position: absolute;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 30px;
            color: rgba(0, 0, 0, 0.1); /* cinza claro com transparência */
            font-weight: bold;
            white-space: nowrap;
            pointer-events: none; /* não atrapalha clique/seleção */
            z-index: 0;
        }
        
    </style>
</head>
<body>
    
        <!-- A4 -->
    @if (Auth::user()->impressora == "Normal")
    <div class="container" style="width: 21cm; height: auto;">
        <!-- Cabeçalho com logotipo -->
        <table width="95%">
            <tr>
                <td width="50%">
                    @if ($escola->logotipo)
                    <img src="{{ $logotipo }}" alt="Logotipo" style="height: 80px;">
                    @endif
                </td>
                <td width="50%" align="right">
                    <strong>{{ $escola->nome }}</strong><br>
                    {{ $escola->endereco }}<br>
                    NIF: {{ $escola->documento }}<br>
                    Email: {{ $escola->email }}<br>
                    Tel: {{ $escola->telefone1 }} / {{ $escola->telefone2 }}
                </td>
            </tr>
        </table>
        
        @if ($pagamento->anulado == "Y")
        <div class="watermark">FACTURA ANULADA</div>
        @endif

        <!-- Cliente -->
        <div class="client-info">
            <strong>Fatura para:</strong><br>
            Nome: {{ $estudante->nome }} {{ $estudante->sobre_nome }} | Matrícula: {{ $matricula->numero_estudante ?? '--- --- ---' }}<br>
            Classe: {{ $classe ->classes ?? ""}} | Curso: {{ $curso->curso ?? '--- --- ---' }} | Turno: {{ $turno->turno ?? '--- --- ---' }}<br>
            NIF: {{ $estudante->bilheite ?? '--- --- ---' }}<br>
            Telefone: {{ $estudante->telefone_estudante ?? '--- --- ---' }}
        </div>
        
        <!-- Detalhes -->
        <div class="invoice-details">
            <strong>RECIBO <br> {{ $pagamento->next_factura }}</strong>
            <br> 
            <span style="font-size: 9px">ORGINAL</span>
            <br>
            <strong>Data:</strong> {{ \Carbon\Carbon::parse($pagamento->data_at)->format('d/m/Y') }}<br>
        </div>
        
        <table class="table">
            <thead style="border-bottom: 1px dashed #000;border-top: 1px dashed #000">
                <tr style="text-align: center;font-size: 12px;">
                    <th style="padding: 4px 0">N.º</th>
                    <th>Data documento</th>
                    <th>Documento</th>
                    <th>Total do documento</th>
                    <th>Total Imposto</th>
                    <th>Valor Pago</th>
                    <th>Dívida</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 4px 0">1</td>
                    <td>{{ $pagamento->data_at }}</td>
                    <td>{{ $pagamento->numeracao_proforma }}</td>
                    <td>{{ number_format($pagamento->total_incidencia + $pagamento->total_iva + $pagamento->multa, 2, ',', '.')  }}</td>
                    <td>{{ number_format(0, 2, ',', '.')  }}</td>
                    <td>{{ number_format($pagamento->total_incidencia + $pagamento->total_iva + $pagamento->multa, 2, ',', '.')  }}</td>
                    <td>{{ number_format($pagamento->valor - $pagamento->valor, 2, ',', '.')  }}</td>
                </tr>
            </tbody>
        </table>
        
        
                <!-- Pagamento -->
        <div style="margin-top: 20px;">
            <strong>Forma de Pagamento:</strong> {{ $pagamento->descricao_forma_pagamento($pagamento->tipo_pagamento) }} | <strong>Operador:</strong> {{ $funcionarioAtendente->nome ?? "" }}<br>
            <table width="95%" style="margin-top: 20px;border-top: 2px solid #c4c4c4;border-bottom: 2px solid #c4c4c4">
                <tr>
                    <td width="50%"></td>
                    <td width="50%"> Total Iliquido: <strong>{{ number_format(($pagamento->total_incidencia + $pagamento->total_iva + $pagamento->multa ?? 0), '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%"> Total Desconto: <strong>{{ number_format($pagamento->desconto, '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%"> Total Imposto: <strong>{{ number_format(0, '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%"> Retenção na fonte: <strong>{{ number_format($total_retencao, '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%"> Total Multa: <strong>{{ number_format(($pagamento->multa) , '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%"> Total: <strong>{{ number_format(($pagamento->total_incidencia + $pagamento->total_iva + $pagamento->multa ?? 0) , '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%"> Total Pago: <strong>{{ number_format(($pagamento->total_incidencia + $pagamento->total_iva + $pagamento->multa ?? 0), '2', ',', '.') }}</strong></td>
                </tr>
                
            </table>
        </div>

        <!-- Rodapé -->
        <div class="footer" style="margin-top: 20px; font-size: 11px;">

            <p>{{ $pagamento->obterCaracteres($pagamento->hash) }}</p>
            <p>Os bens serviços foram colocados à disposição do adquirente na data do documento</p>

            @if ($escola->tipo_regime_id == "regime_exclusao")
            <p><strong>IVA - REGIME DE EXCLUSÃO</strong></p>
            @endif

            @if ($escola->tipo_regime_id == "regime_geral")
            <p><strong>IVA - REGIME GERAL</strong></p>
            @endif

            @if ($escola->tipo_regime_id == "regime_simplificado")
            <p><strong>IVA - REGIME SIMPLIFICADO</strong></p>
            @endif

            <p>Software de gestão escolar, desenvolvido pela {{ env('APP_NAME') }}.</p>
        </div>
        
    </div>
    
    
    
    @endif

    @if (Auth::user()->impressora == "Ticket")
    <!-- A6 -->
    <div class="a6">
    </div>
    @endif

    {{-- <main style="position: absolute;top: 270px;right: 30px;left: 30px;">
        <table>
            <tr>
                <td style="font-size: 13px;padding: 1px 0"><strong>RECIBO</strong> <br> <span style="font-size: 9px">ORGINAL</span> </td>
                <td style="font-size: 9px;padding: 1px 0;text-align: right"><strong>{{ $pagamento->next_factura }}</strong></td>
            </tr>
        </table>

        <table style="width: 100%" class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
            <thead style="border-bottom: 1px dashed #000;border-top: 1px dashed #000">
                <tr style="text-align: center;font-size: 12px;">
                    <th style="padding: 4px 0">N.º</th>
                    <th>Data documento</th>
                    <th>Documento</th>
                    <th>Total do documento</th>
                    <th>Total Imposto</th>
                    <th>Valor Pago</th>
                    <th>Dívida</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 4px 0">1</td>
                    <td>{{ $pagamento->data_at }}</td>
                    <td>{{ $pagamento->numeracao_proforma }}</td>
                    <td>{{ number_format($pagamento->total_incidencia + $pagamento->total_iva + $pagamento->multa, 2, ',', '.')  }}</td>
                    <td>{{ number_format(0, 2, ',', '.')  }}</td>
                    <td>{{ number_format($pagamento->total_incidencia + $pagamento->total_iva + $pagamento->multa, 2, ',', '.')  }}</td>
                    <td>{{ number_format($pagamento->valor - $pagamento->valor, 2, ',', '.')  }}</td>
                </tr>
            </tbody>
        </table>

        @if ($pagamento->status == "Pendente")
        <table style="margin-top: 50px ">
            <tbody>
                <tr>
                    <th style="padding: 4px 0">
                        <p style="font-size: 20px;color: red;text-transform: uppercase"><em>ESTADO DO PAGAMENTO: {{ $pagamento->status }}</em></p>
                    </th>
                </tr>
            </tbody>
        </table>
        @endif

    </main>

    <footer style="position: absolute;bottom: 30;right: 30px;left: 30px;">
        <table style="">
            <tbody>

                <tr>
                    <td style="padding-bottom: 20px">OPERADOR <br>
                        _____________________________________ <br>
                        <strong> {{ $funcionarioAtendente->nome ?? "" }} </strong>
                    </td>
                    <td></td>
                </tr>

                <tr>
                    <td>Observação: Pago</td>
                    <td style="text-align: right;padding: 1px 0;"><strong>Total Pago:</strong> {{ number_format(($pagamento->total_incidencia + $pagamento->total_iva) + $pagamento->multa, '2', ',', '.') }}</td>
                </tr>

                <tr>
                    <td style="padding: 1px 0;">{{ $pagamento->obterCaracteres($pagamento->hash) }}</td>
                    <td></td>
                </tr>
                <tr style="">
                    <td style="padding: 1px 0; text-align: center;margin: 10px 0;border-top: 3px solid #000000" colspan="2">Total Por Extenso: <strong> {{ $pagamento->valor_extenso }}</strong></td>
                </tr>

                <tr style="">
                    <td style="padding: 1px 0; text-align: center;margin: 10px 0;border-top: 3px solid #000000" colspan="2">Software de gestão escolar, desenvolvido pela {{ env('APP_NAME') }}</td>
                </tr>

            </tbody>
        </table>
    </footer> --}}

</body>
</html>
