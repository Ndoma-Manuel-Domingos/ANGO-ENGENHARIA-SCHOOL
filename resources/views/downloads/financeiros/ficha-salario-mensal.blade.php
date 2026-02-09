<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Ficha de Matricula & Confirmação </title>
	<style type="text/css">
		*{
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		body{
			padding: 30px;
            font-family: Arial, Helvetica, sans-serif;
		}
		h1{
			font-size: 12pt;
			margin-bottom: 4px;
		}

		.titulo{
			font-size: 12pt;
			text-align: center;
            margin-top: 0;
		}

		p{
			/* margin-bottom: 20px; */
			line-height: 20px;
            font-size: 12pt;
		}

		table{
			width: 100%;
			text-align: left;
			border-spacing: 0;	
			margin-bottom: 10px;
			border: 1px solid #fff;
            font-size: 12pt;
            /* background-color: #ddd; */
		}
		thead{
			background-color: #eaeaea;
			border-bottom: 1px solid #006699;
            font-size: 10pt;

		}
		th, td{
			padding: 12px;
			border-top: 1px solid #000;
            font-size: 9pt;
			text-align: left;
		}

		.border{
			border: 1px solid #eaeaea;
		}

		.flex{
			display: flex;
			flex-direction: row;
			justify-content: space-between;
			align-content: center;
			align-items: center;
		}

		.col{
			width: 25%;
			padding-left: 2px;
			padding-right: 2px;
		}

		.cols{
			width: 50%;
			padding-left: 2px;
			padding-right: 2px;
		}

		.col-2{
			width: 10%;
			padding-left: 2px;
			padding-right: 2px;
		}

		.col-8{
			width: 80%;
			padding-left: 2px;
			padding-right: 2px;
		}

		.logo{
			height: 70px;
			width: 70px;
			/*border-radius: 300px;*/
			/* padding: 30px;  */
            border: 1px solid #000;
		}
		.ml{
			margin-left: 80px;
		}
		.text-center{
			text-align: center;
		}
		</style>
</head>
<body>


	<div style="background-color: rgb(255, 255, 255);color: #000000;padding: 5px;font-family: Arial, Helvetica, sans-serif;">
		<img src="{{ $logotipo }}" alt="" style="text-align: center;height: 100px;width: 100px;float: right;">
        <h1>{{ $escola->nome }}</h1>
		<p>{{ $escola->natureza }}</p> 
		<br>
		<p>NIF: {{ $escola->documento }}</p> 
		<p>Angola - {{ $escola->provincia }}</p> 
		<br>
	</div>
	<br>

    <div style="width: 100%;float: left;text-align: center;">
        <h4>Mapa Salárial Mensal Referente o mês === {{ $mes->meses }}</h4>
    </div>
    <br>

    @if ($detalhes)
        <table>
            <thead>
                <tr>
                    <th rowspan="2">Cod</th>
                    <th rowspan="2">Funcionário</th>
                    <th rowspan="2">Categria ou Cargo</th>
                    <th rowspan="2">Salário Base</th>
                    <th colspan="6" style="text-align: center;">Subcídios</th>
                    <th rowspan="2">Salário Iliquido</th>
                    <th colspan="3" style="text-align: center;">Desconto</th>
                    <th rowspan="2">Total Desconto</th>
                    <th rowspan="2">Salário Líquido</th>
                </tr>

                <tr>
            
                    <th>Transporte</th>
                    <th>Alimentação</th>
                    <th>Natal</th>
                    <th>Ferias</th>
                    <th>Abono Familia</th>
                    <th>Outros Subcídios</th>

                    <th>INSS</th>
                    <th>IRT</th>
                    <th>Faltas</th>

                </tr>
            </thead>

            <tbody>
                @foreach ($detalhes as $item)
                @php
                    $pagamentos = (new App\Models\web\calendarios\Pagamento())::where([
                        ['ficha', '=', $item->code],
                        ['model', '=', 'funcionario'],
                    ])->first();
                    
                    // --------------------------- cada funcionario START
                        $somaIrtGeral = (new App\Models\web\calendarios\Pagamento())::where([
                            ['ficha', '=', $item->code], ['model', '=', 'funcionario'],
                        ])->sum('irt');

                        $somaInssGeral = (new App\Models\web\calendarios\Pagamento())::where([
                            ['ficha', '=', $item->code], ['model', '=', 'funcionario'],
                        ])->sum('inss');

                        $somaValorGeral = (new App\Models\web\calendarios\Pagamento())::where([
                            ['ficha', '=', $item->code], ['model', '=', 'funcionario'],
                        ])->sum('valor');

                        $somaDescontoGeral = (new App\Models\web\calendarios\Pagamento())::where([
                            ['ficha', '=', $item->code], ['model', '=', 'funcionario'],
                        ])->sum('desconto');

                        $somaSubcidioGeral = (new App\Models\web\calendarios\Pagamento())::where([
                            ['ficha', '=', $item->code], ['model', '=', 'funcionario'],
                        ])->sum('subcidio');

                        $somaSubcidioTransporteGeral = (new App\Models\web\calendarios\Pagamento())::where([
                            ['ficha', '=', $item->code], ['model', '=', 'funcionario'],
                        ])->sum('subcidio_transporte');

                        $somaSubcidioNatalGeral = (new App\Models\web\calendarios\Pagamento())::where([
                            ['ficha', '=', $item->code], ['model', '=', 'funcionario'],
                        ])->sum('subcidio_natal');

                        $somaSubcidioAbonoFamiliaGeral = (new App\Models\web\calendarios\Pagamento())::where([
                            ['ficha', '=', $item->code], ['model', '=', 'funcionario'],
                        ])->sum('subcidio_abono_familiar');

                        $somaSubcidioFeriasGeral = (new App\Models\web\calendarios\Pagamento())::where([
                            ['ficha', '=', $item->code], ['model', '=', 'funcionario'],
                        ])->sum('subcidio_ferias');

                        $somaSubcidioAlimentacaoGeral = (new App\Models\web\calendarios\Pagamento())::where([
                            ['ficha', '=', $item->code], ['model', '=', 'funcionario'],
                        ])->sum('subcidio_alimentacao');

                        $somaFaltasGeral = (new App\Models\web\calendarios\Pagamento())::where([
                            ['ficha', '=', $item->code], ['model', '=', 'funcionario'],
                        ])->sum('faltas');

                    // --------------------------- cada funcionario END

                    $contrato = (new App\Models\web\funcionarios\FuncionariosControto())::where([
                        ['funcionarios_id', '=', $pagamentos->estudantes_id]
                    ])
                    ->join('tb_professores', 'tb_contratos.funcionarios_id', '=', 'tb_professores.id')
                    ->first();

                @endphp
                    <tr>
                        <td>{{ $contrato->documento }}</td>
                        <td>{{ $contrato->nome }} {{ $contrato->sobre_nome }}</td>
                        <td>{{ $contrato->cargo }}</td>
                        <td>{{ number_format(($pagamentos->valor - ($pagamentos->subcidio_transporte + $pagamentos->subcidio_alimentacao + $pagamentos->subcidio)) , 2, ',', '.') }}  kz</td>

                        <td>{{ number_format($pagamentos->subcidio_transporte , 2, ',', '.')  }} kz</td>
                        <td>{{ number_format($pagamentos->subcidio_alimentacao , 2, ',', '.') }} kz</td>
                        <td>{{ number_format($pagamentos->subcidio_natal , 2, ',', '.') }} kz</td>
                        <td>{{ number_format($pagamentos->subcidio_ferias , 2, ',', '.') }} kz</td>
                        <td>{{ number_format($pagamentos->subcidio_abono_familiar , 2, ',', '.') }} kz</td>
                        <td>{{ number_format($pagamentos->subcidio , 2, ',', '.') }} kz</td>

                        <td>{{ number_format($pagamentos->valor , 2, ',', '.') }} kz</td>

                        <td>{{ number_format($pagamentos->inss , 2, ',', '.') }} kz</td>
                        <td>{{ number_format($pagamentos->irt , 2, ',', '.') }} kz</td>
                        <td>{{ number_format(($pagamentos->faltas * $contrato->falta_por_dia) , 2, ',', '.') }} kz</td>

                        <td>{{ number_format($pagamentos->desconto , 2, ',', '.') }} kz</td>
                        <td>{{ number_format(($pagamentos->valor - $pagamentos->desconto) , 2, ',', '.') }} kz</td>
                    </tr>                     
                @endforeach

                {{-- <tr>
                    <td style="background-color: #eee;">-------</td>
                    <td style="background-color: #eee;">-------</td>
                    <td style="background-color: #eee;">-------</td>
                    
                    <td style="background-color: #eee;">{{ number_format(($somaValorGeral - ($somaSubcidioTransporteGeral + $somaSubcidioAbonoFamiliaGeral + $somaSubcidioFeriasGeral + $somaSubcidioNatalGeral +  $somaSubcidioAlimentacaoGeral + $somaSubcidioGeral)) , 2, ',', '.') }}  kz</td>
                    
                    <td style="background-color: #eee;">{{ number_format($somaSubcidioTransporteGeral , 2, ',', '.')  }} kz</td>
                    <td style="background-color: #eee;">{{ number_format($somaSubcidioAlimentacaoGeral , 2, ',', '.') }} kz</td>
                    <td style="background-color: #eee;">{{ number_format($somaSubcidioFeriasGeral , 2, ',', '.') }} kz</td>
                    <td style="background-color: #eee;">{{ number_format($somaSubcidioNatalGeral , 2, ',', '.') }} kz</td>
                    <td style="background-color: #eee;">{{ number_format($somaSubcidioAbonoFamiliaGeral , 2, ',', '.') }} kz</td>
                    <td style="background-color: #eee;">{{ number_format($somaSubcidioGeral , 2, ',', '.') }} kz</td>
                    
                    <td style="background-color: #eee;">{{ number_format($somaValorGeral , 2, ',', '.') }} kz</td>

                    <td style="background-color: #eee;">{{ number_format($somaInssGeral , 2, ',', '.') }} kz</td>
                    <td style="background-color: #eee;">{{ number_format($somaIrtGeral , 2, ',', '.') }} kz</td>
                    <td style="background-color: #eee;">{{ number_format(($somaFaltasGeral) , 2, ',', '.') }} kz</td>

                    <td style="background-color: #eee;">{{ number_format($somaDescontoGeral , 2, ',', '.') }} kz</td>
                    <td style="background-color: #eee;">{{ number_format(($somaValorGeral - $somaDescontoGeral) , 2, ',', '.') }} kz</td>
                </tr> --}}

            </tbody>

            {{-- @if ($contratos)
                <tbody>
                    @foreach ($contratos as $item)
                    
                        @php
                            $pagamentos = (new App\Models\web\calendarios\Pagamento())::where([
                                ['estudantes_id', '=', $item->funcionarios_id],
                                ['ano_lectivos_id', '=', $ano_lectivo],
                                ['model', '=', 'funcionario'],
                            ])->first();

                            // --------------------------- cada funcionario START
                                $somaIrt = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['estudantes_id', '=', $item->funcionarios_id],['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('irt');

                                $somaInss = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['estudantes_id', '=', $item->funcionarios_id],['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('inss');

                                $somaValor = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['estudantes_id', '=', $item->funcionarios_id],['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('valor');

                                $somaDesconto = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['estudantes_id', '=', $item->funcionarios_id],['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('desconto');

                                $somaSubcidio = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['estudantes_id', '=', $item->funcionarios_id],['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('subcidio');

                                $somaSubcidioTransporte = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['estudantes_id', '=', $item->funcionarios_id],['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('subcidio_transporte');

                                $somaSubcidioAlimentacao = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['estudantes_id', '=', $item->funcionarios_id],['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('subcidio_alimentacao');

                                $somaFaltas = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['estudantes_id', '=', $item->funcionarios_id],['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('faltas');

                            // --------------------------- cada funcionario END

                            // --------------------------- cada funcionario START
                                $somaIrtGeral = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('irt');

                                $somaInssGeral = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('inss');

                                $somaValorGeral = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('valor');

                                $somaDescontoGeral = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('desconto');

                                $somaSubcidioGeral = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('subcidio');

                                $somaSubcidioTransporteGeral = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('subcidio_transporte');

                                $somaSubcidioAlimentacaoGeral = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('subcidio_alimentacao');

                                $somaFaltasGeral = (new App\Models\web\calendarios\Pagamento())::where([
                                    ['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                                ])->sum('faltas');

                            // --------------------------- cada funcionario END

                        @endphp

                    @endforeach
                            
                    <tr>
                        <td style="background-color: #eee;">-------</td>
                        <td style="background-color: #eee;">-------</td>
                        <td style="background-color: #eee;">-------</td>
                        
                        <td style="background-color: #eee;">{{ number_format(($somaValorGeral - ($somaSubcidioTransporteGeral + $somaSubcidioAlimentacaoGeral + $somaSubcidioGeral)) , 2, ',', '.') }}  kz</td>
                        
                        <td style="background-color: #eee;">{{ number_format($somaSubcidioTransporteGeral , 2, ',', '.')  }} kz</td>
                        <td style="background-color: #eee;">{{ number_format($somaSubcidioAlimentacaoGeral , 2, ',', '.') }} kz</td>
                        <td style="background-color: #eee;">{{ number_format($somaSubcidioGeral , 2, ',', '.') }} kz</td>
                        
                        <td style="background-color: #eee;">{{ number_format($somaValorGeral , 2, ',', '.') }} kz</td>

                        <td style="background-color: #eee;">{{ number_format($somaInssGeral , 2, ',', '.') }} kz</td>
                        <td style="background-color: #eee;">{{ number_format($somaIrtGeral , 2, ',', '.') }} kz</td>
                        <td style="background-color: #eee;">{{ number_format(($somaFaltasGeral * 500) , 2, ',', '.') }} kz</td>

                        <td style="background-color: #eee;">{{ number_format($somaDescontoGeral , 2, ',', '.') }} kz</td>
                        <td style="background-color: #eee;">{{ number_format(($somaValorGeral - $somaDescontoGeral) , 2, ',', '.') }} kz</td>
                    </tr>
                    
                </tbody>            
            @endif --}}

            {{-- @if ($pagamentos)
                <tbody>
                    @foreach ($pagamentos as $item)
                        @php
                            $contrato = (new App\Models\web\funcionarios\FuncionariosControto())::where([
                                ['funcionarios_id', '=', $item->id]
                            ])->first();
                        @endphp
                        <tr>
                            <td>{{ $contrato->documento }}</td>
                            <td>{{ $item->nome }} {{ $item->sobre_nome }}</td>
                            <td>{{ $contrato->cargo }}</td>
                            <td>{{ number_format(($item->valor - ($item->subcidio_transporte + $item->subcidio_alimentacao + $item->subcidio)) , 2, ',', '.') }}  kz</td>

                            <td>{{ number_format($item->subcidio_transporte , 2, ',', '.')  }} kz</td>
                            <td>{{ number_format($item->subcidio_alimentacao , 2, ',', '.') }} kz</td>
                            <td>{{ number_format($item->subcidio , 2, ',', '.') }} kz</td>

                            <td>{{ number_format($item->valor , 2, ',', '.') }} kz</td>

                            <td>{{ number_format($item->inss , 2, ',', '.') }} kz</td>
                            <td>{{ number_format($item->irt , 2, ',', '.') }} kz</td>
                            <td>{{ number_format(($item->faltas * $contrato->falta_por_dia) , 2, ',', '.') }} kz</td>

                            <td>{{ number_format($item->desconto , 2, ',', '.') }} kz</td>
                            <td>{{ number_format(($item->valor - $item->desconto) , 2, ',', '.') }} kz</td>
                        </tr>    
                    @endforeach
                    
                </tbody>            
            @endif --}}

        </table>        
    @endif




</body>
</html>