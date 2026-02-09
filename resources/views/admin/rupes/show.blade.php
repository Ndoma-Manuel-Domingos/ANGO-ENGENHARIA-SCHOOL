@extends('layouts.escolas')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Detalhe Escola</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('web.escolas-afilhares.index', ) }}">Voltar</a></li>
            <li class="breadcrumb-item active">Detalhe</li>
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

            {{-- @if (Auth::user()->can('read: ano lectivo')) --}}
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>{{ $total_matriculas }}</h3>
                        <p>Estudantes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <a href="{{ route('web.escolas-afilhares.estudantes', Crypt::encrypt($escolas->id)) }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            {{-- @endif --}}

            {{-- @if (Auth::user()->can('read: ano lectivo')) --}}
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                    <div class="inner">
                        <h3>.</h3>
                        <p>Adicionar Estudantes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <a href="{{ route('web.escolas-afilhares.create-estudantes',  Crypt::encrypt($escolas->id)) }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            {{-- @endif --}}
        </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection
