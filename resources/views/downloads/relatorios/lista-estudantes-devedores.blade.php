<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $titulo }} | Gestão Escolar</title>

    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding: 20px;
            font-size: 10px;
            font-family: Arial, Helvetica, sans-serif;
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

<body class="hold-transition sidebar-mini">
    <header style="position: absolute;top: 0;right: 10px;left: 10px;">
        <table>
            <tr>
                <td style="text-align: left">
                    <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 70px;width: 70px;">
                </td>
            </tr>
            <tr>
                <td style="padding: 4px 0;text-align: left;text-transform: uppercase"> {{ $escola->nome }} </td>
            </tr>
            <tr>
                <td style="padding: 4px 0;text-align: left;text-transform: uppercase">NIF: {{ $escola->documento }} </td>
            </tr>
            <tr>
                <td style="padding: 4px 0;text-align: left;text-transform: uppercase">Endereço: {{ $escola->endereco }} </td>
            </tr>
        </table>
    </header>
    <!-- Table row -->
    <div class="row" style="margin-top: 170px">
        
      <div>
        <h1 style="font-size: 12px; margin-top: 10px; border-bottom: 1px solid rgb(75, 75, 75); padding-bottom: 5px; text-align: left; text-transform: uppercase">{{ $titulo }}</h1>
      </div>
      
        <table id="example1"  style="width: 100%">
          <thead>
            <tr>
              <th style="font-size: 12px; margin-bottom: 30px; border-bottom: 1px solid rgb(75, 75, 75); padding-bottom: 5px; text-align: left;
              text-transform: uppercase" colspan="6">B.I ESTUDANTE: {{ $requests['input_estudante'] ?? 'TODOS' }}</th>
              <th style="font-size: 12px; margin-bottom: 30px; border-bottom: 1px solid rgb(75, 75, 75); padding-bottom: 5px; text-align: left;
              text-transform: uppercase" colspan="4">ESTADO: {{ $requests['condicao'] ?? 'TODOS' }}</th>
            </tr>

            <tr>
              <th style="font-size: 12px; margin-bottom: 30px; border-bottom: 1px solid rgb(75, 75, 75); padding-bottom: 5px; text-align: left;
                text-transform: uppercase" colspan="2">CURSO: {{ $curso->curso ?? 'TODOS' }}</th>
              <th style="font-size: 12px; margin-bottom: 30px; border-bottom: 1px solid rgb(75, 75, 75); padding-bottom: 5px; text-align: left;
                text-transform: uppercase" colspan="2">CLASSE: {{ $classe->classes ?? 'TODAS' }}</th>
              <th style="font-size: 12px; margin-bottom: 30px; border-bottom: 1px solid rgb(75, 75, 75); padding-bottom: 5px; text-align: left;
                text-transform: uppercase" colspan="2">TURNO: {{ $turno->turno ?? 'TODOS' }}</th>
              <th style="font-size: 12px; margin-bottom: 30px; border-bottom: 1px solid rgb(75, 75, 75); padding-bottom: 5px; text-align: left;
                text-transform: uppercase" colspan="2">SERVIÇO: {{ $servico->servico ?? 'TODOS' }}</th>
              <th style="font-size: 12px; margin-bottom: 30px; border-bottom: 1px solid rgb(75, 75, 75); padding-bottom: 5px; text-align: left;
                text-transform: uppercase" colspan="2">ANO LECTIVO: {{ $ano_lectivo->ano ?? 'TODOS' }}</th>
            </tr>
            
            <tr>
                @php
                    // Verifica se o campo 'mes' existe e se é um array
                    $mesesSelecionados = isset($requests['mes']) && is_array($requests['mes']) ? $requests['mes'] : [];
                @endphp
                <th style="font-size: 12px; margin-bottom: 30px; border-bottom: 1px solid rgb(75, 75, 75); padding-bottom: 5px; text-align: left;
                text-transform: uppercase" colspan="10">MESES:                 
                <span> {{ in_array("Jan", $mesesSelecionados) ? 'Jan,' : '' }}</span>
                <span> {{ in_array("Feb", $mesesSelecionados) ? 'Fev,' : '' }}</span>
                <span> {{ in_array("Mar", $mesesSelecionados) ? 'Mar,' : '' }}</span>
                <span> {{ in_array("Apr", $mesesSelecionados) ? 'Abr,' : '' }}</span>
                <span> {{ in_array("May", $mesesSelecionados) ? 'Mai,' : '' }}</span>
                <span> {{ in_array("Jun", $mesesSelecionados) ? 'Jun,' : '' }}</span>
                <span> {{ in_array("Jul", $mesesSelecionados) ? 'Jul,' : '' }}</span>
                <span> {{ in_array("Aug", $mesesSelecionados) ? 'Ago,' : '' }}</span>
                <span> {{ in_array("Sep", $mesesSelecionados) ? 'Set,' : '' }}</span>
                <span> {{ in_array("Oct", $mesesSelecionados) ? 'Out,' : '' }}</span>
                <span> {{ in_array("Nov", $mesesSelecionados) ? 'Nov,' : '' }}</span>
                <span> {{ in_array("Dec", $mesesSelecionados) ? 'Des' : '' }}</span>
              
              </th>
            </tr>
          </thead>
        </table>
        <br>
        <br>
        
        <table id="example1"  style="width: 100%">
            <thead>
                <tr>
                    <th style="text-align: left;background-color: #ccc;padding: 5px">Nº</th>
                    <th style="text-align: left;background-color: #ccc;padding: 5px">Nome</th>
                    <th style="text-align: left;background-color: #ccc;padding: 5px">Bilhete</th>
                    <th style="text-align: left;background-color: #ccc;padding: 5px">Mês</th>
                    <th style="text-align: left;background-color: #ccc;padding: 5px">Estado</th>
                    <th style="text-align: right;background-color: #ccc;padding: 5px">Multa</th>
                    <th style="text-align: right;background-color: #ccc;padding: 5px">Preço</th>
                    <th style="text-align: right;background-color: #ccc;padding: 5px">Total</th>
                </tr>
            </thead>
            
            @php
                $total_multa = 0;
                $total_preco = 0;
                $total = 0;
            @endphp

            <tbody>
                @foreach ($cartoes as $item)
                <tr>
                    <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: left">{{ $item->id }}</td>
                    <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: left">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                    <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: left">{{ $item->estudante->bilheite }}</td>
                    <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: left">{{ $item->mes($item->month_name) }}</td>
                    @if ($item->status == "divida")
                    <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: left">{{ $item->status }}</td>
                    @else
                    @if ($item->status == "Pago")
                    <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: left">{{ $item->status }}</td>
                    @else
                    @if ($item->status == "Nao Pago")
                    <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: left">{{ $item->status }}</td>
                    @else
                    <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: left">{{ $item->status }}</td>
                    @endif
                    @endif
                    @endif
                    <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: right">{{ number_format($item->multa ?? 0, 2, ',', '.') }} Kz</td>
                    <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: right">{{ number_format($item->preco_unitario ?? 0, 2, ',', '.') }} Kz</td>
                    <td style="padding: 3px 0;border-bottom: 1px dashed #ccc;text-align: right">{{ number_format(($item->multa ?? 0) + ($item->preco_unitario ?? 0), 2, ',', '.') }} Kz</td>
                
                    @php
                        $total_multa += $item->multa ?? 0;
                        $total_preco += $item->preco_unitario ?? 0;
                        $total += ($item->multa ?? 0) + ($item->preco_unitario ?? 0);
                    @endphp
                </tr>
                @endforeach

            </tbody>
            
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>TOTAL:</th>
                    <th style="text-align: right">{{ number_format($total_multa ?? 0, 2, ',', '.') }} Kz</th>
                    <th style="text-align: right">{{ number_format($total_preco ?? 0, 2, ',', '.') }} Kz</th>
                    <th style="text-align: right">{{ number_format($total ?? 0, 2, ',', '.') }} Kz</th>
                </tr>
            </tfoot>
        </table>
    </div>

</body>

</html>
