@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cursos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel de controle</a></li>
                    <li class="breadcrumb-item active">Cursos</li>
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
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        @if (Auth::user()->can('create: curso'))
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalCurso">Novo
                            Curso</a>
                        @endif
                        <a href="{{ route('web.cursos-pdf-ano-lectivo') }}" class="btn btn-danger float-end mx-1" target="_blink"> <i class="fas fa-pdf"></i> Imprimir PDF</a>
                        <a href="{{ route('web.cursos-excel-ano-lectivo') }}" class="btn btn-success float-end mx-1" target="_blink"> Imprimir Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaCursos" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Curso</th>
                                    <th>Total de Vagas</th>
                                    <th>Abreviação</th>
                                    <th>Tipo</th>

                                    @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                                    <th>Coordenador</th>
                                    <th>Faculdade</th>
                                    <th>Candidatura</th>
                                    <th>Duração</th>
                                    <th>Nº Max. Cadeira</th>
                                    @endif

                                    <th>Área de Formação</th>
                                    <th>Status</th>
                                    <th style="width: 100px;"> Acções </th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @if (count($cursos))
                                @foreach ($cursos as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->curso->curso }}</td>
                                    <td>{{ $item->total_vagas }}</td>
                                    <td>{{ $item->curso->abreviacao }}</td>
                                    <td>{{ $item->curso->tipo }}</td>

                                    @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                                    <td>{{ $item->coordenador->nome }}</td>
                                    <td>{{ $item->faculdade->nome }}</td>
                                    <td>{{ $item->candidatura->nome }}</td>
                                    <td>{{ $item->duracao }}</td>
                                    <td>{{ $item->max_cadeira }}</td>
                                    @endif

                                    <td>{{ $item->curso->area_formacao }}</td>
                                    <td>{{ $item->curso->status }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('update: curso'))
                                                <a title="Editar Cursos" id="{{ $item->id }}" class="editar dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                @endif
                                                @if (Auth::user()->can('delete: curso'))
                                                <a href="{{ route('ano-lectivo.excluir-cursos-ano-lectivo', $item->id) }}" title="Excluir Curso" id="{{ $item->id }}" class="dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
                                                @endif
                                                @if (Auth::user()->can('read: curso'))
                                                <a title="Configuração Cursos" id="{{ $item->curso->id }}" class="configurar_cursos dropdown-item"><i class="fa fa-cogs"></i> Configurar</a>
                                                @endif
                                                <a href="{{ route('disciplinas-curso-imprmir', $item->curso->id) }}" target="_blink" title="Imprimir lista das disciplinas" class="dropdown-item"><i class="fa fa-print"></i>
                                                    Imprimir lista de disciplinas</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Outros</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->


{{-- apresentar --}}
<div class="modal fade" id="modalConfiguracaoCurso">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Configuração do Curso de <span class="cursoACtivo"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class=""> {{--card card-primary card-tabs --}}
                            <div class="card-header p-0 pt-1">

                                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#formClasses" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Disciplinas</a>
                                    </li>
                                </ul>

                            </div>

                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-one-tabContent">

                                    <div class="tab-pane fade show active" id="formClasses" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                        <form class="row">
                                            <input type="hidden" id="cursos_select_id" class="cursos_select_id">

                                            <div class="col-md-4 mb-3 col-12" id="nome_disciplinas">
                                                <label for="nome_disciplinas">Discilpinas <span class="text-danger">*</span></label>
                                                <select class="form-control nome_disciplinas select2" style="width: 100%;" multiple="multiple">
                                                    @if ($disciplinas)
                                                    @foreach ($disciplinas as $disciplina)
                                                    <option value="{{ $disciplina->disciplina->id }}">{{ $disciplina->disciplina->disciplina }}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger error-text nome_disciplinas_error"></span>
                                            </div>

                                            <div class="col-md-4 mb-3 col-12" id="editar_nome_disciplinas">
                                                <label for="editar_nome_disciplinas">Discilpinas <span class="text-danger">*</span></label>
                                                <select class="form-control editar_nome_disciplinas" id="editar_nome_disciplinas">
                                                    @if ($disciplinas)
                                                    @foreach ($disciplinas as $disciplina)
                                                    <option value="{{ $disciplina->disciplina->id }}">{{ $disciplina->disciplina->disciplina }}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger error-text editar_nome_disciplinas_error"></span>
                                            </div>

                                            <div class="col-md-4 mb-3 col-12">
                                                <label for="categoria_disciplina_curso">Componentes de formação <span class="text-danger">*</span></label>
                                                <select class="form-control categoria_disciplina_curso" style="width: 100%">
                                                    <option value="">Selecionar</option>
                                                    @foreach ($categorias as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error-text categoria_disciplina_curso_error"></span>
                                            </div>

                                            <div class="col-md-4 mb-3 col-12">
                                                <label for="peso">Peso <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control peso" name="peso" placeholder="Informe o Peso da disciplina no curso">
                                                <span class="text-danger error-text peso_error"></span>
                                            </div>

                                            <input type="hidden" class="curso_disciplina_id" value="">

                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary cadastrar_disciplinas_cursos">Salvar</button>
                                                <button type="submit" class="btn btn-success editar_disciplinas_cursos">Actualizar</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <table id="carregarTabelaCursos2" style="width: 100%" class="table table-bordered  ">
                        <thead>
                            <th>Cod</th>
                            <th>Disciplina</th>
                            <th>Abreviação</th>
                            <th>Categoria</th>
                            <th>Peso</th>
                            <th>Acções</th>
                        </thead>

                        <tbody class="table_disciplinas_cursos">
                            {{-- carregamento automatico --}}
                        </tbody>
                    </table>
                </div>


            </div>
            <div class="modal-footer justify-content-between">
                <input type="hidden" value="" id="id_ano_trimestre">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modalCurso">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('web.cadastrar-cursos-ano-lectivo') }}" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cadastrar Cursos</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4 col-12">
                            <label for="cursos_id">Cursos <span class="text-danger">*</span></label>
                            <select name="cursos_id[]" class="form-control cursos_id select2" id="cursos_id" style="width: 100%;" data-placeholder="Selecione um conjunto de Curso" multiple="multiple">
                                <option value="">Selecione Curso</option>
                                @foreach ($lista_cursos as $item)
                                <option value="{{ $item->id }}">{{ $item->curso }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text cursos_id_error"></span>
                        </div>

                        @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")

                        <div class="form-group col-md-4 col-12">
                            <label for="faculdade_id">Faculdades <span class="text-danger">*</span></label>
                            <select name="faculdade_id" class="form-control faculdade_id select2" id="faculdade_id" style="width: 100%;" data-placeholder="Selecione a faculdade">
                                <option value="">Selecione Faculdades</option>
                                @foreach ($faculdades as $item)
                                <option value="{{ $item->id }}">{{ $item->faculdade->nome }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text faculdade_id_error"></span>
                        </div>

                        <div class="form-group col-md-4 col-12">
                            <label for="candidatura_id">Candidatura <span class="text-danger">*</span></label>
                            <select name="candidatura_id" class="form-control candidatura_id select2" id="candidatura_id" style="width: 100%;" data-placeholder="Selecione Candidatura">
                                <option value="">Selecione Candidatura</option>
                                @foreach ($candidaturas as $item)
                                <option value="{{ $item->id }}">{{ $item->candidatura->nome }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text candidatura_id_error"></span>
                        </div>

                        <div class="form-group col-md-4 col-12">
                            <label for="max_cadeira">Número Maximo de Cadeira</label>
                            <input type="number" name="max_cadeira" value="0" class="form-control max_cadeira" id="max_cadeira" placeholder="Número Maximo de Cadeira">
                            <span class="text-danger error-text max_cadeira_error"></span>
                        </div>

                        <div class="form-group col-md-4 col-12">
                            <label for="duracao">Duarção</label>
                            <input type="number" name="duracao" value="0" class="form-control duracao" id="duracao" placeholder="Duarção">
                            <span class="text-danger error-text duracao_error"></span>
                        </div>

                        <div class="form-group col-md-4 col-12">
                            <label for="coordenador_id">Coodernador <span class="text-danger">*</span> <a href="{{ route('web.outro-funcionarios-create') }}" class="float-right text-right">Cadastrar Coodernador</a></label>
                            <select name="coordenador_id" class="form-control coordenador_id select2" id="coordenador_id" style="width: 100%;" data-placeholder="Escolher o Decano">
                                <option value="">Selecione Coodernador</option>
                                @foreach ($lista_funcionarios as $item)
                                <option value="{{ $item->id }}">{{ $item->nome }} {{ $item->sobre_nome }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text coordenador_id_error"></span>
                        </div>

                        @endif

                        <div class="form-group col-md-4 col-12">
                            <label for="turnos_id">Número de Vagas</label>
                            <input type="number" name="total_vagas" value="0" class="form-control total_vagas" id="total_vagas" placeholder="Número de vagas para este Curso">
                            <span class="text-danger error-text total_vagas_error"></span>
                        </div>

                        <div class="form-group col-md-4 col-12">
                            <label for="ano_lectivo_id">Ano Lectivo <span class="text-danger">*</span></label>
                            <select name="ano_lectivo_id" class="form-control ano_lectivo_id" id="ano_lectivo_id">
                                @if ($ano_lectivo)
                                <option value="{{ $ano_lectivo->id }}">{{ $ano_lectivo->ano }}</option>
                                @endif
                            </select>
                            <span class="text-danger error-text ano_lectivo_id_error"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="modalCursoUpdate">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('web.cursos-update-ano-lectivo') }}" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar Curso</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="cursos_id">Cursos <span class="text-danger">*</span></label>
                            <select name="cursos_id" class="form-control cursos_id_edit" id="turnos_id" data-placeholder="Selecione um conjunto de Curso">
                                <option value="">Selecione Curso</option>
                                @foreach ($lista_cursos as $item)
                                <option value="{{ $item->id }}">{{ $item->curso }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text cursos_id_error"></span>
                        </div>

                        <input type="hidden" name="id" class="id">


                        @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")

                        <div class="form-group col-md-4 col-12">
                            <label for="faculdade_id">Faculdades <span class="text-danger">*</span></label>
                            <select name="faculdade_id" class="form-control faculdade_id" id="faculdade_id" style="width: 100%;" data-placeholder="Selecione a faculdade">
                                <option value="">Selecione Faculdades</option>
                                @foreach ($faculdades as $item)
                                <option value="{{ $item->id }}">{{ $item->faculdade->nome }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text faculdade_id_error"></span>
                        </div>

                        <div class="form-group col-md-4 col-12">
                            <label for="candidatura_id">Candidatura <span class="text-danger">*</span></label>
                            <select name="candidatura_id" class="form-control candidatura_id" id="candidatura_id" style="width: 100%;" data-placeholder="Selecione Candidatura">
                                <option value="">Selecione Candidatura</option>
                                @foreach ($candidaturas as $item)
                                <option value="{{ $item->id }}">{{ $item->candidatura->nome }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text candidatura_id_error"></span>
                        </div>

                        <div class="form-group col-md-4 col-12">
                            <label for="max_cadeira">Número Maximo de Cadeira</label>
                            <input type="number" name="max_cadeira" value="0" class="form-control max_cadeira" id="max_cadeira" placeholder="Número Maximo de Cadeira">
                            <span class="text-danger error-text max_cadeira_error"></span>
                        </div>

                        <div class="form-group col-md-4 col-12">
                            <label for="duracao">Duarção</label>
                            <input type="number" name="duracao" value="0" class="form-control duracao" id="duracao" placeholder="Duarção">
                            <span class="text-danger error-text duracao_error"></span>
                        </div>

                        <div class="form-group col-md-4 col-12">
                            <label for="coordenador_id">Coodernador <span class="text-danger">*</span> <a href="{{ route('web.outro-funcionarios-create') }}" class="float-right text-right">Cadastrar Coodernador</a></label>
                            <select name="coordenador_id" class="form-control coordenador_id" id="coordenador_id" style="width: 100%;" data-placeholder="Escolher o Decano">
                                <option value="">Selecione Coodernador</option>
                                @foreach ($lista_funcionarios as $item)
                                <option value="{{ $item->id }}">{{ $item->nome }} {{ $item->sobre_nome }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text coordenador_id_error"></span>
                        </div>

                        @endif

                        <div class="form-group col-md-4">
                            <label for="turnos_id">Número de Vagas</label>
                            <input type="number" name="total_vagas" value="0" class="form-control total_vagas" id="total_vagas" placeholder="Número de vagas para este Curso">
                            <span class="text-danger error-text total_vagas_error"></span>
                        </div>


                        <div class="form-group col-md-4">
                            <label for="ano_lectivo_id">Ano Lectivo <span class="text-danger">*</span></label>
                            <select name="ano_lectivo_id" class="form-control ano_lectivo_id" id="ano_lectivo_id">
                                @if ($ano_lectivo)
                                <option value="{{ $ano_lectivo->id }}">{{ $ano_lectivo->ano }}</option>
                                @endif
                            </select>
                            <span class="text-danger error-text ano_lectivo_id_error"></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.content -->
@endsection


@section('scripts')
<script>
    $(function() {

        $("#editar_nome_disciplinas").css({
            'display': 'none'
        });
        $(".editar_disciplinas_cursos").css({
            'display': 'none'
        });

        $(document).on('click', '.editar', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');
            $("#modalCursoUpdate").modal("show");

            $.ajax({
                type: "GET"
                , url: `cursos-ano-lectivo/${novo_id}/editar/`
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.cursos_id_edit').html("");
                    $('.cursos_id_edit').append('<option value="' + response.dados.curso.id + '" selected>' + response.dados.curso.curso + '</option>');
                    for (let index = 0; index < response.cursos.length; index++) {
                        $('.cursos_id_edit').append('<option value="' + response.cursos[index].id + '">' + response.cursos[index].curso + '</option>');
                    }
                    $('.total_vagas').val(response.dados.total_vagas)
                    $('.max_cadeira').val(response.dados.max_cadeira)

                    $('.duracao').val(response.dados.duracao)

                    $('.faculdade_id').val(response.dados.faculdade_id)
                    $('.candidatura_id').val(response.dados.candidatura_id)
                    $('.coordenador_id').val(response.dados.coordenador_id)

                    $('.id').val(response.dados.id)
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        // configuração curso cadastrar disciplinas para curso
        $(document).on('click', '.configurar_cursos', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');
            $("#modalConfiguracaoCurso").modal("show");
            $("#cursos_select_id").val(novo_id);


            $.ajax({
                type: "GET"
                , url: `../cursos/carregar-disciplinas-cursos/${novo_id}`
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.table_disciplinas_cursos').html("");
                    response.result.forEach(element => {
                        $('.table_disciplinas_cursos').append('<tr>\
                            <td>' + element.id + '</td>\
                            <td>' + element.disciplina.disciplina + '</td>\
                            <td>' + element.disciplina.abreviacao + '</td>\
                            <td>' + element.categoria.nome + '</td>\
                            <td>' + element.peso + '</td>\
                            <td>\
                              <button type="button" title="Eliminar Disciplina do Curso" value="' + element.id + '" class="deletar_disciplina_curso btn-danger btn"><i class="fa fa-trash"></i></button>\
                              <button type="button" title="Editar Disciplina do curso" value="' + element.id + '" class="editar_disciplina_curso btn-success btn"><i class="fa fa-edit"></i></button>\
                            </td>\
                          </tr>');
                    });

                    $('.cursoACtivo').text(" " + response.curso.curso);

                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });

        });

        // remover disciplina do horario da turma
        $(document).on('click', '.deletar_disciplina_curso', function(e) {
            e.preventDefault();
            var novo_id = $(this).val();
            Swal.fire({
                title: "Tens a certeza"
                , text: "Que desejas remover esta informação"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Sim, Apagar Estes dados!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "DELETE"
                        , url: "../cursos/excluir-disciplina-cursos/" + novo_id
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
                }
            });
        });


        // remover disciplina do horario da turma
        $(document).on('click', '.editar_disciplina_curso', function(e) {
            e.preventDefault();
            var novo_id = $(this).val();
            $.ajax({
                type: "GET"
                , url: "../cursos/editar-disciplina-cursos/" + novo_id
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.categoria_disciplina_curso').val(response.disiciplinas.categoria_id);
                    $('.editar_nome_disciplinas').val(response.disiciplinas.disciplinas_id);
                    $('.peso').val(response.disiciplinas.peso);
                    $('.curso_disciplina_id').val(response.disiciplinas.id);

                    $("#editar_nome_disciplinas").css({
                        'display': 'block'
                    });
                    $("#nome_disciplinas").css({
                        'display': 'none'
                    });

                    $(".cadastrar_disciplinas_cursos").css({
                        'display': 'none'
                    });
                    $(".editar_disciplinas_cursos").css({
                        'display': 'block'
                    });
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });


        // cadastrar_disciplinas_cursos
        $(document).on('click', '.cadastrar_disciplinas_cursos', function(e) {
            e.preventDefault();

            var data = {
                'nome_disciplinas': $('.nome_disciplinas').val()
                , 'categoria_disciplina_curso': $('.categoria_disciplina_curso').val()
                , 'cursos_select_id': $('.cursos_select_id').val()
                , 'peso': $('.peso').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.cadastrar-disciplinas-cursos') }}"
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

        // editar_disciplinas_cursos
        $(document).on('click', '.editar_disciplinas_cursos', function(e) {
            e.preventDefault();

            var id = $('.curso_disciplina_id').val();

            var data = {
                'nome_disciplinas': $('.editar_nome_disciplinas').val()
                , 'categoria_disciplina_curso': $('.categoria_disciplina_curso').val()
                , 'peso': $('.peso').val()
                , 'curso_disciplina_id': $('.curso_disciplina_id').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $.ajax({
                type: "PUT"
                , url: "../cursos/editar-disciplinas-cursos/" + id
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

    $(function() {
        $("#carregarTabelaCursos").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });

</script>
@endsection
