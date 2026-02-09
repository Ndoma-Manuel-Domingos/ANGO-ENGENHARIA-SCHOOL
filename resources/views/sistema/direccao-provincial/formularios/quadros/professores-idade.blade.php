{{-- table 03 --}}
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
                        <th colspan="{{ count($formacoes) * 2 + 3 }}" class="text-center bg-info">Quadro 6. Idade do
                            Pessoal Docente(Diante dos alunos)</th>
                    </tr>
                    <tr>
                        <th rowspan="2"></th>
                        @foreach ($formacoes as $formacao)
                        <th colspan="2" class="text-center">C/Formação Pedagógica</th>
                        @endforeach
                        <th colspan="2" class="text-center">Total</th>
                    </tr>

                    <tr>
                        @foreach ($formacoes as $formacao)
                        <th>MF</th>
                        <th>F</th>
                        @endforeach

                        <th>MF</th>
                        <th>F</th>
                    </tr>
                </thead>
                <tbody id="">
                    <tr>
                        <td>18-19 Anos</td>
                        @php
                        $professores_masculino_18_19 = 0;
                        $professores_femenino_18_19 = 0;
                        @endphp
                        @foreach ($formacoes as $formacao)
                        @php
                        $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" AND TIMESTAMPDIFF(YEAR,
                        tb_professores.nascimento, CURDATE()) BETWEEN 18 AND 19 THEN 1 ELSE 0 END) AS
                        professores_masculino'),
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" AND TIMESTAMPDIFF(YEAR,
                        tb_professores.nascimento, CURDATE()) BETWEEN 18 AND 19 THEN 1 ELSE 0 END) AS
                        professores_femenino'),
                        )
                        ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_professores.nascimento, CURDATE())'), [18, 19])
                        ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                        ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                        'tb_professores_academicos.professor_id')
                        ->whereIn('tb_professores.id', $ids_professores)
                        ->where('tb_contratos.cargo_geral', 'professor')
                        ->where('tb_contratos.level', '4')
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
                        $professores_masculino_18_19 += $professores->professores_masculino ?? 0;
                        $professores_femenino_18_19 += $professores->professores_femenino ?? 0;
                        @endphp
                        @endforeach

                        <td>{{ $professores_masculino_18_19 ?? 0 }}</td>
                        <td>{{ $professores_femenino_18_19 ?? 0 }}</td>
                    </tr>

                    <tr>
                        <td>20-24 Anos</td>

                        @php
                        $professores_masculino_20_24 = 0;
                        $professores_femenino_20_24 = 0;
                        @endphp
                        @foreach ($formacoes as $formacao)
                        @php
                        $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" AND TIMESTAMPDIFF(YEAR,
                        tb_professores.nascimento, CURDATE()) BETWEEN 20 AND 24 THEN 1 ELSE 0 END) AS
                        professores_masculino'),
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" AND TIMESTAMPDIFF(YEAR,
                        tb_professores.nascimento, CURDATE()) BETWEEN 20 AND 24 THEN 1 ELSE 0 END) AS
                        professores_femenino'),
                        )
                        ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_professores.nascimento, CURDATE())'), [20, 24])
                        ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                        ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                        'tb_professores_academicos.professor_id')
                        ->where('tb_contratos.cargo_geral', 'professor')
                        ->whereIn('tb_professores.id', $ids_professores)
                        ->where('tb_contratos.level', '4')
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
                        $professores_masculino_20_24 += $professores->professores_masculino ?? 0;
                        $professores_femenino_20_24 += $professores->professores_femenino ?? 0;
                        @endphp
                        @endforeach

                        <td>{{ $professores_masculino_20_24 ?? 0 }}</td>
                        <td>{{ $professores_femenino_20_24 ?? 0 }}</td>
                    </tr>

                    <tr>
                        <td>25-29 Anos</td>

                        @php
                        $professores_masculino_25_29 = 0;
                        $professores_femenino_25_29 = 0;
                        @endphp
                        @foreach ($formacoes as $formacao)
                        @php
                        $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" AND TIMESTAMPDIFF(YEAR,
                        tb_professores.nascimento, CURDATE()) BETWEEN 25 AND 29 THEN 1 ELSE 0 END) AS
                        professores_masculino'),
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" AND TIMESTAMPDIFF(YEAR,
                        tb_professores.nascimento, CURDATE()) BETWEEN 25 AND 29 THEN 1 ELSE 0 END) AS
                        professores_femenino'),
                        )
                        ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_professores.nascimento, CURDATE())'), [25, 29])
                        ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                        ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                        'tb_professores_academicos.professor_id')
                        ->where('tb_contratos.cargo_geral', 'professor')
                        ->where('tb_contratos.level', '4')
                        ->whereIn('tb_professores.id', $ids_professores)
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
                        $professores_masculino_25_29 += $professores->professores_masculino ?? 0;
                        $professores_femenino_25_29 += $professores->professores_femenino ?? 0;
                        @endphp
                        @endforeach

                        <td>{{ $professores_masculino_25_29 ?? 0 }}</td>
                        <td>{{ $professores_femenino_25_29 ?? 0 }}</td>
                    </tr>

                    <tr>
                        <td>30-34 Anos</td>

                        @php
                        $professores_masculino_30_34 = 0;
                        $professores_femenino_30_34 = 0;
                        @endphp
                        @foreach ($formacoes as $formacao)
                        @php
                        $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" AND TIMESTAMPDIFF(YEAR,
                        tb_professores.nascimento, CURDATE()) BETWEEN 30 AND 34 THEN 1 ELSE 0 END) AS
                        professores_masculino'),
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" AND TIMESTAMPDIFF(YEAR,
                        tb_professores.nascimento, CURDATE()) BETWEEN 30 AND 34 THEN 1 ELSE 0 END) AS
                        professores_femenino'),
                        )
                        ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_professores.nascimento, CURDATE())'), [30, 34])
                        ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                        ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                        'tb_professores_academicos.professor_id')
                        ->where('tb_contratos.cargo_geral', 'professor')
                        ->whereIn('tb_professores.id', $ids_professores)
                        ->where('tb_contratos.level', '4')
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
                        $professores_masculino_30_34 += $professores->professores_masculino ?? 0;
                        $professores_femenino_30_34 += $professores->professores_femenino ?? 0;
                        @endphp
                        @endforeach

                        <td>{{ $professores_masculino_30_34 ?? 0 }}</td>
                        <td>{{ $professores_femenino_30_34 ?? 0 }}</td>
                    </tr>

                    <tr>
                        <td>35-39 Anos</td>

                        @php
                        $professores_masculino_35_39 = 0;
                        $professores_femenino_35_39 = 0;
                        @endphp
                        @foreach ($formacoes as $formacao)
                        @php
                        $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" AND TIMESTAMPDIFF(YEAR,
                        tb_professores.nascimento, CURDATE()) BETWEEN 35 AND 39 THEN 1 ELSE 0 END) AS
                        professores_masculino'),
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" AND TIMESTAMPDIFF(YEAR,
                        tb_professores.nascimento, CURDATE()) BETWEEN 35 AND 39 THEN 1 ELSE 0 END) AS
                        professores_femenino'),
                        )
                        ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_professores.nascimento, CURDATE())'), [35, 39])
                        ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                        ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                        'tb_professores_academicos.professor_id')
                        ->where('tb_contratos.cargo_geral', 'professor')
                        ->whereIn('tb_professores.id', $ids_professores)
                        ->where('tb_contratos.level', '4')
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
                        $professores_masculino_35_39 += $professores->professores_masculino ?? 0;
                        $professores_femenino_35_39 += $professores->professores_femenino ?? 0;
                        @endphp
                        @endforeach

                        <td>{{ $professores_masculino_35_39 ?? 0 }}</td>
                        <td>{{ $professores_femenino_35_39 ?? 0 }}</td>
                    </tr>

                    <tr>
                        <td>40 Anos ou mais</td>

                        @php
                        $professores_masculino_40 = 0;
                        $professores_femenino_40 = 0;
                        @endphp
                        @foreach ($formacoes as $formacao)
                        @php
                        $professores = App\Models\web\funcionarios\FuncionariosControto::select(
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Masculino" AND TIMESTAMPDIFF(YEAR,
                        tb_professores.nascimento, CURDATE()) >= 40 THEN 1 ELSE 0 END) AS professores_masculino'),
                        DB::raw('SUM(CASE WHEN tb_professores.genero = "Femenino" AND TIMESTAMPDIFF(YEAR,
                        tb_professores.nascimento, CURDATE()) >= 40 THEN 1 ELSE 0 END) AS professores_femenino'),
                        )
                        ->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR, tb_professores.nascimento, CURDATE())'), [40, 60])
                        ->join('tb_professores', 'tb_contratos.funcionarios_id' , '=', 'tb_professores.id')
                        ->join('tb_professores_academicos', 'tb_professores.id' , '=',
                        'tb_professores_academicos.professor_id')
                        ->where('tb_contratos.cargo_geral', 'professor')
                        ->whereIn('tb_professores.id', $ids_professores)
                        ->where('tb_contratos.level', '4')
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
                        $professores_masculino_40 += $professores->professores_masculino ?? 0;
                        $professores_femenino_40 += $professores->professores_femenino ?? 0;
                        @endphp
                        @endforeach

                        <td>{{ $professores_masculino_40 ?? 0 }}</td>
                        <td>{{ $professores_femenino_40 ?? 0 }}</td>
                    </tr>

                    <tr>
                        <td>TOTAL</td>
                        @php
                        $professores_masculino_geral = 0;
                        $professores_femenino_geral = 0;
                        @endphp
                        @foreach ($formacoes as $formacao)

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
                        $professores_masculino_geral += $professores->professores_masculino ?? 0;
                        $professores_femenino_geral += $professores->professores_femenino ?? 0;
                        @endphp

                        @endforeach

                        <td>{{ $professores_masculino_geral ?? 0 }}</td>
                        <td>{{ $professores_femenino_geral ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>