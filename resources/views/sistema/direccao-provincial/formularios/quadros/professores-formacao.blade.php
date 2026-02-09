<div class="col-12 col-md-12">
    <div class="card">
        
        <div class="card-header text-end">
            <a href="" class="btn btn-danger" target="_blink"><i class="fas fa-file-pdf"></i> PDF</a>
        </div>
    
        <div class="card-body">
            <table id="example1" style="width: 100%"
                class="table table-bordered  ">
                <thead>

                    <tr>
                        <th class="bg-info text-center" colspan="19">Qudro 5. Pessoal Docente (Diante dos alunos) total
                            com e sem formação pedagógica e Nível Académico dos Docentes</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th class="text-center" colspan="18">Nível Académico dos Docentes</th>
                    </tr>

                    <tr>
                        <th></th>
                        @foreach ($escolaridades as $escolaridade)
                        <th colspan="2">{{ $escolaridade->nome }}</th>
                        @endforeach
                        <th colspan="2">Total</th>
                    </tr>

                    <tr>
                        <td></td>
                        @foreach ($escolaridades as $escolaridade)
                        <td>MF</td>
                        <td>F</td>
                        @endforeach
                        <td>MF</td>
                        <td>F</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($formacoes as $formacao)
                    <tr>
                        @php
                        $professores_masculinos = 0;
                        $professores_femenino = 0;
                        @endphp
                        <td>{{ $formacao->nome }}</td>
                        @foreach ($escolaridades as $escolaridade)

                        @php
                        $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" THEN 1 ELSE 0 END) AS
                        professores_masculino'),
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" THEN 1 ELSE 0 END) AS
                        professores_femenino'),
                        )
                        ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                        ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                        'tb_professores_academicos.professor_id')
                        ->where('tb_contratos.cargo_geral', 'professor')
                        ->where('tb_contratos.level', '4')
                        ->whereIn('tb_professores.id', $ids_professores)
                        ->where('tb_professores_academicos.escolaridade_id', $escolaridade->id)
                        ->where('tb_professores_academicos.formacao_academica_id', $formacao->id)
                        ->when($requests['ano_lectivo_id'], function($query, $value){
                            $query->where('tb_contratos.ano_lectivos_id', $value);
                        })
                        ->when($requests['municipio_id'], function($query, $value){
                            $query->where('tb_contratos.municipio_id', $value);
                        })
                        ->when($requests['distrito_id'], function($query, $value){
                            $query->where('tb_contratos.distrito_id', $value);
                        })
                        ->when($requests['shcools_id'], function($query, $value){
                            $query->where('tb_contratos.shcools_id', $value);
                        })
                        ->first()

                        @endphp

                        <td>{{ $professores->professores_masculino ?? 0 }}</td>
                        <td>{{ $professores->professores_femenino ?? 0 }}</td>

                        @php
                        $professores_masculinos += $professores->professores_masculino;
                        $professores_femenino += $professores->professores_femenino;
                        @endphp

                        @endforeach
                        <td>{{ $professores_masculinos ?? 0 }}</td>
                        <td>{{ $professores_femenino ?? 0 }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>TOTAL</td>
                        @php
                        $professores_masculinos_total = 0;
                        $professores_femenino_total = 0;
                        @endphp
                        @foreach ($escolaridades as $escolaridade)
                        @php
                        $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" THEN 1 ELSE 0 END) AS
                        professores_masculino'),
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" THEN 1 ELSE 0 END) AS
                        professores_femenino'),
                        )
                        ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                        ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                        'tb_professores_academicos.professor_id')
                        ->where('tb_contratos.cargo_geral', 'professor')
                        ->whereIn('tb_professores.id', $ids_professores)
                        ->where('tb_contratos.level', '4')
                        ->where('tb_professores_academicos.escolaridade_id', $escolaridade->id)
                        ->when($requests['ano_lectivo_id'], function($query, $value){
                            $query->where('tb_contratos.ano_lectivos_id', $value);
                        })
                        ->when($requests['municipio_id'], function($query, $value){
                            $query->where('tb_contratos.municipio_id', $value);
                        })
                        ->when($requests['distrito_id'], function($query, $value){
                            $query->where('tb_contratos.distrito_id', $value);
                        })
                        ->when($requests['shcools_id'], function($query, $value){
                            $query->where('tb_contratos.shcools_id', $value);
                        })
                        ->first()
                        @endphp

                        <td>{{ $professores->professores_masculino ?? 0 }}</td>
                        <td>{{ $professores->professores_femenino ?? 0 }}</td>

                        @php
                        $professores_masculinos_total += $professores->professores_masculino;
                        $professores_femenino_total += $professores->professores_femenino;
                        @endphp
                        @endforeach
                        <td>{{ $professores_masculinos_total ?? 0 }}</td>
                        <td>{{ $professores_femenino_total ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>