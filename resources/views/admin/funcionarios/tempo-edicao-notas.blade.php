@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Actualização do tempo de Edição de Notas</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="">Voltar</a></li>
            <li class="breadcrumb-item active">Professor</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mb-3">
                <form action="{{ route('web.funcionarios-actualizar-prazo-notas-store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-4 col-12">
                                    <label for="tempo">Define o Tempo</label>
                                    <input type="date" name="tempo" value="" placeholder="tempo" class="form-control">
                                    @error('tempo')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <input type="hidden" value="{{ $funcionario->id }}" name="professor_id">

                                <div class="form-group col-md-4 col-12">
                                    <label for="turma_id">Turmas</label>
                                    <select name="turma_id" class="form-control select2" id="turma_id" style="width: 100%">
                                        <option value="todas">Todas Turmas</option>
                                        @foreach ($turmas as $item)
                                            <option value="{{ $item->id }}">{{ $item->turma}}</option>
                                        @endforeach
                                    </select>
                                    @error('turma_id')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                  <label for="trimestre_edicao">Trimestre</label>
                                  <select name="trimestre_edicao" class="form-control select2" id="trimestre_edicao" style="width: 100%">
                                    @foreach ($trimestres as $item)
                                      <option value="{{ $item->id }}">{{ $item->trimestre}}</option>
                                    @endforeach
                                  </select>
                                  @error('trimestre_edicao')
                                    <span class="text-danger"> {{ $message }}</span>
                                  @enderror
                              </div>
                              
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                    <table id="carregarTabelaFuncionarios"  style="width: 100%" class="table table-bordered  ">
                        <thead>
                            <tr>
                              <th>Turma</th>
                              <th>Disciplina</th>
                              <th>Tempo</th>
                              <th>Ultima actualização</th>
                            </tr>
                        </thead>
                        <tbody>
                          @if (count($turmas) != 0)
                              @foreach ($turmas as $item)
                                <tr>
                                  <td>{{ $item->turma }}</td>
                                  <td>{{ $item->disciplina }}</td>
                                  @if ($item->tempo_edicao < date("Y-m-d"))
                                    <td class="text-danger">{{ $item->tempo_edicao }}</td>
                                  @else
                                    <td class="text-success">{{ $item->tempo_edicao }}</td>
                                  @endif
                                  <td>{{ date("Y-m-d", strtotime($item->updated_at)) }} Ás {{ date("H:i:s", strtotime($item->updated_at)) }}</td>
                                </tr>    
                              @endforeach
                          @endif                        
                        </tbody>
                    </table>
                    </div>
                    <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
        </div>

    </div><!-- /.container-fluid -->
  </section>
  <!-- Main content -->

  <!-- /.content -->
@endsection
