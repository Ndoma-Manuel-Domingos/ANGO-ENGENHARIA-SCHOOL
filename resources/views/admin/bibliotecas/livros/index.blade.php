@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Livros</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('biblioteca.controle') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Livros</li>
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
                <h4 class="modal-title">Cadastrar Livro</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="nome">Titulo</label>
                        <input type="text" name="nome" id="nome" class="form-control nome" placeholder="Nome">
                        <span class="text-danger error-text nome_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="subtitulo">Subtitulo</label>
                        <input type="text" name="subtitulo" id="subtitulo" class="form-control subtitulo" placeholder="subtitulo">
                        <span class="text-danger error-text subtitulo_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="isbn">ISBN <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="isbn" id="isbn" class="form-control isbn" placeholder="ISBN">
                        <span class="text-danger error-text isbn_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="codigo_interno">Codigo Interno <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="codigo_interno" id="codigo_interno" class="form-control codigo_interno" placeholder="codigo_interno">
                        <span class="text-danger error-text codigo_interno_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="autor_id">Autores <span class="text-secondary">(opicional)</span></label>
                        <select name="autor_id" id="autor_id" class="form-control autor_id" placeholder="autor_id">
                            <option value="">Escolher</option>
                            @foreach ($autores as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text autor_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="editora_id">Editoras <span class="text-secondary">(opicional)</span></label>
                        <select name="editora_id" id="editora_id" class="form-control editora_id" placeholder="editora_id">
                            <option value="">Escolher</option>
                            @foreach ($editoras as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text editora_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="tipo_material_id">Tipo Matérial <span class="text-secondary">(opicional)</span></label>
                        <select name="tipo_material_id" id="tipo_material_id" class="form-control tipo_material_id" placeholder="tipo_material_id">
                            <option value="">Escolher</option>
                            @foreach ($tipos_materiais as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text tipo_material_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="genero_id">Genero <span class="text-secondary">(opicional)</span></label>
                        <select name="genero_id" id="genero_id" class="form-control genero_id" placeholder="genero_id">
                            <option value="">Escolher</option>
                            @foreach ($generos as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text genero_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="edicao">Edição <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="edicao" id="edicao" class="form-control edicao" placeholder="edicao">
                        <span class="text-danger error-text edicao_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="volume">Volume <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="volume" id="volume" class="form-control volume" placeholder="volume">
                        <span class="text-danger error-text volume_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="numero_paginas">Número de Paginas <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="numero_paginas" id="numero_paginas" class="form-control numero_paginas" placeholder="numero_paginas">
                        <span class="text-danger error-text numero_paginas_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="idioma">Idioma <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="idioma" id="idioma" class="form-control idioma" placeholder="idioma">
                        <span class="text-danger error-text idioma_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="localizacao">Localização <span class="text-secondary">(opicional)</span></label>
                        <select name="localizacao" id="localizacao" class="form-control localizacao" placeholder="localizacao">
                            <option value="">Escolher</option>
                            <option value="Prateleira">Prateleira</option>
                            <option value="Estante">Estante</option>
                        </select>
                        <span class="text-danger error-text localizacao_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="status">Localização <span class="text-secondary">(opicional)</span></label>
                        <select name="status" id="status" class="form-control status" placeholder="status">
                            <option value="">Escolher</option>
                            <option value="Indisponível">Indisponível</option>
                            <option value="Danificado">Danificado</option>
                            <option value="Extraviado">Extraviado</option>
                            <option value="Disponível">Disponível</option>
                            <option value="Emprestado">Emprestado</option>
                        </select>
                        <span class="text-danger error-text status_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="data_publicacao">Data Publicação <span class="text-secondary">(opicional)</span></label>
                        <input type="date" name="data_publicacao" id="data_publicacao" class="form-control data_publicacao">
                        <span class="text-danger error-text data_publicacao_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="data_aquisicao">Data Aquisição <span class="text-secondary">(opicional)</span></label>
                        <input type="date" name="data_aquisicao" id="data_aquisicao" class="form-control data_aquisicao">
                        <span class="text-danger error-text data_aquisicao_error"></span>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="descricao">Descrição <span class="text-secondary">(opicional)</span></label>
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
                <h4 class="modal-title">Editar Livro</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" value="" class="editar_registro_id">
                    <div class="form-group col-md-6">
                        <label for="nome">Titulo</label>
                        <input type="text" name="nome" id="nome" class="form-control editar_nome" placeholder="Nome">
                        <span class="text-danger error-text editar_nome_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="subtitulo">Subtitulo</label>
                        <input type="text" name="subtitulo" id="subtitulo" class="form-control editar_subtitulo" placeholder="subtitulo">
                        <span class="text-danger error-text editar_subtitulo_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="isbn">ISBN <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="isbn" id="isbn" class="form-control editar_isbn" placeholder="ISBN">
                        <span class="text-danger error-text editar_isbn_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="codigo_interno">Codigo Interno <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="codigo_interno" id="codigo_interno" class="form-control editar_codigo_interno" placeholder="codigo_interno">
                        <span class="text-danger error-text editar_codigo_interno_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="autor_id">Autores <span class="text-secondary">(opicional)</span></label>
                        <select name="autor_id" id="autor_id" class="form-control editar_autor_id" placeholder="autor_id">
                            <option value="">Escolher</option>
                            @foreach ($autores as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text editar_autor_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="editora_id">Editoras <span class="text-secondary">(opicional)</span></label>
                        <select name="editora_id" id="editora_id" class="form-control editar_editora_id" placeholder="editora_id">
                            <option value="">Escolher</option>
                            @foreach ($editoras as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text editar_editora_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="tipo_material_id">Tipo Matérial <span class="text-secondary">(opicional)</span></label>
                        <select name="tipo_material_id" id="tipo_material_id" class="form-control editar_tipo_material_id" placeholder="tipo_material_id">
                            <option value="">Escolher</option>
                            @foreach ($tipos_materiais as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text editar_tipo_material_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="genero_id">Genero <span class="text-secondary">(opicional)</span></label>
                        <select name="genero_id" id="genero_id" class="form-control editar_genero_id" placeholder="genero_id">
                            <option value="">Escolher</option>
                            @foreach ($generos as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text editar_genero_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="edicao">Edição <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="edicao" id="edicao" class="form-control editar_edicao" placeholder="edicao">
                        <span class="text-danger error-text editar_edicao_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="volume">Volume <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="volume" id="volume" class="form-control editar_volume" placeholder="volume">
                        <span class="text-danger error-text editar_volume_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="numero_paginas">Número de Paginas <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="numero_paginas" id="numero_paginas" class="form-control editar_numero_paginas" placeholder="numero_paginas">
                        <span class="text-danger error-text editar_numero_paginas_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="idioma">Idioma <span class="text-secondary">(opicional)</span></label>
                        <input type="text" name="idioma" id="idioma" class="form-control editar_idioma" placeholder="idioma">
                        <span class="text-danger error-text editar_idioma_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="localizacao">Localização <span class="text-secondary">(opicional)</span></label>
                        <select name="localizacao" id="localizacao" class="form-control editar_localizacao" placeholder="localizacao">
                            <option value="">Escolher</option>
                            <option value="Prateleira">Prateleira</option>
                            <option value="Estante">Estante</option>
                        </select>
                        <span class="text-danger error-text editar_localizacao_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="status">Localização <span class="text-secondary">(opicional)</span></label>
                        <select name="status" id="status" class="form-control editar_status" placeholder="status">
                            <option value="">Escolher</option>
                            <option value="Indisponível">Indisponível</option>
                            <option value="Danificado">Danificado</option>
                            <option value="Extraviado">Extraviado</option>
                            <option value="Disponível">Disponível</option>
                            <option value="Emprestado">Emprestado</option>
                        </select>
                        <span class="text-danger error-text editar_status_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="data_publicacao">Data Publicação <span class="text-secondary">(opicional)</span></label>
                        <input type="date" name="data_publicacao" id="data_publicacao" class="form-control editar_data_publicacao">
                        <span class="text-danger error-text editar_data_publicacao_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="data_aquisicao">Data Aquisição <span class="text-secondary">(opicional)</span></label>
                        <input type="date" name="data_aquisicao" id="data_aquisicao" class="form-control editar_data_aquisicao">
                        <span class="text-danger error-text editar_data_aquisicao_error"></span>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="descricao">Descrição <span class="text-secondary">(opicional)</span></label>
                        <textarea rows="5" name="descricao" id="descricao" class="form-control editar_descricao"></textarea>
                        <span class="text-danger error-text editar_descricao_error"></span>
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
            <div class="col-md-12 col-12">
                <form>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <label for="" class="form-label">Livro</label>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="data_publicacao" class="form-label">Data Publicação</label>
                                    <input type="date" id="data_publicacao" name="data_publicacao" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary">Buscar <i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalFormCadastro">Novo Livro</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Titulo</th>
                                    <th>Subiitulo</th>
                                    <th>ISBN</th>
                                    <th>Genero</th>
                                    <th>Editora</th>
                                    <th>Autor</th>
                                    <th>Tipo</th>
                                    <th style="width: 120px">Acções</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @foreach ($livros as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ $item->subtitulo }}</td>
                                    <td>{{ $item->isbn }}</td>
                                    <td>{{ $item->genero->nome }}</td>
                                    <td>{{ $item->editora->nome }}</td>
                                    <td>{{ $item->autor->nome }}</td>
                                    <td>{{ $item->tipo_material->nome }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a title="Mais Detalhe" href="{{ route('livros.show', $item->id) }}" class="dropdown-item"><i class="fa fa-info"></i> Mais
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
    $(function() {
        // Cadastrar
        $(document).on('click', '.cadastrar', function(e) {
            e.preventDefault();
            var data = {
                'nome': $('.nome').val()
                , 'subtitulo': $('.subtitulo').val()
                , 'isbn': $('.isbn').val()
                , 'codigo_interno': $('.codigo_interno').val()
                , 'autor_id': $('.autor_id').val()
                , 'editora_id': $('.editora_id').val()
                , 'tipo_material_id': $('.tipo_material_id').val()
                , 'genero_id': $('.genero_id').val()
                , 'edicao': $('.edicao').val()
                , 'volume': $('.volume').val()
                , 'numero_paginas': $('.numero_paginas').val()
                , 'idioma': $('.idioma').val()
                , 'localizacao': $('.localizacao').val()
                , 'status': $('.status').val()
                , 'data_publicacao': $('.data_publicacao').val()
                , 'data_aquisicao': $('.data_aquisicao').val()
                , 'descricao': $('.descricao').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('livros.store') }}"
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
                        , url: `{{ route('livros.destroy', ':id') }}`.replace(':id'
                            , recordId)
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
                , url: `{{ route('livros.edit', ':id') }}`.replace(':id', recordId)
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.editar_nome').val(response.registro.nome);
                    $('.editar_subtitulo').val(response.registro.subtitulo);
                    $('.editar_isbn').val(response.registro.isbn);
                    $('.editar_codigo_interno').val(response.registro.codigo_interno);
                    $('.editar_autor_id').val(response.registro.autor_id);
                    $('.editar_editora_id').val(response.registro.editora_id);
                    $('.editar_tipo_material_id').val(response.registro
                        .tipo_material_id);
                    $('.editar_genero_id').val(response.registro.genero_id);
                    $('.editar_edicao').val(response.registro.edicao);
                    $('.editar_volume').val(response.registro.volume);
                    $('.editar_numero_paginas').val(response.registro.numero_paginas);
                    $('.editar_idioma').val(response.registro.idioma);
                    $('.editar_localizacao').val(response.registro.localizacao);
                    $('.editar_status').val(response.registro.status);
                    $('.editar_data_publicacao').val(response.registro.data_publicacao);
                    $('.editar_data_aquisicao').val(response.registro.data_aquisicao);
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
                'nome': $('.editar_nome').val()
                , 'subtitulo': $('.editar_subtitulo').val()
                , 'isbn': $('.editar_isbn').val()
                , 'codigo_interno': $('.editar_codigo_interno').val()
                , 'autor_id': $('.editar_autor_id').val()
                , 'editora_id': $('.editar_editora_id').val()
                , 'tipo_material_id': $('.editar_tipo_material_id').val()
                , 'genero_id': $('.editar_genero_id').val()
                , 'edicao': $('.editar_edicao').val()
                , 'volume': $('.editar_volume').val()
                , 'numero_paginas': $('.editar_numero_paginas').val()
                , 'idioma': $('.editar_idioma').val()
                , 'localizacao': $('.editar_localizacao').val()
                , 'status': $('.editar_status').val()
                , 'data_publicacao': $('.editar_data_publicacao').val()
                , 'data_aquisicao': $('.editar_data_aquisicao').val()
                , 'descricao': $('.editar_descricao').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT"
                , url: `{{ route('livros.update', ':id') }}`.replace(':id', recordId)
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
