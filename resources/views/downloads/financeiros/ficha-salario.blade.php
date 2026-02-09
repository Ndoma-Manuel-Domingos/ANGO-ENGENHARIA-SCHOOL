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

    <h1 style="text-align: center;">Mapa de Salário Anual</h1>

    <table>
        <thead>
            <tr>
                <th rowspan="2">Cod</th>
                <th rowspan="2">Funcionário</th>
                <th rowspan="2">Categria ou Cargo</th>
                <th rowspan="2">Salário Base</th>
                <th colspan="6" style="text-align: center;">Subsídios</th>
                <th rowspan="2">Salário Iliquido</th>
                <th colspan="3" style="text-align: center;">Desconto</th>
                <th rowspan="2">Total Desconto</th>
                <th rowspan="2">Salário Líquido</th>
            </tr>

            <tr>
           
                <th>Transporte</th>
                <th>Alimentação</th>
                <th>Férias</th>
                <th>Natal</th>
                <th>Abono Familia</th>
                <th>Outros Subsídios</th>

                <th>INSS</th>
                <th>IRT</th>
                <th>Faltas</th>

            </tr>
        </thead>

        @if ($contratos)
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

                            $somaSubcidioNatal = (new App\Models\web\calendarios\Pagamento())::where([
                                ['estudantes_id', '=', $item->funcionarios_id],['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                            ])->sum('subcidio_natal');

                            $somaSubcidioAbonoFamilia = (new App\Models\web\calendarios\Pagamento())::where([
                                ['estudantes_id', '=', $item->funcionarios_id],['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                            ])->sum('subcidio_abono_familiar');

                            $somaSubcidioFerias = (new App\Models\web\calendarios\Pagamento())::where([
                                ['estudantes_id', '=', $item->funcionarios_id],['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                            ])->sum('subcidio_ferias');

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

                            $somaSubcidioNatalGeral = (new App\Models\web\calendarios\Pagamento())::where([
                                ['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                            ])->sum('subcidio_natal');

                            $somaSubcidioAbonoFamiliaGeral = (new App\Models\web\calendarios\Pagamento())::where([
                                ['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                            ])->sum('subcidio_abono_familiar');

                            $somaSubcidioFeriasGeral = (new App\Models\web\calendarios\Pagamento())::where([
                                ['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                            ])->sum('subcidio_ferias');

                            $somaSubcidioAlimentacaoGeral = (new App\Models\web\calendarios\Pagamento())::where([
                                ['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                            ])->sum('subcidio_alimentacao');

                            $somaFaltasGeral = (new App\Models\web\calendarios\Pagamento())::where([
                                ['ano_lectivos_id', '=', $ano_lectivo],['model', '=', 'funcionario'],
                            ])->sum('faltas');

                        // --------------------------- cada funcionario END

                    @endphp

                    @if ($pagamentos)
                        <tr>
                            <td>{{ $item->documento }}</td>
                            <td>{{ $item->nome }} {{ $item->sobre_nome }}</td>
                            <td>{{ $item->cargo }}</td>

                            <td>{{ number_format(($somaValor - ($somaSubcidioTransporte + $somaSubcidioAbonoFamilia + $somaSubcidioFerias + $somaSubcidioNatal + $somaSubcidioAlimentacao + $somaSubcidio)) , 2, ',', '.') }}  kz</td>

                            <td>{{ number_format($somaSubcidioTransporte , 2, ',', '.')  }} kz</td>
                            <td>{{ number_format($somaSubcidioAlimentacao , 2, ',', '.') }} kz</td>
                            <td>{{ number_format($somaSubcidioFerias , 2, ',', '.') }} kz</td>
                            <td>{{ number_format($somaSubcidioNatal , 2, ',', '.') }} kz</td>
                            <td>{{ number_format($somaSubcidioAbonoFamilia , 2, ',', '.') }} kz</td>
                            <td>{{ number_format($somaSubcidio , 2, ',', '.') }} kz</td>

                            <td>{{ number_format($somaValor , 2, ',', '.') }} kz</td>
                            <td>{{ number_format($somaInss , 2, ',', '.') }} kz</td>
                            <td>{{ number_format($somaIrt , 2, ',', '.') }} kz</td>
                            <td>{{ number_format(($somaFaltas) , 2, ',', '.') }} kz</td>

                            <td>{{ number_format($somaDesconto , 2, ',', '.') }} kz</td>
                            <td>{{ number_format(($somaValor - $somaDesconto) , 2, ',', '.') }} kz</td>
                        </tr>  
                        
                    @endif

                @endforeach
                        
                <tr>
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
                    {{-- <td style="background-color: #eee;">-------</td> --}}
                    <td style="background-color: #eee;">{{ number_format(($somaFaltasGeral) , 2, ',', '.') }} kz</td>

                    <td style="background-color: #eee;">{{ number_format($somaDescontoGeral , 2, ',', '.') }} kz</td>
                    <td style="background-color: #eee;">{{ number_format(($somaValorGeral - $somaDescontoGeral) , 2, ',', '.') }} kz</td>
                </tr>
                
            </tbody>            
        @endif

    </table>



</body>
</html>