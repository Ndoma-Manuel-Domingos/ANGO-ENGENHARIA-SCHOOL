@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Sincronização de cartões</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Sincronização</li>
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
            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-header text-center py-4">
                        <h3><i class="fas fa-file"></i></h3>
                        <h3>Regularizar Primeira Taxa</h3>
                        <p>
                            Faça sempre que tiver uma irregularidade nas sincronização de multas da primeira taxa das multas
                        </p>
                    </div>

                    <div class="card-body text-center">
                        <a href="{{ route('web.verificar-actualizacoes-cartao-primeira-taxa') }}" class="btn btn-outline-primary d-block my-4">Regularizar</a>

                        <p>Clicar em Regularizar</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-header text-center py-4">
                        <h3><i class="fas fa-file"></i></h3>
                        <h3>Regularizar Segunda Taxa</h3>
                        <p>
                            Faça sempre que tiver uma irregularidade nas sincronização de multas da segunda taxa das multas
                        </p>
                    </div>

                    <div class="card-body text-center">
                        <a href="{{ route('web.verificar-actualizacoes-cartao-segunda-taxa') }}" class="btn btn-outline-primary d-block my-4">Regularizar</a>

                        <p>Clicar em Regularizar</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-header text-center py-4">
                        <h3><i class="fas fa-file"></i></h3>
                        <h3>Regularizar Terceira Taxa</h3>
                        <p>
                            Faça sempre que tiver uma irregularidade nas sincronização de multas da terceira taxa das multas
                        </p>
                    </div>

                    <div class="card-body text-center">
                        <a href="{{ route('web.verificar-actualizacoes-cartao-terceira-taxa') }}" class="btn btn-outline-primary d-block my-4">Regularizar</a>

                        <p>Clicar em Regularizar</p>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
