@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Painel administrativo geral</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">sistema</a></li>
                <li class="breadcrumb-item active">geral</li>
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
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Cadastrar, activar, desactivar, visualizar escolas</h5>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12 col-md-4">
                <!-- small box -->
                <div class="small-box bg-light">
                  <div class="inner">
                    <h3>{{ number_format($total_escola, 2, ',', '.') }}</h3>
                    
                    <p>Total de Escolas</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-university"></i>
                    </div>
                    <a href="{{ route('listagem-escola') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-12 col-md-4">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                    <h3>{{ number_format($total_estudante, 2, ',', '.') }}</h3>
            
                    <p>Total de Estudantes</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-user-graduate"></i>
                    </div>
                    <a href="{{ route('app.listagem-estudantes-geral') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-12 col-md-4">
              <!-- small box -->
              <div class="small-box bg-light">
                  <div class="inner">
                  <h3>{{ number_format($total_professores, 2, ',', '.') }}</h3>
          
                  <p>Total de Professores</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-user-tie"></i>
                  </div>
                  <a href="{{ route('app.professores-index') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
              </div>
          </div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <h3 class="m-0 text-white mb-3">Escola por província e estudantes dentro do Ensino.</h3>
            </div><!-- /.col -->
          </div>

          <div class="row">
             @foreach ($provincias as $item)
              <div class="col-12 col-md-2">
                <!-- small box -->
                <div class="small-box bg-light">
                  <div class="inner">
                    <h5><a href="{{ route('listagem-escola', [$item->id]) }}" class="text-decoration-none">{{ $item->nome }}</a></h5>
                    <p>Escolas - {{ $item->total_escola_provincia($item->id) }}</p>
                    <p>Estudantes - {{ $item->total_estudante_provincia($item->id) }}</p>
                    <p>Professores - {{ $item->total_professores_provincia($item->id) }}</p>
                  </div>
                  <a href="{{ route('listagem-escola', $item->name) }}" class="small-box-footer"></a>
                </div>
            </div>
            @endforeach            
          </div>
    
        </div>
      </div>
      
@endsection
