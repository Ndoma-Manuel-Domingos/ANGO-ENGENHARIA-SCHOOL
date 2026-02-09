@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Genero Livros</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('biblioteca.controle') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Genero Livros</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

{{-- cadastrar principal cuross --}}
<div class="modal fade" id="modalFormCadastro">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cadastrar Generos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="nome">Designação</label>
                        <input type="text" name="nome" class="form-control nome">
                        <span class="text-danger error-text nome_error"></span>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary cadastrar">Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<!-- /.modal -->
{{-- editar principal cursos --}}
<div class="modal fade" id="modalFormEditar">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Genero</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" value="" class="editar_registro_id">
                    <div class="form-group col-md-12">
                        <label for="nome">Designação</label>
                        <input type="text" name="nome" id="nome" class="form-control editar_nome">
                        <span class="text-danger error-text nome_error"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success updated">Actualizar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>

<!-- /.modal -->
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalFormCadastro">Novo Genero</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Designação</th>
                                    <th style="width: 120px">Acções</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @foreach ($generos as $item)
                                <tr>
                                    <td> 0{{ $item->id }}</td>
                                    <td>{{ $item->nome }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a title="Editar" id="{{ $item->id }}" href="#" class="editar_id dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                <a title="Excluir" id="{{ $item->id }}" href="#" class="delelte dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Outros</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
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
</section>
<!-- /.content -->
@endsection


@section('scripts')
<script>
    $(function() {
        // Cadastrar
        $(document).on('click', '.cadastrar', function(e) {
            e.preventDefault();
            var data = {
                'nome': $('.nome').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('genero-livros.store') }}"
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
        $(document).on('click', '.delelte', function(e) {
            e.preventDefault();
            var recordId = $(this).attr('id');

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
                        , url: `{{ route('genero-livros.destroy', ':id') }}`.replace(':id', recordId)
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
        $(document).on('click', '.editar_id', function(e) {
            e.preventDefault();
            var recordId = $(this).attr('id');
            $("#modalFormEditar").modal("show");

            $.ajax({
                type: "GET"
                , url: `{{ route('genero-livros.edit', ':id') }}`.replace(':id', recordId)
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.editar_nome').val(response.registro.nome);
                    $('.editar_registro_id').val(response.registro.id);

                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        // updated
        $(document).on('click', '.updated', function(e) {
            e.preventDefault();
            var recordId = $('.editar_registro_id').val();
            var data = {
                'nome': $('.editar_nome').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT"
                , url: `{{ route('genero-livros.update', ':id') }}`.replace(':id', recordId)
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

        $("#carregarTabela").DataTable({
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
