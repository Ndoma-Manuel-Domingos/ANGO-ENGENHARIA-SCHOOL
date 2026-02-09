@extends('layouts.municipal')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Candidaturas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home-municipal') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Candidaturas</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

{{-- cadastrar principal cuross --}}
<div class="modal fade" id="modalFormCadastraCandidatura">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cadastrar Candidatura</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="nome_candidaturas">Nome</label>
                        <input type="text" name="nome_candidaturas" class="form-control nome_candidaturas">
                        <span class="text-danger error-text nome_candidaturas_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="status_candidaturas">Estado</label>
                        <select name="status_candidaturas" id="status_candidaturas" class="form-control status_candidaturas select2" style="width: 100%">
                            <option value="">Selecionar</option>
                            <option value="activo">Activo</option>
                            <option value="desactivo">Desactivo</option>
                        </select>
                        <span class="text-danger error-text status_candidaturas_error"></span>
                    </div>

                    <div class="mb-3">
                        <label for="descricao_candidaturas" class="form-label">Descrição</label>
                        <textarea class="form-control descricao_candidaturas" name="descricao_candidaturas" id="descricao_candidaturas" rows="3"></textarea>
                        <span class="text-danger error-text descricao_candidaturas_error"></span>
                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary cadastrar_candidaturas">Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

{{-- editar principal cursos --}}
<div class="modal fade" id="modalFormEditarCandidaturas">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Candidatura</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <input type="hidden" value="" class="editar_candidatura_id">
                    <div class="form-group col-md-6">
                        <label for="nome_candidaturas">Nome</label>
                        <input type="text" name="nome_candidaturas" class="form-control editar_nome_candidaturas">
                        <span class="text-danger error-text nome_candidaturas_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="editar_status_candidaturas">Estados</label>
                        <select name="status_candidaturas" id="editar_status_candidaturas" class="form-control editar_status_candidaturas select2" style="width: 100%">
                            <option value="">Selecionar</option>
                            <option value="activo">Activo</option>
                            <option value="desactivo">Desactivo</option>
                        </select>
                        <span class="text-danger error-text status_candidaturas_error"></span>
                    </div>

                    <div class="mb-3">
                        <label for="disciplinas" class="form-label">Descrição</label>
                        <textarea name="descricao_candidaturas" class="form-control editar_descricao_candidaturas" id="descricao_candidaturas" rows="3"></textarea>
                        <span class="text-danger error-text descricao_candidaturas_error"></span>
                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success editar_candidatura_form">Actualizar</button>
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
                        @if (Auth::user()->can('create: candidatura'))
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalFormCadastraCandidatura">Nova Candidatura</a>
                        @endif
                        <a href="{{ route('candidaturas-imprmir') }}" class="btn btn-danger float-end mx-1" target="blink">Imprimir PDF</a>
                        <a href="{{ route('candidaturas-excel') }}" class="btn btn-success float-end mx-1" target="blink">Imprimir Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaCandidaturas" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Candidatura</th>
                                    <th>Estados</th>
                                    <th style="width: 170px;">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($candidaturas) != 0)
                                @foreach ($candidaturas as $key => $item)
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
                                                @if (Auth::user()->can('update: candidatura'))
                                                <a title="Editar Candidatura" id="{{ $item->id }}" class="editar_candidaturas_id dropdown-item"><i class="fa fa-edit"></i> Editar </a>
                                                @endif
                                                @if (Auth::user()->can('delete: candidatura'))
                                                <a title="Excluir candidatura" id="{{ $item->id }}" class="delete_candidaturas dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
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

        // CAdastrar
        $(document).on('click', '.cadastrar_candidaturas', function(e) {
            e.preventDefault();

            var data = {
                'nome_candidaturas': $('.nome_candidaturas').val()
                , 'status_candidaturas': $('.status_candidaturas').val()
                , 'descricao_candidaturas': $('.descricao_candidaturas').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.cadastrar-candidaturas') }}"
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
        $(document).on('click', '.delete_candidaturas', function(e) {
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
                        , url: "excluir-candidaturas/" + novo_id
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
        $(document).on('click', '.editar_candidaturas_id', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');
            $("#modalFormEditarCandidaturas").modal("show");

            $.ajax({
                type: "GET"
                , url: "editar-candidaturas/" + novo_id
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.editar_nome_candidaturas').val(response.candidatura.nome);
                    $('.editar_status_candidaturas').val(response.candidatura.status);
                    $('.editar_descricao_candidaturas').val(response.candidatura.descricao);
                    $('.editar_candidatura_id').val(response.candidatura.id);
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        // actualizar
        $(document).on('click', '.editar_candidatura_form', function(e) {
            e.preventDefault();

            var id = $('.editar_candidatura_id').val();
            var data = {
                'nome_candidaturas': $('.editar_nome_candidaturas').val()
                , 'status_candidaturas': $('.editar_status_candidaturas').val()
                , 'descricao_candidaturas': $('.editar_descricao_candidaturas').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT"
                , url: "editar-candidaturas/" + id
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
        $("#carregarTabelaCandidaturas").DataTable({
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
