<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>PAGAMENTOS</title>
	<style type="text/css">
		*{
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		body{
			padding: 30px;
			font-family: Arial, Helvetica, sans-serif;
			font-size: 10px;
		}
		h1{
			font-size: 12pt;
			margin-bottom: 4px;
		}
		table{
			width: 100%;
		}
	</style>
</head>
<body>
	
	<header style="position: absolute;top: 0;right: 10px;left: 10px;">
        <table>
            <tr>
                <td style="text-align: center">
                    <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 70px;width: 70px;">
                </td>
            </tr>
			<tr>
				<td style="padding: 4px 0;text-align: center;text-transform: uppercase"> {{ $escola->nome }} </td>
			</tr>
        </table>
    </header>
    
	<main style="position: absolute;top: 150px;right: 10px;left: 10px;">
		<div>
			<h1 class="m-0 text-center" 
			style="font-size: 12px;
			border-bottom: 1px solid rgb(75, 75, 75);
			padding-bottom: 5px;
			text-align: center;
			text-transform: uppercase">Lista de Facturas ou Pagamentos</h1>
		</div>
		
		<div>
			<h1 class="m-0 text-left" style="font-size: 12px;border-bottom: 1px solid rgb(75, 75, 75);padding-bottom: 5px;text-align: left;text-transform: uppercase">Data Inicio: <span style="border-bottom: 1px solid rgb(61, 61, 61)">{{ $data1 ?? 'Todas.' }}</span> Data Final: <span style="border-bottom: 1px solid rgb(61, 61, 61)">{{ $data2 ?? 'Todas.' }}</span> Serviço: <span style="border-bottom: 1px solid rgb(61, 61, 61)">{{ $servico ? $servico->servico : 'Todos.' }}</span></h1>
		</div>

		@if ($pagamentos)
			<table id="example1"  style="width: 100%" class="">
				<thead style="border-bottom: 1px solid rgb(61, 61, 61) ">
					<tr>
						<th style="text-align: left">Nº Ficha</th>
						<th style="text-align: left">Pagamento</th>
						<th style="text-align: left">Nomes</th>
						<th title="Valores" style="text-align: left">Val.</th>
						<th title="Descontos" style="text-align: left">Des.</th>
						<th title="Multas" style="text-align: left">Mult.</th>
						<th style="text-align: left">Total</th>
						<th title="Funcionário" style="text-align: left">Func.</th>
						<th style="text-align: right">Data</th>
					</tr>
				</thead>
				<tbody>
				@php
					$pagamentosValores = 0;
					$pagamentosDesconto = 0;
					$pagamentosMulta = 0;
					$pagamentosQuantidade = 0;
				@endphp
				@foreach ($pagamentos as $item)
				@php
					$pagamentosValores = $pagamentosValores + $item->valor;
					$pagamentosDesconto = $pagamentosDesconto + $item->desconto;
					$pagamentosMulta = $pagamentosMulta + $item->multa;
					$pagamentosQuantidade = $pagamentosQuantidade + $item->quantidade;
				@endphp
					<tr>
						<td style="padding: 3px 0;border-bottom: 1px dashed #ccc">{{ $item->next_factura }}</td>
						<td style="padding: 3px 0;border-bottom: 1px dashed #ccc">{{ $item->servico ?? "" }}</td>
						<td style="padding: 3px 0;border-bottom: 1px dashed #ccc">{{ $item->model($item->model, $item->estudantes_id) }}</td>
						<td style="padding: 3px 0;border-bottom: 1px dashed #ccc">{{ number_format($item->valor, 2, ',', '.')  }} <small>kz</small></td>
						<td style="padding: 3px 0;border-bottom: 1px dashed #ccc">{{ number_format($item->desconto, 2, ',', '.') }} <small>kz</small></td>
						<td style="padding: 3px 0;border-bottom: 1px dashed #ccc">{{ number_format($item->multa, 2, ',', '.') }} <small>kz</small></td>
						<td style="padding: 3px 0;border-bottom: 1px dashed #ccc">{{ number_format( ($item->valor * $item->quantidade) - $item->desconto , 2, ',', '.') }} <small>kz</small></td>
						<td style="padding: 3px 0;border-bottom: 1px dashed #ccc">{{ $item->operador->nome }}</td>
						<td style="text-align: right;border-bottom: 1px dashed #ccc">{{ $item->data_at }}</td>
					</tr>    
				@endforeach
					<tr style="background-color: rgba(0,0,0,1); color: #ffffff;"   >
						<td colspan="10" style="padding: 5px "> Saldo Final {{ number_format((($pagamentosValores  - $pagamentosDesconto ) + $pagamentosMulta) , 2, ',', '.') }} <small>kz</small></td>
					</tr>

				</tbody>
			</table>    
		@endif
	</main>

</body>
</html>