<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de pagamento de {{ $pagamento->servico->servico }}</title>
    
    <style type="text/css">
		*{
			margin: 0;
			padding: 0;
			-webkit-box-sizing: border-box;
			box-sizing: border-box;
			font-family: Arial, Helvetica, sans-serif;
            text-align: left;
		}
        body{
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

		h1{
			font-size: 15pt;
			margin-bottom: 10px;
		}
        h2{
            font-size: 12pt;
        }
		p{
			/* margin-bottom: 20px; */
			line-height: 25px;
            font-size: 12pt;
            text-align: justify;
		}
        strong{
            font-size: 12pt;
        }

		table{
			width: 100%;
			text-align: left;
			border-spacing: 0;	
			margin-bottom: 10px;
			/* border: 1px solid rgb(0, 0, 0); */
            font-size: 12pt;
		}
		thead{
			background-color: #fdfdfd;
            font-size: 10px;
		}
		th, td{
			padding: 6px;
            font-size: 9px;
            margin: 0;
            padding: 0;
		}
        strong{
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


    <header class="header" style="position: absolute;top: 30px;right: 30px;left: 30px;">
        <div class="flex">
            <div style="background-color: rgb(255, 255, 255);color: #111111;font-family: Arial, Helvetica, sans-serif;">
                <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 70px;width: 70px;">
                <h1 style="font-family: Arial, Helvetica, sans-serif;font-size: 10pt;margin: 10px 0">
                    {{ $escola->nome }}
                </h1>
                <h5 style="font-size: 11px;padding: 2px">{{ $escola->provincia->nome ?? ""}} - {{ $escola->municipio->nome ?? "" }}</h5>
                <h5 style="font-size: 11px;padding: 2px">Distrito: {{ $escola->distrito->nome ?? "" }}</h5>
                <h5 style="font-size: 11px;padding: 2px">NIF: {{ $escola->documento }}</h5>
                
            </div>
        </div>
    </header>

    <main style="position: absolute;top: 230px;right: 30px;left: 30px;">

        <table class="table">
            <tr>
                <th style="font-size: 10pt;padding: 10px 0">NOTA DE SAÍDA Nº: {{ $pagamento->next_factura }}</th>
            </tr>
            <tr>
                <th style="font-size: 10pt;padding: 10px 0 0 0">Serviço Pago: {{ $pagamento->servico->servico ?? "" }}</th>
            </tr>
        </table>
        
        <table class="table table-stripeds" style="border-top: 1px dashed #000;border-bosttom: 1px dashed #000;margin-top: 40px">
            <thead>
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
                    {{-- <td>{{ $item->servico->servico ?? "" }}</td> --}}
                    <td>{{ $item->descricao_mes($item->mes) }}</td>
                    <td>{{ number_format($item->preco, 2, ',', '.') }} Kz</td>
                    <td>{{ number_format( $item->quantidade, 1, ',', '.') }}</td>
                    <td>un</td>
                    <td>{{ number_format( $item->desconto, 1, ',', '.') }}</td>
                    <td>{{ number_format( $item->taxa_id, 1, ',', '.') }}</td>
                    <td>{{ number_format( $item->multa, 2, ',', '.') }} KZ</td>
                    <td style="text-align: right">{{ number_format($item->total_pagar, 2, ',', '.') }} KZ</td>
                </tr>
                @endforeach
             
            </tbody>
        </table>
        
        <table  style="border-top: 1px solid #000;">
            <tbody>
                <tr>
                    <td>OBSERVAÇÃO</td>
                    <th style="text-align: right;padding: 7px 0;font-size: 12pt">Total: {{ number_format(($pagamento->valor2 + $pagamento->desconto), '2', ',', '.') }}</th>
                </tr>
                <tr>
                    <td style="padding: 7px 0;">{{ $pagamento->observacao ?? "" }}</td>
                    <td></td>
                </tr>
           
            </tbody>
        </table>
        
                
        <table style="margin-top: 70px">
            <thead style="">
                <tr>
                    <th style="padding: 2px 0">Responsável</th>
                    <th>Recebeu</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>_____________________________</td>
                    <td>_____________________________</td>
                </tr>
            </tbody>
        </table>
  
    </main>

</body>
</html>







