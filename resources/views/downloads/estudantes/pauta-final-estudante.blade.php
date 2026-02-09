<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
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
            width: 100%;
            max-width: 18cm;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #000;
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

    </style>
</head>
<body>

    <div class="container">
        <!-- Cabeçalho -->
        <div class="header">
            <img src="{{ $logotipo }}" alt="Logotipo"><br>
            <div class="empresa">REPÚBLICA DE ANGOLA</div>
            <div class="empresa">MINISTERIO DE EDUCAÇÃO</div>
            <div class="empresa">GOVERNO PROVINCIAL DE LUANDA</div>
            <div class="empresa">{{ $escola->nome }}</div>
        </div>

        @if ($condicao == "trimestre1" || $condicao == "trimestre2" || $condicao == "trimestre3" || $condicao == "classificacao-final")
        <div class="titulo">
            BOLETIN DE NOTAS DO ESTUDANTE
        </div>
        <!-- Conteúdo -->
        <div class="conteudo">
            <p>Nome: <strong>{{ $estudantes->nome}} {{ $estudantes->sobre_nome }}</strong> ,
                Turma: <strong>{{ $turma->turma }}</strong> ,
                classe: <strong>{{ $classe->classes }}</strong> ,
                Sala <strong>{{ $sala->salas }}</strong> ,
                Turno: <strong>{{ $turno->turno }} </strong>,
                Período: <strong> {{ $condicao == "trimestre1" ? "Iº Trimestre" : ($condicao == "trimestre2" ? "IIº Trimestre" : ($condicao == "trimestre3" ? "IIIº Trimestre" : "PAUTA GERAL DO ESTUDANTE" )) }} </strong>,
                Ano Lectivo: <strong>{{ $anoLectivo->ano }}</strong>.
            </p>
        </div>

        @if ($condicao == "trimestre1")
        @php
        $notas1 = (new App\Models\web\turmas\NotaPauta)::where('controlo_trimestres_id', $trimestre1->id)
        ->where('estudantes_id', $estudantes->id)
        ->where('ano_lectivos_id', $anoLectivo->id)
        ->with(['disciplina'])
        ->get();
        @endphp



        @if ($turma->notas($estudantes->id, $anoLectivo->id, $trimestre1->id))
        <table style="margin-top: 10px;width: 100%;border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align: left;border: 1px solid #000;padding: 2px">Disciplinas</th>
                    <th style="text-align: center;border: 1px solid #000;padding: 2px">MAC</th>
                    {{-- <th style="text-align: center;border: 1px solid #000;padding: 2px">NPP</th> --}}
                    <th style="text-align: center;border: 1px solid #000;padding: 2px">NPT</th>
                    <th style="text-align: center;border: 1px solid #000;padding: 2px">MAT</th>
                    <th style="text-align: left;border: 1px solid #000;padding: 2px">Observação</th>
                </tr>
            </thead>

            <tbody style="border-bottom: 1px solid #000;">
                @foreach ($turma->notas($estudantes->id, $anoLectivo->id, $trimestre1->id) as $item)
                <tr>
                    <td>{{ $item->disciplina->disciplina }}</td>
                    <td style="text-align: center">{{ round($item->mac) }}</td>
                    {{-- <td style="text-align: center">{{ round($item->npp) }}</td> --}}
                    <td style="text-align: center">{{ round($item->npt) }}</td>
                    <td style="text-align: center">{{ round($item->mt) }}</td>
                    @if ($item->mt >= ($classe->tipo_avaliacao_nota / 2))
                    <td>{{ round($item->mt) }}</td>
                    @else
                    <td>{{ round($item->mt) }}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        @endif

        @if ($condicao == "trimestre2")
        @php
        $notas1 = (new App\Models\web\turmas\NotaPauta)::where('controlo_trimestres_id', $trimestre2->id)
        ->where('estudantes_id', $estudantes->id)
        ->where('ano_lectivos_id', $anoLectivo->id)
        ->with(['disciplina'])
        ->get();
        @endphp

        @if ($turma->notas($estudantes->id, $anoLectivo->id, $trimestre2->id))
        <table style="margin-top: 10px;width: 100%;border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align: left;border: 1px solid #000;padding: 2px">Disciplinas</th>
                    <th style="text-align: center;border: 1px solid #000;padding: 2px">MAC</th>
                    {{-- <th style="text-align: center;border: 1px solid #000;padding: 2px">NPP</th> --}}
                    <th style="text-align: center;border: 1px solid #000;padding: 2px">NPT</th>
                    <th style="text-align: center;border: 1px solid #000;padding: 2px">MAT</th>
                    <th style="text-align: left;border: 1px solid #000;padding: 2px">Observação</th>
                </tr>
            </thead>

            <tbody style="border-bottom: 1px solid #000">
                @foreach ($turma->notas($estudantes->id, $anoLectivo->id, $trimestre2->id) as $item)
                <tr>
                    <td>{{ $item->disciplina->disciplina }}</td>
                    <td style="text-align: center;">{{ round($item->mac) }}</td>
                    {{-- <td style="text-align: center;">{{ round($item->npp) }}</td> --}}
                    <td style="text-align: center;">{{ round($item->npt) }}</td>
                    <td style="text-align: center;">{{ round($item->mt) }}</td>
                    @if ($item->mt >= ($classe->tipo_avaliacao_nota / 2))
                    <td>{{ round($item->mt) }}</td>
                    @else
                    <td>{{ round($item->mt) }}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        @endif

        @if ($condicao == "trimestre3")
        @php
        $notas1 = (new App\Models\web\turmas\NotaPauta)::where('controlo_trimestres_id', $trimestre3->id)
        ->where('estudantes_id', $estudantes->id)
        ->where('ano_lectivos_id', $anoLectivo->id)
        ->with(['disciplina'])
        ->get();
        @endphp
        @if ($turma->notas($estudantes->id, $anoLectivo->id, $trimestre3->id))
        <table style="margin-top: 10px;width: 100%;border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="text-align: left;border: 1px solid #000;padding: 2px">Disciplinas</th>
                    <th style="text-align: left;border: 1px solid #000;padding: 2px">MAC</th>
                    {{-- <th style="text-align: left;border: 1px solid #000;padding: 2px">NPP</th> --}}
                    <th style="text-align: left;border: 1px solid #000;padding: 2px">NPT</th>
                    <th style="text-align: left;border: 1px solid #000;padding: 2px">MAT</th>
                    <th style="text-align: left;border: 1px solid #000;padding: 2px">Observação</th>
                </tr>
            </thead>

            <tbody style="border-bottom: 1px solid #000">
                @foreach ($turma->notas($estudantes->id, $anoLectivo->id, $trimestre3->id) as $item)
                <tr>
                    <td>{{ $item->disciplina->disciplina }}</td>
                    <td>{{ round($item->mac) }}</td>
                    {{-- <td>{{ round($item->npp) }}</td> --}}
                    <td>{{ round($item->npt) }}</td>
                    <td>{{ round($item->mt) }}</td>
                    @if ($item->mt >= ($classe->tipo_avaliacao_nota / 2))
                    <td>{{ round($item->mt) }}</td>
                    @else
                    <td>{{ round($item->mt) }}</td>
                    @endif
                </tr>
                @endforeach

            </tbody>
        </table>
        @endif
        @endif

        @if ($condicao == "classificacao-final")
        @php
        $notas1 = (new App\Models\web\turmas\NotaPauta)::where('controlo_trimestres_id', $trimestre4->id)
        ->where('estudantes_id', $estudantes->id)
        ->where('ano_lectivos_id', $anoLectivo->id)
        ->with(['disciplina'])
        ->get();
        @endphp
        @if ($turma->notas($estudantes->id, $anoLectivo->id, $trimestre4->id))
        <table style="width: 100%;border-collapse: collapse">
            <thead>
                <tr>
                    <th style="text-align: left;border: 1px solid #000;padding: 2px">Disciplinas</th>
                    <th style="text-align: left;border: 1px solid #000;padding: 2px">MFD</th>
                </tr>
            </thead>

            <tbody style="border-bottom: 1px solid #000">
                @foreach ($turma->notas($estudantes->id, $anoLectivo->id, $trimestre4->id) as $item)
                <tr>
                    <td>{{ $item->disciplina->disciplina }}</td>
                    <td>{{ $item->mfd }}</td>
                </tr>
                @endforeach
            </tbody>

            <tfoot style="border-bottom: 1px solid #000">
                <tr>
                    <td>RESULTADO FINAL <br>
                        @if (($somaMFD / $totalDisciplinas) >= ($classe->tipo_avaliacao_nota / 2))
                        <span>Transita</span>
                        @else
                        <span>N/Transita</span>
                        @endif
                    </td>
                    <td>MÉDIA ANUAL <br> ({{ $somaMFD / $totalDisciplinas }})</td>
                </tr>
            </tfoot>
        </table>
        @endif
        @endif

        @if ($condicao != "declarcao-sem-nota" && $condicao != "declaracao-nota" && $condicao != "trimestre1" && $condicao != "trimestre2" && $condicao != "trimestre3" && $condicao != "classificacao-final")
        <table style="width: 100%;border-collapse: collapse;margin-top: 20px">
            <thead>
                <tr>
                    <th style="text-align: left;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px" rowspan="2">Disciplina</th>
                    <th style="text-align: left;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px" colspan="3" class="text-center">Iª Trimestre</th>
                    <th style="text-align: left;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px" colspan="3" class="text-center">IIª Trimestre</th>
                    <th style="text-align: left;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px" colspan="3" class="text-center">IIIª Trimestre</th>
                    <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px" rowspan="2">MDF</th>
                    <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px" rowspan="2">Obsevação</th>
                </tr>

                <tr>
                    <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px">MAC</th>
                    {{-- <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px">NPP</th> --}}
                    <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px">NPT</th>
                    <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px" class="text-info">MT</th>

                    <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px">MAC</th>
                    {{-- <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px">NPP</th> --}}
                    <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px">NPT</th>
                    <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px" class="text-info">MT</th>

                    <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px">MAC</th>
                    {{-- <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px">NPP</th> --}}
                    <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px">NPT</th>
                    <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;padding: 5px" class="text-info">MT</th>

                </tr>
            </thead>

            <tbody style="">
                @if ($turmaDisciplinas)
                @foreach($turmaDisciplinas as $item)

                @foreach ($turma->notas($estudantes->id, $anoLectivo->id, $trimestre1->id, $item->id) as $item1)
                @foreach ($turma->notas($estudantes->id, $anoLectivo->id, $trimestre2->id, $item->id) as $item2)
                @foreach ($turma->notas($estudantes->id, $anoLectivo->id, $trimestre3->id, $item->id) as $item3)
                @foreach ($turma->notas($estudantes->id, $anoLectivo->id, $trimestre4->id, $item->id) as $item4)
                <tr>
                    <td>{{ $item1->disciplina->disciplina }}</td>

                    <td>{{ round($item1->mac) }}</td>
                    {{-- <td>{{ round($item1->npp) }}</td> --}}
                    <td>{{ round($item1->npt) }}</td>
                    <td>{{ round($item1->mt) }}</td>

                    <td>{{ round($item2->mac) }}</td>
                    {{-- <td>{{ round($item2->npp) }}</td> --}}
                    <td>{{ round($item2->npt) }}</td>
                    <td>{{ round($item2->mt) }}</td>

                    <td>{{ round($item3->mac) }}</td>
                    {{-- <td>{{ round($item3->npp)}}</td> --}}
                    <td>{{ round($item3->npt) }}</td>
                    <td>{{ round($item3->mt) }}</td>

                    <td>{{ round($item4->mfd) }}</td>
                    @if ($item4->mfd >= ($classe->tipo_avaliacao_nota / 2))
                    <td>Transita</td>
                    @else
                    <td>N/Transita</td>
                    @endif
                </tr>
                @endforeach
                @endforeach
                @endforeach
                @endforeach
                @endforeach
                @endif
            </tbody>

            <tfoot>
                <th colspan="11" style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;" class="text-danger">Media Final CA <br> ({{ ($somaMFD /  $totalDisciplinas) }})</th>
                <th style="text-align: center;border: 1px solid #949494;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;" class="text-danger">Resultado Final <br>
                    (@if (($somaMFD / $totalDisciplinas) >= ($classe->tipo_avaliacao_nota / 2))
                    <span style="text-align: center;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;" class="text-danger">Aprovado</span>
                    @else
                    <span style="text-align: center;font-family: Arial, Helvetica, sans-serif;font-size: 9pt;" class="text-danger">Reprovado</span>
                    @endif)
                </th>
            </tfoot>
        </table>
        @endif

        @endif


        @if ($condicao == "declarcao-sem-nota" || $condicao == "declaracao-nota")
        <!-- Título -->
        <div class="titulo">
            {{ $condicao == "declarcao-sem-nota"  ? "DECLARAÇÃO" : ($condicao == "declaracao-nota" ? "DECLARAÇÃO COM NOTAS" : "")  }}
        </div>
        <!-- Conteúdo -->
        <div class="conteudo">
            <p>
                A Direção da <strong>{{ $escola->nome }}</strong>, criado sob o Decreto Executivo nº {{ $escola->decreto }}, declara para os devidos efeitos que o(a) estudante
                <strong>{{ $estudantes->nome }} {{ $estudantes->sobre_nome }}</strong>, filho(a) de {{ $estudantes->pai}} e de {{ $estudantes->mae}}, nascido(a) aos {{ date("d", strtotime($estudantes->nascimento)) }} de {{ $estudantes->descricao_mes(date("M", strtotime($estudantes->nascimento))) }} de {{ date("Y", strtotime($estudantes->nascimento)) }}, natural de {{ $estudantes->naturalidade }}, Município da(o) {{ $estudantes->municipio->nome }}, Província de {{ $estudantes->provincia->nome }},
                portador do B.I. nº <strong>{{ $estudantes->bilheite }}</strong>, Emitido pela Direcção Nacional de Identicação em Luanda @if ($estudantes->data_emissao_documento)
                {{ date("d", strtotime($estudantes->data_emissao_documento)) }} de {{ $estudantes->descricao_mes(date("M", strtotime($estudantes->data_emissao_documento))) }} de {{ date("Y", strtotime($estudantes->data_emissao_documento)) }}.
                @else
                , aos 03 de Dezembro de 2018.
                @endif
            </p>

            <p style="margin-top: 5px">Frequenta nesta Instituição de Ensino <strong>({{ $escola->nome }})</strong>, no Ano Lectivo de {{ $anoLectivo->ano }}, na Turma {{ $turma->turma }}, Sala {{ $sala->salas }}, sob o nº do processo <strong style="font-family: Arial, Helvetica, sans-serif">{{ $estudantes->numero_processo }}</strong>, a <strong>{{ $classe->classes }}</strong>.</p>

            @if ($condicao == "declaracao-nota")
            @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
            <table style="border: none;width: 100%;margin: 0 auto">
                <tbody>
                    @foreach ($notas as $item)
                    <tr>
                        <td style="text-align: center;padding: 2px">{{ $item->disciplina->disciplina }}</td>
                        @if ($item->resultado_final >= 0 && $item->resultado_final <=9) <td style="text-align: center;padding: 2px">{{ round($item->resultado_final) }} - ({{ $item->valor_por_extenso(round($item->resultado_final)) }})</td>
                            @else
                            <td style="text-align: center;padding: 2px">{{ round($item->resultado_final) }} - ({{ $item->valor_por_extenso(round($item->resultado_final)) }})</td>
                            @endif
                            <td style="text-align: center;padding: 2px">{{ $item->ano->ano }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else

            <table style="margin-top: 0px">
                <thead>
                    <tr>
                        <th style="text-align: left">Nº</th>
                        <th style="text-align: left">DISCIPLINAS</th>
                        <th style="text-align: left">MÉDIAS FINAL</th>
                        <th style="text-align: left">MÉDIA POR EXTENSO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($turma->notas($estudantes->id, $anoLectivo->id, $trimestre4->id) as $key => $item)
                    <tr>
                        <td style="text-align: left;padding: 0px">{{ $key + 1 }}</td>
                        <td style="text-align: left;padding: 0px">{{ $item->disciplina->disciplina }}</td>
                        @if ($item->mfd >= 0 && $item->mfd <=9) <td style="text-align: center;padding: 0px">{{ round($item->mfd) }}</td>
                            @else
                            <td style="text-align: center;padding: 0px">{{ round($item->mfd) }}</td>
                            @endif
                            @if ($item->mfd >= 0 && $item->mfd <=9) <td style="text-align: center;padding: 0px">{{ $item->valor_por_extenso(round($item->mfd)) }}</td>
                                @else
                                <td style="text-align: center;padding: 0px">{{ $item->valor_por_extenso(round($item->mfd)) }}</td>
                                @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            @endif

            <p style="margin-top: 20px">
                <strong style="font-family: Arial, Helvetica, sans-serif">
                    <strong style="color: red;font-family: Arial, Helvetica, sans-serif">Obs.</strong>: Esta Declaração destina-se para efeito de:
                    <span style="color: red;font-family: Arial, Helvetica, sans-serif">{{ $efeito->nome ?? "Prova de Existência do aluno na nossa instituição" }}</span>
                </strong>
            </p>

            <p>Por ser verdade e nos ter solicitado, passou-se a presente Declaração que vai assinada e autenticada com o carimbo à óleo em uso nesta Instituição de Ensino.</p>


        </div>
        @endif


        <!-- Assinatura -->
        <div class="assinatura">
            Luanda, {{ now()->format('d/m/Y') }}<br><br>
            _________________________________________<br>
            <strong>Direção da Instituição</strong><br>
            (Assinatura e carimbo)
        </div>

        <!-- Rodapé -->
        <div class="footer">
            Documento gerado automaticamente em {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

</body>
</html>
