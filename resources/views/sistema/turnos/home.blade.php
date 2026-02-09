@extends('layouts.municipal')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Turnos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home-municipal') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Turnos</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

{{-- cadastrar principal Ano Lectivo --}}
<div class="modal fade" id="modalFormCadastraTurnos">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cadastrar Turnos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="nome_turnos">Nome Turno</label>
                        <input type="text" name="nome_turnos" class="form-control nome_turnos">
                        <span class="text-danger error-text nome_turnos_error"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="horario_turnos">Horário do Turno</label>
                        <input type="text" name="horario_turnos" class="form-control horario_turnos">
                        <span class="text-danger error-text horario_turnos_error"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="status_ano">Status</label>
                        <select name="status_ano" class="form-control status_turnos" id="status_ano">
                            <option value="">Selecionar Status</option>
                            <option value="activo">Activo</option>
                            <option value="desactivo">Desactivo</option>
                        </select>
                        <span class="text-danger error-text status_turnos_error"></span>
                    </div>

                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Descrição</label>
                        <textarea class="form-control descricao_turnos" name="descricao" id="exampleFormControlTextarea1" rows="3"></textarea>
                        <span class="text-danger error-text descricao_turnos_error"></span>
                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary cadastrar_turnos">Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

{{-- editar principal Ano Lectivo --}}
<div class="modal fade" id="modalFormEditarTurnos">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Turnos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <input type="hidden" value="" class="editar_turno_id">

                    <div class="form-group col-md-4">
                        <label for="ano_lectivo">Nome Turno</label>
                        <input type="text" name="nome_turnos" class="form-control editar_nome_turnos">
                        <span class="text-danger error-text nome_turnos_error"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="horario_turnos">Horário do Turno</label>
                        <input type="text" name="horario_turnos" class="form-control editar_horario_turnos">
                        <span class="text-danger error-text horario_turnos_error"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="status_ano">Status</label>
                        <select name="status_ano" class="form-control editar_status_turnos" id="status_ano">
                            <option value="">Selecionar Status</option>
                            <option value="activo">Activo</option>
                            <option value="desactivo">Desactivo</option>
                        </select>
                        <span class="text-danger error-text status_turnos_error"></span>
                    </div>

                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Descrição</label>
                        <textarea class="form-control editar_descricao_turnos" name="descricao" id="exampleFormControlTextarea1" rows="3"></textarea>
                        <span class="text-danger error-text descricao_turnos_error"></span>
                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success editar_turnos_form">Actualizar</button>
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
                <div class="card">
                    <div class="card-header">
                        @if (Auth::user()->can('create: turno'))
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalFormCadastraTurnos">Novo Turno</a>
                        @endif
                        <a href="{{ route('turnos-imprmir') }}" class="btn-danger btn float-end mx-1" target="_blink"> Imprimir PDF</a>
                        <a href="{{ route('turnos-excel') }}" class="btn-success btn float-end mx-1" target="_blink"> Imprimir Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaTurnos" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Turnos</th>
                                    <th>Horário do Turno</th>
                                    <th>Status</th>
                                    <th>Descrição</th>
                                    <th style="width: 170px;">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($listarTurnos) != 0)
                                @foreach ($listarTurnos as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->turno }}</td>
                                    <td>{{ $item->horario }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->descricao }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('update: estado'))
                                                <a title="Activar ou desactivar Turno" id="{{ $item->id }}" class="activar_turnos_id dropdown-item"><i class="fa fa-check"></i> Activar e Desactivar</a>
                                                @endif
                                                @if (Auth::user()->can('update: turno'))
                                                <a title="Editar Turno" id="{{ $item->id }}" class="editar_turnos_id dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                @endif
                                                @if (Auth::user()->can('delete: turno'))
                                                <a title="Excluir Turno" id="{{ $item->id }}" class="delete_turnos dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
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

        // activar ou desactivar ano lectivo 
        $(document).on('click', '.activar_turnos_id', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');
            var load = $(".ajax_load");

            $.ajax({
                type: "GET"
                , url: "activar-turnos/" + novo_id
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
        $(document).on('click', '.cadastrar_turnos', function(e) {
            e.preventDefault();

            var load = $(".ajax_load");
            var data = {
                'nome_turnos': $('.nome_turnos').val()
                , 'status_turnos': $('.status_turnos').val()
                , 'horario_turnos': $('.horario_turnos').val()
                , 'descricao_turnos': $('.descricao_turnos').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.cadastrar-turnos') }}"
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
        $(document).on('click', '.delete_turnos', function(e) {
            e.preventDefault();
            var ano_lectivo_id = $(this).attr('id');
            var load = $(".ajax_load");

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
                        , url: "excluir-turnos/" + ano_lectivo_id
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
        $(document).on('click', '.editar_turnos_id', function(e) {
            e.preventDefault();
            var turnos_id = $(this).attr('id');
            var load = $(".ajax_load");
            $("#modalFormEditarTurnos").modal("show");


            $.ajax({
                type: "GET"
                , url: "editar-turno/" + turnos_id
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();

                    $('.editar_nome_turnos').val(response.turnos.turno);
                    $('.editar_status_turnos').val(response.turnos.status);
                    $('.editar_horario_turnos').val(response.turnos.horario);
                    $('.editar_descricao_turnos').val(response.turnos.descricao);
                    $('.editar_turno_id').val(response.turnos.id);

                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        // actualizar
        $(document).on('click', '.editar_turnos_form', function(e) {
            e.preventDefault();

            var id = $('.editar_turno_id').val();
            var load = $(".ajax_load");

            var data = {
                'nome_turnos': $('.editar_nome_turnos').val()
                , 'horario_turnos': $('.editar_horario_turnos').val()
                , 'status_turnos': $('.editar_status_turnos').val()
                , 'descricao_turnos': $('.editar_descricao_turnos').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT"
                , url: "editar-turno/" + id
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
        $("#carregarTabelaTurnos").DataTable({
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
