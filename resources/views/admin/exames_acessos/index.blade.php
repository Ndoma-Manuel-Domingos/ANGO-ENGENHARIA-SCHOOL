@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Provas Exames de Acesso</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel de controle</a></li>
                    <li class="breadcrumb-item active">Inicio</li>
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
            @if ($escola->processo_admissao_estudante == 'Prova')
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>{{ $totalEstudantesExamesAcesso }}</h3>

                        <p>Provas de Exames de Acesso</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <a href="{{ route('web.estudantes-inscricao-exameAcesso') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            @endif
            
            @if ($escola->processo_admissao_estudante == 'Prova')
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>{{ $totalEstudantesExamesAcessoFeito }}</h3>

                        <p>Estudantes com Exames já Feitas.</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-check text-success"></i>
                    </div>
                    <a href="#" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            @endif
            

            @if ($escola->processo_admissao_estudante == 'Prova')
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>{{ $totalEstudantesExamesAcessoNaoFeito }}</h3>

                        <p>Estudantes com Exames Não Feitas.</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-times text-danger"></i>
                    </div>
                    <a href="#" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            @endif
        </div>

    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection
