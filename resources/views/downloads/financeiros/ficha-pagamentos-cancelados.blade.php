<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pagamento ou Facturas Canceladas</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding: 30px;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1 {
            font-size: 12pt;
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
            /* background-color: #ddd; */
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
            font-size: 9pt;
            text-align: left;
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
            padding-right: 2px;
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
    <div
        style="background-color: rgb(255, 255, 255);color: #111111;padding: 10px;font-family: Arial, Helvetica, sans-serif;">
        <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 100px;width: 100px;float: right;">
        <h1>{{ $escola->nome }}</h1>
        {{-- <p>{{ $escola->natureza }}</p> --}}
        <br>
        <p>NIF: {{ $escola->documento }}</p>
        <p>Angola - {{ $escola->provincia }}</p>
        <br>
    </div>
    <br>

    <div>
        <h1 class="m-0 text-center">Lista de Todas as Facturas Pagamentos Cancelados</h1>
    </div>


    @php
        $pagamentoArrecadoValores = 0;
        $pagamentoArrecadoQuantidade = 0;
        $pagamentoArrecadoTotal = 0;
    @endphp

    @if ($pagamentos)
    <table id="example1"  style="width: 100%" class="table table-bordered">
        <thead>
            <tr>
                <th>Nº Fact</th>
                <th>Pagamento</th>
                {{-- <th>Status</th> --}}
                <th>Nomes</th>
                <th title="Valores">Val.</th>
                <th>Qtd.</th>
                <th title="Descontos">Des.</th>
                <th title="Multas">Mult.</th>
                <th>Total</th>
                <th title="Funcionário">Func.</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @php
            $soma = 0;
            @endphp
            @foreach ($pagamentos as $item)
            @php
                $soma ++;
                $pagamentoArrecadoValores = $pagamentoArrecadoValores + $item->valor;
                $pagamentoArrecadoQuantidade = $pagamentoArrecadoQuantidade + $item->quantidade;
                $pagamentoArrecadoTotal = $pagamentoArrecadoTotal + ($item->valor * $item->quantidade);
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
                <td><strong>Fact Nº</strong> {{ $item->id }}</td>
                <td>{{ $item->servico }}</td>
                {{-- <td>{{ $item->model }}</td> --}}
                <td>{{ $dados->nome }} {{ $dados->sobre_nome }}</td>
                <td>{{ number_format($item->valor, 2, ',', '.') }} <small>kz</small></td>
                <td>{{ $item->quantidade }}</td>
                <td>{{ number_format($item->desconto, 2, ',', '.') }} <small>kz</small></td>
                <td>{{ number_format($item->multa, 2, ',', '.') }} <small>kz</small></td>

                <td>{{ number_format( ($item->valor * $item->quantidade) - $item->desconto , 2, ',', '.') }}
                    <small>kz</small></td>
                <td>{{ $item->usuario }}</td>
                <td>{{ $item->data_at }}</td>
            </tr>
            @endforeach
            <tr style="background-color: rgba(0,0,0,.5); color: #ffffff;">
                <td>-----</td>
                <td>-----</td>
                <td>-----</td>
                <td>{{ number_format($pagamentoArrecadoValores, 2, ',', '.') }} <small>kz</small></td>
                <td>{{ $pagamentoArrecadoQuantidade }}</td>
                <td>{{ number_format($pagamentosDesconto, 2, ',', '.') }} <small>kz</small></td>
                <td>{{ number_format($pagamentosMulta, 2, ',', '.') }} <small>kz</small></td>
                <td>{{ number_format($pagamentoArrecadoTotal , 2, ',', '.') }}
                    <small>kz</small></td>
                <td>------</td>
                <td>------</td>
            </tr>

            <tr style="background-color: rgba(0,0,0,.5); color: #ffffff;">
                <td colspan="10"> Saldo Final {{ number_format($pagamentoArrecadoTotal , 2, ',', '.') }} <small>kz</small></td>
            </tr>

        </tbody>
    </table>
    @endif

</body>

</html>