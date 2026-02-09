@extends('layouts.estudantes')

@section('content')

<div class="content">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Minhas Contas de Depositos</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route("est.home-estudante") }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Depositos</li>
                  </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
              <div class="card">
                <div class="card-body">
                  <form action="{{ route('est.meus-depositos-estudante') }}" method="get" id="formulario">
                    @csrf
                    <div class="row">
                      <div class="col-12 col-md-3 mb3">
                        <label for="" class="form-label">Data Inicio</label>
                        <input type="date" name="data_inicio" value="{{ $requestAll['data_inicio'] ?? ''}}" class="form-control">
                      </div>
                      
                      <div class="col-12 col-md-3 mb3">
                        <label for="" class="form-label">Data Final</label>
                        <input type="date" name="data_final" value="{{ $requestAll['data_final'] ?? ''}}" class="form-control">
                      </div>
                      
                    </div>
                  </form>
                </div>
                <div class="card-footer">
                  <button type="submit" form="formulario" class="btn btn-primary">Filtrar</button>
                </div>
              </div>
            </div>        
            <div class="col-12 col-md-12">
              <div class="card">
                <div class="card-header">
                    <h5>Meus pagamentos</h5>
                </div>
                <div class="card-body">
                  <table id="carregarTabelaMatricula"
                     style="width: 100%" class="table table-bordered  ">
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Valor Actual</th>
                            <th>Valor Anterior</th>
                            <th>Operador</th>
                            <th>Estudante</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($depositos as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ number_format($item->valor, '2', ',', '.')  }} kz</td>
                            <td>{{ number_format($item->valor_anterior, '2', ',', '.')  }} kz</td>
                            <td>{{ $item->operador->nome }} {{ $item->operador->sobre_nome }}</td>
                            <td>{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                            <td>{{ $item->date_at }}</td>
                       
                        </tr>
                        @endforeach
                    </tbody>
    
                  </table>
                </div>
              </div>
            </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>



@endsection