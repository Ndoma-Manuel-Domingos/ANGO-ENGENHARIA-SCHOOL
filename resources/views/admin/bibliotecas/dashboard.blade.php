@extends('layouts.escolas')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Painel Administrativo</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('biblioteca.controle') }}">Painel da Biblioteca</a></li>
                        <li class="breadcrumb-item active">Biblioteca</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                {{-- GERAL --}}

                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ count($livros) }}</h3>
                            <p>Livros</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="{{ route('livros.index') }}" class="small-box-footer bg-info">Mais Informação <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ count($editoras) }}</h3>
                            <p>Editoras</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="{{ route('editoras.index') }}" class="small-box-footer bg-info">Mais Informação <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ count($autores) }}</h3>
                            <p>Autores</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="{{ route('autores.index') }}" class="small-box-footer bg-info">Mais Informação <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ count($generos) }}</h3>
                            <p>Generos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="{{ route('genero-livros.index') }}" class="small-box-footer bg-info">Mais Informação <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ count($tipo_materias) }}</h3>
                            <p>Tipos de Materias</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="{{ route('tipos-materiais.index') }}" class="small-box-footer bg-info">Mais Informação <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>23</h3>
                            <p>Usuários</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="{{ route('web.concluir-pagamentos') }}" class="small-box-footer bg-info">Mais Informação
                            <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ count($emprestimos) }}</h3>
                            <p>Emprestimos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="{{ route('emprestimo-livros.index') }}" class="small-box-footer bg-info">Mais Informação
                            <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h3>{{ count($devolucoes) }}</h3>
                            <p>Devoluções</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="{{ route('devolucoes-emprestimo-livros.index') }}" class="small-box-footer bg-info">Mais
                            Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection

@section('scripts')
@endsection
