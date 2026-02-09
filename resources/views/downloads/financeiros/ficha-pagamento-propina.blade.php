<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de pagamento de {{ $pagamento->servico->servico }}</title>

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
    
            
    @if (Auth::user()->impressora == "Ticket")
    
        <header style="position: absolute;top: 5px;right: 5px;left: 5px;max-width: 250px;border: 1px solid #000;padding: 5px">
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
                        <strong>@php echo cabecalho($classe->classes, $escola->cabecalho1, $escola->cabecalho2);
                            @endphp</strong>
                    </td>
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
            
            <table>
                <tr>
                    <td>DADOS CLIENTES</td>
                </tr>
                
                <tr>
                    <td style="border-top: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px;">
                        <strong style="font-size: 9px">{{ $estudante->nome }} {{ $estudante->sobre_nome }}</strong>
                    </td>
                </tr>
                
                <tr>
                    <td style="border-left: #eaeaea 1px solid; padding: 2px">
                        <strong>B.I:</strong> {{ $estudante->bilheite ?? '--- --- ---' }}
                    </td>
                </tr>
                <tr>
                    <td style="border-left: #eaeaea 1px solid; padding: 2px">
                        <strong>Nº MATRICULA: </strong> {{ $matricula->numero_estudante ?? '--- --- ---' }}
                        <strong>| CLASSE: </strong> {{ $classe ->classes ?? ""}}
                    </td>
                </tr>
                <tr>
                    <td style="border-left: #eaeaea 1px solid; padding: 2px">
                        <strong>CURSO: </strong> {{ $curso->curso ?? '--- --- ---' }} | <strong>TURNO: </strong> {{ $turno->turno ?? '--- --- ---' }}
                    </td>
                </tr>
            </table>
        </header>
        
        <main style="position: absolute;top: 250px;right: 5px;left: 5px;max-width: 250px;border: 1px solid #000;padding: 5px">
            <table style="margin-top: 10px">
                <tr>
                    @if ($pagamento->tipo_factura == 'FP')
                    <td style="font-size: 9px;padding: 1px 0"><strong>FACTURA PRÓ-FORMA</strong></td>
                    @endif
        
                    @if ($pagamento->tipo_factura == 'FT')
                    <td style="font-size: 9px;padding: 1px 0"><strong>FACTURA</strong></td>
                    @endif
        
                    @if ($pagamento->tipo_factura == 'FR')
                    <td style="font-size: 9px;padding: 1px 0"><strong>FACTURA RECIBO</strong></td>
                    <td style="font-size: 9px;padding: 1px 0;text-align: right">FORMA PAGAMENTO: {{ $pagamento->forma_pagamento->descricao }}</td>
                    @endif
                </tr>
            </table>
        
            <table>
                <tr>
                    @if ($pagamento->convertido_factura == "Y")
                    <td style="font-size: 9px"><strong>{{ $pagamento->next_factura }} conforme {{
                            $pagamento->numeracao_proforma }}</strong></td>
                    @else
                    <td style="font-size: 9px"><strong>{{ $pagamento->next_factura }}</strong></td>
                    @endif
                </tr>
                <tr>
                    <td style="font-size: 9px;padding: 1px 0"><strong>REF: {{ $pagamento->ficha }}</strong> Moeda: AOA </td>
                    <td style="text-align: right">OPERADOR: {{ $funcionarioAtendente->nome ?? "" }} <br>
                        _______________________</td>
                </tr>
            </table>
            
            <table style="width: 100%" class="table table-stripeds"
                style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
                <thead style="border-bottom: 1px dashed #000;x">
                    <tr>
                        <th style="padding: 2px 0">N.º</th>
                        <th style="text-align: center">Desc.</th>
                        <th style="text-align: center">P.Unit.</th>
                        <th style="text-align: center">Qtd</th>
                        <th style="text-align: center">Desc. %</th>
                        <th style="text-align: center">Taxa%</th>
                        <th style="text-align: center">Multa</th>
                        <th style="text-align: right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalhes as $key => $item)
                    <tr>
                        <td style="padding: 2px 0">{{ $key + 1 }}</td>
                        <td style="text-align: center">{{ $item->servico->servico ?? "" }}({{ $item->mes }})|</td>
                        <td style="text-align: center">{{ number_format($item->preco, 2, ',', '.') }}|</td>
                        <td style="text-align: center">{{ number_format( $item->quantidade, 1, ',', '.') }}|</td>
                        {{-- <td>un</td> --}}
                        <td style="text-align: center">{{ number_format( $item->desconto, 1, ',', '.') }}|</td>
                        <td style="text-align: center">{{ number_format( $item->taxa_id, 1, ',', '.') }}|</td>
                        <td style="text-align: center">{{ number_format( ($item->multa), 2, ',', '.') }}|</td>
                        <td style="text-align: right">{{ number_format($item->total_pagar, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <table style="margin-top: 10px ">
                <thead>
                    <tr>
                        <th style="padding: 4px 4px;border-bottom: 1px solid #eaeaea">Descrição</th>
                        <th style="padding: 4px 4px;border-bottom: 1px solid #eaeaea">Taxa%</th>
                        <th style="padding: 4px 4px;border-bottom: 1px solid #eaeaea">Incidência</th>
                        <th style="padding: 4px 4px;border-bottom: 1px solid #eaeaea">Valor Imposto</th>
                        <th style="padding: 4px 4px;border-bottom: 1px solid #eaeaea">Motivo de Isenção</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($total_incidencia_ise != 0 || $total_iva_ise != 0)
                        <tr>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">ISENTO</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">0</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_incidencia_ise, 2, ',', '.') }}</td> 
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_iva_ise, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">Isento nos termos da alínea d) do nº1 do artigo 12.º do CIVA </td>
                        </tr>  
                    @endif
    
                    @if ($total_incidencia_out != 0 || $total_iva_out != 0)
                        <tr>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">IVA</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">7</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_incidencia_out, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_iva_out, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">Regime Simplificado</td>
                        </tr>  
                    @endif
    
                    @if ($total_incidencia_nor != 0 || $total_iva_nor != 0)
                        <tr>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">IVA</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">14</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_incidencia_nor, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_iva_nor, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">IVA - Regime Geral</td>
                        </tr>  
                    @endif
                
                </tbody>
            </table>
            
            @if ($pagamento->status == "Pendente")
                <table style="margin-top: 10px">
                    <tbody>
                        <tr>
                            <th style="padding: 4px 0"><p style="font-size: 12px;color: red;text-transform: uppercase"><em>ESTADO DO PAGAMENTO: {{ $pagamento->status }}</em></p></th>
                        </tr>
                    </tbody>
                </table>
            @endif
            
            <table style="margin-top: 10px">
                <tbody>
                    <tr>
                        <td style="text-align: right;padding: 1px 0;"><strong>Total:</strong></td>
                        <td style="text-align: right;padding: 1px 0;"><span>{{ number_format(($pagamento->valor2 + $pagamento->desconto), '2', ',', '.') }}</span></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;padding: 1px 0;"><strong>Total Desconto:</strong></td>
                        <td style="text-align: right;padding: 1px 0;">{{ number_format($pagamento->desconto, '2', ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;padding: 1px 0;"><strong>Total Multa:</strong></td>
                        <td style="text-align: right;padding: 1px 0;">{{ number_format($pagamento->multa, '2', ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;padding: 1px 0;"><strong>Total A Pago:</strong></td>
                        <td style="text-align: right;padding: 1px 0;">{{ number_format(($pagamento->valor2), '2', ',', '.') }}</td>
                    </tr>
        
                    @if ($pagamento->tipo_pagamento == "OU")
                        <tr>
                            <td style="text-align: right;padding: 1px 0;"><strong>Valor Entregue Multicaixa:</strong></td>
                            <td style="text-align: right;padding: 1px 0;">{{ number_format($pagamento->valor_multicaixa, '2', ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: right;padding: 1px 0;"><strong>Valor Entregue Numerário:</strong></td>
                            <td style="text-align: right;padding: 1px 0;">{{ number_format($pagamento->valor_cash, '2', ',', '.') }}</td>
                        </tr>
                    @endif
                    
                    @if ($pagamento->tipo_pagamento == "NU")
                        <tr>
                            <td style="text-align: right;padding: 1px 0;"><strong>Valor Entregue Numerário:</strong></td>
                            <td style="text-align: right;padding: 1px 0;">{{ number_format($pagamento->valor_cash, '2', ',', '.') }}</td>
                        </tr>
                    @endif
                    
                    @if ($pagamento->tipo_pagamento == "MB")
                        <tr>
                            <td style="text-align: right;padding: 1px 0;"><strong>Valor Entregue Multicaixa:</strong></td>
                            <td style="text-align: right;padding: 1px 0;">{{ number_format($pagamento->valor_multicaixa, '2', ',', '.') }}</td>
                        </tr>
                    @endif
                    
                    <tr>
                        <td style="text-align: right;padding: 1px 0;"><strong>Troco/Saldo:</strong></td>
                        <td style="text-align: right;padding: 1px 0;">{{ number_format($pagamento->troco, '2', ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
            
            <table style="margin-top: 10px">
                <tbody>
                    <tr>
                        <td style="padding: 1px 0;font-size: 9px">Os bens serviços foram colocados à disposição do adquirente na data do
                            documento</td>
                    </tr>
        
                    <tr>
                        <td style="padding: 1px 0;">{{ $pagamento->obterCaracteres($pagamento->hash) }}</td>
                    </tr>
                    
                    @if ($escola->tipo_regime_id == "regime_exclusao")
                    <tr>
                        <td style="padding: 1px 0; text-align: center" colspan="2"><strong>IVA - REGIME DE EXCLUSÃO</strong></td>
                    </tr>
                    @endif
                    
                    @if ($escola->tipo_regime_id == "regime_geral")
                    <tr>
                        <td style="padding: 1px 0; text-align: center" colspan="2"><strong>VA - REGIME GERAL</strong>I</td>
                    </tr>
                    @endif
                    
                    @if ($escola->tipo_regime_id == "regime_simplificado")
                    <tr>
                        <td style="padding: 1px 0; text-align: center" colspan="2"><strong>IVA - REGIME SIMPLIFICADO</strong></td>
                    </tr>
                    @endif
        
                    <tr>
                        <td style="padding: 1px 0; text-align: center">Software de gestão escolar, desenvolvido pela {{ env('APP_NAME') }}</td>
                    </tr>
                </tbody>
            </table>
        </main>
    
    @endif
    
        
    @if (Auth::user()->impressora == "Normal")
    
        <header style="position: absolute;top: 0;right: 30px;left: 30px;">
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
    
        <main style="position: absolute;top: 230px;right: 30px;left: 30px;">
        
            <table>
                <tr>
                    <td style="font-size: 13px"><strong>Luanda-Angola</strong></td>
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
                    <td style="font-size: 13px"><strong>{{ $pagamento->next_factura }} conforme {{ $pagamento->numeracao_proforma }}</strong></td>
                    @else
                    <td style="font-size: 13px"><strong>{{ $pagamento->next_factura }}</strong></td>
                    @endif
                </tr>
                <tr>
                    <td style="font-size: 9px;padding: 1px 0"><strong>REF: {{ $pagamento->ficha }}</strong> Moeda: AOA </td>
                    <td style="text-align: right">OPERADOR: {{ $pagamento->operador->nome }} <br>
                        _______________________</td>
                </tr>
            </table>
            
            @php $numero = 0; @endphp
            
            <table style="width: 100%" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
                <thead style="border-bottom: 1px dashed #000;x">
                    <tr>
                        <th style="padding: 2px 0">N.º</th>
                        <th>Descrição</th>
                        <th>Valor Unitário</th>
                        <th>Qtd</th>
                        <th>Multa</th>
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
                        <td>{{ $item->servico->servico ?? "" }}({{ $item->descricao_mes($item->mes) }})</td>
                        <td>{{ number_format($item->total_pagar, 2, ',', '.')  }} Kz</td>
                        <td>{{ number_format( $item->quantidade, 1, ',', '.') }}</td>
                        <td>{{ number_format( $item->multa, 1, ',', '.') }}</td>
                        <td>un</td>
                        <td>{{ number_format( $item->desconto, 1, ',', '.') }}</td>
                        <td>{{ number_format( 0, 1, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format( ($item->total_pagar * $item->quantidade) + $item->multa, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    
            <table style="margin-top: 50px ">
                <thead>
                    <tr>
                        <th style="padding: 4px 0">Descrição</th>
                        <th style="padding: 4px 0">Taxa%</th>
                        <th>Incidência</th>
                        <th>Valor Imposto</th>
                        <th>Motivo de Isenção</th>
                    </tr>
                </thead>
                <tbody>
                
                    @if ($total_incidencia_ise != 0 || $total_iva_ise != 0)
                        <tr>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">ISENTO</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">0</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_incidencia_ise, 2, ',', '.') }}</td> 
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_iva_ise, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">Isento nos termos da alínea d) do nº1 do artigo 12.º do CIVA </td>
                        </tr>  
                    @endif
    
                    @if ($total_incidencia_out != 0 || $total_iva_out != 0)
                        <tr>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">IVA</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">7</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_incidencia_out, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_iva_out, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">Regime Simplificado</td>
                        </tr>  
                    @endif
    
                    @if ($total_incidencia_nor != 0 || $total_iva_nor != 0)
                        <tr>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">IVA</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">14</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_incidencia_nor, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">{{ number_format($total_iva_nor, 2, ',', '.') }}</td>
                            <td style="padding: 2px 0;border-top: 1px dashed #000;">IVA - Regime Geral</td>
                        </tr>  
                    @endif
                
                </tbody>
            </table>
            
                             
            @if ($pagamento->status == "Pendente")
                <table style="margin-top: 50px ">
                    <tbody>
                        <tr>
                            <th style="padding: 4px 0"><p style="font-size: 20px;color: red;text-transform: uppercase"><em>ESTADO DO PAGAMENTO: {{ $pagamento->status }}</em></p></th>
                        </tr>
                    </tbody>
                </table>
            @endif
    
        </main>
    
        <footer style="position: absolute;bottom: 0;right: 30px;left: 30px;">
            <table style="">
                <tbody>
                    <tr>
                        <td>COORDENADAS BANCARIAS</td>
                        <td style="text-align: right;padding: 1px 0;"><strong>Total:</strong> {{ number_format($pagamento->valor2 + $pagamento->desconto, '2', ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>BANCO:</strong> {{ $escola->banco }}</td>
                        <td style="text-align: right;padding: 1px 0;"><strong>Total Desconto:</strong> {{ number_format($pagamento->desconto, '2', ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nº CONTA:</strong> {{ $escola->conta }}</td>
                        <td style="text-align: right;padding: 1px 0;"><strong>Total Imposto:</strong> {{ number_format(0, '2', ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nº IBAN:</strong> {{ $escola->iban }}</td>
                        <td style="text-align: right;padding: 1px 0;"><strong>Multa:</strong> {{ number_format($pagamento->multa , '2', ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Observação:</td>
                        <td style="text-align: right;padding: 1px 0;"><strong>Total a Pago:</strong> {{ number_format(($pagamento->valor2) , '2', ',', '.') }}</td>
                    </tr>
                    @if ($pagamento->convertido_factura == "Y")
                    <tr>
                        <td></td>
                        <td style="text-align: right;padding: 1px 0;"><strong>Total Entregue:</strong> {{ number_format($pagamento->factura_recibo->valor_entregue , '2', ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: right;padding: 1px 0;"><strong>Troco/Saldo:</strong> {{ number_format($pagamento->factura_recibo->troco, '2', ',', '.') }}</td>
                    </tr>
                    @endif
    
                    <tr>
                        <td style="padding: 1px 0;">Os bens serviços foram colocados à disposição do adquirente na data do documento</td>
                        <td></td>
                    </tr>
    
                    <tr>
                        <td style="padding: 1px 0;">{{ $pagamento->obterCaracteres($pagamento->hash) }}</td>
                        <td></td>
                    </tr>
                    
                    @if ($escola->tipo_regime_id == "regime_exclusao")
                    <tr>
                        <td style="padding: 1px 0; text-align: center" colspan="2"><strong>IVA - REGIME DE EXCLUSÃO</strong></td>
                    </tr>
                    @endif
                    
                    @if ($escola->tipo_regime_id == "regime_geral")
                    <tr>
                        <td style="padding: 1px 0; text-align: center" colspan="2"><strong>IVA - REGIME GERAL</strong></td>
                    </tr>
                    @endif
                    
                    @if ($escola->tipo_regime_id == "regime_simplificado")
                    <tr>
                        <td style="padding: 1px 0; text-align: center" colspan="2"><strong>IVA - REGIME SIMPLIFICADO</strong></td>
                    </tr>
                    @endif
    
                    <tr>
                        <td style="padding: 1px 0; text-align: center" colspan="2">Software de gestão escolar, desenvolvido pela {{ env('APP_NAME') }}</td>
                    </tr>
    
                </tbody>
            </table>
        </footer>
        
    @endif
    

</body>
</html>
