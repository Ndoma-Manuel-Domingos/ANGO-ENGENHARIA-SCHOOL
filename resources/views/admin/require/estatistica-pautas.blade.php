
@if (count($disciplinas))
<div class="row">
    <div class="col-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('pedagogicos.estatistica-turmas-unica-pdf', ['turmas_id' => $turma->id ?? '', 'ano_lectivos_id' => $requests['ano_lectivos_id'] ?? '']) }}" class="btn btn-danger mx-1 float-right" target="_blink"><i class="fas fa-file-pdf"></i> Imprimir PDF</a>
                <a href="{{ route('pedagogicos.estatistica-turmas-unica-excel', ['turmas_id' => $turma->id ?? '', 'ano_lectivos_id' => $requests['ano_lectivos_id'] ?? '']) }}" class="btn btn-success mx-1 float-right" target="_blink"><i class="fas fa-file-excel"></i> Imprimir EXCEL</a>
            </div>
            <div class="card-body table-responsive">
                <table style="width: 100%" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th colspan="{{ count($disciplinas) * 4 + 6 }}">{{ $turma->classe->classes }}</th>
                        </tr>
                        <tr>
                            <th colspan="{{ count($disciplinas) * 4 + 6 }}" class="text-uppercase">CURSO: {{ $turma->curso->curso }}</th>
                        </tr>
                        <tr>
                            <th colspan="4">TURMA: {{ $turma->turma }}</th>
                            @foreach ($disciplinas as $disciplina)
                            <th colspan="4" class="text-center"> {{ $disciplina->disciplina->abreviacao }}</th>
                            @endforeach
                            <th rowspan="3" class="align-middle bg-info text-center"> Resultados</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Nº</th>
                            <th rowspan="2">Nome Completo</th>
                            <th rowspan="2"><span>Processo</span> </th>
                            <th rowspan="2"><span>Sexo</span> </th>
                            @foreach ($disciplinas as $disciplina)
                            <th colspan="4" class="text-center align-middle bg-primary"> {{ $turma->classe->classes }}</th>
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
                                <span class="">CFD</span>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $contador = 0;
                        @endphp
                        @foreach ($estudantes as $key => $estudante)
                         @php $contador++; @endphp
                        <tr>
                            <td>{{ $contador }}</td>
                            <td>{{ $estudante->estudante->nome }} {{ $estudante->estudante->sobre_nome }}</td>
                            <td>{{ $estudante->estudante->numero_processo }}</td>
                            <td>{{ $estudante->estudante->sigla_genero($estudante->estudante->genero) }}</td>
                            
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
                                $soma_mfd += $notas_t_4->mfd;
                            @endphp

                            <td><strong>{{ $notas_t_1->arredondar($notas_t_1->mt1) }}</strong></td>
                            <td><strong>{{ $notas_t_2->arredondar($notas_t_2->mt2) }}</strong></td>
                            <td><strong>{{ $notas_t_3->arredondar($notas_t_3->mt3) }}</strong></td>
                            <td><strong>{{ $notas_t_4->arredondar($notas_t_4->mfd) }}</strong></td>
                            @endforeach
                            <td>
                                @if ( $soma_mfd !== 0)
                                    @if ( ($soma_mfd / $total_disciplina) < $turma->classe->tipo_avaliacao_nota)
                                        <span style="color: red;">N/TRANSITA</span>
                                    @else
                                        <span style="color: blue;">TRANSITA</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@else
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <p>Nenhum plano curricular disponíbilizado neste turma. Cria um plano curricular.</p>
                </div>
            </div>
        </div>
    </div>

@endif
