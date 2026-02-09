@extends('layouts.professores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Lista da Escolas</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('prof.home-profs') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Escolas</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="row mt-3">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        Listagem
                    </div>

                    <div class="card-body">
                        <table  style="width: 100%"  style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Nome</th>
                                    <th>NIF</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($escolas as $escola)
                                <tr>
                                    <td> {{ $escola->id }}</td>
                                    <td> {{ $escola->nome }}</td>
                                    <td> {{ $escola->documento }}</td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>                    
                    </div>

                    <div class="card-footer">
                        Listagem das escolas
                    </div>
                </div>

            </div>
        </div>
   
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- /.content -->
@endsection