<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ficha de Matricula & Confirmação </title>
    <style type="text/css">
        * {
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
			margin-bottom: 4px;
		}

        .titulo {
            font-size: 12pt;
            text-align: center;
            margin-top: 0;
        }

        p {
            /* margin-bottom: 20px; */
            line-height: 20px;
            font-size: 12pt;
        }

        table {
            width: 100%;
            text-align: left;
            border-spacing: 0;
            margin-bottom: 10px;
            border: 1px solid #fff;
            font-size: 12pt;
        }

        thead {
            background-color: #eaeaea;
            border-bottom: 1px solid #006699;
            font-size: 10pt;

        }

        th,
        td {
            padding: 12px;
            border-top: 1px solid #000;
            font-size: 10pt;

        }

        .border {
            border: 1px solid #eaeaea;
        }

        .flex {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-content: center;
            align-items: center;
        }

        .col {
            width: 25%;
            padding-left: 2px;
            padding-right: 2px;
        }

        .cols {
            width: 50%;
            padding-left: 2px;
            padding-right: 2px;
        }

        .col-2 {
            width: 10%;
            padding-left: 2px;
            padding-right: 50px;
            margin-right: 50px;
            float: right;
        }

        .col-8 {
            width: 80%;
            padding-left: 2px;
            padding-right: 2px;
        }

        .logo {
            height: 70px;
            width: 70px;
            /*border-radius: 300px;*/
            /* padding: 30px;  */
            border: 1px solid #000;
        }

        .ml {
            margin-left: 80px;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="colum" style="height: 985px">
        <div class="flex">
            <div style="background-color: rgb(255, 255, 255)48);color: #ffffff;padding: 10px;font-family: Arial, Helvetica, sans-serif;">
                <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 90px;width: 90px;float: right;">
                <h1>{{ $escola->nome }}</h1>
                <p>{{ $escola->natureza }}</p>
                <br>
                <p>NIF: {{ $escola->documento }}</p>
                <p>Angola - {{ $escola->provincia }}</p>
                <br>
            </div>
        </div>

        <h1 class="titulo" style="text-align: left;margin-top: 10px;margin-bottom: 5px;" ><span>Ficha </span> Nº {{ $pagamento->next_factura }} </h1>
        <h1 class="titulo" style="text-align: left;margin-top: 10px;margin-bottom: 5px;" ><span>Referência da factura </span> Nº {{ $pagamento->ficha }} </h1>

        <table style="background-color: rgba(219, 217, 217, 0.808); ">
            <tbody>
                {{-- <tr>
                        <td colspan="2" rowspan="2"><img src="{{ public_path('assets/images/user.png') }}" alt=""
                            style="text-align: center;height: 50px;width: 50px;float: right;border:1px solid #000;padding: 10px;"></td>
                    </tr> --}}
                <tr>
                    <td><strong>Nome:</strong> {{ $dados->nome }} {{ $dados->sobre_nome }}</td>
                    <td><strong>Genero:</strong> {{ $dados->genero }}</td>
                </tr>
                <tr>
                    <td><strong>Estado Civil:</strong> {{ $dados->estado_civil }}</td>
                    <td><strong>Data Nascimento:</strong> {{ $dados->nascimento }}</td>
                </tr>

            </tbody>
        </table>

        <table class="border" style="background-color: rgba(240, 240, 240, 0.945); ">
            <tbody>
                <tr>
                    <th style="text-align: left">V. Unitário:
                        {{ number_format($pagamento->valor, 2, ',', '.') }} Kz</th>
                    <th style="text-align: left">Multa: {{ number_format($pagamento->multa, 2, ',', '.') }} Kz
                    </th>
                </tr>

                <tr>
                    <th style="text-align: left">Desconto:
                        {{ number_format($pagamento->desconto, 2, ',', '.') }} Kz</th>
                    <th style="text-align: left">Troco:
                        {{ number_format($pagamento->valor2 - $pagamento->valor, 2, ',', '.') }} Kz</th>
                </tr>

                <tr>
                    <th colspan="2" style="text-align: left">Pagamento: {{ $pagamento->status }}</th>
                </tr>

            </tbody>
        </table>
        <table class="border" style="background-color: rgba(240, 240, 240, 0.945); text-aling: left;">
            <tbody>

                <tr>
                    <th style="text-align: left;">Forma Pagamento: {{ $pagamento->tipo_pagamento }}</th>
                </tr>

                <tr>
                    <th style="text-align: left;">Número da ordem bancária: @if ($pagamento->banco)
                            {{ $pagamento->numero_transacao }}
                        @else
                            =========
                        @endif
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left;">Banco: @if ($pagamento->banco)
                            {{ $pagamento->banco }}
                        @else
                            ========
                        @endif
                    </th>
                </tr>

                <tr>
                    <th style="text-align: left;">Valor Pago: {{ number_format( ($pagamento->valor) + ($pagamento->multa) - ($pagamento->desconto), 2, ',', '.')  }} Kz</th>
                </tr>
                <tr>
                    <th style="text-align: left;">Valor Entregue: {{ number_format( ($pagamento->valor_entregue), 2, ',', '.')  }} Kz</th>
                </tr>
                <tr>
                    <th style="text-align: left;">Troco: {{ number_format($pagamento->troco, 2, ',', '.')  }} Kz</th>
                </tr>

            </tbody>
        </table>

        <div class="col-2 text-center">

            <h5><br>Data <br>{{ $pagamento->data_at }}</h5> <br> <br>

            <h5>Funcionário</h5>
            ----------------------------------------------------
            <p>{{ $funcionarioAtendente->nome ?? "" }}</p>
        </div>
        

    </div> <br>

    <p>--------------------------------------------------------------------------------------------------------------------------------------------
    </p>

</body>

</html>
