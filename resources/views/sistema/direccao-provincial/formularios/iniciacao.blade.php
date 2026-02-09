@extends('layouts.provinciais')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Formulário Pré-Escolar(Iniciação)</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('app.provincial-fornulario-ficha') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Formulário</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="content">
        <div class="container-fluid">
        
            <div class="row">
                <div class="col-12-col-md-12">
                    <form action="{{ route('app.formulario.provincial.iniciacao') }}" method="get">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">

                                    <div class="form-group pt-4 col-md-3 col-12">
                                        <label for="municipio_id" class="form-label">Municípios</label>
                                        <select name="municipio_id" id="municipio_id"
                                            class="form-control municipio_id select2">
                                            <option value="">Selecione Município</option>
                                            @foreach ($municipios as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $requests['municipio_id'] == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('municipio_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group pt-4 col-md-3 col-12">
                                        <label for="distrito_id" class="form-label">Distritos</label>
                                        <select name="distrito_id" id="distrito_id"
                                            class="form-control distrito_id select2">
                                            <option value="">Selecione Distritos</option>
                                            @foreach ($distritos as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $requests['distrito_id'] == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('distrito_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group pt-4 col-md-3 col-12">
                                        <label for="shcools_id" class="form-label">Escolas</label>
                                        <select name="shcools_id" id="shcools_id" class="form-control shcools_id select2">
                                            <option value="">Escola</option>
                                            @foreach ($escolas as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $requests['shcools_id'] == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('shcools_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="form-group pt-4 col-md-3 col-12">
                                        <label for="ano_lectivo_id" class="form-label">Ano Lectivo</label>
                                        <select name="ano_lectivo_id" id="ano_lectivo_id"
                                            class="form-control ano_lectivo_id select2">
                                            <option value="">Selecione Ano Lectivo</option>
                                            @foreach ($ano_lectivos as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $requests['ano_lectivo_id'] == $item->id ? 'selected' : '' }}>
                                                    {{ $item->ano }}</option>
                                            @endforeach
                                        </select>
                                        @error('ano_lectivo_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                            </div>
                            <div class="card-footer pt-4">
                                <button type="submit" class="btn btn-primary"> Filtrar</button>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6" style="width: 20px">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                                </svg>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                {{-- table 01 --}}
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table id="example1" style="width: 100%" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="24" class="text-center bg-info">Quadro 1. Total de Alunos
                                            Matriculados por classe e idade <span>(incluindo os repetentes e os alunos com deficiência)</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="24" class="text-center bg-primary">Total alunos por idade</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2">Idades/Classes</th>
                                        <th colspan="2">5 Anos</th>
                                        <th colspan="2">6 Anos</th>
                                        <th colspan="2">7 Anos</th>
                                        <th colspan="2">8 Anos</th>
                                        <th colspan="2">9 Anos</th>
                                        <th colspan="2">10 ou mais Anos</th>
                                        <th colspan="2">Total</th>
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

                                        <th>MF</th>
                                        <th>F</th>

                                        <th>MF</th>
                                        <th>F</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    <tr>
                                        <td>Iniciação</td>

                                        <td>{{ $result->matriculados_masculino_5_anos ?? 0 }}</td>
                                        <td>{{ $result->matriculados_feminino_5_anos ?? 0 }}</td>

                                        <td>{{ $result->matriculados_masculino_6_anos ?? 0 }}</td>
                                        <td>{{ $result->matriculados_feminino_6_anos ?? 0 }}</td>

                                        <td>{{ $result->matriculados_masculino_7_anos ?? 0 }}</td>
                                        <td>{{ $result->matriculados_feminino_7_anos ?? 0 }}</td>

                                        <td>{{ $result->matriculados_masculino_8_anos ?? 0 }}</td>
                                        <td>{{ $result->matriculados_feminino_8_anos ?? 0 }}</td>

                                        <td>{{ $result->matriculados_masculino_9_anos ?? 0 }}</td>
                                        <td>{{ $result->matriculados_feminino_9_anos ?? 0 }}</td>

                                        <td>{{ $result->matriculados_masculino_10_anos ?? 0 }}</td>
                                        <td>{{ $result->matriculados_feminino_10_anos ?? 0 }}</td>

                                        <td>{{ $result->matriculados_masculino ?? 0 }}</td>
                                        <td>{{ $result->matriculados_feminino ?? 0 }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- table 02 --}}
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" style="width: 100%"
                                class="table table-bordered  ">
                                <thead>
                                    <tr>
                                        <th colspan="24" class="text-center bg-info">Quadro 2. Total de Alunos
                                            Repetentes por classe e idade <span>(que frequentam a mesma classes pela segunda ou mais vezes)</span>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="24" class="text-center bg-primary">Total de alunos por idade</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2">Idades/Classes</th>
                                        <th colspan="2">6 Anos</th>
                                        <th colspan="2">7 Anos</th>
                                        <th colspan="2">8 Anos</th>
                                        <th colspan="2">9 Anos</th>
                                        <th colspan="2">10 ou mais Anos</th>
                                        <th colspan="2">Total</th>
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

                                        <th>MF</th>
                                        <th>F</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    <tr>
                                        <td>Inicição</td>

                                        <td>{{ $result->repitentes_masculino_6_anos ?? 0 }}</td>
                                        <td>{{ $result->repitentes_feminino_6_anos ?? 0 }}</td>

                                        <td>{{ $result->repitentes_masculino_7_anos ?? 0 }}</td>
                                        <td>{{ $result->repitentes_feminino_7_anos ?? 0 }}</td>

                                        <td>{{ $result->repitentes_masculino_8_anos ?? 0 }}</td>
                                        <td>{{ $result->repitentes_feminino_8_anos ?? 0 }}</td>

                                        <td>{{ $result->repitentes_masculino_9_anos ?? 0 }}</td>
                                        <td>{{ $result->repitentes_feminino_9_anos ?? 0 }}</td>

                                        <td>{{ $result->repitentes_masculino_10_anos ?? 0 }}</td>
                                        <td>{{ $result->repitentes_feminino_10_anos ?? 0 }}</td>

                                        <td>{{ $result->repitentes_masculino ?? 0 }}</td>
                                        <td>{{ $result->repitentes_feminino ?? 0 }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- table 03 --}}
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" style="width: 100%"
                                class="table table-bordered  ">
                                <thead>
                                    <tr>
                                        <th colspan="8" class="text-center bg-info">Quadro 3. Turnos, Turma por classe
                                        </th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2"></th>
                                        @foreach ($turnos as $item)
                                            <th colspan="2" class="text-center">{{ $item->turno }}</th>
                                        @endforeach
                                        <th rowspan="2" class="text-center">Total Turmas</th>
                                    </tr>

                                    <tr>
                                        @foreach ($turnos as $item)
                                            <th>Turmas</th>
                                            <th>Turnos</th>
                                        @endforeach
                                    </tr>

                                </thead>
                                <tbody id="">
                                    @php
                                        $total_geral = 0;
                                    @endphp
                                    @foreach ($classes as $classe)
                                        <tr>
                                            @php
                                                $total_turma_final = 0;
                                            @endphp
                                            <td>{{ $classe->classes }}</td>
                                            @foreach ($turnos as $turno)
                                                @php
                                                    
                                                    $total_turno = App\Models\web\turmas\Turma::where('turnos_id', $turno->id)
                                                        ->where('classes_id', $classe->id)
                                                        ->distinct('turnos_id')
                                                        ->count();
                                                    
                                                    $total_turma = App\Models\web\turmas\Turma::where('turnos_id', $turno->id)
                                                        ->where('classes_id', $classe->id)
                                                        ->distinct('id')
                                                        ->count();
                                                    
                                                    $total_turma_final += $total_turma;
                                                @endphp
                                                <td>{{ $total_turma }}</td>
                                                <td>{{ $total_turno }}</td>
                                            @endforeach
                                            <td>{{ $total_turma_final }}</td>
                                        </tr>
                                        @php
                                            $total_geral += $total_turma_final;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- table 03 --}}
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" style="width: 100%"
                                class="table table-bordered  ">
                                <thead>
                                    <tr>
                                        <th colspan="12" class="text-center bg-info">Quadro 4. Alunos com deficiências
                                        </th>
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
                                        <th>{{ $result->estudante_dificiencia_visual_masculino ?? 0 }}</th>
                                        <th>{{ $result->estudante_dificiencia_visual_feminino ?? 0 }}</th>

                                        <th>{{ $result->estudante_dificiencia_auditiva_masculino ?? 0 }}</th>
                                        <th>{{ $result->estudante_dificiencia_auditiva_feminino ?? 0 }}</th>

                                        <th>{{ $result->estudante_dificiencia_motora_masculino ?? 0 }}</th>
                                        <th>{{ $result->estudante_dificiencia_motora_feminino ?? 0 }}</th>

                                        <th>{{ $result->estudante_dificiencia_outras_masculino ?? 0 }}</th>
                                        <th>{{ $result->estudante_dificiencia_outras_feminino ?? 0 }}</th>

                                        <th>{{ $result->estudante_dificiencia_visual_masculino ?? (0 + $result->estudante_dificiencia_auditiva_masculino ?? (0 + $result->estudante_dificiencia_motora_masculino ?? (0 + $result->estudante_dificiencia_outras_masculino ?? 0))) }}
                                        </th>
                                        <th>{{ $result->estudante_dificiencia_visual_feminino ?? (0 + $result->estudante_dificiencia_auditiva_feminino ?? (0 + $result->estudante_dificiencia_motora_feminino ?? (0 + $result->estudante_dificiencia_outras_feminino ?? 0))) }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    <tr>
                                        <td>Iniciação</td>

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

                {{-- professores por formação 03 --}}
                @include('sistema.direccao-provincial.formularios.quadros.professores-formacao')

                {{-- professores pode idade 03 --}}
                @include('sistema.direccao-provincial.formularios.quadros.professores-idade')

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" style="width: 100%"
                                class="table table-bordered  ">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="text-center bg-info">Quadro 7. Manuais Escolares
                                            recebidos</th>
                                    </tr>
                                    <tr>
                                        <th rowspan=""></th>
                                        <th colspan="" class="text-center">Professor</th>
                                        <th colspan="" class="text-center">Alunos</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    <tr>
                                        <td>Ficha de Inscricão</td>

                                        <td class="text-center">0</td>

                                        <td class="text-center">0</td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    <!-- /.content -->
@endsection


@section('scripts')
    <script>

        $("#provincia_id").change(() => {
            let id = $("#provincia_id").val();
            $.get('../carregar-municipios/' + id, function(data) {
                $("#municipio_id").html("")
                $("#municipio_id").html(data)
            })
        })

        $("#municipio_id").change(() => {
            let id = $("#municipio_id").val();
            $.get('../carregar-distritos/' + id, function(data) {
                $("#distrito_id").html("")
                $("#distrito_id").html(data)
            })
        })

        $("#municipio_id").change(() => {
            let id = $("#municipio_id").val();
            $.get('../carregar-escolas-municipio/' + id, function(data) {
                $("#shcools_id").html("")
                $("#shcools_id").html(data)
            })
        })

        $("#distrito_id").change(() => {
            let id = $("#distrito_id").val();
            $.get('../carregar-escolas-distrito/' + id, function(data) {
                $("#shcools_id").html("")
                $("#shcools_id").html(data)
            })
        })

        $("#shcools_id").change(() => {
            let id = $("#shcools_id").val();
            $.get('../carregar-ano-lectivos-escolas/' + id, function(data) {
                $("#ano_lectivo_id").html("")
                $("#ano_lectivo_id").html(data)
            })
        })

        $("#ano_lectivo_id").change(() => {
            let id = $("#ano_lectivo_id").val();
            $.get('../carregar-todas-turmas-anolectivos-escolas/' + id, function(data) {
                $("#turmas_id").html("")
                $("#turmas_id").html(data)
            })
        })
        
        $(function () {
            $("#carregarTabelaEstudantes").DataTable({
                language: {
                    url: "{{ asset('plugins/datatables/pt_br.json') }}"
                },
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
          }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
        });
        
        
    </script>
@endsection
