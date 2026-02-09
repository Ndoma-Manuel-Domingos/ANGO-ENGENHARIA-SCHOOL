<div class="row">
    {{-- table 01--}}
    <div class="col-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <table id="example1" style="width: 100%"
                    class="table table-bordered  ">
                    <thead>
                        <tr>
                            <th colspan="24" class="text-center bg-info">Quadro 1. Aproveitamento dos Alunos da
                                Iniciação.</th>
                        </tr>
                        <tr>
                            <th colspan="24" class="text-center bg-primary">Total alunos por idade</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Idades/Classes</th>
                            <th colspan="2" rowspan="2" class="text-center">Matriculados</th>
                            <th colspan="2" rowspan="2" class="text-center">Aprovados</th>
                            <th colspan="2" rowspan="2" class="text-center">Reprovados</th>

                            <th colspan="4" class="text-center">Transferidos</th>

                            <th colspan="2" rowspan="2" class="text-center">Desistidos</th>
                        </tr>

                        <tr>
                            <th colspan="2" class="text-center">Entrada</th>
                            <th colspan="2" class="text-center">Saída</th>
                        </tr>

                        <tr>
                            <th></th>

                            <th>MF</th>
                            <th>F</th>

                            <th>MF</th>
                            <th>F</th>

                            <th>MF</th>
                            <th>F</th>

                            <th>MF</th>
                            <th>F</th>

                            <th>MF</th>
                            <th>F</th>

                            <th>MF</th>
                            <th>F</th>
                        </tr>

                    </thead>
                    <tbody id="">
                        @foreach ($classes as $item)

                        @php
                        $result = App\Models\web\calendarios\Matricula::select(
                        // estudantes com matriculados
                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "confirmado" AND tb_estudantes.genero =
                        "Masculino" THEN 1 ELSE 0 END) AS estudantes_matriculados_masculino'),
                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "confirmado" AND tb_estudantes.genero =
                        "Femenino" THEN 1 ELSE 0 END) AS estudantes_matriculados_feminino'),

                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "confirmado" AND
                        tb_matriculas.resultado_final = "aprovado" AND tb_estudantes.genero = "Masculino" THEN 1 ELSE 0
                        END) AS estudantes_aprovado_masculino'),
                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "confirmado" AND
                        tb_matriculas.resultado_final = "aprovado" AND tb_estudantes.genero = "Femenino" THEN 1 ELSE 0
                        END) AS estudantes_aprovado_feminino'),

                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "confirmado" AND
                        tb_matriculas.resultado_final = "reprovado" AND tb_estudantes.genero = "Masculino" THEN 1 ELSE 0
                        END) AS estudantes_reprovado_masculino'),
                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "confirmado" AND
                        tb_matriculas.resultado_final = "reprovado" AND tb_estudantes.genero = "Femenino" THEN 1 ELSE 0
                        END) AS estudantes_reprovado_feminino'),

                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "desistente" AND tb_estudantes.genero =
                        "Masculino" THEN 1 ELSE 0 END) AS estudantes_desistente_masculino'),
                        DB::raw('SUM(CASE WHEN tb_matriculas.status_matricula = "desistente" AND tb_estudantes.genero =
                        "Femenino" THEN 1 ELSE 0 END) AS estudantes_desistente_feminino'),

                        )
                        ->join('tb_estudantes', 'tb_matriculas.estudantes_id' , '=', 'tb_estudantes.id')
                        ->where('tb_matriculas.classes_id', $item->id)
                        ->where('tb_matriculas.shcools_id', $escola->id)
                        ->where('tb_matriculas.ano_lectivos_id', $anolectivoactual)
                        ->first();
                        @endphp


                        <tr>
                            <td>{{ $item->classes }}</td>

                            <td>{{ $result->estudantes_matriculados_masculino ?? 0 }}</td>
                            <td>{{ $result->estudantes_matriculados_feminino ?? 0 }}</td>

                            <td>{{ $result->estudantes_aprovado_masculino ?? 0 }}</td>
                            <td>{{ $result->estudantes_aprovado_feminino ?? 0 }}</td>

                            <td>{{ $result->estudantes_reprovado_masculino ?? 0 }}</td>
                            <td>{{ $result->estudantes_reprovado_feminino ?? 0 }}</td>

                            <td>0</td>
                            <td>0</td>

                            <td>0</td>
                            <td>0</td>

                            <td>{{ $result->estudantes_desistente_masculino ?? 0 }}</td>
                            <td>{{ $result->estudantes_desistente_feminino ?? 0 }}</td>

                        </tr>
                        @endforeach
                        
                        <tr>
                            <td>TOTAL</td>
                            
                            <td>0</td>
                            <td>0</td>
                            
                            <td>0</td>
                            <td>0</td>
                            
                            <td>0</td>
                            <td>0</td>
                            
                            <td>0</td>
                            <td>0</td>
                            
                            <td>0</td>
                            <td>0</td>
                            
                            <td>0</td>
                            <td>0</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>