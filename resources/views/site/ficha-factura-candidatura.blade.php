<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title style="display: block;margin-top: 30px">FICHA DE CANDIDATURA</title>

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
            padding: 0px;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1 {
            font-size: 15px;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 12x;
        }

        p {
            /* margin-bottom: 20px; */
            line-height: 25px;
            font-size: 12px;
            text-align: justify;
        }

        strong {
            font-size: 12px;
        }

        table {
            width: 100%;
            text-align: left;
            border-spacing: 0;
            margin-bottom: 10px;
            /* border: 1px solid rgb(0, 0, 0); */
            font-size: 12px;
        }

        thead {
            background-color: #fdfdfd;
            font-size: 12px;
        }

        th,
        td {
            padding: 6px;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        strong {
            font-size: 12px;
        }

        /* Estilo para a impressão */
        @media print {
            @page {
                margin: 10px;
                /* Remove todas as margens da página */
                background-color: #000;
            }

            .pagina {
                width: 95%;
                height: 1000px;
                page-break-after: always;
                /* Força quebra de página após cada seção */
            }

            body {
                margin: 0;
                padding: 0;
            }
        }

        /* Estilo para visualização na tela */
        @media screen {
            .pagina {
                width: 95%;
                height: 1000px;
                padding: 20px;
            }
        }

    </style>
</head>
<body>


    <div class="pagina">

        <header style="position: absolute;top: 30px;right: 30px;left: 30px;">
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
                        <strong>{{ $escola->ome }}</strong>
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
                        <strong>CURSO: </strong> {{ $curso->curso ?? '--- --- ---' }}
                    </td>
                </tr>

                <tr>
                    <td>

                    </td>
                    <td style="border-left: #eaeaea 1px solid; padding: 2px">
                        <strong>CLASSE: </strong> {{ $classe->classes ?? '--- --- ---' }}
                    </td>
                </tr>

                <tr>
                    <td>

                    </td>
                    <td style="border-left: #eaeaea 1px solid; padding: 2px">
                        <strong>TURNO: </strong> {{ $turno->turno ?? '--- --- ---' }}
                    </td>
                </tr>
                <tr>
                    <td>

                    </td>
                    <td style="border-bottom: #eaeaea 1px solid;border-right: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px">
                        <strong>TEL:</strong> {{ $estudante->telefone_estudante ?? '--- --- ---' }}
                    </td>
                </tr>

            </table>
        </header>

        <main style="position: absolute;top: 270px;right: 30px;left: 30px;">
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
                    <td style="font-size: 9px;padding: 1px 0;text-align: right">FORMA PAGAMENTO: {{ $pagamento->forma_pagamento->descricao }}</td>
                    @endif
                </tr>
            </table>

            <table>
                <tr>
                    @if ($pagamento->convertido_factura == "Y")
                    <td style="font-size: 13px"><strong>{{ $pagamento->next_factura }} conforme {{
                            $pagamento->numeracao_proforma }}</strong></td>
                    @else
                    <td style="font-size: 13px"><strong>{{ $pagamento->next_factura }}</strong></td>
                    @endif
                </tr>
                <tr>
                    <td style="font-size: 9px;padding: 1px 0"><strong>REF: {{ $pagamento->ficha }}</strong> Moeda: AOA </td>
                    <td style="text-align: right">OPERADOR: Electronico <br> _______________________</td>
                </tr>
            </table>

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
                        <th>Multa</th>
                        <th style="text-align: right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalhes as $key => $item)
                    <tr>
                        <td style="padding: 2px 0">{{ $key + 1 }}</td>
                        <td>{{ $item->servico->servico ?? "" }}({{ $item->descricao_mes($item->mes) }})</td>
                        <td>{{ number_format($item->preco, 2, ',', '.') }} Kz</td>
                        <td>{{ number_format( $item->quantidade, 1, ',', '.') }}</td>
                        <td>un</td>
                        <td>{{ number_format( $item->desconto, 1, ',', '.') }}</td>
                        <td>{{ number_format( $item->taxa_id, 1, ',', '.') }}</td>
                        <td>{{ number_format( ($item->multa), 2, ',', '.') }} KZ</td>
                        <td style="text-align: right">{{ number_format($item->total_pagar, 2, ',', '.') }} KZ</td>
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
                        <th style="padding: 4px 0">
                            <p style="font-size: 20px;color: red;text-transform: uppercase"><em>ESTADO DO PAGAMENTO: {{ $pagamento->status }}</em></p>
                        </th>
                    </tr>
                </tbody>
            </table>
            @endif
        </main>

        <footer style="position: absolute;bottom: 30px;right: 30px;left: 30px;border-top: 5px solid #005000">
            <table style="margin-top: 10px">
                <tbody>
                    <tr>
                        <td>COORDENADAS BANCARIAS</td>
                        <td style="text-align: right;padding: 5px 0;"><strong>Total:</strong> {{
                            number_format(($pagamento->valor2 + $pagamento->desconto), '2', ',', '.')
                            }}</td>
                    </tr>
                    <tr>
                        <td><strong>BANCO:</strong> {{ $escola->banco }}</td>
                        <td style="text-align: right;padding: 5px 0;"><strong>Total Desconto:</strong> {{
                            number_format($pagamento->desconto, '2', ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nº CONTA:</strong> {{ $escola->conta }}</td>
                        <td style="text-align: right;padding: 5px 0;"><strong>Total Imposto:</strong> {{ number_format(0,
                            '2', ',', '.') }}</td>
                    </tr>

                    <tr>
                        <td>Observação:</td>
                        <td style="text-align: right;padding: 5px 0;"><strong>Total A Pagar:</strong> {{
                            number_format(($pagamento->total_incidencia + $pagamento->total_iva + $pagamento->multa ?? 0), '2', ',', '.') }}</td>
                    </tr>

                    <tr>
                        <td style="padding: 5px 0;">Os bens serviços foram colocados à disposição do adquirente na data do
                            documento</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td style="padding: 5px 0;">{{ $pagamento->obterCaracteres($pagamento->hash) }}</td>
                        <td></td>
                    </tr>


                    @if ($escola->tipo_regime_id == "regime_exclusao")
                    <tr>
                        <td style="padding: 5px 0; text-align: center" colspan="2"><strong>IVA - REGIME DE EXCLUSÃO</strong></td>
                    </tr>
                    @endif

                    @if ($escola->tipo_regime_id == "regime_geral")
                    <tr>
                        <td style="padding: 5px 0; text-align: center" colspan="2"><strong>IVA - REGIME GERAL</strong></td>
                    </tr>
                    @endif

                    @if ($escola->tipo_regime_id == "regime_simplificado")
                    <tr>
                        <td style="padding: 5px 0; text-align: center" colspan="2"><strong>IVA - REGIME SIMPLIFICADO</strong></td>
                    </tr>
                    @endif

                    <tr>
                        <td style="padding: 5px 0; text-align: center" colspan="2">Software de gestão escolar, desenvolvido pela {{ env('APP_NAME') }}</td>
                    </tr>

                </tbody>
            </table>
        </footer>
    </div>


    <div class="pagina">
        <header style="width: 100%">
            <table style="border: 1px solid #000">
                <tr>
                    <td>
                        <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 70px;width: 70px;">
                    </td>
                    <td style="text-align: left">
                        <strong>{{ $escola->nome }} </strong><br>
                        <span style="display: block;margin-top: 30px">FICHA DE CANDIDATURA</span>
                    </td>

                    <td style="text-align: right">
                        <span style="display: block">Data: {{ date("Y-m-d") }} </span><br>
                        <span style="display: block">Hora: {{ date("H:i:s") }} </span><br>
                        <span style="display: block">Pág: 1/1 </span>
                    </td>
                </tr>
            </table>
        </header>

        <main style="width: 100%">
            <table style="border: 1px solid #000">
                <tbody>
                    <tr>
                        <td style="font-size: 13px;padding: 10px; border-top: 1px dashed #000;border-bottom: 1px dashed #000;">DADOS PESSOAIS</td>
                        <td style="font-size: 13px;padding: 10px; border-top: 1px dashed #000;border-bottom: 1px dashed #000;"></td>
                        <td style="font-size: 13px;padding: 10px; border-top: 1px dashed #000;border-bottom: 1px dashed #000;"></td>
                        <td style="font-size: 13px;padding: 10px; border-top: 1px dashed #000;border-bottom: 1px dashed #000;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;padding: 20px">
                            <img src="{{ public_path("assets/images/user.png") }}" alt="" style="text-align: center;height: 70px;width: 70px;">
                        </td>
                        <td colspan="3">
                            <strong style="margin-bottom: 5px;display: inline-block">MATRICULA: {{ $matricula->ficha }}</strong> <br>
                            <strong style="margin-bottom: 5px;display: inline-block">ALUNO(a): {{ $matricula->estudante->nome }} {{ $matricula->estudante->sobre_nome }}</strong> <br>
                            <strong style="margin-bottom: 5px;display: inline-block">FILHAÇÃO: {{ $matricula->estudante->pai }} e {{ $matricula->estudante->mae }}</strong> <br>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000">GENERO: <strong>{{ $matricula->estudante->genero }}</strong></td>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000">B.I/CÉDULA: <strong>{{ $matricula->estudante->bilheite }}</strong></td>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000">ESTADO CIVIL: <strong>{{ $matricula->estudante->estado_civil }}</strong></td>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000">NASCIMENTO: <strong>{{ $matricula->estudante->nascimento }}</strong></td>
                    </tr>

                    <tr>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000">TELEFONE: <strong>{{ $matricula->estudante->telefone_estudante ?? '--- --- ---' }}</strong></td>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000">TELEFONE PAI: <strong>{{ $matricula->estudante->telefone_pai ?? '--- --- ---' }}</strong></td>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000">TELEFONE MÃE: <strong>{{ $matricula->estudante->telefone_mae ?? '--- --- ---' }}</strong></td>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000"></td>
                    </tr>
                </tbody>
            </table>

            <table style="border: 1px solid #000">
                <tbody>
                    <tr>
                        <td style="font-size: 13px;padding: 10px; border-top: 1px dashed #000;border-bottom: 1px dashed #000;">DADOS ACADEMICOS</td>
                        <td style="font-size: 13px;padding: 10px; border-top: 1px dashed #000;border-bottom: 1px dashed #000;"></td>
                        <td style="font-size: 13px;padding: 10px; border-top: 1px dashed #000;border-bottom: 1px dashed #000;"></td>
                        <td style="font-size: 13px;padding: 10px; border-top: 1px dashed #000;border-bottom: 1px dashed #000;"></td>
                    </tr>

                    <tr>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000"><strong style="font-size: 9px;padding: 5px">CLASSE: {{ $matricula->classe->classes }}</strong></td>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000"><strong style="font-size: 9px;padding: 5px">CLASSE ANTEIRIOR: {{ $matricula->classe_at->classes }}</strong></td>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000" colspan="2"><strong style="font-size: 9px;padding: 5px">TURNO: {{ $matricula->turno->turno }}</strong></td>
                    </tr>
                    <tr>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000" colspan="2"><strong style="font-size: 9px;padding: 5px">CURSO: {{ $matricula->curso->curso }}</strong></td>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000" colspan="2"><strong style="font-size: 9px;padding: 5px">ÁREA FORMAÇÃO: {{ $matricula->curso->area_formacao }}</strong> </td>
                    </tr>
                    <tr>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000" colspan="2"><strong style="font-size: 9px;padding: 5px">ANO LECTIVO: {{ $matricula->ano_lectivo->ano }}</strong> </td>
                        <td style="font-size: 9px;padding: 5px;border: 1px solid #000" colspan="2"><strong style="font-size: 9px;padding: 5px"><span style="color: red">REF MAT:</span> {{ $matricula->ficha }}</strong> </td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>

</body>
</html>
