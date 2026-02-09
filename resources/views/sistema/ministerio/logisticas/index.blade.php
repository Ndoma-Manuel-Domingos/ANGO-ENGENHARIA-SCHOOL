@extends('layouts.admin')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Painel de Logistica</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('logisticas.index') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Distritos</li>
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
                <h5><i class="fas fa-info"></i> Controle geral de mercadorias</h5>
            </div>
        </div>
      </div>
      
      <div class="row">

        <!-- ./col -->
        <div class="col-lg-3 col-12 col-md-3">
            <!-- small box -->
            <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                <div class="inner">
                    <h3>.</h3>
    
                    <p>Stock de Mercadorias</p>
                </div>
                <div class="icon">
                    <i class="fas fa-map"></i>
                </div>
                <a href="{{ route('web.stock-mercadorias') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        
           <!-- ./col -->
        <div class="col-lg-3 col-12 col-md-3">
            <!-- small box -->
            <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                <div class="inner">
                  <h3>{{ number_format($total_mercadorias, 2, ',', '.') }}</h3>
    
                  <p>Novas Mercadorias</p>
                </div>
                <div class="icon">
                    <i class="fas fa-map"></i>
                </div>
                <a href="{{ route('web.mercadorias') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-lg-3 col-12 col-md-3">
            <!-- small box -->
            <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
                <div class="inner">
                    <h3>.</h3>
    
                    <p>Novos Tipos de Mercadorias</p>
                </div>
                <div class="icon">
                    <i class="fas fa-map"></i>
                </div>
                <a href="{{ route('web.tipos-mercadorias') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <div class="col-lg-3 col-12 col-md-3">
          <!-- small box -->
          <div class="small-box bg-light" style="border: 4px solid  {{ Auth::user()->color_fundo }}">
              <div class="inner">
                  <h3>.</h3>
  
                  <p>Distribuição de Mercadorias</p>
              </div>
              <div class="icon">
                  <i class="fas fa-map"></i>
              </div>
              <a href="{{ route('web.stock-mercadorias-distribuicao') }}" class="small-box-footer {{ Auth::user()->color_fundo }}">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>


    </div>
    

    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

