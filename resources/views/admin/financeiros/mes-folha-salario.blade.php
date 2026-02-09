@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Folha de Salário</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">Voltar</li>
            <li class="breadcrumb-item active">Folha Salario</li>
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
                <h5><i class="fas fa-info"></i> Folha de salário de funcionários de todos os meses. Clica em um dos mês para visualizar e ter Mais informações  . </h5>
            </div>
        </div>
      </div>

    @if ($meses)
      <div class="row">
            @foreach ($meses as $item)
                <div class="col-lg-2 col-6">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                        <h4>{{ $item->meses }}</h4>

                        <p>.</p>
                        </div>
                        <div class="icon">
                        <i class="fas fa-print"></i>
                        </div>
                        <a href="{{ route('down.ficha-salario-mensal', $item->id) }}" target="_blank" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>     
            @endforeach
            <div class="col-lg-12 col-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                    <h4>Geral</h4>

                    <p>Falha de Salário</p>
                    </div>
                    <div class="icon">
                    <i class="fas fa-print"></i>
                    </div>
                    <a href="{{ route('down.ficha-salario') }}" target="_blank" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div> 
      </div>        
    @endif


    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection
