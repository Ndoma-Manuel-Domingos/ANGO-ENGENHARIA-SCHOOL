<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 2cm 1.5cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 20px;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }

        .header img {
            height: 60px;
            margin-bottom: 10px;
        }

        .empresa {
            font-weight: bold;
            font-size: 14px;
        }

        .titulo {
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            page-break-inside: auto;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .footer {
            position: fixed;
            bottom: 5px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
            color: #777;
        }

        .pagenum:before {
            content: counter(page);
        }

    </style>
</head>
<body>

    <div class="header">
        <img src="{{ $logotipo }}" alt="Logotipo"><br>
        <div class="empresa">{{ $escola->nome }}</div>
        <div>{{ $escola->endereco ?? "" }} | NIF: {{ $escola->documento ?? "" }}</div>
        <div class="titulo">{{ $titulo }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th colspan="4">TOTAL REGISTROS:</th>
                <th colspan="6">{{ count($matriculas) }}</th>
            </tr>

            <tr>
                <th colspan="4">Estudantes:
                    @if ($status == 'Admitido')
                    <span style="color: green">{{ $status }}s(as)</span>
                    @else
                    @if ($status == 'Nao Admitido')
                    <span style="color: red">{{ $status }}s(as)</span>
                    @else
                    <span>Todos</span>
                    @endif
                    @endif
                </th>
                <th colspan="3">
                    COM MÉDIA: {{ $media > 0 ? $media : 'TODAS' }}
                </th>
                <th colspan="3">
                    COM IDADE: {{ $idade ?? 'TODAS' }}
                </th>
            </tr>

            <tr>
                <th colspan="4">CURSO: {{ $curso ? $curso->curso : 'TODOS' }}</th>
                <th colspan="3">CLASSE: {{ $classe ? $classe->classes : 'TODAS' }}</th>
                <th colspan="3">TURNO: {{ $turno ? $turno->turno : 'TODOS' }}</th>
            </tr>

            <tr>
                <th>Nº</th>
                <th>Nome</th>
                <th>Sexo</th>
                <th>Idade</th>
                <th>Bilhete</th>
                <th>Classe</th>
                <th>Curso</th>
                <th>Turno</th>
                <th>Média</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matriculas as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                @if($item->estudante->genero == "Masculino")
                <td>M</td>
                @else
                <td>F</td>
                @endif
                <td>{{ $item->estudante->idade($item->estudante->nascimento) }}</td>
                <td>{{ $item->estudante->bilheite }}</td>
                <td>{{ $item->classe->classes }}</td>
                <td>{{ $item->curso->curso }}</td>
                <td>{{ $item->turno->turno }}</td>
                <td>{{ $item->media }}</td>
                @if ($item->status_inscricao == 'Admitido')
                <td class="text-warning" style="color: green">Adimitido(a)</td>
                @endif
                @if ($item->status_inscricao == 'Nao Admitido')
                <td class="text-danger" style="color: red">Não Adimitido(a)</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Rodapé com paginação -->
    <div class="footer">
        <span class="pagenum"></span>
        <script type="text/php">
            if (isset($pdf)) {
      $pdf->page_script(function ($pageNumber, $pageCount, $pdf) {
          $pdf->text(270, 820, "Página $pageNumber de $pageCount", null, 10);
      });
    }
  </script>
    </div>

</body>
</html>
