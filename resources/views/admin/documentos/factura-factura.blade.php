<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>FACTURA</title>
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
                    <img src="{{ $logotipo }}" alt="Logotipo" style="height: 80px;">
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
            <strong>
                {{ $pagamento->tipo_factura == 'FR' ? "Factura Recibo Nº:" : ($pagamento->tipo_factura == 'FT' ? "Factura Nº:" : ($pagamento->tipo_factura == 'FP' ? "Factura Pro-forma Nº:" : "Documento não indetificado")) }}
            </strong>
            {{ $pagamento->convertido_factura == "Y" ? $pagamento->next_factura . " conforme ". $pagamento->numeracao_proforma : $pagamento->next_factura }}
            <br>

            <strong>Data:</strong> {{ \Carbon\Carbon::parse($pagamento->data_at)->format('d/m/Y') }}<br>
            <strong>Descrição:</strong> {{ $pagamento->observacao ?? "sem descrição" }}<br>
            <strong style="float: right;margin-right: 40px">{{ $opcao ?? "" }}</strong>
        </div>

        <!-- Tabela de itens -->
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Descrição</th>
                    <th style="text-align: right">Qtd</th>
                    <th style="text-align: right">Preço Unitário</th>
                    <th style="text-align: right">Taxa</th>
                    <th style="text-align: right">Desconto</th>
                    <th style="text-align: right">Multa</th>
                    <th style="text-align: right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalhes as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->servico->servico ?? "" }}({{ $item->mes }})</td>
                    <td style="text-align: right">{{ number_format( $item->quantidade, 1, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($item->preco, 2, ',', '.')  }}</td>
                    <td style="text-align: right">{{ number_format( $item->taxa_id, 1, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format( $item->desconto, 1, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format( $item->multa, 1, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($item->total_pagar, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table-taxas">
            <thead>
                <tr>
                    <th style="text-align: left">Desc.</th>
                    <th style="text-align: left">Taxa%</th>
                    <th style="text-align: left">Incidência</th>
                    <th style="text-align: left">Valor Imposto</th>
                    <th style="text-align: left">Motivo de Isenção</th>
                </tr>
            </thead>
            <tbody>
                @if ($total_incidencia_ise != 0 || $total_iva_ise != 0)
                <tr>
                    <td style="text-align: left">ISENTO</td>
                    <td style="text-align: center">0</td>
                    <td style="text-align: center">{{ number_format($total_incidencia_ise, 2, ',', '.') }}</td>
                    <td style="text-align: center">{{ number_format($total_iva_ise, 2, ',', '.') }}</td>
                    <td style="text-align: left">Isento nos termos da alínea d) do nº1 do artigo 12.º do CIVA </td>
                </tr>
                @endif

                @if ($total_incidencia_out != 0 || $total_iva_out != 0)
                <tr>
                    <td style="text-align: left">IVA</td>
                    <td style="text-align: center">7</td>
                    <td style="text-align: center">{{ number_format($total_incidencia_out, 2, ',', '.') }}</td>
                    <td style="text-align: center">{{ number_format($total_iva_out, 2, ',', '.') }}</td>
                    <td style="text-align: left">Regime Simplificado</td>
                </tr>
                @endif

                @if ($total_incidencia_nor != 0 || $total_iva_nor != 0)
                <tr>
                    <td style="text-align: left">IVA</td>
                    <td style="text-align: center">14</td>
                    <td style="text-align: center">{{ number_format($total_incidencia_nor, 2, ',', '.') }}</td>
                    <td style="text-align: center">{{ number_format($total_iva_nor, 2, ',', '.') }}</td>
                    <td style="text-align: left">IVA - Regime Geral</td>
                </tr>
                @endif

            </tbody>
        </table>

        <!-- Pagamento -->
        <div style="margin-top: 20px;">
            <strong>Forma de Pagamento:</strong> {{ $pagamento->descricao_forma_pagamento($pagamento->tipo_pagamento) }} | <strong>Operador:</strong> {{ $funcionarioAtendente->nome ?? "" }}<br>
            <table width="95%" style="margin-top: 20px;border-top: 2px solid #c4c4c4;border-bottom: 2px solid #c4c4c4">
                <tr>
                    <td width="50%"> Coordenadas Bancarias </td>
                    <td width="50%"> Total Iliquido: <strong>{{ number_format(($pagamento->total_incidencia + $pagamento->total_iva), '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%">BANCO: <strong>{{ $escola->banco }}</strong></td>
                    <td width="50%"> Total Desconto: <strong>{{ number_format($pagamento->desconto, '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%">Nº CONTA: <strong>{{ $escola->conta }}</strong></td>
                    <td width="50%"> Total Imposto: <strong>{{ number_format(0, '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%">Nº IBAN: <strong>{{ $escola->iban }}</strong></td>
                    <td width="50%"> Retenção na fonte: <strong>{{ number_format($total_retencao, '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%"> Total: <strong>{{ number_format(($pagamento->total_incidencia + $pagamento->total_iva) , '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td width="50%"> Total a pagar: <strong>{{ number_format(($pagamento->total_incidencia + $pagamento->total_iva) - $total_retencao , '2', ',', '.') }}</strong></td>
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
        <div style="text-align: center;">
            <img src="{{ $logotipo }}" alt="Logotipo" style="height: 40px;"><br>
            <strong>{{ $escola->nome }}</strong><br>
            NIF: {{ $escola->documento }}
        </div>

        <hr>
        
        @if ($pagamento->anulado == "Y")
        <div class="watermark-a6">FACTURA ANULADA</div>
        @endif

        <div style="font-size: 11px;">
            <strong>Fatura para:</strong><br>
            Nome: {{ $estudante->nome }} {{ $estudante->sobre_nome }} | Matrícula: {{ $matricula->numero_estudante ?? '--- --- ---' }}<br>
            Classe: {{ $classe ->classes ?? ""}} | Curso: {{ $curso->curso ?? '--- --- ---' }} | Turno: {{ $turno->turno ?? '--- --- ---' }}<br>
            NIF: {{ $estudante->bilheite ?? '--- --- ---' }}<br>
            Telefone: {{ $estudante->telefone_estudante ?? '--- --- ---' }}
            <br>
                <strong style="float: right;">{{ $opcao ?? "" }}</strong>
            <br>
            <strong>
                {{ $pagamento->tipo_factura == 'FR' ? "Factura Recibo Nº:" : ($pagamento->tipo_factura == 'FT' ? "Factura Nº:" : ($pagamento->tipo_factura == 'FP' ? "Factura Pro-forma Nº:" : "Documento não indetificado")) }}
            </strong>
            {{ $pagamento->convertido_factura == "Y" ? $pagamento->next_factura . " conforme ". $pagamento->numeracao_proforma : $pagamento->next_factura }}
        </div>

        <table style="width: 100%; font-size: 11px; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #000;text-align: left">Descrição</th>
                    <th style="border: 1px solid #000;text-align: right">Qtd</th>
                    <th style="border: 1px solid #000;text-align: right">Preço</th>
                    <th style="border: 1px solid #000;text-align: right">Taxa</th>
                    <th style="border: 1px solid #000;text-align: right">Desc.</th>
                    <th style="border: 1px solid #000;text-align: right">Multa</th>
                    <th style="border: 1px solid #000;text-align: right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalhes as $key => $item)
                <tr>
                    <td style="padding: 2px;border: 1px solid #000;">{{ $item->servico->servico ?? "" }}({{ $item->mes }})</td>
                    <td style="padding: 2px;border: 1px solid #000;text-align: right">{{ number_format( $item->quantidade, 1, ',', '.') }}</td>
                    <td style="padding: 2px;border: 1px solid #000;text-align: right">{{ number_format($item->preco, 2, ',', '.')  }}</td>
                    <td style="padding: 2px;border: 1px solid #000;text-align: right">{{ number_format( $item->taxa_id, 1, ',', '.') }}</td>
                    <td style="padding: 2px;border: 1px solid #000;text-align: right">{{ number_format( $item->desconto, 1, ',', '.') }}</td>
                    <td style="padding: 2px;border: 1px solid #000;text-align: right">{{ number_format( $item->multa, 1, ',', '.') }}</td>
                    <td style="padding: 2px;border: 1px solid #000;text-align: right">{{ number_format($item->total_pagar, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table-taxas">
            <thead>
                <tr>
                    <th style="padding: 2px;text-align: left">Desc.</th>
                    <th style="padding: 2px;text-align: left">Taxa%</th>
                    <th style="padding: 2px;ext-align: left">Inc.</th>
                    <th style="padding: 2px;text-align: left">Valor</th>
                    <th style="padding: 2px;text-align: left">M.Isenção</th>
                </tr>
            </thead>
            <tbody>
                @if ($total_incidencia_ise != 0 || $total_iva_ise != 0)
                <tr>
                    <td style="padding: 2px;text-align: left">ISENTO</td>
                    <td style="padding: 2px;text-align: center">0</td>
                    <td style="padding: 2px;text-align: center">{{ number_format($total_incidencia_ise, 2, ',', '.') }}</td>
                    <td style="padding: 2px;text-align: center">{{ number_format($total_iva_ise, 2, ',', '.') }}</td>
                    <td style="padding: 2px;text-align: left">M10 </td>
                </tr>
                @endif

                @if ($total_incidencia_out != 0 || $total_iva_out != 0)
                <tr>
                    <td style="padding: 2px;text-align: left">IVA</td>
                    <td style="padding: 2px;text-align: center">7</td>
                    <td style="padding: 2px;text-align: center">{{ number_format($total_incidencia_out, 2, ',', '.') }}</td>
                    <td style="padding: 2px;text-align: center">{{ number_format($total_iva_out, 2, ',', '.') }}</td>
                    <td style="padding: 2px;text-align: left">Regime Simplificado</td>
                </tr>
                @endif

                @if ($total_incidencia_nor != 0 || $total_iva_nor != 0)
                <tr>
                    <td style="padding: 2px;text-align: left">IVA</td>
                    <td style="padding: 2px;text-align: center">14</td>
                    <td style="padding: 2px;text-align: center">{{ number_format($total_incidencia_nor, 2, ',', '.') }}</td>
                    <td style="padding: 2px;text-align: center">{{ number_format($total_iva_nor, 2, ',', '.') }}</td>
                    <td style="padding: 2px;text-align: left">IVA - Regime Geral</td>
                </tr>
                @endif

            </tbody>
        </table>

        <!-- Pagamento -->
        <div style="margin-top: 20px;">
            <strong>Forma de Pagamento:</strong> {{ $pagamento->descricao_forma_pagamento($pagamento->tipo_pagamento) }} <br>
            <strong>Operador:</strong> {{ $funcionarioAtendente->nome ?? "" }}<br>
            <table width="95%" style="margin-top: 20px;border-top: 2px solid #c4c4c4;border-bottom: 2px solid #c4c4c4">
                <tr>
                    <td width="50%"> Total Iliquido: <strong>{{ number_format(($pagamento->total_incidencia + $pagamento->total_iva), '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%"> Total Desconto: <strong>{{ number_format($pagamento->desconto, '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%"> Total Imposto: <strong>{{ number_format(0, '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%"> Retenção na fonte: <strong>{{ number_format($total_retencao, '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%"> Total: <strong>{{ number_format(($pagamento->total_incidencia + $pagamento->total_iva) , '2', ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td width="50%"> Total a pagar: <strong>{{ number_format(($pagamento->total_incidencia + $pagamento->total_iva) - $total_retencao , '2', ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <div style="text-align: center; margin-top: 10px; font-size: 10px;">

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
</body>
</html>
