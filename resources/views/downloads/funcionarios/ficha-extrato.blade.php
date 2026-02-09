<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Extrato do Funcionário | </title>

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
			font-size: 10pt;
			margin-bottom: 10px;
		}
        h2{
            font-size: 12pt;
        }
        .section{
            /* margin-top: 90px; */
            /* padding: 20px;  */
        }
		.titulo{
			font-size: 12pt;
			text-align: center;
            margin-top: 0;
		}

        strong{
            font-size: 12pt;
        }
		p{
			/* margin-bottom: 20px; */
			line-height: 30px;
            font-size: 12pt;
		}

		table{
			width: 100%;
			text-align: left;
			border-spacing: 0;	
			margin-bottom: 10px;
			border: 1px solid rgb(0, 0, 0);
            font-size: 12pt;
		}
		thead{
			background-color: #fdfdfd;
			border-bottom: 1px solid #006699;
            font-size: 10pt;

		}
		th, td{
			padding: 6px;
			border: 1px solid rgb(0, 0, 0);
            font-size: 9pt;
		}

        strong{
            font-size: 9pt;
		}

		.border{
			border: 1px solid #eaeaea;
		}

        .header{
            /* position: fixed;
            top: 0; */
            /* padding: 40px 20px; */
            border-bottom: 1px solid rgba(0, 0, 0, .1);
            width: 100%;
            float: left;
        }

        .div01{
            width: 50%;
            float: left;
            text-align: center;
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
		{{-- <p>{{ $escola->natureza }}</p>  --}}
		<p>NIF: {{ $escola->documento }}</p> 
		<p>Angola - {{ $escola->provincia->nome ?? ""}}</p> 
        <h1>Departamento de Recursos Humanos</h1>
	</div>

    <div class="section">
        <h1>Extratos de Pagamento do Fincionário {{ $funcionario->nome }} {{ $funcionario->sobre_nome }}</h1>
        <table  style="width: 100%;margin-top: 30px;">
            <thead>
                <tr>
                    <td colspan="4" 
                    style="text-align: left;background-color: rgb(202, 202, 202);
                    color: #464646;font-size: 10pt;"
                    >Dados Pessoais Funcionário</td>
                </tr>
            </thead>
            
            <tbody>
                
                <tr>
                    <td>Nome</td>
                    <td>Nascimento</td>
                    <td>Genero</td>
                    <td>Estado Civil</td>
                </tr> 
                <tr>    
                    <td><strong>{{ $funcionario->nome }} {{ $funcionario->sobre_nome }}</strong></td>
                    <td><strong>{{ $funcionario->nascimento }}</strong></td>
                    <td><strong>{{ $funcionario->genero }}</strong></td>
                    <td><strong>{{ $funcionario->estado_civil }}</strong></td>
                </tr> 

                <tr>
                    <td>Bilhete</td>
                    <td>Telefone</td>
                    <td>Endereco</td>
                    <td>Data Assinatura</td>
                </tr>   
                
                <tr>
                    <td><strong>{{ $funcionario->bilheite }}</strong></td>
                    <td><strong>{{ $funcionario->telefone }}</strong></td>
                    <td><strong>{{ $funcionario->endereco }}</strong></td>
                    <td><strong>{{ $contratos->data_at }}</strong></td>
                </tr> 
            </tbody>
        </table>
        <!-- Table row -->
        @if ($extratoFinaceiro)
            <table  style="width: 100%;margin-top: 30px;" class="table table-stripeds fs-6 text-center">
                <thead>
                    <tr>
                        <td colspan="3" style="text-align: left;background-color: rgb(202, 202, 202); color: #464646;font-size: 10pt;">Situação dos Meses</td>
                    </tr>

                    <tr>
                        <th style="text-align: left">Meses Pago</th>
                        <th style="text-align: left">Abreviação</th>
                        <th style="text-align: left">Status</th>
                    </tr>
                </thead>
                <tbody style="background-color: rgba(244, 244, 244, .5); font-size: 12pt;">
                    @foreach ($extratoFinaceiro as $item)
                        <tr>
                            <td style="text-align: left">{{ $item->meses }}</td>
                            <td style="text-align: left">{{ $item->abreviacao }}</td>
                            <td style="text-align: left">{{ $item->status }}</td>
                        </tr>
                    @endforeach
                    
                </tbody>
                <tfoot style="background-color: rgba(244, 244, 244, 1.0);">
                    <tr>
                        <td style="text-align: left">
                            Meses Pagos <strong> {{ $mesesPagos }}</strong>
                        </td>

                        <td style="text-align: left">
                            Meses Não Pagos <strong>{{ $mesesNPagos }}</strong>
                        </td>

                        <td style="text-align: left">
                            Meses Devendo <strong>{{ $dividas }}</strong>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: left">
                            Valor a Pagar <strong>{{ number_format(($mesesPagos * ($contratos->salario + $contratos->subcidio_transporte + $contratos->subcidio_alimentacao)) , '2', ',', '.') }} Kz</strong>
                        </td>

                        <td style="text-align: left">
                            Valor a Pagar <strong>{{ number_format(($mesesNPagos * ($contratos->salario + $contratos->subcidio_transporte + $contratos->subcidio_alimentacao)), '2', ',', '.')  }} Kz</strong>
                        </td>

                        <td style="text-align: left">
                            Valor a Pagar <strong>{{ number_format(($dividas * ($contratos->salario + $contratos->subcidio_transporte + $contratos->subcidio_alimentacao)), '2', ',', '.')  }} Kz</strong>
                        </td>
                    </tr>

                </tfoot>
            </table>
        @endif      
        
        
        <div class="div01">
            <h5>Assinatura</h5>
            <p>-----------------------------------------------------------</p>
        </div>

        <div class="div01">
            <h5>Assinatura</h5>
            <p>-----------------------------------------------------------</p>
        </div>

        <div class="" style="text-align: center;">
            <p style="text-align: right;"> Data impresão: <br>  @php
                echo date('d-m-Y');
            @endphp
            </p>
        </div>      
    </div>




</body>
</html>