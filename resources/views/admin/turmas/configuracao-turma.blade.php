@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Configuracão da Turma <a href="{{ route('web.apresentar-turma-informacoes', Crypt::encrypt($turma->id)) }}" class="text-secondary">{{ $turma->turma }}</a></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.turmas') }}">Turmas</a></li>
                    <li class="breadcrumb-item active">Configuração da turma</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card">
                    <div class="card-header p-0 bg-light">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            @if (Auth::user()->can('create: disciplina'))
                            <li class="nav-item mx-2 my-3">
                                <a class="active btn btn-primary" id="custom-tabs-one-home-tab" data-toggle="pill" href="#formDisciplinas" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">&plus; Disciplinas</a>
                            </li>
                            @endif

                            @if (Auth::user()->can('create: horario'))
                            <li class="nav-item mx-2 my-3">
                                <a class="btn btn-primary" id="custom-tabs-one-home-tab" data-toggle="pill" href="#formHorario" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">&plus; Horário</a>
                            </li>
                            @endif
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">

                            @if (Auth::user()->can('create: disciplina'))
                            <div class="tab-pane fade show active" id="formDisciplinas" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                <form action="{{ route('web.cadastrar-disciplinas-turmas') }}" method="POST" id="CreateDisciplina">
                                    @csrf
                                    <div class="card">
                                        <div class="card-body">
                                            <input type="hidden" id="turma_select_id" name="turma_select_id" class="turma_select_id" value="{{ $turma->id }}">
                                            <div class="row">
                                                <div class="col-md-2 col-12 mb-1">
                                                    <label for="disciplina_id" class="form-label">Disciplinas</label>
                                                    <select id="disciplina_id" name="disciplina_id[]" class="form-select disciplina_id select2" style="width: 100%;" data-placeholder="Selecione um conjunto de Disciplinas" multiple="multiple">
                                                        <option value="">Disciplinas</option>
                                                        @foreach ($disciplinasCurso as $item)
                                                        <option value="{{ $item->disciplina->id }}">{{ $item->disciplina->disciplina }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                                                <div class="col-md-2 col-12 mb-1">
                                                    <label for="trimestre_id" class="form-label">Semestres</label>
                                                    <select name="trimestre_id" id="trimestre_id" class="form-select trimestre_id select2" style="width: 100%;" data-placeholder="Selecione Semestre">
                                                        <option value="">Semestres</option>
                                                        @foreach ($trimestres as $item)
                                                        <option value="{{ $item->id }}">{{ $item->trimestre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2 col-12 mb-1">
                                                    <label for="peso_primeira_freq" class="form-label">Peso primeira frequência</label>
                                                    <input type="number" name="peso_primeira_freq" id="peso_primeira_freq" class="peso_primeira_freq form-control" placeholder="Peso primeira Frequencia">
                                                </div>

                                                <div class="col-md-2 col-12 mb-1">
                                                    <label for="peso_segunda_freq" class="form-label">Peso segunda frequência</label>
                                                    <input type="number" name="peso_segunda_freq" id="peso_segunda_freq" class="peso_segunda_freq form-control" placeholder="Peso segunda Frequencia">
                                                </div>
                                                @endif

                                                <div class="col-md-2 col-12 mb-1">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select name="status" id="status" class="form-select status select2" aria-label="Default select example" style="width: 100%">
                                                        <option value="Activo">Activo</option>
                                                        <option value="Desactivo">Desactivo</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary cadastrar_disciplinas_turmas">Salvar</button>
                                        </div>
                                    </div>
                                </form>

                                <div class="card">
                                    <div class="card-body">
                                        <table style="width: 100%" class="table table-bordered" id="carregarTabela1">
                                            <thead>
                                                <th>Code</th>
                                                <th>Disciplina</th>
                                                <th>Abreviação</th>
                                                @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                                                <th>Semestre</th>
                                                <th>Peso Primeira Frequência</th>
                                                <th>Peso Pegunda Frequência</th>
                                                @endif
                                                <th>Acções</th>
                                            </thead>
                                            <tbody class="disciplinas_turma_load">
                                                @foreach ($disciplinas as $item)
                                                <tr>
                                                    <td>{{ $item->disciplina->code }} </td>
                                                    <td>{{ $item->disciplina->disciplina }}</td>
                                                    <td>{{ $item->disciplina->abreviacao }}</td>
                                                    @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                                                    <td>{{ $item->trimestre->trimestre }}</td>
                                                    <td>{{ $item->peso_primeira_freq }}</td>
                                                    <td>{{ $item->peso_segunda_freq }}</td>
                                                    @endif
                                                    <td>
                                                        <button type="button" title="Remover disciplina da turma" id="{{ $item->id }}" class="deleteModal btn-danger btn"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer"></div>
                                </div>
                            </div>
                            @endif

                            @if (Auth::user()->can('create: horario'))
                            <div class="tab-pane fade show " id="formHorario" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                <form class="row" action="{{ route('web.cadastrar-horario-turmas') }}" id="CreateHorario" method="POST">
                                    @csrf
                                    <div class="card">
                                        <div class="card-body">
                                            <input type="hidden" id="turma_select_id" name="turma_select_id" class="turma_select_id" value="{{ $turma->id }}">
                                            <div class="row">
                                                <div class="col-md-2 mb-3">
                                                    <label for="nome_disciplinas_curso" class="form-label">Disciplina <span class="text-danger">*</span></label>
                                                    <select name="disciplinas_horario" class="form-select editar_disciplinas_horario disciplinas_horario" aria-label="Default select example" style="width: 100%">
                                                        <option value="">Disciplinas</option>
                                                        @foreach ($disciplinas as $item)
                                                        <option value="{{ $item->disciplina->id }}">{{ $item->disciplina->disciplina }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2 mb-3">
                                                    <label for="tempo_disciplina" class="form-label">Tempo <span class="text-danger">*</span></label>
                                                    <select id="tempo_disciplina" name="tempo_disciplina" class="form-select tempo_disciplina editar_tempo_disciplina" aria-label="Default select example" style="width: 100%">
                                                        <option value="">Selecione</option>
                                                        @foreach ($tempos as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nome }}º Tempo</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label for="dias_semanas_horario" class="form-label">Dias de Semanas <span class="text-danger">*</span></label>
                                                    @if ($semanas)
                                                    <select name="dias_semanas_horario" id="dias_semanas_horario" class="form-control editar_dias_semanas_horario dias_semanas_horario" style="width: 100%">
                                                        @foreach ($semanas as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger error-text dias_semanas_horario_error"></span>
                                                    @endif
                                                </div>

                                                <div class="form-group col-md-2 col-12">
                                                    <label for="professores_id" class="form-label">Professores</label>
                                                    <select name="professores_id" id="professores_id" class="form-control editar_professores_id professores_id" style="width: 100%">
                                                        <option value="">Selecione Prefossores</option>
                                                        @foreach ($professores as $professor)
                                                        <option value="{{ $professor->funcionario->id }}">{{ $professor->funcionario->nome }} {{ $professor->funcionario->sobre_nome }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                                <div class="col-md-2 mb-3">
                                                    <label for="nome_disciplinas_curso" class="form-label">Hora Inicio</label>
                                                    <input type="time" name="hora_inicio" id="hora_inicio" class="hora_inicio editar_hora_inicio form-control" placeholder="Informe a Hora do Incicio">
                                                </div>

                                                <div class="col-md-2 mb-3">
                                                    <label for="nome_disciplinas_curso" class="form-label">Hora Final</label>
                                                    <input type="time" name="hora_final" id="hora_final" class="hora_final editar_hora_final form-control" placeholder="Informe a Hora do Final">
                                                </div>

                                                <input type="hidden" class="editar_horario_id" value="">
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Salvar</button>
                                            <button type="button" style="display: none" id="btn_actualizar_horario" class="btn btn-success actualizar_horario_turmas">Actualizar</button>
                                        </div>
                                </form>
                            </div>

                            <div class="card">
                                <div class="card-body table-responsive">
                                    <table style="width: 100%" class="table table-bordered" id="carregarTabela">
                                        <thead>
                                            <tr>
                                                <th>Tempo</th>
                                                @foreach ($semanas as $semana)
                                                <th>{{ $semana->nome }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tempos as $tempo)
                                            <tr>
                                                <td>{{ $tempo->nome }}ª T</td>
                                                @foreach ($semanas as $semana)
                                                @php
                                                $horario = App\Models\web\turmas\Horario::with(["disciplina", "turma", "professor", "tempo", "semana"])
                                                ->where("turmas_id", $turma->id)
                                                ->where("semanas_id", $semana->id)
                                                ->where("tempos_id", $tempo->id)
                                                ->first();
                                                @endphp
                                                @if ($horario)
                                                <td>
                                                    <div>
                                                        <h4 class="h5">{{ $horario->disciplina->disciplina ?? "" }}</h4>
                                                        <p>
                                                            <small>{{ $horario->hora_inicio ?? "00:00" }} até {{ $horario->hora_final ?? "00:00" }}</small> <br>
                                                            <small>Prof: </small><strong>{{ $horario->professor->nome ?? "" }} {{ $horario->professor->sobre_nome ?? "" }}</strong> <br>
                                                        </p>
                                                        <button type="button" title="Excluir O horário do professor" id="{{ $horario->id }}" class="deleteModal2 btn btn-danger p-1"><i class="fa fa-times"></i></button>
                                                        <button type="button" title="Editar O horário do professor" value="{{ $horario->id }}" class="editar_disciplina_horario_id btn btn-success p-1"><i class="fa fa-edit"></i></button>
                                                    </div>
                                                </td>
                                                @else
                                                <td><strong>...</strong></td>
                                                @endif
                                                @endforeach
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                                <div class="card-footer"></div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                <div class="card-footer"></div>
            </div>
        </div>
    </div>
    </div>
</section>
<!-- /.content -->
@endsection

@section('scripts')

<script>
    $(function() {

        const tabelas = [
            "#carregarTabela"
            , "#carregarTabela1", 
        , ];
        tabelas.forEach(inicializarTabela);


        ajaxFormSubmit('#CreateDisciplina');
        ajaxFormSubmit('#CreateHorario');

        excluirRegistro('.deleteModal', `{{ route('web.remover-disciplina-turma', ':id') }}`);
        excluirRegistro('.deleteModal2', `{{ route('web.remover-horario-turma', ':id') }}`);

        // editar
        $(document).on('click', '.editar_disciplina_horario_id', function(e) {
            e.preventDefault();
            var novo_id = $(this).val();

            $('#btn_actualizar_horario').css({
                "display": "inline-block"
            });

            $.ajax({
                type: "GET"
                , url: "../editar-horario-turma/" + novo_id
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');

                    $('.editar_professores_id').val(response.horario.professor_id);
                    $('.editar_disciplinas_horario').val(response.horario.disciplinas_id);
                    $('.editar_hora_inicio').val(response.horario.hora_inicio);
                    $('.editar_hora_final').val(response.horario.hora_final);
                    $('.editar_tempo_disciplina').val(response.horario.tempos_id);
                    $('.editar_dias_semanas_horario').val(response.horario.semanas_id);
                    $('.editar_horario_id').val(response.horario.id);
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        // cadastrar horario
        $(document).on('click', '.actualizar_horario_turmas', function(e) {
            e.preventDefault();

            var data = {
                'professores_id': $('.editar_professores_id').val()
                , 'hora_inicio': $('.editar_hora_inicio').val()
                , 'hora_final': $('.editar_hora_final').val()
                , 'tempo_disciplina': $('.editar_tempo_disciplina').val()
                , 'dias_semanas': $('.editar_dias_semanas_horario').val()
                , 'disciplinas_horario': $('.editar_disciplinas_horario').val()
                , 'editar_horario_id': $('.editar_horario_id').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.editar-horario-turmas-update') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.location.reload();
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });

        });

    });

</script>


@endsection
