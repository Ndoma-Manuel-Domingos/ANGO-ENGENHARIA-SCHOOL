@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Estatística pedagógica</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Estatística</li>
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
                <h5><i class="fas fa-info"></i> Estatísticas geral {{ $anoLectivo->ano }}</h5>
            </div>
        </div>
      </div>

      <div class="row">

        <div class="col-12 col-sm-6 col-md-6">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-graduate"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Estudantes Antigos da Escola</span>
              <span class="info-box-number">
                {{ $matriculasAntigas }}
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-check"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Estudantes Novos</span>
              <span class="info-box-number">{{ $matriculasConfirmado }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-graduate"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Estudantes</span>
              <span class="info-box-number">
                {{ $matriculas }}
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-thumbs-up"></i></span>
  
              <div class="info-box-content">
                <span class="info-box-text">Total Estudantes Aptos</span>
                <span class="info-box-number">
                  {{ $aprovados }}
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
          <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-down"></i></span>
  
              <div class="info-box-content">
                <span class="info-box-text">Total Estudantes Não Aptos</span>
                <span class="info-box-number">{{ $reprovados }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
          <!-- /.col -->

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-minus"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Estudantes Desistentes</span>
              <span class="info-box-number">{{ $desistentes }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-tag"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Estudantes Transferidos</span>
              <span class="info-box-number">{{ $transferidos }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

      </div>
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