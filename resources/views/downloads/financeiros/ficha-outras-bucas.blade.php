<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Relatório Mensal</title>
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

	<div style="background-color: rgb(255, 255, 255);color: #111111;padding: 10px;font-family: Arial, Helvetica, sans-serif;">
		<img src="{{ $logotipo }}" alt="" style="text-align: center;height: 100px;width: 100px;float: right;">
		<h1>{{ $escola->nome ?? '' }}</h1>
		<br>
		<p>NIF: {{ $escola->documento ?? '' }}</p> 
		<p>Angola - {{ $escola->provincia->nome ?? ""?? '' }}</p> 
		<br>
	</div>
	<br>

	<div>
		<h1 class="m-0 text-center">Relatório  
            
            @if (isset($_GET['mensals']) AND $_GET['mensals'] != "todas")
                @if ($_GET['mensals'] == "January")
                    do Mês Janeiro
                @endif
                @if ($_GET['mensals'] == "February")
                    do Mês Fevereiro
                @endif
                @if ($_GET['mensals'] == "March")
                    do Mês Março
                @endif
                @if ($_GET['mensals'] == "April")
                    do Mês Abril
                @endif
                @if ($_GET['mensals'] == "May")
                    do Mês Maio
                @endif
                @if ($_GET['mensals'] == "June")
                    do Mês Junho 
                @endif
                @if ($_GET['mensals'] == "July")
                    do Mês Julho 
                @endif
                @if ($_GET['mensals'] == "August")
                    do Mês Agosto 
                @endif
                @if ($_GET['mensals'] == "September")
                    do Mês Setembro
                @endif
                @if ($_GET['mensals'] == "October")
                    do Mês Outubro
                @endif
                @if ($_GET['mensals'] == "November")
                    do Mês Novembro
                @endif
                @if ($_GET['mensals'] == "December")
                    do Mês Dezembro
                @endif
            @else 
                dos pagamentos de {{ $servico->servico ?? '' }} do Mês {{ $mes_mensal ?? '---' }}
            @endif

		</h1>
	</div>

	@if (isset($pagamentosDetalhes) && count($pagamentosDetalhes)  > 0)
		<table id="example1"  style="width: 100%" class="table table-bordered  ">
			<thead>
				<tr>
					<th>Nº Fact</th>
					<th>Nomes</th>
					<th title="Funcionário">Func.</th>
					<th>Data</th>
					<th style="text-align: right">Total</th>
				</tr>
			</thead>
			<tbody>
				@php
					$soma = 0;
					$totalArrecadado = 0;
					$totalArrecadadoUnico = 0;
				@endphp
				@foreach ($pagamentosDetalhes as $items)
					@php
						$pagamentos = (new App\Models\web\calendarios\Pagamento)::where('ficha', $items->code)
						->join('users', 'tb_pagamentos.funcionarios_id', '=', 'users.id')
						->join('tb_servicos', 'tb_pagamentos.servicos_id', '=', 'tb_servicos.id')
						->select('tb_servicos.servico', 'users.usuario', 'tb_pagamentos.ficha', 'tb_pagamentos.next_factura','tb_pagamentos.mensal', 'tb_pagamentos.quantidade', 'tb_pagamentos.data_at', 'tb_pagamentos.funcionarios_id', 'tb_pagamentos.multa', 'tb_pagamentos.desconto', 'tb_pagamentos.model', 'tb_pagamentos.estudantes_id', 'tb_pagamentos.valor', 'tb_pagamentos.id')
						->orderBy('tb_pagamentos.data_at')
						->get();
					@endphp
					@foreach ($pagamentos as $item)
						@php
							$soma ++;
						@endphp

						@if ($item->model == "estudante")
							@php
								$dados = (new App\Models\web\estudantes\Estudante)::findOrFail($item->estudantes_id);
							@endphp
							@else
							@if ($item->model == "funcionario")
								@php
									$dados = (new App\Models\web\funcionarios\Funcionarios)::findOrFail($item->estudantes_id);
								@endphp
							@else
								@if ($item->model == "escola")
									@php
										$dados = (new App\Models\Shcool)::findOrFail($item->estudantes_id);
									@endphp
								@else
									@php
										$dados->nome = "";
										$dados->sobre_nome = "";
									@endphp
								@endif						
							@endif
						@endif
						<tr>
							<td>{{ $item->next_factura ?? '' }}</td>
							<td>{{ $dados->nome ?? '' }} {{ $dados->sobre_nome ?? '' }}</td>
							<td>{{ $item->usuario ?? '' }}</td>
							<td>{{ $item->data_at ?? '' }}</td>
							<td style="text-align: right">{{ number_format( ($items->preco * $items->quantidade), 2, ',', '.') }} <small>kz</small></td>
						</tr>   
						@php
							$totalArrecadado = $totalArrecadado + ($items->preco * $items->quantidade);
							$totalArrecadadoUnico = $totalArrecadadoUnico + $items->preco;
						@endphp 
					@endforeach
				@endforeach
					
					<tr style="background-color: rgba(0,0,0,.5); color: #ffffff;">
						<td colspan="5" style="font-size: 12pt;text-align: right"> Saldo Final {{ number_format(($totalArrecadadoUnico) , 2, ',', '.') }} <small>kz</small></td>
					</tr>

				</tbody>
		</table> 
	@else
		@if ($pagamentos)
			<table id="example1"  style="width: 100%" class="table table-bordered  ">
				<thead>
					<tr>
						<th>Nº Fact</th>
						<th>Nomes</th>
						<th title="Funcionário">Func.</th>
						<th>Data</th>
						<th style="text-align: right">Total</th>
					</tr>
				</thead>
				<tbody>
				@php
					$soma = 0;
					$total = 0;
				@endphp
				@foreach ($pagamentos as $item)
					@php
						$soma ++;
						$total 
					@endphp
	
					@if ($item->model == "estudante")
						@php
							$dados = (new App\Models\web\estudantes\Estudante)::findOrFail($item->estudantes_id);
						@endphp
						@else
						@if ($item->model == "funcionario")
							@php
								$dados = (new App\Models\web\funcionarios\Funcionarios)::findOrFail($item->estudantes_id);
							@endphp
						@else
							@if ($item->model == "escola")
								@php
									$dados = (new App\Models\Shcool)::findOrFail($item->estudantes_id);
								@endphp
							@else
								@php
									$dados->nome = "";
									$dados->sobre_nome = "";
								@endphp
							@endif						
						@endif
					@endif
					<tr>
						<td>{{ $item->next_factura }}</td>
						<td>{{ $dados->nome }} {{ $dados->sobre_nome }}</td>
						<td>{{ $item->usuario }}</td>
						<td>{{ $item->data_at }}</td>
						<td style="text-align: right">{{ number_format( ($item->valor * $item->quantidade) , 2, ',', '.') }} <small>kz</small></td>
					</tr>   
					
					@php
						$total = $total + ($item->valor * $item->quantidade);
					@endphp
				@endforeach
					<tr style="background-color: rgba(0,0,0,.5); color: #ffffff;">
						<td colspan="5" style="font-size: 12pt;text-align: right"> Saldo Final {{ number_format($total, 2, ',', '.') }} <small>kz</small></td>
					</tr>
				</tbody>
			</table>    
		@endif
	@endif

</body>
</html>