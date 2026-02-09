@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Classes</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Voltar</a></li>
                    <li class="breadcrumb-item active">Classes</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

{{-- cadastrar principal Ano Lectivo --}}
<div class="modal fade" id="modalFormCadastraClasses">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cadastrar Classes</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="ano_lectivo">Nome Classe</label>
                        <input type="text" name="nome_classes" class="form-control nome_classes">
                        <span class="text-danger error-text nome_classes_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="status_ano">Status</label>
                        <select name="status_classes" class="form-control status_classes" id="status_classes">
                            <option value="">Selecionar Status</option>
                            <option value="activo">Activo</option>
                            <option value="desactivo">Desactivo</option>
                        </select>
                        <span class="text-danger error-text status_classes_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="status_ano">Tipo</label>
                        <select name="tipo_classes" class="form-control tipo_classes" id="tipo_classes">
                            <option value="">Selecionar Tipo</option>
                            <option value="Transição">Transição</option>
                            <option value="Exame">Exame</option>
                        </select>
                        <span class="text-danger error-text tipo_classes_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="tipo_avaliacao_nota">Nota Avaliação</label>
                        <select name="tipo_avaliacao_nota" class="form-control tipo_avaliacao_nota" id="tipo_avaliacao_nota">
                            <option value="">Selecionar Nota de Avaliação</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                        </select>
                        <span class="text-danger error-text tipo_avaliacao_nota_error"></span>
                    </div>

                    <div class="mb-3">
                        <label for="categoria_classes" class="form-label">Categoria</label>
                        <textarea name="categoria_classes" class="form-control categoria_classes" id="categoria_classes" rows="3"></textarea>
                        <span class="text-danger error-text categoria_classes_error"></span>
                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary cadastrar_classes">Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

{{-- editar principal Ano Lectivo --}}
<div class="modal fade" id="modalFormEditarClasses">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Classes</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <input type="hidden" value="" class="editar_classe_id">

                    <div class="form-group col-md-6">
                        <label for="" class="form-label">Nome Classe</label>
                        <input type="text" name="nome_classes" class="form-control editar_nome_classes">
                        <span class="text-danger error-text nome_classes_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="status_classes">Status</label>
                        <select name="status_classes" class="form-control editar_status_classes" id="status_classes">
                            <option value="">Selecionar Status</option>
                            <option value="activo">Activo</option>
                            <option value="desactivo">Desactivo</option>
                        </select>
                        <span class="text-danger error-text status_classes_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="status_ano">Tipo</label>
                        <select name="tipo_classes" class="form-control editar_tipo_classes" id="tipo_classes">
                            <option value="">Selecionar Tipo</option>
                            <option value="Transição">Transição</option>
                            <option value="Exame">Exame</option>
                        </select>
                        <span class="text-danger error-text tipo_classes_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="tipo_avaliacao_nota">Nota Avaliação</label>
                        <select name="tipo_avaliacao_nota" class="form-control editar_tipo_avaliacao_nota" id="tipo_avaliacao_nota">
                            <option value="">Selecionar Nota de Avaliação</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                        </select>
                        <span class="text-danger error-text tipo_avaliacao_nota_error"></span>
                    </div>

                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Categoria</label>
                        <textarea class="form-control editar_categoria_classes" name="categoria_classes" id="exampleFormControlTextarea1" rows="3"></textarea>
                        <span class="text-danger error-text categoria_classes_error"></span>
                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success editar_classes_form">Actualizar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Cadastro, listar, editar e eliminar de classes. Classes para todos os anos. Busca avançada para melhorar na navegação do software.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        @if (Auth::user()->can('create: classe'))
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalFormCadastraClasses">Nova Classe</a>
                        @endif
                        <a href="{{ route('classes-imprmir') }}" class="btn-danger btn float-end mx-1" target="_blink">Imprimir PDF</a>
                        <a href="{{ route('classes-excel') }}" class="btn-success btn float-end mx-1" target="_blink">Imprimir Excel</a>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaClasses" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Classes</th>
                                    <th>Status</th>
                                    <th>Tipo</th>
                                    <th>Nota Avaliação</th>
                                    <th>Descrição</th>
                                    <th style="width: 170px;">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($listarClasses) != 0)
                                @foreach ($listarClasses as $item)
                                <tr>
                                    <td>00{{ $item->id }}</td>
                                    <td>{{ $item->classes }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->tipo }}</td>
                                    <td>{{ $item->tipo_avaliacao_nota }} Valores</td>
                                    <td>{{ $item->categoria }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('update: classe'))
                                                <a title="Editar Classe" id="{{ $item->id }}" class="editar_classes_id dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                @endif

                                                @if (Auth::user()->can('delete: classe'))
                                                <a title="Excluir Classe" id="{{ $item->id }}" class="delete_classes dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
                                                @endif

                                                @if (Auth::user()->can('update: classe') && Auth::user()->can('update: classe'))
                                                <a title="Activar ou desactivar Classe" id="{{ $item->id }}" class="activar_classes_id dropdown-item"><i class="fa fa-check"></i> Activar Desactivar</a>
                                                @endif
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

        // activar ou desactivar classes
        $(document).on('click', '.activar_classes_id', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');

            $.ajax({
                type: "GET"
                , url: "activar-classes/" + novo_id
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

        // Cadastrar
        $(document).on('click', '.cadastrar_classes', function(e) {
            e.preventDefault();

            var data = {
                'nome_classes': $('.nome_classes').val()
                , 'status_classes': $('.status_classes').val()
                , 'tipo_classes': $('.tipo_classes').val()
                , 'tipo_avaliacao_nota': $('.tipo_avaliacao_nota').val()
                , 'categoria_classes': $('.categoria_classes').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.cadastrar-classes') }}"
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
        $(document).on('click', '.delete_classes', function(e) {
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
                        , url: "excluir-classes/" + novo_id
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
        $(document).on('click', '.editar_classes_id', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');
            $("#modalFormEditarClasses").modal("show");

            $.ajax({
                type: "GET"
                , url: "editar-classes/" + novo_id
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.editar_nome_classes').val(response.classes.classes);
                    $('.editar_status_classes').val(response.classes.status);
                    $('.editar_tipo_classes').val(response.classes.tipo);
                    $('.editar_categoria_classes').val(response.classes.categoria);
                    $('.editar_classe_id').val(response.classes.id);
                    $('.editar_tipo_avaliacao_nota').val(response.classes.tipo_avaliacao_nota);


                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        // actualizar
        $(document).on('click', '.editar_classes_form', function(e) {
            e.preventDefault();

            var id = $('.editar_classe_id').val();
            var data = {
                'nome_classes': $('.editar_nome_classes').val()
                , 'status_classes': $('.editar_status_classes').val()
                , 'tipo_classes': $('.editar_tipo_classes').val()
                , 'tipo_avaliacao_nota': $('.editar_tipo_avaliacao_nota').val()
                , 'categoria_classes': $('.editar_categoria_classes').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT"
                , url: "editar-classes/" + id
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
        $("#carregarTabelaClasses").DataTable({
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
