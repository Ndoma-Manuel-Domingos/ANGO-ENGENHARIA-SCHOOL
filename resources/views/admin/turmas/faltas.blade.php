@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Gestão de Faltas</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Faltas</li>
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
        <div class="col-12 col-md-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Marcação e Justfição de faltas</h5>
            </div>
        </div>
      </div>

      <div class="row">
      
        {{-- @if (Auth::user()->can('read: ano lectivo')) --}}
        <div class="col-lg-3 col-12 col-md-12">
            <!-- small box -->
            <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                <div class="inner">
                    <h3>.</h3>
                    <p>Gerar Lista de presença para estudantes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
                <a href="{{ route('web.faltas-turmas-estudantes') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        {{-- @endif --}}


        {{-- @if (Auth::user()->can('read: ano lectivo')) --}}
        <div class="col-lg-3 col-12 col-md-12">
            <!-- small box -->
            <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                <div class="inner">
                    <h3>.</h3>
                    <p>Geral Lista de presença dos professores</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
                <a href="{{ route('web.faltas-turmas-funcionarios') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        {{-- @endif --}}


        {{-- @if (Auth::user()->can('read: ano lectivo')) --}}
        <div class="col-lg-3 col-12 col-md-12">
            <!-- small box -->
            <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                <div class="inner">
                    <h3>.</h3>
                    <p>Justificar faltas de estudantes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
                <a href="{{ route('web.faltas-turmas-estudantes-justifcar') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        {{-- @endif --}}

      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@section('scripts')

  <script>
    
  </script>

@endsection