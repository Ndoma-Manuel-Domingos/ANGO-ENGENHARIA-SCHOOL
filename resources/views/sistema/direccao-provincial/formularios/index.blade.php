@extends('layouts.provinciais')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Formulários e Fichas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
                        <li class="breadcrumb-item active">Formulários</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h2>:</h2>
                            <p>Formulário Iniciação</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <a href="{{ route('app.formulario.provincial.iniciacao') }}" class="small-box-footer">Mais Informação <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h2>:</h2>
                            <p>Formulário Primário Regular</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <a href="{{ route('app.formulario.provincial.primario.regular') }}" class="small-box-footer">Mais Informação <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h2>:</h2>
                            <p>Formulário Iº Cliclo Regular</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <a href="{{ route('app.formulario.provincial.primario-ciclo.regular') }}" class="small-box-footer">Mais
                            Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>


                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h2>:</h2>
                            <p>Formulário IIº Cliclo Regular</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <a href="{{ route('app.formulario.provincial.segundo-ciclo.regular') }}" class="small-box-footer">Mais
                            Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>



                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h2>:</h2>
                            <p>Ficha AFEP - Iniciação</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <a href="{{ route('app.formulario.provincial.ficha-afep-iniciacao') }}" class="small-box-footer">Mais
                            Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h2>:</h2>
                            <p>Ficha AFEP Ensino Primário Regular</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <a href="{{ route('app.formulario.provincial.ficha-afep-ensino-primario.regular') }}"
                            class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h2>:</h2>
                            <p>Ficha AFEP Ensino Iº Ciclo Regular</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <a href="{{ route('app.formulario.provincial.ficha-afep-ensino-primario-ciclo.regular') }}"
                            class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>


                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h2>:</h2>
                            <p>Ficha AFEP Ensino IIº Ciclo Regular</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <a href="{{ route('app.formulario.provincial.ficha-afep-ensino-segundo-ciclo.regular') }}"
                            class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
