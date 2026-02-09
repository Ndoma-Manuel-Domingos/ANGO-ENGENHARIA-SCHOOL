@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Políticas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Escola</a></li>
                    <li class="breadcrumb-item active">Políticas</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                  <div class="card card-outline card-info">
                    <div class="card-header">
                      <h3 class="card-title">
                        Definição
                      </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('definicoes-editar') }}" method="post">
                            @csrf
                            <div class="card-body row">
                              <div class="form-group col-3">
                                <label for="exampleInputEmail1">Telefone 1</label>
                                <input type="text" class="form-control" name="telefone1" value="{{ $definicao->telefone1 }}" id="exampleInputEmail1" placeholder="Informe o telefone 1">
                              </div>

                              <div class="form-group col-3">
                                <label for="exampleInputEmail1">Telefone 2</label>
                                <input type="text" class="form-control" name="telefone2" value="{{ $definicao->telefone2 }}" id="exampleInputEmail1" placeholder="Informe o telefone 2">
                              </div>

                              <div class="form-group col-3">
                                <label for="exampleInputEmail1">Telefone 3</label>
                                <input type="text" class="form-control" name="telefone3" value="{{ $definicao->telefone3 }}" id="exampleInputEmail1" placeholder="Informe o telefone 3">
                              </div>

                              <div class="form-group col-3">
                                <label for="exampleInputEmail1">Telefone 4</label>
                                <input type="text" class="form-control" name="telefone4" value="{{ $definicao->telefone4 }}" id="exampleInputEmail1" placeholder="Informe o telefone 4">
                              </div>

                              <div class="form-group col-3">
                                <label for="exampleInputEmail1">Facebook</label>
                                <input type="text" class="form-control" name="facebook" value="{{ $definicao->facebook }}" id="exampleInputEmail1" placeholder="Informe o facebook">
                              </div>

                              <div class="form-group col-3">
                                <label for="exampleInputEmail1">Instagram</label>
                                <input type="text" class="form-control" name="instagram" value="{{ $definicao->instagram }}" id="exampleInputEmail1" placeholder="Informe o Instagram">
                              </div>

                              <div class="form-group col-3">
                                <label for="exampleInputEmail1">Twetter</label>
                                <input type="text" class="form-control" name="twetter" value="{{ $definicao->twetter }}" id="exampleInputEmail1" placeholder="Informe o twetter">
                              </div>

                              <div class="form-group col-3">
                                <label for="exampleInputEmail1">YouTube</label>
                                <input type="text" class="form-control" name="youtube" value="{{ $definicao->youtube }}" id="exampleInputEmail1" placeholder="Informe o YouTube">
                              </div>

                              <div class="form-group col-12">
                                <label for="exampleInputEmail1">Whtasapp</label>
                                <input type="text" class="form-control" name="whatsapp" value="{{ $definicao->whatsapp }}" id="exampleInputEmail1" placeholder="Informe o Whtasapp">
                              </div>

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