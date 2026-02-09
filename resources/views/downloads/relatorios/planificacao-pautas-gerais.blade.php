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
            font-size: 9px;
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
            border: 1px solid #131313;
            font-size: 8px;
        }

        th,
        td {
            padding: 2px;
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
        <img src="{{ $logotipo }}" alt="Logotipo"><br>
        <div class="empresa">REPÚBLICA DE ANGOLA </div>
        <div class="empresa">GOVERNO PROVINCIALDE LUANDA</div>
        <div class="empresa" style="font-size: 12pt;text-transform: uppercase;">{{ $escola->nome }}</div>
        <div class="titulo">{{ $titulo }}</div>
    </div>
    
    @if ($disciplinas)
        <table style="width: 100%;">
            <thead style="background-color: #006699;text-align: left;">
                <tr>
                    <th colspan="{{ count($disciplinas) * 4 + 6 }}">{{ $turma->classe->classes }} </th>
                </tr>
                <tr>
                    <th colspan="{{ count($disciplinas) * 4 + 6 }}">CURSO: {{ $turma->curso->curso }}</th>
                </tr>
                <tr>
                    <th colspan="5">TURMA: {{ $turma->turma }}</th>
                    @foreach ($disciplinas as $disciplina)
                        <th colspan="4" style="text-align: center"> {{ $disciplina->disciplina->abreviacao }}</th>
                    @endforeach
                    <th rowspan="3" style="text-align: center"> Resultados</th>
                </tr>
        
                <tr>
                    <th rowspan="2">Nº</th>
                    <th rowspan="2">Nome Completo</th>
                    <th style="text-align: center" rowspan="2"><span>Processo</span></th>
                    <th style="text-align: center" rowspan="2"><span>Sexo</span></th>
                    <th style="text-align: center" rowspan="2"><span>Nascimento</span></th>
                    @foreach ($disciplinas as $disciplina)
                        <th colspan="4" style="text-align: center">{{ $turma->classe->classes }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($disciplinas as $disciplina)
                        <th>
                            <span class="">MTI</span>
                        </th>
                        <th>
                            <span class="">MTII</span>
                        </th>
                        <th>
                            <span class="">MTIII</span>
                        </th>
                        <th>
                            <span class="">MFD</span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $contador = 0;
                @endphp
                @foreach ($estudantes as $key => $estudante)
                    @php
                        $contador++;
                    @endphp
                    <tr {{ $contador % 2 == 0 ? 'style=background-color:#ffffff' : 'style=background-color:#ebebeb' }}>
                        <td style="text-align: center;">{{ $contador }}</td>
                        <td>{{ $estudante->estudante->nome }} {{ $estudante->estudante->sobre_nome }}</td>
                        <td style="text-align: center;">{{ $estudante->estudante->numero_processo }}</td>
                        <td style="text-align: center;">{{ $estudante->estudante->sigla_genero($estudante->estudante->genero) }}</td>
                        <td style="text-align: center;">{{ $estudante->estudante->nascimento }}</td>
                        @php
                            $soma_mfd = 0;
                            $total_disciplina = count($disciplinas);
                        @endphp
                        
                        @foreach ($disciplinas as $disciplina)
                            @php
                                $notas_t_1 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->disciplina->id, $trimestre1->id, $ano_lectivo->id);
                                $notas_t_2 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->disciplina->id, $trimestre2->id, $ano_lectivo->id);
                                $notas_t_3 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->disciplina->id, $trimestre3->id, $ano_lectivo->id);
                                $notas_t_4 = $estudante->estudante->getNotasPorTurmaDisciplinaTrimestreAno($turma->id, $disciplina->disciplina->id, $trimestre4->id, $ano_lectivo->id);
                                $soma_mfd += $notas_t_4->mfd ?? 0;
                            @endphp
                            @if ($notas_t_1)
                            <td style="text-align: center;"><strong>{{ $notas_t_1->arredondar($notas_t_1->mt1 ?? 0) }}</strong></td>
                            <td style="text-align: center;"><strong>{{ $notas_t_2->arredondar($notas_t_2->mt2 ?? 0) }}</strong></td>
                            <td style="text-align: center;"><strong>{{ $notas_t_3->arredondar($notas_t_3->mt3 ?? 0) }}</strong></td>
                            <td style="text-align: center;"><strong>{{ $notas_t_4->arredondar($notas_t_4->mfd ?? 0) }}</strong></td>
                            @else
                            <td colspan="4" style="text-align: center;color: red;text-decoration: line-through">PLANO CURRICULAR</td>
                            @endif
                        @endforeach
                        
                        <td style="text-align: center;">
                            @if ( ($soma_mfd / $total_disciplina) < $turma->classe->tipo_avaliacao_nota)
                                <span style="color: red;">N/TRANSITA</span>
                            @else
                                <span style="color: blue;">TRANSITA</span>
                            @endif
                        </td>
                    
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
        
    <div style="width: 100%;text-align: center;margin-top: 50px;">
        <div style="width: 50%;float: right;">
            <h5>O COORDENADOR DA ÁREA DE FORMÇÃO</h5>
            <p>_________________________________________________</p>
        </div>
        <div style="width: 50%;float: right;">
            <h5>DIRECTOR(A)  DE TURMA</h5>
            <p>_________________________________________________</p>
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
