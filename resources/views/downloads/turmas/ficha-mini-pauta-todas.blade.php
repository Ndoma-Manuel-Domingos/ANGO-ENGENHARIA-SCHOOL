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
            <div class="">
                <div class="logo">
                    <img src="{{ $logotipo ?? 'assets/images/insigna.png' }}" alt="" style="text-align: center;height: 60px;width: 60px;">
                </div>
                <h5><strong>República de Angola</strong></h5>
                <h5><strong>Governo Provincial de Luanda</strong></h5>
                <h5><strong>{{ $escola->nome }}</strong></h5>
                <h3 style="color: red;"><strong>PAUTA - TODOS DISCIPLINAS</strong></h3>
            </div>
        </div>
    </div>

    <div class="conteudo">
        <table>
            @if ( isset($turma) AND isset($trimestre))
            <thead>
                @if ($disciplinasTurma)
                <tr>
                    <th colspan="7">TURMA: {{ $turma->turma }}</th>
                    <th colspan="8">PERÍODO: {{ $trimestre->trimestre }}</th>
                    <th colspan="8">ANO LECTIVO: {{ $anoLectivo->ano }}</th>
                    <th colspan="8">TURNO: {{ $turma->turno->turno }}</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    @foreach ($disciplinasTurma as $itemDisciplina)
                        @if ($turma->curso->tipo === "Outros")
                            <th colspan="3" style="text-align: center;"> {{ $itemDisciplina->disciplina->abreviacao }}</th>
                        @else
                            <th colspan="4" style="text-align: center;"> {{ $itemDisciplina->disciplina->abreviacao }}</th>
                        @endif
                    @endforeach
                </tr>

                <tr>
                    <th>Nº</th>
                    <th>Nome Completo</th>
                    <th>Sexo</th>
                    @foreach ($disciplinasTurma as $itemDisciplina)
                        
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
                    
                    <th style="text-align: center;background-color: rgba(0,0,0,.1)">MT</th>
                    @endforeach
                </tr>
                @endif
            </thead>
            <body>

                @if ($estudantesTurma)
                @foreach ($estudantesTurma as $itemEstudante)
                <tr>
                    <td>{{ $itemEstudante->estudante->numero_processo }}</td>
                    <td>{{ $itemEstudante->estudante->nome }} {{ $itemEstudante->estudante->sobre_nome }}</td>
                    <td>{{ $itemEstudante->estudante->sigla_genero($itemEstudante->estudante->genero) }}</td>
                    
                    @foreach ($disciplinasTurma as $itemDisciplina)
                        @php
                        $notas = (new App\Models\web\turmas\NotaPauta)::where('disciplinas_id', $itemDisciplina->disciplinas_id)
                            ->where('estudantes_id', $itemEstudante->estudantes_id)
                            ->where('controlo_trimestres_id', $trimestre->id)
                            ->where('turmas_id', $turma->id)
                            // ->where('ano_lectivos_id', $anoLectivo->id)
                        ->get();
                        @endphp
                        
                        @if ($notas)
                            @foreach ($notas as $itemNota)
                                @if ($turma->curso->tipo === "Técnico")
                                    <td style="text-align: center;">{{ $itemNota->mac }}</td>
                                    <td style="text-align: center;">{{ $itemNota->npp }}</td>
                                    <td style="text-align: center;">{{ $itemNota->npt }}</td>
                                @endif
                                
                                @if ($turma->curso->tipo === "Punível")
                                    <td style="text-align: center;">{{ $itemNota->mac }}</td>
                                    <td style="text-align: center;">{{ $itemNota->npp }}</td>
                                    <td style="text-align: center;">{{ $itemNota->npt }}</td>
                                @endif
                                
                                @if ($turma->curso->tipo === "Outros")
                                    <td style="text-align: center;">{{ $itemNota->mac }}</td>
                                    <td style="text-align: center;">{{ $itemNota->npt }}</td>
                                @endif
                                                        
                                @if ($itemNota->mt >= ($turma->classe->tipo_avaliacao_nota / 2))
                                    <td style="text-align: center;color: #006699;">{{ round($itemNota->mt) }}</td>
                                @else
                                    <td style="text-align: center;color: #ff0000;">{{ round($itemNota->mt) }}</td>
                                @endif
                                
                            @endforeach
                        @endif
                    @endforeach
                </tr>
                @endforeach
                @endif
            </body>
        </table>
    </div>


    <div class="footer">
        <div style="width: 100%;text-align: center;">
            <div style="width: 50%;float: left;">
                <h5>O (A) DIRECTOR DA TURMA</h5>
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
    @endif
</body>
</html>
