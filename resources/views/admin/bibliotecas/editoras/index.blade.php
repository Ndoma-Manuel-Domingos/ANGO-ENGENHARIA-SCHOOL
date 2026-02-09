@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editoras</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('biblioteca.controle') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Editoras</li>
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
                <h4 class="modal-title">Cadastrar Editora</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control nome" placeholder="Nome">
                        <span class="text-danger error-text nome_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="nif">NIF</label>
                        <input type="text" name="nif" id="nif" class="form-control nif" placeholder="NIF">
                        <span class="text-danger error-text nif_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="email">E-mail <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="email" id="email" class="form-control email" placeholder="E-mail">
                        <span class="text-danger error-text email_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="telefone">Telefone <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="telefone" id="telefone" class="form-control telefone" placeholder="Telefone">
                        <span class="text-danger error-text telefone_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="website">Website <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="website" id="website" class="form-control website" placeholder="Website">
                        <span class="text-danger error-text website_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="ano_fundacao">Ano Fundação <span class="text-secondary">(opicional)</span></label>
                        <input type="date" name="ano_fundacao" id="ano_fundacao" class="form-control ano_fundacao">
                        <span class="text-danger error-text ano_fundacao_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="endereco">Endereço <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="endereco" id="endereco" class="form-control endereco" placeholder="Endereço">
                        <span class="text-danger error-text endereco_error"></span>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="observacao">Observação <span class="text-secondary">(opicional)</span></label>
                        <textarea name="observacao" rows="3" id="observacao" class="form-control observacao" placeholder="Observação"></textarea>
                        <span class="text-danger error-text observacao_error"></span>
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
                <h4 class="modal-title">Editar Editora</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" value="" class="editar_registro_id">
                    <div class="form-group col-md-6">
                        <label for="nome">Designação</label>
                        <input type="text" name="nome" id="nome" class="form-control editar_nome">
                        <span class="text-danger error-text nome_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="nif">NIF</label>
                        <input type="text" name="nif" id="nif" class="form-control editar_nif" placeholder="NIF">
                        <span class="text-danger error-text nif_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="email">E-mail <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="email" id="email" class="form-control editar_email" placeholder="E-mail">
                        <span class="text-danger error-text email_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="telefone">Telefone <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="telefone" id="telefone" class="form-control editar_telefone" placeholder="Telefone">
                        <span class="text-danger error-text telefone_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="website">Website <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="website" id="website" class="form-control editar_website" placeholder="Website">
                        <span class="text-danger error-text website_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="ano_fundacao">Ano Fundação <span class="text-secondary">(opicional)</span></label>
                        <input type="date" name="ano_fundacao" id="ano_fundacao" class="form-control editar_ano_fundacao">
                        <span class="text-danger error-text ano_fundacao_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="endereco">Endereço <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="endereco" id="endereco" class="form-control editar_endereco" placeholder="Endereço">
                        <span class="text-danger error-text endereco_error"></span>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="observacao">Observação <span class="text-secondary">(opicional)</span></label>
                        <textarea name="observacao" rows="3" id="observacao" class="form-control editar_observacao" placeholder="Observação"></textarea>
                        <span class="text-danger error-text observacao_error"></span>
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
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalFormCadastro">Nova Editora</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>NIF</th>
                                    <th>E-mail</th>
                                    <th>Telefone</th>
                                    <th>Website</th>
                                    <th>Data Fundação</th>
                                    <th>Endereço</th>
                                    <th style="width: 120px">Acções</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @foreach ($editoras as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ $item->nif }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->telefone }}</td>
                                    <td>{{ $item->website }}</td>
                                    <td>{{ $item->ano_fundacao }}</td>
                                    <td>{{ $item->endereco }}</td>
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
                , 'nif': $('.nif').val()
                , 'email': $('.email').val()
                , 'telefone': $('.telefone').val()
                , 'website': $('.website').val()
                , 'ano_fundacao': $('.ano_fundacao').val()
                , 'endereco': $('.endereco').val()
                , 'observacao': $('.observacao').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('editoras.store') }}"
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
                        , url: `{{ route('editoras.destroy', ':id') }}`.replace(':id', recordId)
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
                , url: `{{ route('editoras.edit', ':id') }}`.replace(':id', recordId)
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.editar_nome').val(response.registro.nome);
                    $('.editar_nif').val(response.registro.nif);
                    $('.editar_email').val(response.registro.email);
                    $('.editar_telefone').val(response.registro.telefone);
                    $('.editar_website').val(response.registro.website);
                    $('.editar_ano_fundacao').val(response.registro.ano_fundacao);
                    $('.editar_endereco').val(response.registro.endereco);
                    $('.editar_observacao').val(response.registro.observacao);


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
                , 'nif': $('.editar_nif').val()
                , 'email': $('.editar_email').val()
                , 'telefone': $('.editar_telefone').val()
                , 'website': $('.editar_website').val()
                , 'ano_fundacao': $('.editar_ano_fundacao').val()
                , 'endereco': $('.editar_endereco').val()
                , 'observacao': $('.editar_observacao').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT"
                , url: `{{ route('editoras.update', ':id') }}`.replace(':id', recordId)
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
