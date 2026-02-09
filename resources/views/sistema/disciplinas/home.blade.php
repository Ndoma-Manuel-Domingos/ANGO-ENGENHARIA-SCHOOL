@extends('layouts.municipal')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Disciplinas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home-municipal') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Disciplinas</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

{{-- cadastrar principal cuross --}}
<div class="modal fade" id="modalFormCadastraDisciplinas">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cadastrar Disciplinas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="nome_disciplinas">Nome Disciplina</label>
                        <input type="text" name="nome_disciplinas" class="form-control nome_disciplinas">
                        <span class="text-danger error-text nome_disciplinas_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="abreviacao_disciplinas">Abreviação</label>
                        <input type="text" name="abreviacao_disciplinas" class="form-control abreviacao_disciplinas">
                        <span class="text-danger error-text abreviacao_disciplinas_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="code_disciplinas">Code</label>
                        <input type="text" name="code_disciplinas" class="form-control code_disciplinas">
                        <span class="text-danger error-text code_disciplinas_error"></span>
                    </div>

                    <div class="mb-3">
                        <label for="disciplinas" class="form-label">Descrição</label>
                        <textarea class="form-control descricao_disciplinas" name="descricao_disciplinas" id="descricao_disciplinas" rows="3"></textarea>
                        <span class="text-danger error-text descricao_disciplinas_error"></span>
                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary cadastrar_disciplinas">Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

{{-- editar principal cursos --}}
<div class="modal fade" id="modalFormEditarDisciplinas">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Disciplinas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <input type="hidden" value="" class="editar_disciplina_id">
                    <div class="form-group col-md-6">
                        <label for="nome_disciplinas">Nome Disciplinas</label>
                        <input type="text" name="nome_disciplinas" class="form-control editar_nome_disciplinas">
                        <span class="text-danger error-text nome_disciplinas_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="abreviacao_disciplinas">Abreviação</label>
                        <input type="text" name="abreviacao_disciplinas" class="form-control editar_abreviacao_disciplinas">
                        <span class="text-danger error-text abreviacao_disciplinas_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="code_disciplinas">Code</label>
                        <input type="text" name="code_disciplinas" class="form-control editar_code_disciplinas">
                        <span class="text-danger error-text code_disciplinas_error"></span>
                    </div>

                    <div class="mb-3">
                        <label for="disciplinas" class="form-label">Descrição</label>
                        <textarea name="descricao_disciplinas" class="form-control editar_descricao_disciplinas" id="descricao_disciplinas" rows="3"></textarea>
                        <span class="text-danger error-text descricao_disciplinas_error"></span>
                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success editar_disciplinas_form">Actualizar</button>
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
                        @if (Auth::user()->can('create: disciplina'))
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalFormCadastraDisciplinas">Nova Disciplinas</a>
                        @endif
                        <a href="{{ route('disciplinas-imprmir') }}" class="btn-danger btn float-end mx-1" target="_blink">Imprimir PDF</a>
                        <a href="{{ route('disciplinas-excel') }}" class="btn-success btn float-end mx-1" target="_blink">Imprimir Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaDisciplinas" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Disciplina</th>
                                    <th>Abreviação</th>
                                    <th>Code</th>
                                    <th style="width: 170px;">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($listarDisciplinas) != 0)
                                @foreach ($listarDisciplinas as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->disciplina }}</td>
                                    <td>{{ $item->abreviacao }}</td>
                                    <td>{{ $item->code }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('update: disciplina'))
                                                <a title="Editar Disciplina" id="{{ $item->id }}" class="editar_disciplinas_id dropdown-item"><i class="fa fa-edit"></i> Editar </a>
                                                @endif
                                                @if (Auth::user()->can('delete: disciplina'))
                                                <a title="Excluir Disciplina" id="{{ $item->id }}" class="delete_disciplinas dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
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
        $(document).on('click', '.cadastrar_disciplinas', function(e) {
            e.preventDefault();

            var data = {
                'nome_disciplinas': $('.nome_disciplinas').val()
                , 'abreviacao_disciplinas': $('.abreviacao_disciplinas').val()
                , 'code_disciplinas': $('.code_disciplinas').val()
                , 'descricao_disciplinas': $('.descricao_disciplinas').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.cadastrar-disciplinas') }}"
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
        $(document).on('click', '.delete_disciplinas', function(e) {
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
                        , url: "excluir-disciplinas/" + novo_id
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
        $(document).on('click', '.editar_disciplinas_id', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');
            $("#modalFormEditarDisciplinas").modal("show");

            $.ajax({
                type: "GET"
                , url: "editar-disciplinas/" + novo_id
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.editar_nome_disciplinas').val(response.disciplinas.disciplina);
                    $('.editar_abreviacao_disciplinas').val(response.disciplinas.abreviacao);
                    $('.editar_code_disciplinas').val(response.disciplinas.code);
                    $('.editar_descricao_disciplinas').val(response.disciplinas.descricao);
                    $('.editar_disciplina_id').val(response.disciplinas.id);
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        // actualizar
        $(document).on('click', '.editar_disciplinas_form', function(e) {
            e.preventDefault();

            var id = $('.editar_disciplina_id').val();
            var data = {
                'nome_disciplinas': $('.editar_nome_disciplinas').val()
                , 'abreviacao_disciplinas': $('.editar_abreviacao_disciplinas').val()
                , 'code_disciplinas': $('.editar_code_disciplinas').val()
                , 'descricao_disciplinas': $('.editar_descricao_disciplinas').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT"
                , url: "editar-disciplinas/" + id
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
        $("#carregarTabelaDisciplinas").DataTable({
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
