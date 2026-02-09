<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $titulo }}</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding: 30px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
        }

        h1 {
            font-size: 12pt;
            margin-bottom: 4px;
        }

        table {
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
            <h1 class="m-0 text-center" style="font-size: 12px;
			border-bottom: 1px solid rgb(75, 75, 75);
			padding-bottom: 5px;
			text-align: center;
			text-transform: uppercase">Facturas sem Pagamentos - {{ $verAnoLectivoActivo->ano }}</h1>
        </div>

        @if ($pagamentos)
        <div class="table-responsive">
            <table id="example1" style="width: 100%" class="table table-bordered">
                <thead style="border-bottom: 1px solid rgb(61, 61, 61) ">
                    <tr>
                        <th style="text-align: left">Nº Ficha</th>
                        <th style="text-align: left">Pagamento</th>
                        <th style="text-align: left">Nomes</th>
                        <th style="text-align: left">Valor Un.</th>
                        <th style="text-align: left">Qtd.</th>
                        <th style="text-align: left">Multa</th>
                        <th style="text-align: right">Data</th>
                        <th style="text-align: right">Data Vencimento</th>
                        <th style="text-align: right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $soma = 0;
                    @endphp
                    @foreach ($pagamentos as $item)
                    @php
                    $soma ++;
                    @endphp

                    @php
                    $servicosPago = (new App\Models\web\calendarios\Servico)::findOrFail($item->servicos_id);
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
                        <td style="padding: 3px 0;border-bottom: 1px dashed #ccc">{{ $item->next_factura }}</td>
                        <td style="padding: 3px 0;border-bottom: 1px dashed #ccc">{{ $servicosPago->servico }}</td>
                        <td style="padding: 3px 0;border-bottom: 1px dashed #ccc">{{ $dados->nome }} {{ $dados->sobre_nome }}</td>
                        <td style="padding: 3px 0;border-bottom: 1px dashed #ccc">{{ number_format( $item->valor, 2, ',', '.') }}</td>
                        <td style="padding: 3px 0;border-bottom: 1px dashed #ccc">{{ number_format( $item->quantidade, 1, ',', '.') }}</td>
                        <td style="padding: 3px 0;border-bottom: 1px dashed #ccc">{{ number_format( $item->multa, 1, ',', '.') }}</td>
                        <td style="text-align: right;border-bottom: 1px dashed #ccc">{{ $item->data_at }}</td>
                        <td style="text-align: right;border-bottom: 1px dashed #ccc">{{ $item->data_vencimento }}</td>
                        <td style="text-align: right;padding: 3px 0;border-bottom: 1px dashed #ccc">{{ number_format( ($item->valor * $item->quantidade) + $item->multa, 2, ',', '.') }} <small>kz</small></td>
                    </tr>
                    @endforeach
                    <tr style="background-color: rgba(0,0,0,1); color: #ffffff;">
                        <td colspan="10" style="padding: 5px "> Saldo Final {{ number_format($divCorr - $divVenc , 2, ',', '.') }} <small>kz</small></td>
                    </tr>

                </tbody>
            </table>
        </div>
        @endif
    </main>

</body>
</html>
