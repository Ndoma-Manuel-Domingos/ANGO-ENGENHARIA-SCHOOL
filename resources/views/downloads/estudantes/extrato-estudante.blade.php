<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>
        @if ($matricula->tipo == "inscricao") FICHA DE INSCRIÇÃO @endif
        @if ($matricula->tipo == "matricula") FICHA DE MATRICULA @endif
        @if ($matricula->tipo == "candidatura") FICHA DE CANDIDATURA @endif
        @if ($matricula->tipo == "confirmacao") FICHA DE CONFIRMAÇÃO @endif
    </title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 2cm 2cm 2.5cm 2cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        .container {
            width: 100%;
            max-width: 18cm;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }

        .header img {
            height: 60px;
            margin-bottom: 5px;
        }

        .titulo {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .info-table td {
            padding: 6px;
            border: 1px solid #ccc;
            vertical-align: top;
        }

        .info-table .label {
            font-weight: bold;
            width: 30%;
            background-color: #f9f9f9;
        }

        .foto {
            width: 4cm;
            height: 5cm;
            border: 1px solid #000;
            object-fit: cover;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 11px;
            color: #777;
        }

    </style>
</head>
<body>

    <div class="container">

        <div class="header">
            <img src="{{ $logotipo }}" alt="Logotipo"><br>
            <div><strong>{{ $escola->nome }}</strong></div>
            <div>{{ $escola->endereco }} | NIF: {{ $escola->documento }}</div>
            <div class="titulo">
                @if ($condicao == "Meses_Pago") Extrato dos Meses Pago de {{ $servico->servico }} @endif
                @if ($condicao == "Meses_Nao_Pago") Extrato dos Meses Não Pago de {{ $servico->servico }} @endif
                @if ($condicao == "Meses_Devendo") Extrato dos Meses em Dívidas Pago de {{ $servico->servico }}  @endif
                @if ($condicao == "Meses_Bloqueado") Extrato Não Obrigatórios a pagar de {{ $servico->servico }} @endif
                @if ($condicao == "Meses_Obrigatorios") Extrato Obrigatórios a Pagar de {{ $servico->servico }} @endif
                @if ($condicao != "Meses_Pago" && $condicao != "Meses_Nao_Pago" && $condicao != "Meses_Devendo" && $condicao != "Meses_Bloqueado" && $condicao != "Meses_Obrigatorios") Extrato pagamento de {{ $servico->servico }} @endif
            </div>
        </div>

        <!-- Foto + Dados principais -->
        <table style="width: 100%; margin-top: 20px;">
            <tr>
                <td style="width: 80%;">
                    <table class="info-table">
                        <tr>
                            <td colspan="2" class="label">Dados Pessoais</td>
                        </tr>
                        <tr>
                            <td class="label">Nome Completo:</td>
                            <td>{{ $estudante->nome }} {{ $estudante->sobre_nome }}</td>
                        </tr>
                        <tr>
                            <td class="label">Número de Processo:</td>
                            <td>{{ $estudante->numero_processo }}</td>
                        </tr>
                        <tr>
                            <td class="label">Género | E. Cívil</td>
                            <td>{{ $estudante->genero }} | {{ $estudante->estado_civil }}</td>
                        </tr>
                        <tr>
                            <td class="label">D. Nascimento:</td>
                            <td>{{ \Carbon\Carbon::parse($estudante->nascimento)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Nacionalidade:</td>
                            <td>{{ $estudante->nacionalidade }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 20%;" align="center">
                    <img src="{{ public_path('assets/images/user.png') }}" class="foto" alt="Foto do estudante" style="text-align: center;height: 160px;width: 150px;">
                </td>
            </tr>
        </table>

        <!-- dados academicos -->
        <table class="info-table" style="margin-top: 20px;">
            <tr>
                <td colspan="2" class="label">Dados Academicos</td>
            </tr>
            <tr>
                <td class="label">Curso:</td>
                <td>{{ $curso->curso ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Turno:</td>
                <td>{{ $turno->turno ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Classe:</td>
                <td>{{ $classe->classes ?? '-' }}</td>
            </tr>
        </table>
            
        @if ($cartao)
            <table class="info-table">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>Meses</th>
                        <th colspan="2">Status</th>
                        <th>Situação</th>
                        <th>Preço Unitário</th>
                    </tr>
                </thead>
           
                @if ($calendario)
                <tbody>
                    @foreach ($cartao as $key => $item)               
                        <tr style="text-align: center;">
                            <td>000{{ $key + 1 }}</td>
                            <td>{{ $item->mes($item->month_name) }}</td>
                            @if ($item->status == "excepto")
                                <td colspan="2">Não Obrigatório</td>
                            @else
                                <td colspan="2">Obrigatório</td>
                            @endif
        
                            @if ($item->status == "excepto")
                                <td>====</td>
                            @else
                                <td>{{ $item->status }}</td>
                            @endif
                            <th>{{ number_format($calendario->preco ?? 0, 2, ',', '.') }}</th>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    
                    @if ($condicao == "Meses_Bloqueado")
                        <tr>
                            <td colspan="6">
                                Total Pagamento {{ number_format($mesesExcepto * $calendario->preco ?? 0, 2, ' ,', '.')  }} Kz 
                            </td>
                        </tr>
                    @endif
                       
                    @if ($condicao == "Meses_Obrigatorios")
                        <tr>
                            <td colspan="6">
                                <strong>Total Pagamento Anual: {{ number_format((
                                    (
                                    ($mesesPago * $calendario->preco ?? 0) + 
                                    ($mesesDividas * $calendario->preco ?? 0) + 
                                    ($mesesNaoPago * $calendario->preco ?? 0)
                                    ) - ($mesesExcepto * $calendario->preco ?? 0)), 2, ' ,', '.')  }} Kz
                                </strong> 
                            </td>
                        </tr>
                    @endif
                    
                    
                    @if ($condicao == "Meses_Devendo")
                        <tr>
                            <td colspan="6">
                                Total Pagamento {{ number_format($mesesDividas * $calendario->preco ?? 0, 2, ' ,', '.')  }} Kz 
                            </td>
                        </tr>
                    @endif
                    
                    @if ($condicao == "Meses_Nao_Pago")
                        <tr>
                            <td colspan="6">
                                Total Pagamento {{ number_format($mesesNaoPago * $calendario->preco ?? 0, 2, ' ,', '.')  }} Kz 
                            </td>
                        </tr>
                    @endif
                                
                    @if ($condicao == "Meses_Pago")
                        <tr>
                            <td colspan="6">
                                Total Pagamento {{ number_format($mesesPago * $calendario->preco ?? 0, 2, ' ,', '.')  }} Kz 
                            </td>
                        </tr>
                    @endif    
                                        
                    @if ($condicao != "Meses_Pago" && $condicao != "Meses_Nao_Pago" && $condicao != "Meses_Devendo" && $condicao != "Meses_Bloqueado" && $condicao != "Meses_Obrigatorios")
                        <tr>
                            <td colspan="6">
                                Meses Pagos {{ $mesesPago }} 
                            </td>
                        </tr>
            
                        <tr>
                            <td colspan="6">
                                Meses Não Pagos {{ $mesesNaoPago }} 
                            </td>
                        </tr> 
            
                        <tr>
                            <td colspan="6">
                                Meses Devendo {{ $mesesDividas }}
                            </td>
                        </tr> 
            
                        <tr>
                            <td colspan="6">
                                Valor Pago {{ number_format($mesesPago * $calendario->preco ?? 0, 2, ' ,', '.') }} Kz
                            </td>
                        </tr> 
            
                        <tr>
                            <td colspan="6">
                                Valor Não Pago 
                                    {{ number_format($mesesNaoPago * $calendario->preco ?? 0, 2, ' ,', '.') }} Kz
                            </td>
                        </tr> 
            
                        <tr>
                            <td colspan="6">
                                Valor Devendo  
                                @if ($mesesExcepto)
                                    {{ number_format((($mesesDividas * $calendario->preco ?? 0) - ($mesesExcepto * $calendario->preco ?? 0)), 2, ' ,', '.')  }} Kz
                                @else
                                    {{ number_format($mesesDividas * $calendario->preco ?? 0, 2, ' ,', '.')  }} Kz 
                                @endif
                            </td>
                        </tr>
            
                        <tr>
                            <td colspan="6">
                                <strong>Total Pagamento Anual: {{ number_format(((
                                    ($mesesPago * $calendario->preco ?? 0) + 
                                    ($mesesDividas * $calendario->preco ?? 0) + 
                                    ($mesesNaoPago * $calendario->preco ?? 0)
                                    ) - ($mesesExcepto * $calendario->preco ?? 0)), 2, ' ,', '.')  }} Kz
                                </strong>
                            </td>
                        </tr>      
                    @endif
          
                </tfoot>
                @endif
            </table>
        @endif

        <!-- Rodapé -->
        <div class="footer">
            Documento gerado automaticamente em {{ now()->format('d/m/Y H:i') }}<br>
            Angoengenharia e Sistemas Informáticos © {{ now()->year }}
        </div>
    </div>

</body>
</html>
