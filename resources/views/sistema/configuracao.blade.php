@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Configuração da Escola</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Administrador</a></li>
                    <li class="breadcrumb-item active">Sistema</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                  <div class="card card-outline card-info">
                    <div class="card-header">
                      <h3 class="card-title">
                        Configuração das datas
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('configuracao-escola') }}" method="post">
                            @csrf
                            <div class="card-body row">
                              <div class="form-group col-12 col-md-6">
                                <label for="inicio">Data Inicio</label>
                                <input type="date" class="form-control" name="inicio" value="{{ $sistema->inicio }}" id="inicio">
                              </div>

                              <div class="form-group col-12 col-md-6">
                                <label for="final">Data Final</label>
                                <input type="date" class="form-control" name="final" value="{{ $sistema->final }}" id="final">
                              </div>

                            <input type="hidden" class="form-control" name="configuracao_id" value="{{ $sistema->id }}" >

                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                              <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
            </div> 

        </div>
    </div>

@endsection


@section('scripts')
    <script>
        $(function () {
            // Summernote
            $('#summernote').summernote()
        
        })
    </script>
@endsection