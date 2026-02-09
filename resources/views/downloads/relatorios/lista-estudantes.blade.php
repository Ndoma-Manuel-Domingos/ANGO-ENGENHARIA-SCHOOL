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
                <th colspan="2">ESTADOS:</th>
                <th colspan="7">{{ $filtros['status'] ? $filtros['status'] : "TODOS" }}</th>
            </tr>
            <tr>
                <th colspan="2">GENEROS:</th>
                <th colspan="7">{{ $filtros['genero'] ? $filtros['genero'] : "TODOS" }}</th>
            </tr>
            <tr>
                <th colspan="2">CURSOS:</th>
                <th colspan="7">{{ $curso ? $curso->curso : "TODOS" }}</th>
            </tr>
            <tr>
                <th colspan="2">CLASSES:</th>
                <th colspan="7">{{ $classe ? $classe->classes : "TODOS" }}</th>
            </tr>
            <tr>
                <th colspan="2">TURNOS:</th>
                <th colspan="7">{{ $turno ? $turno->turno : "TODOS" }}</th>
            </tr>

            <tr>
                <th colspan="2">TOTAL REGISTROS:</th>
                <th colspan="7">{{ count($matriculas) }}</th>
            </tr>

            <tr>
                <th>Nº</th>
                <th>Precesso</th>
                <th>Nome</th>
                <th>Sexo</th>
                <th>Bilhete</th>
                <th>Classe</th>
                <th>Curso</th>
                <th>Turno</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matriculas as $key => $item)
            <tr>
                <td>{{ $key + 1}}</td>
                <td>{{ $item->estudante->numero_processo ?? 'sem - processo' }}</td>
                <td>{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                @if($item->estudante->genero == "Masculino")
                <td>M</td>
                @else
                <td>F</td>
                @endif
                <td>{{ $item->estudante->bilheite }}</td>
                <td>{{ $item->classe->classes }}</td>
                <td>{{ $item->curso->curso }}</td>
                <td>{{ $item->turno->turno }}</td>
                @if ($item->status_matricula == 'confirmado')
                <td>Confirmado</td>
                @endif
                @if ($item->status_matricula == 'desistente')
                <td>Desistente</td>
                @endif

                @if ($item->status_matricula == 'nao_confirmado')
                <td>Não Confirmado</td>
                @endif

                @if ($item->status_matricula == 'inactiva')
                <td>Inactivo</td>
                @endif

                @if ($item->status_matricula == 'rejeitado')
                <td>Rejeitada</td>
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
