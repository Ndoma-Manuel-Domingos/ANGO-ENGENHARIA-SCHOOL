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

    <header style="position: absolute;top: 0;right: 10px;left: 10px;">
        <table>
            <tr>
                <td rowspan="">
                    <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 70px;width: 70px;">
                </td>
                <td style="text-align: right">
                    <span>Pág: 1/1</span> <br> <br>
                    {{ $pagamento->data_at }}
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
                <td  style="border-top: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px;">
                    <strong style="font-size: 9px">{{ $estudante->nome }} {{ $estudante->sobre_nome }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Telefone:</strong> {{ $escola->telefone1 }}
                </td>
                <td  style="border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>NIF:</strong> {{ $estudante->bilheite ?? '--- --- ---' }}
                </td>
            </tr>
            <tr>
                <td>
                    
                </td>
                <td  style="border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>Nº MATRICULA: </strong> {{ $matricula->numero_estudante ?? '--- --- ---' }}
                </td>
            </tr>
    
            <tr>
                <td>
                    
                </td>
                <td  style="border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>TURMA: </strong> {{ $turma->turma ?? '--- --- ---' }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>E-mail:</strong> {{ $escola->site }}
                </td>
                <td  style="border-bottom: #eaeaea 1px solid;border-right: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px">
                    <strong>TEL:</strong> {{ $estudante->telefone_estudante ?? '--- --- ---' }}
                </td>
            </tr>
            
        </table>
    </header>

    <main style="position: absolute;top: 230px;right: 10px;left: 10px;">
        <table>
            <tr>
                <td style="font-size: 13px"><strong>Luanda-Angola</strong></td>
            </tr>

            <tr>
                <td style="font-size: 13px;padding: 1px 0"><strong>RECIBO</strong> <br> <span style="font-size: 9px">ORGINAL</span> </td>
                <td style="font-size: 9px;padding: 1px 0;text-align: right"><strong>{{ $pagamento->next_factura }}</strong></td>
            </tr>
        </table>

        <table  style="width: 100%" class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
            <thead style="border-bottom: 1px dashed #000;x">
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
                    <td>{{ $pagamento->servico->servico }}</td>
                    <td>{{ $pagamento->numeracao_proforma }}</td>
                    <td>{{ number_format($pagamento->valor * $pagamento->quantidade, 2, ',', '.')  }}</td>
                    <td>{{ number_format(0, 2, ',', '.')  }}</td>
                    <td>{{ number_format($pagamento->valor * $pagamento->quantidade, 2, ',', '.')  }}</td>
                    <td>{{ number_format($pagamento->valor - $pagamento->valor, 2, ',', '.')  }}</td>
                </tr>    
            </tbody>

        </table> 
      
    </main>

    <footer style="position: absolute;bottom: 0;right: 10px;left: 10px;">
        <table style="">
            <tbody>

                <tr>
                    <td style="padding-bottom: 20px">OPERADOR <br>
                        _____________________________________ <br>
                        <strong> {{ $pagamento->operador->nome }} </strong> 
                    </td>
                    <td></td>
                </tr> 

                <tr>
                    <td>Observação: Pago</td>
                    <td style="text-align: right;padding: 1px 0;"><strong>Total Pago:</strong> {{ number_format($pagamento->valor * $pagamento->quantidade, '2', ',', '.') }}</td>
                </tr>
              
                <tr>
                    <td style="padding: 1px 0;">DXQO-Processado por programa válido nº 469/AGT/2024</td>
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
    </footer>

</body>
</html>







