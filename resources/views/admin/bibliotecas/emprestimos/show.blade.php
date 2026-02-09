@extends('layouts.escolas')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Mais informação sobre o empréstimo</h1>
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
                            <h5>Dados do Empréstimo</h5>
                        </div>

                        <div class="card-body">
                            <table id="carregarTabela" style="width: 100%" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Ref</th>
                                        <th>Emprestado por</th>
                                        <th>Emprestado para</th>
                                        <th>Tipo Pessoa Emprestado</th>
                                    </tr>

                                    <tr>
                                        <td>{{ $registro->codigo_referencia }}</td>
                                        <td>{{ $registro->emprestado_por->nome }}</td>
                                        <td>
                                            @if ($registro->emprestado_para->acesso == 'estudante')
                                                <a
                                                    href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($registro->emprestado_para->funcionarios_id)) }}">
                                                    {{ $registro->emprestado_para->nome }}
                                                </a>
                                            @else
                                                @if ($registro->emprestado_para->acesso == 'professor')
                                                    <a
                                                        href="{{ route('web.mais-informacao-funcionarios', Crypt::encrypt($registro->emprestado_para->funcionarios_id)) }}">
                                                        {{ $registro->emprestado_para->nome }}
                                                    </a>
                                                @else
                                                    {{ $registro->emprestado_para->nome }}
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $registro->tipo_pessoa_para }}</td>
                                    </tr>

                                    <tr>
                                        <th>Hora do Empréstimo</th>
                                        <th>Hara Prevista de Entrega</th>
                                        <th>Data Empréstimo</th>
                                        <th>Data Prevista de Entrega</th>
                                    </tr>
                                    <tr>
                                        <td>{{ $registro->hora_emprestimo }}</td>
                                        <td>{{ $registro->hora_devolucao }}</td>
                                        <td>{{ $registro->data_emprestimo }}</td>
                                        <td>{{ $registro->data_prevista_devolucao }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-header">
                            <h5>Dados dos Livros</h5>
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
                                    @foreach ($registro->items as $item)
                                        <tr>
                                            <td>{{ $item->livro->id }}</td>
                                            <td>{{ $item->livro->nome }}</td>
                                            <td>{{ $item->livro->subtitulo }}</td>
                                            <td>{{ $item->livro->isbn }}</td>
                                            <td>{{ $item->livro->genero->nome }}</td>
                                            <td>{{ $item->livro->editora->nome }}</td>
                                            <td>{{ $item->livro->autor->nome }}</td>
                                            <td>{{ $item->livro->tipo_material->nome }}</td>
                                        </tr>
                                    @endforeach
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
