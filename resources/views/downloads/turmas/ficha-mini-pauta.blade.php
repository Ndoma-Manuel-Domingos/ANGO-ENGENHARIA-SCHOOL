<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $titulo }}</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 2cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 96%;
            margin: 0 auto;
            margin: 20px;
        }

        .header {
            text-align: center;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header img {
            height: 60px;
        }

        .empresa {
            font-weight: bold;
            font-size: 14px;
        }

        .titulo {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 30px 0 20px;
            text-transform: uppercase;
        }

        .conteudo {
            text-align: justify;
            line-height: 1.7;
        }

        .notas-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .notas-table th,
        .notas-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .notas-table th {
            background-color: #f0f0f0;
        }

        .assinatura {
            margin-top: 60px;
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }


        table {
            width: 95%;
            margin: 0 auto;
            text-align: left;
            border-spacing: 0;
            margin-bottom: 10px;
            /* border: 1px solid rgb(0, 0, 0); */
            font-size: 12pt;
        }

        thead {
            background-color: #fdfdfd;
            font-size: 12px;
        }

        th,
        td {
            padding: 2px;
            font-size: 12px;
            text-align: left;
            border: 1px solid #777;
        }

    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <div class="col-sm-12 col-md-12 invoice-col text-center">
                <div class="logo">
                    <img src="{{ $logotipo ?? 'assets/images/insigna.png' }}" alt="" style="text-align: center;height: 60px;width: 60px;">
                </div>
                <h5><strong>República de Angola</strong></h5>
                <h5><strong>Governo Provincial de Luanda</strong></h5>
                <h5><strong>{{ $escola->nome }}</strong></h5>
                <h3 style="color: red;"><strong>PAUTA </strong></h3>
            </div>
        </div>
    </div>

    <div class="conteudo">
        <table>
            <thead>
                <tr>
                    <th colspan="5" style="text-transform: uppercase">TURMA: {{ $turma->turma }}</th>
                    <th colspan="6" style="text-transform: uppercase">DISCIPLINA: {{ $disciplina->disciplina }}</th>
                </tr>
                <tr>
                    <th colspan="3" style="text-transform: uppercase">PERÍODO: {{ $trimestre->trimestre }}</th>
                    <th colspan="2" style="text-transform: uppercase">ANO LECTIVO: {{ $anoLectivo->ano }}</th>
                    <th colspan="3" style="text-transform: uppercase">TURNO: {{ $turma->turno->turno }}</th>
                    <th colspan="3" style="text-transform: uppercase">SALA: {{ $turma->classe->classes }}</th>
                </tr>
                <tr>
                    <th>Nº</th>
                    <th>Nº Processo</th>
                    <th>Nome Completo</th>
                    <th style="text-align: center;">Genero</th>
                    <th style="text-align: center;">Data Nascimento</th>
                    <th style="text-align: center;">Idade</th>
                    @if ($turma->curso->tipo === "Técnico")
                        <th style="text-align: center;">MAC</th>
                        <th style="text-align: center;">NPP</th>
                        <th style="text-align: center;">NPT</th>
                    @endif
                    
                    @if ($turma->curso->tipo === "Punível")
                        <th style="text-align: center;">P1</th>
                        <th style="text-align: center;">P2</th>
                        <th style="text-align: center;">PT</th>
                    @endif
                    
                    @if ($turma->curso->tipo === "Outros")
                        <th style="text-align: center;">MAC</th>
                        <th style="text-align: center;">NPT</th>
                    @endif
                    
                    <th>MT</th>

                    <th>Observação</th>
                </tr>
            </thead>

            <tbody>
                @php $contador = 0; @endphp
                @if ($notas)
                    @foreach ($notas as $item)
                    @php $contador++; @endphp
                    <tr>
                        <td>{{ $contador }}</td>
                        <td>{{ $item->estudante->numero_processo }}</td>
                        <td width="400px">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                        <td style="text-align: center;">{{ $item->estudante->sigla_genero($item->estudante->genero) }}</td>
                        <td style="text-align: center;">{{ $item->estudante->nascimento }}</td>
                        <td style="text-align: center;">{{ $item->estudante->idade($item->estudante->nascimento) }}</td>
                        
                        @if ($turma->curso->tipo === "Técnico")
                            <td style="text-align: center;">{{ $item->mac }}</td>
                            <td style="text-align: center;">{{ $item->npp }}</td>
                            <td style="text-align: center;">{{ $item->npt }}</td>
                        @endif
                        
                        @if ($turma->curso->tipo === "Punível")
                            <td style="text-align: center;">{{ $item->mac }}</td>
                            <td style="text-align: center;">{{ $item->npp }}</td>
                            <td style="text-align: center;">{{ $item->npt }}</td>
                        @endif
                        
                        @if ($turma->curso->tipo === "Outros")
                            <td style="text-align: center;">{{ $item->mac }}</td>
                            <td style="text-align: center;">{{ $item->npt }}</td>
                        @endif
                        
                        
                        @if ($item->controlo_trimestres_id == $trimestre1->id)
                            @if ($item->mt >= ($turma->classe->tipo_avaliacao_nota / 2))
                                <td style="text-align: center;color: #006699;">{{ $item->mt }}</td>
                                <td style="text-align: center;color: #006699;">TRANSITA</td>
                            @else
                                <td style="text-align: center;color: #ff0000;">{{ $item->mt }}</td>
                                <td style="text-align: center;color: #ff0000;">N/TRANSITA</td>
                            @endif
                        @else

                            @if ($item->controlo_trimestres_id == $trimestre2->id)
                                @if ($item->mt >= ($turma->classe->tipo_avaliacao_nota / 2))
                                    <td style="text-align: center;color: #006699;">{{ $item->mt }}</td>
                                    <td style="text-align: center;color: #006699;">TRANSITA</td>
                                @else
                                    <td style="text-align: center;color: #ff0000;">{{ $item->mt }}</td>
                                    <td style="text-align: center;color: #ff0000;">N/TRANSITA</td>
                                @endif
                              
                        @else
                            @if ($item->controlo_trimestres_id == $trimestre3->id)
                                @if ($item->mt >= ($turma->classe->tipo_avaliacao_nota / 2))
                                    <td style="text-align: center;color: #006699;">{{ $item->mt }}</td>
                                    <td style="text-align: center;color: #006699;">TRANSITA</td>
                                @else
                                    <td style="text-align: center;color: #ff0000;">{{ $item->mt }}</td>
                                    <td style="text-align: center;color: #ff0000;">N/TRANSITA</td>
                                @endif
                                @endif
                            @endif
                        @endif
                        
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div style="width: 100%;text-align: center;">
            <div style="width: 50%;float: left;">
                <h5>O (A) PROFESSOR DA DISCIPLINA</h5>
                <p>_________________________________________________</p>
                <p>__________/____________/______________</p>
            </div>

            <div style="width: 50%;float: right;">
                <h5>O SUBDIRECTOR PEDAGOGICO</h5>
                <p>_________________________________________________</p>
                <p>__________/____________/______________</p>
            </div>
        </div>
    </div>


</body>
</html>
