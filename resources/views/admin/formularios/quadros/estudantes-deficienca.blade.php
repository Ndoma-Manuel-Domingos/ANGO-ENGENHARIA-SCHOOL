{{-- table 03 --}}
<div class="col-12 col-md-12">
    <div class="card">
        
        <div class="card-header text-end">
            <a href="{{ route('web.formulario.imprimir-estudante-dificienca-relatorio', 
                ['ano_lectivo' => $anolectivoactual, 'ensino' => $ensino->id]) }}" class="btn btn-danger" target="_blink"><i class="fas fa-file-pdf"></i> PDF</a>
        </div>
    
        <div class="card-body">
            <table id="example1" style="width: 100%"
                class="table table-bordered  ">
                <thead>
                    <tr>
                        <th colspan="11" class="text-center bg-info">Quadro 4. Alunos com deficiências</th>
                    </tr>
                    <tr>
                        <th rowspan="2"></th>
                        <th colspan="2" class="text-center">Visual</th>
                        <th colspan="2" class="text-center">Auditivo</th>
                        <th colspan="2" class="text-center">Motora</th>
                        <th colspan="2" class="text-center">Outras</th>
                        <th colspan="2" class="text-center">Total</th>
                    </tr>

                    <tr>
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

                    @php
                    $estudante_dificiencia_visual_masculino = 0;
                    $estudante_dificiencia_visual_feminino = 0;

                    $estudante_dificiencia_auditiva_masculino = 0;
                    $estudante_dificiencia_auditiva_feminino = 0;

                    $estudante_dificiencia_motora_masculino = 0;
                    $estudante_dificiencia_motora_feminino = 0;

                    $estudante_dificiencia_outras_masculino = 0;
                    $estudante_dificiencia_outras_feminino = 0;

                    @endphp

                    @foreach ($classes as $classe)
                    <tr>

                        @php
                        $result = App\Models\web\calendarios\Matricula::select(

                        // // estudantes com definciencias Visual
                        DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Visual" AND tb_estudantes.genero
                        = "Masculino" THEN 1 ELSE 0 END) AS estudante_dificiencia_visual_masculino'),
                        DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Visual" AND tb_estudantes.genero
                        = "Femenino" THEN 1 ELSE 0 END) AS estudante_dificiencia_visual_feminino'),

                        // estudantes com definciencias Auditiva
                        DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Auditiva" AND
                        tb_estudantes.genero = "Masculino" THEN 1 ELSE 0 END) AS
                        estudante_dificiencia_auditiva_masculino'),
                        DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Auditiva" AND
                        tb_estudantes.genero = "Femenino" THEN 1 ELSE 0 END) AS
                        estudante_dificiencia_auditiva_feminino'),

                        // estudantes com definciencias Motora
                        DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Motora" AND tb_estudantes.genero
                        = "Masculino" THEN 1 ELSE 0 END) AS estudante_dificiencia_motora_masculino'),
                        DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Motora" AND tb_estudantes.genero
                        = "Femenino" THEN 1 ELSE 0 END) AS estudante_dificiencia_motora_feminino'),

                        // estudantes com definciencias Outras
                        DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Outras" AND tb_estudantes.genero
                        = "Masculino" THEN 1 ELSE 0 END) AS estudante_dificiencia_outras_masculino'),
                        DB::raw('SUM(CASE WHEN tb_estudantes.dificiencia = "Outras" AND tb_estudantes.genero
                        = "Femenino" THEN 1 ELSE 0 END) AS estudante_dificiencia_outras_feminino'),

                        )
                        ->join('tb_estudantes', 'tb_matriculas.estudantes_id' , '=', 'tb_estudantes.id')
                        ->where('tb_matriculas.classes_id', $classe->id)
                        ->where('tb_matriculas.shcools_id', $escola->id)
                        ->where('tb_matriculas.ano_lectivos_id', $anolectivoactual)
                        ->first();
                        @endphp


                        <td>{{ $classe->classes }}</td>

                        <td>{{ $result->estudante_dificiencia_visual_masculino ?? 0 }}</td>
                        <td>{{ $result->estudante_dificiencia_visual_feminino ?? 0 }}</td>

                        @php
                        $estudante_dificiencia_visual_masculino +=
                        $result->estudante_dificiencia_visual_masculino;
                        $estudante_dificiencia_visual_feminino +=
                        $result->estudante_dificiencia_visual_feminino;
                        @endphp

                        <td>{{ $result->estudante_dificiencia_auditiva_masculino ?? 0 }}</td>
                        <td>{{ $result->estudante_dificiencia_auditiva_feminino ?? 0 }}</td>

                        @php
                        $estudante_dificiencia_auditiva_masculino +=
                        $result->estudante_dificiencia_auditiva_masculino;
                        $estudante_dificiencia_auditiva_feminino +=
                        $result->estudante_dificiencia_auditiva_feminino;
                        @endphp

                        <td>{{ $result->estudante_dificiencia_motora_masculino ?? 0 }}</td>
                        <td>{{ $result->estudante_dificiencia_motora_feminino ?? 0 }}</td>

                        @php
                        $estudante_dificiencia_motora_masculino +=
                        $result->estudante_dificiencia_motora_masculino;
                        $estudante_dificiencia_motora_feminino +=
                        $result->estudante_dificiencia_motora_feminino;
                        @endphp

                        <td>{{ $result->estudante_dificiencia_outras_masculino ?? 0 }}</td>
                        <td>{{ $result->estudante_dificiencia_outras_feminino ?? 0 }}</td>

                        @php
                        $estudante_dificiencia_outras_masculino +=
                        $result->estudante_dificiencia_outras_masculino;
                        $estudante_dificiencia_outras_feminino +=
                        $result->estudante_dificiencia_outras_feminino;
                        @endphp

                        <td>{{ $result->estudante_dificiencia_visual_masculino ?? 0 +
                            $result->estudante_dificiencia_auditiva_masculino ?? 0 +
                            $result->estudante_dificiencia_motora_masculino ?? 0 +
                            $result->estudante_dificiencia_outras_masculino ?? 0 }}</td>
                        <td>{{ $result->estudante_dificiencia_visual_feminino ?? 0 +
                            $result->estudante_dificiencia_auditiva_feminino ?? 0 +
                            $result->estudante_dificiencia_motora_feminino ?? 0 +
                            $result->estudante_dificiencia_outras_feminino ?? 0 }}</td>
                    </tr>
                    @endforeach

                    <tr>
                        <td>Total</td>

                        <td>{{ $estudante_dificiencia_visual_masculino ?? 0 }}</td>
                        <td>{{ $estudante_dificiencia_visual_feminino ?? 0 }}</td>

                        <td>{{ $estudante_dificiencia_auditiva_masculino ?? 0 }}</td>
                        <td>{{ $estudante_dificiencia_auditiva_feminino ?? 0 }}</td>

                        <td>{{ $estudante_dificiencia_motora_masculino ?? 0 }}</td>
                        <td>{{ $estudante_dificiencia_motora_feminino ?? 0 }}</td>

                        <td>{{ $estudante_dificiencia_outras_masculino ?? 0 }}</td>
                        <td>{{ $estudante_dificiencia_outras_feminino ?? 0 }}</td>

                        <td>{{ $estudante_dificiencia_visual_masculino ?? 0 +
                            $estudante_dificiencia_auditiva_masculino ?? 0 +
                            $estudante_dificiencia_motora_masculino ?? 0 +
                            $estudante_dificiencia_outras_masculino ?? 0 }}</td>
                        <td>{{ $estudante_dificiencia_visual_feminino ?? 0 +
                            $estudante_dificiencia_auditiva_feminino ?? 0 +
                            $estudante_dificiencia_motora_feminino ?? 0 +
                            $estudante_dificiencia_outras_feminino ?? 0 }}</td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>