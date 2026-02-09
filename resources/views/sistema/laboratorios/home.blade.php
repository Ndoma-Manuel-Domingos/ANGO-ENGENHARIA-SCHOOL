@extends("layouts.{$loyout}")

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Laboratórios</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home-municipal') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Laboratórios</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

{{-- cadastrar principal Ano Lectivo --}}
<div class="modal fade" id="modalCadastro">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cadastrar Laboratórios</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6 col-12">
                        <label for="laboratorio">Designação</label>
                        <input type="text" name="laboratorio" class="form-control laboratorio">
                        <span class="text-danger error-text laboratorio_error"></span>
                    </div>

                    <div class="form-group col-md-6 col-12">
                        <label for="status">Status</label>
                        <select name="status" class="form-control status" id="status">
                            <option value="activo">Activo</option>
                            <option value="desactivo">Desactivo</option>
                        </select>
                        <span class="text-danger error-text status_error"></span>
                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary cadastrar_laboratorio">Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

{{-- editar principal Ano Lectivo --}}
<div class="modal fade" id="modelEdicacao">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Laboratório</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <input type="hidden" value="" class="editar_laboratorio_id">

                    <div class="form-group col-md-6 col-12">
                        <label for="ano_lectivo">Designação</label>
                        <input type="text" name="editar_laboratorio" class="form-control editar_laboratorio">
                        <span class="text-danger error-text editar_laboratorio_error"></span>
                    </div>

                    <div class="form-group col-md-6 col-12">
                        <label for="editar_status">Status</label>
                        <select name="editar_status" class="form-control editar_status" id="status">
                            <option value="activo">Activo</option>
                            <option value="desactivo">Desactivo</option>
                        </select>
                        <span class="text-danger error-text editar_status_error"></span>
                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success formulario_edicao">Actualizar</button>
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
                        @if (Auth::user()->can('create: laboratorio'))
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalCadastro">Novo Laboratório</a>
                        @endif
                        <a href="{{ route('laboratorios-imprmir') }}" class="btn btn-danger float-end mx-1" target="_blink"> Imprimir PDF</a>
                        <a href="{{ route('laboratorios-excel') }}" class="btn btn-success float-end mx-1" target="_blink"> Imprimir Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="tabelaEnsinos" style="width: 100%" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Designação</th>
                                    <th>Status</th>
                                    <th style="width: 170px;">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($datas) != 0)
                                @foreach ($datas as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('update: laboratorio'))
                                                <a title="Activar ou desactivar ensino" id="{{ $item->id }}" class="activar_laboratorios_id dropdown-item"><i class="fa fa-check"></i> Activar e Desactivar</a>
                                                @endif
                                                @if (Auth::user()->can('update: laboratorio'))
                                                <a title="Editar ensino" id="{{ $item->id }}" class="editar_laboratorio_item dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                @endif
                                                @if (Auth::user()->can('delete: laboratorio'))
                                                <a title="Excluir ensino" id="{{ $item->id }}" class="delete_laboratorio dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
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
        $(document).on('click', '.activar_laboratorios_id', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            var load = $(".ajax_load");

            $.ajax({
                type: "GET"
                , url: "activar-laboratorios/" + id
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
        $(document).on('click', '.cadastrar_laboratorio', function(e) {
            e.preventDefault();

            var load = $(".ajax_load");
            var data = {
                'laboratorio': $('.laboratorio').val()
                , 'status': $('.status').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.cadastrar-laboratorios') }}"
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
        $(document).on('click', '.delete_laboratorio', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
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
                        , url: "excluir-laboratorios/" + id
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
        $(document).on('click', '.editar_laboratorio_item', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            var load = $(".ajax_load");
            $("#modelEdicacao").modal("show");

            $.ajax({
                type: "GET"
                , url: "editar-laboratorio/" + id
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.editar_laboratorio').val(response.data.nome);
                    $('.editar_status').val(response.data.status);
                    $('.editar_laboratorio_id').val(response.data.id);
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        // actualizar
        $(document).on('click', '.formulario_edicao', function(e) {
            e.preventDefault();

            var id = $('.editar_laboratorio_id').val();
            var load = $(".ajax_load");

            var data = {
                'laboratorio': $('.editar_laboratorio').val()
                , 'status': $('.editar_status').val()
            , }


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT"
                , url: "editar-laboratorio/" + id
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
        $("#tabelaEnsinos").DataTable({
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
