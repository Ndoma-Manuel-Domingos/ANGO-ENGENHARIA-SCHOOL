@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Processos de estudantes</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Estudantes</li>
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
              @if ($escola->modulo != "Basico")
                @if ($escola->categoria == 'Privado')
                  <h5><i class="fas fa-info"></i> Pesquisas dos processos dos estudantes, Ficha Técnica, declarações, pautas, mini pautas, Ficha Matricula, propinas, transportes etc...</h5>
                @else
                <h5><i class="fas fa-info"></i> Pesquisas dos processos dos estudantes, Ficha Técnica, declarações, pautas, mini pautas, Ficha Matricula etc...</h5>
                @endif
              @else
              <h5><i class="fas fa-info"></i> Pesquisas dos processos dos estudantes, Ficha Técnica, declarações, pautas, mini pautas, Ficha Matricula etc...</h5>
              @endif
            </div>
        </div>
      </div>

      <div class="row">

        @if ($escola->modulo != "Basico")
          @if ($escola->categoria == 'Privado')
          <div class="col-lg-6 col-6">
              <!-- small box -->
              <div class="small-box bg-light">
                  <div class="inner">
                  <h3>Total [{{ $NumeroProcesso }}]</h3>

                  <p><strong>Processos Financeiros</strong> [pagamentos de propinas, entre outros serviços] </p>
                  </div>
                  <div class="icon">
                  <i class="fas fa-book"></i>
                  </div>
                  <a href="{{ route('web.processos-financeiro-estudantes') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
              </div>
          </div>
          <!-- ./col -->
          @endif
        @endif

        <div class="col-lg-6 col-6">
            <!-- small box -->
            <div class="small-box bg-light">
                <div class="inner">
                <h3>Total [{{ $NumeroProcesso }}]</h3>

                <p><strong>Processos Pedagógicos</strong> [Documentos ficha de matricua, declarações <strong>...</strong>] </p>
                </div>
                <div class="icon">
                <i class="fas fa-book"></i>
                </div>
                <a href="{{ route('web.processos-pedagogicos-estudantes') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->

      </div>

    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection
