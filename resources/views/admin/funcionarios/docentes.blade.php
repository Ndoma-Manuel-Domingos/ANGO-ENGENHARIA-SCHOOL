@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Lista dos Docentes</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">Voltar</li>
            <li class="breadcrumb-item active">Docentes</li>
          </ol>

        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('funcionarios-imprmir') }}" target="_blink" class="btn btn-primary float-end mx-2" >Imprimir</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                @if ($docentes)
                    <table id="example1"  style="width: 100%" class="table table-bordered  ">
                        <thead>
                            <tr>
                                <th>Nº Contrato</th>
                                <th>Nome</th>
                                <th>Nascimento</th>
                                <th>Bilhete</th>
                                <th>Contrato</th>
                                <th style="width: 170px;">Acções</th>
                            </tr>
                        </thead>
                        <tbody class="table_funcionarios">
                        @foreach ($docentes as $item)
                      
                            <tr>                                
                                <td> {{ $item->id }}</td>
                                <td><a href="{{ route('web.mais-informacao-funcionarios', Crypt::encrypt($item->funcionario->id)) }}">{{ $item->funcionario->nome }} {{ $item->funcionario->sobre_nome }} </a></td>
                                <td> {{ $item->funcionario->nascimento }}</td>
                                <td> {{ $item->funcionario->bilheite }}</td>
                                <td> {{ $item->status }}</td>
                                <td>
                                  <a href="{{ route('web.mais-informacao-funcionarios', Crypt::encrypt($item->funcionario->id)) }}" title="Bloqueado e desbloqueado" value="{{ $item->id }}" class="btn-info btn"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>    
                @endif
                </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection