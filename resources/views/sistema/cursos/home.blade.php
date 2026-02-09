@extends('layouts.municipal')

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
                    <li class="breadcrumb-item"><a href="{{ route('home-municipal') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Cursos</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

{{-- cadastrar principal cuross --}}
<div class="modal fade" id="modalFormCadastraCursos">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cadastrar Cursos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="nome_cursos">Nome Curso</label>
                        <input type="text" name="nome_cursos" class="form-control nome_cursos">
                        <span class="text-danger error-text nome_cursos_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="abreviacao_cursos">Abreviação</label>
                        <input type="text" name="abreviacao_cursos" class="form-control abreviacao_cursos">
                        <span class="text-danger error-text abreviacao_cursos_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="area_formacao_cursos">Área de Formação</label>
                        <input type="text" name="area_formacao_cursos" class="form-control area_formacao_cursos">
                        <span class="text-danger error-text area_formacao_cursos_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="tipo_cursos">Tipo</label>
                        <select name="tipo_cursos" class="form-control tipo_cursos" id="tipo_cursos">
                            <option value="">Selecionar Tipo</option>
                            <option value="Técnico">Técnico</option>
                            <option value="Punível">Punível</option>
                            <option value="Administrativo">Administrativo</option>
                            <option value="Outros">Outros</option>
                        </select>
                        <span class="text-danger error-text tipo_cursos_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="status_cursos">Status</label>
                        <select name="status_cursos" class="form-control status_cursos" id="status_cursos">
                            <option value="">Selecionar Status</option>
                            <option value="activo">Activo</option>
                            <option value="desactivo">Desactivo</option>
                        </select>
                        <span class="text-danger error-text status_cursos_error"></span>
                    </div>

                    <div class="mb-3">
                        <label for="cursos" class="form-label">Descrição</label>
                        <textarea class="form-control descricao_cursos" name="descricao_cursos" id="descricao_cursos" rows="3"></textarea>
                        <span class="text-danger error-text descricao_cursos_error"></span>
                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary cadastrar_cursos">Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

{{-- editar principal cursos --}}
<div class="modal fade" id="modalFormEditarCursos">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Cursos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <input type="hidden" value="" class="editar_curso_id">
                    <div class="form-group col-md-6">
                        <label for="nome_cursos">Nome Curso</label>
                        <input type="text" name="nome_cursos" class="form-control editar_nome_cursos">
                        <span class="text-danger error-text nome_cursos_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="abreviacao_cursos">Abreviação</label>
                        <input type="text" name="abreviacao_cursos" class="form-control editar_abreviacao_cursos">
                        <span class="text-danger error-text abreviacao_cursos_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="area_formacao_cursos">Área de Formação</label>
                        <input type="text" name="area_formacao_cursos" class="form-control editar_area_formacao_cursos">
                        <span class="text-danger error-text area_formacao_cursos_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="tipo_cursos">Tipoo</label>
                        <select name="tipo_cursos" class="form-control editar_tipo_cursos" id="tipo_cursos">
                            <option value="">Selecionar Tipo</option>
                            <option value="">Selecionar Tipo</option>
                            <option value="Técnico">Técnico</option>
                            <option value="Punível">Punível</option>
                            <option value="Administrativo">Administrativo</option>
                            <option value="Outros">Outros</option>
                        </select>
                        <span class="text-danger error-text tipo_cursos_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="status_cursos">Status</label>
                        <select name="status_cursos" class="form-control editar_status_cursos" id="status_cursos">
                            <option value="">Selecionar Status</option>
                            <option value="activo">Activo</option>
                            <option value="desactivo">Desactivo</option>
                        </select>
                        <span class="text-danger error-text status_cursos_error"></span>
                    </div>

                    <div class="mb-3">
                        <label for="cursos" class="form-label">Descrição</label>
                        <textarea name="descricao_cursos" class="form-control editar_descricao_cursos" id="descricao_cursos" rows="3"></textarea>
                        <span class="text-danger error-text descricao_cursos_error"></span>
                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success editar_cursos_form">Actualizar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

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

                                            <div class="col-md-6 mb-3">
                                                <label for="nome_disciplinas">Discilpina</label>
                                                <select class="form-select nome_disciplinas select2" style="width: 100%;" data-placeholder="Selecione um conjunto de Disciplinas com mesmo tipo" multiple="multiple" id="ano_lectivo_turnos_id">
                                                    @if ($disciplinas)
                                                    @foreach ($disciplinas as $disciplina)
                                                    <option value="{{ $disciplina->id }}">{{ $disciplina->disciplina }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger error-text nome_disciplinas_error"></span>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="categoria_disciplina_curso">Tipo Formação</label>
                                                <select class="form-select categoria_disciplina_curso" aria-label="Default select example">
                                                    <option value="Formação Geral">Formação Geral</option>
                                                    <option value="Formação Especifica">Formação Especifica</option>
                                                    <option value="Opção">Opção</option>
                                                </select>
                                                <span class="text-danger error-text categoria_disciplina_curso_error"></span>
                                            </div>

                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary cadastrar_disciplinas_cursos">Salvar</button>
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
                    <table style="width: 100%" class="table projects  ">
                        <thead>
                            <th>Cod</th>
                            <th>Disciplina</th>
                            <th>Abreviação</th>
                            <th>Categoria</th>
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
                {{-- <button type="button" class="btn btn-success cadastrar_trimestre">Cadastrar Trimestre para este ano</button> --}}
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        @if (Auth::user()->can('create: curso'))
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalFormCadastraCursos">Novo Curso</a>
                        @endif
                        <a href="{{ route('cursos-imprmir') }}" class="btn-danger btn float-end mx-1" target="_blink"> <i class="fas fa-pdf"></i> Imprimir PDF</a>
                        <a href="{{ route('cursos-excel') }}" class="btn-success btn float-end mx-1" target="_blink"> Imprimir Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaCursos" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Curso</th>
                                    <th>Abreviação</th>
                                    <th>Tipo</th>
                                    <th>Área de Formação</th>
                                    <th>Status</th>
                                    <th style="width: 170px;"> Acções</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @if (count($listarCursos))
                                @foreach ($listarCursos as $item)
                                <tr>
                                    <td> 0{{ $item->id }}</td>
                                    <td>{{ $item->curso }}</td>
                                    <td>{{ $item->abreviacao }}</td>
                                    <td>{{ $item->tipo }}</td>
                                    <td>{{ $item->area_formacao }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('update: estado'))
                                                <a title="Activar ou desactivar Curso" id="{{ $item->id }}" class="activar_cursos_id dropdown-item"><i class="fa fa-check"></i> Activar Desactivar</a>
                                                @endif
                                                @if (Auth::user()->can('update: curso'))
                                                <a title="Editar Curso" id="{{ $item->id }}" class="editar_cursos_id dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                @endif
                                                @if (Auth::user()->can('delete: curso'))
                                                <a title="Excluir Curso" id="{{ $item->id }}" class="delete_cursos dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
                                                @endif
                                                @if (Auth::user()->can('read: disciplina curso'))
                                                <a title="Configuração Cursos" id="{{ $item->id }}" class="configurar_cursos dropdown-item"><i class="fa fa-cogs"></i> Configurar</a>
                                                @endif
                                                <a href="{{ route('disciplinas-curso-imprmir', $item->id) }}" target="_blink" title="Imprimir lista das disciplinas" class="dropdown-item"><i class="fa fa-print"></i> Imprimir lista de disciplinas</a>
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
@endsection


@section('scripts')
<script>
    $(function() {

        // configuração curso cadastrar disciplinas para curso
        $(document).on('click', '.configurar_cursos', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');
            $("#modalConfiguracaoCurso").modal("show");
            $("#cursos_select_id").val(novo_id);

            $.ajax({
                type: "GET"
                , url: "carregar-disciplinas-cursos/" + novo_id
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
                          <td>' + element.disciplina + '</td>\
                          <td>' + element.abreviacao + '</td>\
                          <td>' + element.categoria + '</td>\
                          <td>\
                                <button type="button" title="Adicionar Encarregado" value="' + element.id + '" class="deletar_disciplina_curso_updated btn-danger btn"><i class="fa fa-trash"></i></button>\
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

        // delete disciplina curso
        $(document).on('click', '.deletar_disciplina_curso_updated', function(e) {
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
                        , url: "excluir-disciplina-cursos/" + novo_id
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

        // activar ou desactivar ano lectivo 
        $(document).on('click', '.activar_cursos_id', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');

            $.ajax({
                type: "GET"
                , url: "activar-cursos/" + novo_id
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

        // cadastrar_disciplinas_cursos
        $(document).on('click', '.cadastrar_disciplinas_cursos', function(e) {
            e.preventDefault();

            var data = {
                'nome_disciplinas': $('.nome_disciplinas').val()
                , 'categoria_disciplina_curso': $('.categoria_disciplina_curso').val()
                , 'cursos_select_id': $('.cursos_select_id').val()
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

        // CAdastrar
        $(document).on('click', '.cadastrar_cursos', function(e) {
            e.preventDefault();

            var data = {
                'nome_cursos': $('.nome_cursos').val()
                , 'status_cursos': $('.status_cursos').val()
                , 'abreviacao_cursos': $('.abreviacao_cursos').val()
                , 'tipo_cursos': $('.tipo_cursos').val()
                , 'area_formacao_cursos': $('.area_formacao_cursos').val()
                , 'descricao_cursos': $('.descricao_cursos').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.cadastrar-cursos') }}"
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

        // delete
        $(document).on('click', '.delete_cursos', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');

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
                        , url: "excluir-cursos/" + novo_id
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

        // editar
        $(document).on('click', '.editar_cursos_id', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');
            $("#modalFormEditarCursos").modal("show");

            $.ajax({
                type: "GET"
                , url: "editar-curso/" + novo_id
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.editar_nome_cursos').val(response.cursos.curso);
                    $('.editar_abreviacao_cursos').val(response.cursos.abreviacao);
                    $('.editar_tipo_cursos').val(response.cursos.tipo);
                    $('.editar_area_formacao_cursos').val(response.cursos.area_formacao);
                    $('.editar_status_cursos').val(response.cursos.status);
                    $('.editar_descricao_cursos').val(response.cursos.descricao);
                    $('.editar_curso_id').val(response.cursos.id);
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        // actualizar
        $(document).on('click', '.editar_cursos_form', function(e) {
            e.preventDefault();

            var id = $('.editar_curso_id').val();
            var data = {
                'nome_cursos': $('.editar_nome_cursos').val()
                , 'status_cursos': $('.editar_status_cursos').val()
                , 'abreviacao_cursos': $('.editar_abreviacao_cursos').val()
                , 'tipo_cursos': $('.editar_tipo_cursos').val()
                , 'area_formacao_cursos': $('.editar_area_formacao_cursos').val()
                , 'descricao_cursos': $('.editar_descricao_cursos').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT"
                , url: "editar-curso/" + id
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
