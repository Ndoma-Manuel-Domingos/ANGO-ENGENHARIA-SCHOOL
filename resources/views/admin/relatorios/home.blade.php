@extends('layouts.escolas')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Relatórios</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Painel Administrativo</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <div class="content">
    <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                      <h2>:</h2>
                      <p>Estudantes por Turma.</p>
                    </div>
                    <div class="icon">
                    <i class="fas fa-address-book"></i>
                    </div>
                    <a href="{{ route('web.relatorios-turmas-app') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                      <h2>:</h2>
                      <p>Estudantes por Curso</p>
                    </div>
                    <div class="icon">
                    <i class="fas fa-user-shield"></i>
                    </div>
                    <a href="{{ route('web.relatorios-cursos-app') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                      <h2>:</h2>
                      <p>Estudantes por Turno.</p>
                    </div>
                    <div class="icon">
                    <i class="fas fa-user-clock"></i>
                    </div>
                    <a href="{{ route('web.relatorios-turnos-app') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                      <h2>:</h2>
                      <p>Estudantes por Classe.</p>
                    </div>
                    <div class="icon">
                    <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('web.relatorios-classes-app') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

              <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                      <h2>:</h2>
                      <p>Estudantes Novos.</p>
                    </div>
                    <div class="icon">
                    <i class="fas fa-user-plus"></i>
                    </div>
                    <a href="{{ route('web.lista-estudantes-novos') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

              <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                      <h2>:</h2>
                      <p>Estudantes por Repitente.</p>
                    </div>
                    <div class="icon">
                    <i class="fas fa-user-minus"></i>
                    </div>
                    <a href="{{ route('web.lista-estudantes-repitentes') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                      <h2>:</h2>
                      <p>Pagamentos</p>
                    </div>
                    <div class="icon">
                    <i class="fas fa-user-minus"></i>
                    </div>
                    <a href="{{ route('web.financeiro-pagamentos') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

        <!-- ./col -->
        </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->  
  <!-- /.content -->
@endsection
