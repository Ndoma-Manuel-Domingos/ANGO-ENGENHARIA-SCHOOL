@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Mais informação sobre o livro</h1>
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

<!-- /.modal -->
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ $registro->nome }}</h5>
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
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $registro->id }}</td>
                                    <td>{{ $registro->nome }}</td>
                                    <td>{{ $registro->subtitulo }}</td>
                                    <td>{{ $registro->isbn }}</td>
                                    <td>{{ $registro->genero->nome }}</td>
                                    <td>{{ $registro->editora->nome }}</td>
                                    <td>{{ $registro->autor->nome }}</td>
                                    <td>{{ $registro->tipo_material->nome }}</td>
                                </tr>
                            </tbody>
                            
                            <thead>
                                <tr>
                                    <th>Volume</th>
                                    <th>Edição</th>
                                    <th>Número de Paginas</th>
                                    <th>Idioma</th>
                                    <th>Estado</th>
                                    <th>Localização</th>
                                    <th>Data Publicação</th>
                                    <th>Data Aquisição</th>
                                </tr>
                            </thead>
                            
                            <tbody class="tbody">
                                <tr>
                                    <td>{{ $registro->volume ?? "indifinido" }}</td>
                                    <td>{{ $registro->edicao ?? "indifinido" }}</td>
                                    <td>{{ $registro->numero_paginas ?? "indifinido" }}</td>
                                    <td>{{ $registro->idioma ?? "indifinido" }}</td>
                                    <td>{{ $registro->status ?? "indifinido" }}</td>
                                    <td>{{ $registro->localizacao ?? "indifinido" }}</td>
                                    <td>{{ $registro->data_publicacao ?? "indifinido" }}</td>
                                    <td>{{ $registro->data_aquisicao ?? "indifinido" }}</td>
                                </tr>
                            </tbody>
                            
                            <thead>
                                <tr>
                                    <th>Codigo Inteiro</th>
                                    <th colspan="7">Observação</th>
                                </tr>
                            </thead>
                            
                            <tbody class="tbody">
                                <tr>
                                    <td>{{ $registro->codigo_interno ?? "indifinido" }}</td>
                                    <td colspan="7">{{ $registro->observacao ?? "sem resumo" }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="card-footer"></div>
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

