@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Serviços</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel de controle</a></li>
                    <li class="breadcrumb-item active">Serivço</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

{{-- editar principal cursos --}}
<div class="modal fade" id="modalFormEditarCalendarios">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Serviço</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <input type="hidden" class="editar_calendario_id" name="editar_calendario_id" id="editar_calendario_id">

                    <div class="form-group col-md-6">
                        <label for="editar_servico">Serviço <span class="text-danger">*</span></label>
                        <input type="text" name="editar_servico" class="form-control editar_servico" placeholder="Nome Serviço">
                        <span class="text-danger error-text servico_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="contas">Contas</label>
                        <select name="contas" id="contas" id="contas" class="form-control editar_contas">
                            <option value="">Selecione Contas <span class="text-danger">*</span></option>
                            <option value="dispesa">Contas a Pagar ou Despesas</option>
                            <option value="receita">Contas a Receber ou Receitas</option>
                        </select>
                        <span class="text-danger error-text editar_contas_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="editar_status">Status <span class="text-danger">*</span></label>
                        <select name="editar_status" id="editar_status" class="form-control editar_status">
                            <option value="">Selecione Status</option>
                            <option value="activo">Activo</option>
                            <option value="desactivo">Desactivo</option>
                        </select>
                        <span class="text-danger error-text editar_status_error"></span>
                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success editar_calendarios_form">Actualizar</button>
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
                    <h5><i class="fas fa-info"></i> Serviços, activar, desactivar, editar e eliminar serviços. Buscas avançadas e configurar serviços para turmas, funcionários ou escola.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        @if (Auth::user()->can('create: servicos'))
                        <a href="{{ route('web.calendarios-cadastrar') }}" class="btn btn-primary float-start"><i class="fas fa-plus"></i> Novo serviço</a>
                        <a href="{{ route('web.configuracao-turmas') }}" class="btn btn-primary float-end"><i class="fas fa-book"></i> Configurar serviços</a>
                        @endif
                        <a href="{{ route('calandarios-imprmir') }}" target="_blink" class="btn-danger btn float-start mx-1"><i class="fas fa-print"></i> Imprimir</a>
                        <a href="{{ route('calandarios-excel') }}" target="_blink" class="btn-success btn float-start mx-1"><i class="fas fa-print"></i> Imprimir Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaServicos" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Serviço</th>
                                    <th>Conta</th>
                                    <th>Tipo de Conta</th>
                                    <th>Taxa</th>
                                    <th>Status</th>
                                    <th style="width: 170px;">Acções </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($servicos) != 0)
                                @foreach ($servicos as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->servico }}</td>
                                    <td class="text-capitalize">{{ $item->conta }}</td>
                                    <td class="text-capitalize">{{ $item->contas }}</td>
                                    <td>{{ $item->taxa }} %</td>
                                    <td>{{ $item->status }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('update: servicos'))
                                                <a title="Editar Turma" href="{{ route('web.editar-servico', $item->id) }}" class="dropdown-item"><i class="fa fa-edit"></i> Editar<a>
                                                        @endif
                                                        @if (Auth::user()->can('delete: servicos'))
                                                        <a title="Apagar Disciplina" id="{{ $item->id }}" class="delete_calendarios dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
                                                        @endif
                                                        @if (Auth::user()->can('update: estado'))
                                                        <a title="Activar ou Desactivar" id="{{ $item->id }}" class="activar_calendarios_id dropdown-item"><i class="fa fa-check-square-o"></i> Activar Desactivar</a>
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

        // activar ou desactivar 
        $(document).on('click', '.activar_calendarios_id', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');

            $.ajax({
                type: "GET"
                , url: "activar-calendarios/" + novo_id
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
        $(document).on('click', '.delete_calendarios', function(e) {
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
                        , url: "excluir-calendarios/" + novo_id
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

    });


    $(function() {
        $("#carregarTabelaServicos").DataTable({
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
