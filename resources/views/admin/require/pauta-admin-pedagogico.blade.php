<div class="row">
    <div class="col-12 col-md-12">
        <!-- Main content -->
        <div class="card">
            <div class="card-header">
                <div class="col-sm-12 col-12 text-center">
                    <h1 class="fs-6"><strong>GERAR MINI PAUTA/PAUTA GERAL DO ESTUDANTE</strong></h1>
                </div>

                <div class="col-sm-12 col-12">
                    <table class="table-bordered table-striped ">
                        <thead>
                            <tr>
                                <th><strong>Turma: </strong> <span class="span_turma">
                                        @if (isset($turma))
                                        @php
                                        echo $turma->turma;
                                        @endphp
                                        @else
                                        desc
                                        @endif </span>. &nbsp;
                                </th>
                                <th><strong>Classe: </strong> <span class="span_classe">
                                        @if (isset($classe))
                                        @php
                                        echo $classe->classes;
                                        @endphp
                                        @else
                                        desc
                                        @endif </span>. &nbsp;
                                </th>

                                <th>
                                    <strong>Curso: </strong> <span class="span_curso">
                                        @if (isset($curso))
                                        @php
                                        echo $curso->curso;
                                        @endphp
                                        @else
                                        desc
                                        @endif </span>. &nbsp;
                                </th>
                                <th>
                                    <strong>Turno: </strong> <span class="span_turno">
                                        @if (isset($turno))
                                        @php
                                        echo $turno->turno;
                                        @endphp
                                        @else
                                        desc
                                        @endif </span>. &nbsp;
                                </th>
                                <th>
                                    <strong>Ano Lectivo </strong> <span class="span_ano_lectivo">
                                        @if (isset($anoLectivo))
                                        @php
                                        echo $anoLectivo->ano;
                                        @endphp
                                        @else
                                        desc
                                        @endif </span>.
                                </th>
                                <th>
                                    <strong>Estudante: </strong><span class="span_estudante">
                                        @if (isset($estudantes))
                                        @php
                                        echo "{$estudantes->nome} {$estudantes->sobre_nome}";
                                        @endphp
                                        @else
                                        desc
                                        @endif </span>. &nbsp;
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.col -->
            </div>

            <div class="card-body">
                @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                <div class="col-12 col-md-12">
                    <table style="width: 100%" class="table table-bordered  ">
                        <thead>
                            <tr>
                                <th>Disciplina</th>
                                <th>Semestre</th>
                                <th>P1</th>
                                <th>P2</th>
                                <th>P3</th>
                                <th>P4</th>
                                <th>Média</th>
                                <th>Obs</th>
                                <th>Exame</th>
                                <th>Resultado</th>
                                <th>Recurso</th>
                                <th>Exame Especial</th>
                                <th>NF</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (isset($turmaDisciplinas) && !empty($turmaDisciplinas))
                            @foreach($turmaDisciplinas as $item)
                            @php
                            $notas1 = (new App\Models\web\turmas\NotaPauta)::where('estudantes_id', $estudantes->id)
                            ->where('ano_lectivos_id', $anoLectivo->id)
                            ->where('disciplinas_id', $item->disciplinas_id)
                            ->with(['ano', 'disciplina','trimestre'])
                            ->orderBy('controlo_trimestres_id', 'desc')
                            ->get();
                            @endphp

                            @foreach ($notas1 as $item1)
                            <tr>
                                <td>{{ $item1->disciplina->disciplina }} </td>
                                <td>{{ $item1->trimestre->trimestre }} </td>

                                <td>{{ $item1->p1 }}</td>
                                <td>{{ $item1->p2 }}</td>
                                <td>{{ $item1->p3 }}</td>
                                <td>{{ $item1->p4 }}</td>
                                <td>{{ $item1->med }}</td>
                                <td>{{ $item1->obs1 }}</td>
                                <td>{{ $item1->exame_1_especial }}</td>
                                <td>{{ $item1->obs2 }}</td>
                                <td>{{ $item1->recurso }}</td>
                                <td>{{ $item1->exame_especial }}</td>
                                <td>{{ $item1->resultado_final }}</td>
                                <td>{{ $item1->obs3 }}</td>
                            </tr>
                            @endforeach
                            @endforeach
                            @endif
                        </tbody>

                        <tfoot>
                            <th colspan="13" class="text-center">Media Final CA <br> ({{ ($somaMFD ?? 0 /  $totalDisciplinas ?? 0) }})</th>
                            <th class="text-center">Resultado Final <br>
                                (@if (($somaMFD ?? 0 / $totalDisciplinas ?? 0) >= 10)
                                <span class="text-info">Aprovado</span>
                                @else
                                <span class="text-danger">Reprovado</span>
                                @endif)
                            </th>
                        </tfoot>

                    </table>
                </div>
                @if (Auth::user()->can('read: nota'))
                <div class="col-12 col-md-12 mt-5">
                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("simestre1")]) }}" target="_blink" class="btn btn-primary">Iª Semestre</a>
                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("simestre2")]) }}" target="_blink" class="btn btn-primary">IIª Semestre</a>
                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("declaracao-nota") ]) }}" target="_blink" class="btn btn-primary">Declaração Com notas</a>
                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("declarcao-sem-nota")]) }}" target="_blink" class="btn btn-primary">Declaração Sem notas</a>
                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("classificacao-final")]) }}" target="_blink" class="btn btn-primary">Classificação Final</a>
                </div>
                @endif

                @else
                @if (isset($trimestre1) AND isset($trimestre2) AND isset($trimestre3) AND isset($trimestre4) )
                <div class="col-12 col-md-12">
                    <table style="width: 100%" class="table table-bordered">

                        <thead>
                            <tr>
                                <th>Disciplina</th>
                                <th colspan="3" class="text-center">Iª Trimestre</th>
                                <th colspan="3" class="text-center">IIª Trimestre</th>
                                <th colspan="3" class="text-center">IIIª Trimestre</th>
                            </tr>

                            <tr>
                                <th>Disciplina</th>

                                <th>MAC</th>
                                <th>NPT</th>
                                <th class="text-info">MT</th>

                                <th>MAC</th>
                                <th>NPT</th>
                                <th class="text-info">MT</th>

                                <th>MAC</th>
                                <th>NPT</th>
                                <th class="text-info">MT</th>

                                <th class="bg-success">MDF</th>
                                <th>Obsevação</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if ($turmaDisciplinas)
                            @foreach($turmaDisciplinas as $item)
                            @php
                            $notas1 = (new App\Models\web\turmas\NotaPauta)::where('controlo_trimestres_id', $trimestre1->id)
                            ->where('estudantes_id', $estudantes->id)
                            ->where('ano_lectivos_id', $anoLectivo->id)
                            ->where('disciplinas_id', $item->disciplinas_id)
                            ->with(['disciplina', 'estudante'])
                            ->get();

                            $notas2 = (new App\Models\web\turmas\NotaPauta)::where('controlo_trimestres_id', $trimestre2->id)
                            ->where('estudantes_id', $estudantes->id)
                            ->where('ano_lectivos_id', $anoLectivo->id)
                            ->where('disciplinas_id', $item->disciplinas_id)
                            ->with(['disciplina', 'estudante'])
                            ->get();


                            $notas3 = (new App\Models\web\turmas\NotaPauta)::where('controlo_trimestres_id', $trimestre3->id)
                            ->where('estudantes_id', $estudantes->id)
                            ->where('ano_lectivos_id', $anoLectivo->id)
                            ->where('disciplinas_id', $item->disciplinas_id)
                            ->with(['disciplina', 'estudante'])
                            ->get();

                            $notas4 = (new App\Models\web\turmas\NotaPauta)::where('controlo_trimestres_id', $trimestre4->id)
                            ->where('estudantes_id', $estudantes->id)
                            ->where('ano_lectivos_id', $anoLectivo->id)
                            ->where('disciplinas_id', $item->disciplinas_id)
                            ->with(['disciplina', 'estudante'])
                            ->get();

                            @endphp

                            @foreach ($notas1 as $item1)
                            @foreach ($notas2 as $item2)
                            @foreach ($notas3 as $item3)
                            @foreach ($notas4 as $item4)

                            <tr class="">
                                <td>{{ $item1->disciplina->disciplina }} </td>

                                <td>{{ $item1->mac }}</td>
                                <td>{{ $item1->npt }}</td>
                                <td class="bg-warning">{{ $item1->mt }}</td>

                                <td>{{ $item2->mac }}</td>
                                <td>{{ $item2->npt }}</td>
                                <td class="bg-warning">{{ $item2->mt }}</td>

                                <td>{{ $item3->mac }}</td>
                                <td>{{ $item3->npt }}</td>
                                <td class="bg-warning">{{ $item3->mt }}</td>

                                <td class="bg-success">{{ $item4->mfd }}</td>
                                <td class="bg-success">{{ $item4->obs }}</td>
                            </tr>

                            @endforeach
                            @endforeach
                            @endforeach
                            @endforeach

                            @endforeach
                            @endif
                        </tbody>

                        <tfoot>
                            @if ($turmaDisciplinas)
                            <th colspan="10" class="text-center">Media Final CA <br> ({{ $totalDisciplinas != 0 ? ($somaMFD / $totalDisciplinas) : 0 }})</th>
                            <th class="text-center">Resultado Final <br>
                                @if ($totalDisciplinas != 0)
                                (@if ((($somaMFD / $totalDisciplinas) >= 10))
                                <span class="text-info">Aprovado</span>
                                @else
                                <span class="text-danger">Reprovado</span>
                                @endif)
                                @else
                                <span class="text-danger">Indefinido</span>
                                @endif
                            </th>
                            @endif
                        </tfoot>

                    </table>
                </div>

                <div class="col-12 col-md-12">
                    <h5>As linhas vermelha indicam que as notas ainda foram confirmadas pelo Professores das respectivas disciplinas</h5>
                </div>

                @if (Auth::user()->can('read: nota'))
                <div class="col-12 col-md-12">
                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("trimestre1")]) }}" target="_blink" class="btn btn-primary">Iª Trimestre</a>
                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("trimestre2")]) }}" target="_blink" class="btn btn-primary">IIª Trimestre</a>
                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("trimestre3")]) }}" target="_blink" class="btn btn-primary">IIIª Trimestre</a>
                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("trimestre4")]) }}" target="_blink" class="btn btn-primary">Geral</a>
                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("declaracao-nota") ]) }}" target="_blink" class="btn btn-primary">Declaração Com notas</a>
                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("declarcao-sem-nota")]) }}" target="_blink" class="btn btn-primary">Declaração Sem notas</a>
                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("classificacao-final")]) }}" target="_blink" class="btn btn-primary">Classificação Final</a>
                </div>
                @endif

                @endif
                @endif
            </div>

        </div>
        <!-- /.invoice -->
    </div><!-- /.col -->
</div><!-- /.row -->
