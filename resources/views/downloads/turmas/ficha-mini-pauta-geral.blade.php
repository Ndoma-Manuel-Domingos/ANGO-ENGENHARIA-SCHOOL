<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    @if ( isset($classe) && $classe->tipo == 'Transição')
    <title class="titulo">MINI PAUTA PARA CLASSE DE TRANSIÇÃO</title>
    @else
    @if (isset($classe) && $classe->tipo == 'Exame')
    <title class="titulo">MINI PAUTA PARA CLASSE DE EXAMES</title>
    @else
    <title class="titulo">MINI PAUTA</title>
    @endif
    @endif
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
            font-size: 9px;
            margin: 20px;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            /* border-bottom: 1px solid #000; */
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
            border: 1px solid #131313;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: gradient(to right, #006699, #131313);
            color: #ffffff;
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
        <img src="{{ $logotipo ?? 'assets/images/insigna.png' }}" alt="" style="text-align: center;height: 60px;width: 60px;">
        <br>
        <div class="empresa">REPÚBLICA DE ANGOLA</div>
        <div class="empresa">GOVERNO PROVINCIAL DE LUANDA</div>
        <div class="empresa" style="text-transform: uppercase;">{{ $escola->nome }}</div>
        
        @if ( isset($classe) && $classe->tipo == 'Transição')
            <div class="titulo">MINI PAUTA PARA CLASSE DE TRANSIÇÃO</div>
        @else
            @if (isset($classe) && $classe->tipo == 'Exame')
                <div class="titulo">MINI PAUTA PARA CLASSE DE EXAMES</div>
            @else
                <div class="titulo">MINI PAUTA</div>
            @endif
        @endif

    </div>

    <div class="card">
        <div class="card-body">
            <!-- Table row -->
            @if ( isset($classe) && $classe->tipo == 'Transição')
                @include('admin.require.classe-transicao')
            @else
                @if (isset($classe) && $classe->tipo == 'Exame')
                    @include('admin.require.classe-exames')
                @endif
            @endif
        </div>
    </div>

    <div style="width: 100%;text-align: center;margin-top: 30px">
        <div style="width: 50%;float: left;">
            <h5>O (A) PROFESSOR DA CLASSE</h5>
            <p>_________________________________________________</p>
            <p>__________/____________/______________</p>
        </div>

        <div style="width: 50%;float: right;">
            <h5>O SUBDIRECTOR PEDAGOGICO</h5>
            <p>_________________________________________________</p>
            <p>__________/____________/______________</p>
        </div>
    </div>

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
