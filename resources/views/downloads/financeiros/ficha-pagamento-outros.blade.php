<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de pagamento {{ $pagamento->pago_at }}</title>
    
    <style type="text/css">
		*{
			margin: 0;
			padding: 0;
			box-sizing: border-box;
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

		.titulo{
			font-size: 12pt;
			text-align: center;
            margin-top: 0;
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
			border-bottom: 1px solid #006699;
            font-size: 10pt;

		}
		th, td{
			padding: 6px;
			border-top: 1px solid rgb(0, 0, 0);
			border-bottom: 1px solid rgb(0, 0, 0);
            font-size: 10pt;
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
			text-align: left;
		}

        /* ----------------------------------------- */
        .header{
            position: fixed;
            top: 0;
            padding: 30px 20px;
            /* border-bottom: 1px solid rgba(0, 0, 0, .1); */
            width: 100%;
        }

        .sebheader{
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
            align-content: flex-end;
            align-items: center;
            clear: both;
        }

        .div01{
            width: 50%;
            float: left;
        }

        .padding-left{
            padding-left: 30px;
        }

        .section{
            margin-top: 120px;
            padding: 20px; 
            width: 100%;
            float: left;
        }

        .col{
            width: 50%;
            float: right;
            clear: both;
        }

        .col-2{
            width: 100%;
            float: left;
            clear: both;
        }
	</style>

</head>
<body>

    <header class="header">
        <div class="div01">
            <h2>{{ $escola->nome }}</h2>
            <h2>{{ $escola->provincia }} - {{ $escola->municipio }}</h2>
            <h2></h2>
            <h2>Distrito: {{ $escola->distrito->nome ?? "" }}</h2>
            <h2>Endereço: {{ $escola->endereco }}</h2>
            <h2>Ficha de pagamento de {{ $pagamento->pago_at }}.</h2>
        </div>
        <div class="div01">
            <p style="text-align: right;"> Data:  @php
                echo date('Y-m-d');
            @endphp
            </p>

            <p style="text-align: left;">Observação<hr></p>
            
            <p style="text-align: left;">Apos receber a confirmação do pagamento da factura, liga a direcção geral a 
                fim de confirmar 
            </p>
            <hr>
            <p><strong>Data Emissão:</strong> {{ date('Y-m-d') }}</p>
        </div>
    </header>

    <div class="section">
        <div class="col-sm-2 invoice-col"><br>
            <b class="fs-4">Fictura Nº: {{ $pagamento->ficha }}</b><br><br>
        </div>

        <table class="table">
            <tr>
                <td style="text-align: left;background-color: rgb(65, 64, 64);
                color: #eeeeee;font-size: 17px;" colspan="5">Informação do Estudante</td>
            </tr>
            <tr>
                <td>Mat Nº: <strong>{{ $matricula->documento }}</strong></td>
                <th colspan="3">Nome: <strong>{{ $estudante->nome }} {{ $estudante->sobre_nome }}</strong></th>
                <td>Genero: <strong>{{ $estudante->genero }}</strong></td>
            </tr>
            
            <tr>
                <td>Turma: <strong>{{ $turma->turma }}</strong></td>
                <th>Turno: <strong>{{ $turno->turno }}</strong></th>
                <th>Curso: <strong>{{ $curso->curso }}</strong></th>
                <td>Classe: <strong>{{ $classe->classes }}</strong></td>
                <th>Sala: <strong>{{ $sala->salas }}</strong></th>
            </tr>
        </table>



        <div class="col-2">
            <h1>Pagamento</h1>
            <table class="table">
                <tr>
                    <th>Valor Unitario: <strong>{{ number_format($pagamento->valor, 2, ',', '.') }} kz</strong></th>
                    <th>Quantidade: <strong>{{ $pagamento->quantidade }}</strong></th>
                    <th>Desconto: <strong>{{ number_format($pagamento->desconto, 2, ',', '.') }} kz</strong></th>
                </tr>

                <tr>
                    <th>Pagamento: <strong>{{ $pagamento->status }}</strong></th>
                    <th>Forma Pagamento: <strong>{{ $pagamento->tipo_pagamento }}</strong></th>
                    <th>Número da ordem bancária: <strong>{{ $pagamento->numero_transacao }}</strong></th>
                </tr>
                
                <tr>
                    <th>Banco: <strong>{{ $pagamento->banco }}</strong></th>
                    <th>Funcionário: <strong>{{ $funcionario->usuario }}</strong></th>
                    <th>Total: <strong>{{ number_format((($pagamento->quantidade * $pagamento->valor) - $pagamento->desconto), 2, ',', '.')  }} kz </strong></th>
                </tr>
            </table>                
        </div>

    </div> 
    
</body>
</html>







