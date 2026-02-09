@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Empréstimos de Livros</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('biblioteca.controle') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Empréstimos</li>
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
                <h4 class="modal-title">Cadastrar Novo Empréstimo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="livro_id_cr">Livros </label>
                        <select name="livro_id[]" id="livro_id_cr" class="form-control livro_id  select2" style="width: 100%" placeholder="livro_id" style="width: 100%;" data-placeholder="Selecione um conjunto de Disciplinas" multiple="multiple">
                            <option value="">Escolher</option>
                            @foreach ($livros as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text livro_id_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="emprestado_para_id1">Emprestador </label>
                        <select name="emprestado_para_id" id="emprestado_para_id1" class="form-control emprestado_para_id select2" style="width: 100%" placeholder="emprestado_para_id">
                            <option value="">Escolher</option>
                            @foreach ($users as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text emprestado_para_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="data_emprestimo">Data do Empréstimo </label>
                        <input type="date" name="data_emprestimo" id="data_emprestimo" class="form-control data_emprestimo">
                        <span class="text-danger error-text data_emprestimo_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="data_prevista_devolucao">Data Prevista de Entrega </label>
                        <input type="date" name="data_prevista_devolucao" id="data_prevista_devolucao" class="form-control data_prevista_devolucao">
                        <span class="text-danger error-text data_prevista_devolucao_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="hora_emprestimo">Hora do Empréstimo </label>
                        <input type="time" name="hora_emprestimo" id="hora_emprestimo" class="form-control hora_emprestimo">
                        <span class="text-danger error-text hora_emprestimo_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="hora_devolucao">Hora da Devolução </label>
                        <input type="time" name="hora_devolucao" id="hora_devolucao" class="form-control hora_devolucao">
                        <span class="text-danger error-text hora_devolucao_error"></span>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="descricao">Descrição </label>
                        <textarea rows="5" name="descricao" id="descricao" class="form-control descricao"></textarea>
                        <span class="text-danger error-text descricao_error"></span>
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
                <h4 class="modal-title">Editar Empréstimo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" value="" class="editar_registro_id">
                    <div class="form-group col-md-6">
                        <label for="livro_id">Livros </label>
                        <select name="livro_id" id="livro_id" style="width: 100%" class="form-control select2 editar_livro_id" placeholder="livro_id">
                            <option value="">Escolher</option>
                            @foreach ($livros as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text livro_id_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="emprestado_para_id">Emprestador </label>
                        <select name="emprestado_para_id" style="width: 100%" id="emprestado_para_id" class="form-control editar_emprestado_para_id select2" placeholder="emprestado_para_id">
                            <option value="">Escolher</option>
                            @foreach ($users as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text emprestado_para_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="data_emprestimo">Data do Empréstimo </label>
                        <input type="date" name="data_emprestimo" id="data_emprestimo" class="form-control editar_data_emprestimo">
                        <span class="text-danger error-text data_emprestimo_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="data_prevista_devolucao">Data Prevista de Entrega </label>
                        <input type="date" name="data_prevista_devolucao" id="data_prevista_devolucao" class="form-control editar_data_prevista_devolucao">
                        <span class="text-danger error-text data_prevista_devolucao_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="hora_emprestimo">Hora do Empréstimo </label>
                        <input type="time" name="hora_emprestimo" id="hora_emprestimo" class="form-control editar_hora_emprestimo">
                        <span class="text-danger error-text hora_emprestimo_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="hora_devolucao">Hora Prevista de Entrega </label>
                        <input type="time" name="hora_devolucao" id="hora_devolucao" class="form-control editar_hora_devolucao">
                        <span class="text-danger error-text hora_devolucao_error"></span>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="descricao">Descrição </label>
                        <textarea rows="5" name="descricao" id="descricao" class="form-control editar_descricao"></textarea>
                        <span class="text-danger error-text descricao_error"></span>
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
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalFormCadastro">Novo Empréstimos</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Ref</th>
                                    <th>Emprestado por</th>
                                    <th>Emprestado para</th>
                                    <th>Tipo Pessoa Emprestado</th>
                                    <th>Estado</th>
                                    <th>Hora do Empréstimo</th>
                                    <th>Hara Prevista de Entrega</th>
                                    <th>Data Empréstimo</th>
                                    <th>Data Prevista de Entrega</th>
                                    <th style="width: 120px">Acções</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @foreach ($emprestimos as $item)
                                <tr>
                                    <td>{{ $item->codigo_referencia }}</td>
                                    <td>{{ $item->emprestado_por->nome }}</td>
                                    <td>
                                        @if ($item->emprestado_para->acesso == 'estudante')
                                        <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->emprestado_para->funcionarios_id)) }}">
                                            {{ $item->emprestado_para->nome }}
                                        </a>
                                        @else
                                        @if ($item->emprestado_para->acesso == 'professor')
                                        <a href="{{ route('web.mais-informacao-funcionarios', Crypt::encrypt($item->emprestado_para->funcionarios_id)) }}">
                                            {{ $item->emprestado_para->nome }}
                                        </a>
                                        @else
                                        {{ $item->emprestado_para->nome }}
                                        @endif
                                        @endif

                                    </td>
                                    <td>{{ $item->tipo_pessoa_para }}</td>
                                    <td>{{ $item->status == true ? 'Activo' : 'Devolvido' }}</td>
                                    <td>{{ $item->hora_emprestimo }}</td>
                                    <td>{{ $item->hora_devolucao }}</td>
                                    <td>{{ $item->data_emprestimo }}</td>
                                    <td>{{ $item->data_prevista_devolucao }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a title="Mais Detalhe" href="{{ route('emprestimo-livros.show', $item->id) }}" class="dropdown-item"><i class="fa fa-info"></i> Mais
                                                    Detalhe</a>
                                                <a title="Editar" id="{{ $item->id }}" href="#" class="editar_id dropdown-item"><i class="fa fa-edit"></i>
                                                    Editar</a>
                                                <a title="Excluir" id="{{ $item->id }}" href="#" class="delelte dropdown-item"><i class="fa fa-trash"></i>
                                                    Excluir</a>
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
        
    const tabelas = [
        "#carregarTabela"
    ,];
    tabelas.forEach(inicializarTabela);

    $(function() {
        // Cadastrar
        $(document).on('click', '.cadastrar', function(e) {
            e.preventDefault();
            var data = {
                'livro_id': $('.livro_id').val()
                , 'emprestado_para_id': $('.emprestado_para_id').val()
                , 'data_emprestimo': $('.data_emprestimo').val()
                , 'hora_emprestimo': $('.hora_emprestimo').val()
                , 'hora_devolucao': $('.hora_devolucao').val()
                , 'data_prevista_devolucao': $('.data_prevista_devolucao').val()
                , 'descricao': $('.descricao').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('emprestimo-livros.store') }}"
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
                        , url: `{{ route('emprestimo-livros.destroy', ':id') }}`.replace(
                            ':id', recordId)
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
                , url: `{{ route('emprestimo-livros.edit', ':id') }}`.replace(':id', recordId)
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.editar_livro_id').val(response.registro.livro_id);
                    $('.editar_emprestado_para_id').val(response.registro
                        .emprestado_para_id);
                    $('.editar_data_emprestimo').val(response.registro.data_emprestimo);
                    $('.editar_data_prevista_devolucao').val(response.registro
                        .data_prevista_devolucao);
                    $('.editar_hora_emprestimo').val(response.registro.hora_emprestimo);
                    $('.editar_hora_devolucao').val(response.registro.hora_devolucao);
                    $('.editar_descricao').val(response.registro.descricao);

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
                'livro_id': $('.editar_livro_id').val()
                , 'emprestado_para_id': $('.editar_emprestado_para_id').val()
                , 'data_emprestimo': $('.editar_data_emprestimo').val()
                , 'data_prevista_devolucao': $('.editar_data_prevista_devolucao').val()
                , 'descricao': $('.editar_descricao').val()
                , 'hora_emprestimo': $('.editar_hora_emprestimo').val()
                , 'hora_devolucao': $('.editar_hora_devolucao').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT"
                , url: `{{ route('emprestimo-livros.update', ':id') }}`.replace(':id', recordId)
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
