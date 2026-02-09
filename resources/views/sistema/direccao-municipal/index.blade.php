@extends('layouts.provinciais')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-8">
              <h1 class="m-0 text-dark">Direcções Municipais</h1>
            </div><!-- /.col -->
            <div class="col-sm-4">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
                <li class="breadcrumb-item active">Activadores</li>
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
              <div class="card">
                  <form action="{{ route('direccoes-municipais.index') }}" method="get">
                      <div class="card-body">
                          <div class="row">
                              @csrf
                              <div class="form-group col-md-3">
                                  <label for="municipal_id">Municipios</label>
                                  <select name="municipal_id" class="form-control select2 editar_status_ano" id="municipal_id">
                                    <option value="">Todas</option>
                                    @foreach ($municipios as $item)
                                      <option value="{{ $item->id }}" {{ $requests['municipal_id'] ==  $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                  </select>
                                  @error('municipal_id')
                                  <span class="text-danger">{{ $message }}</span>
                                  @enderror
                              </div>
                              
                          </div>
                      </div>
                      <div class="card-footer justify-content-between">
                          <button type="submit" class="btn btn-success">Buscar</button>
                      </div>
                  </form>
              </div>
          </div>
        </div>

        <div class="row">
          @if (count($direccoes) == 0)
          <div class="col-12 col-md-12">
            <div class="callout callout-danger">
                <h5 class="text-danger"><i class="fas fa-info"></i> Sem registro Encontrados. <a href="{{ route('direccoes-municipais.create') }}" class="btn btn-success float-end text-white mx-2">Nova direcção</a></h5>
            </div>
          </div>    
          @else
          <div class="col-12 col-md-12">
              <div class="card">
                  <div class="card-header bg-light">
                    <h5 class="text-info  float-start">Registro Encontrados. Total: {{ count($direccoes) }}</h5>
                    <a href="{{ route('print.listagem-direccoes-provincias-imprmir', ['municipal_id'=>$requests['municipal_id']]) }}" target="_blink" class="btn btn-primary float-end" >Imprimir</a>
                    <a href="{{ route('direccoes-municipais.create') }}" class="btn btn-success float-end text-white mx-2">Nova direcção</a>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                      <table id="carregarEscolas"  style="width: 100%" class="table table-bordered  ">
                          <thead>
                              <tr>
                                  <th style="width: 5%">Nº</th>
                                  <th style="width: 5%">Documento</th>
                                  <th width="">Escolas</th>
                                  <th width="">Director</th>
                                  <th width="">Província</th>
                                  <th width="">Municipio</th>
                                  <th width="">Distrito</th>
                                  <th style="width: 5%">Status</th>
                                  <th style="width: 5%">Acções</th>
                              </tr>
                          </thead>
                          <tbody id="">
                            @if ($direccoes)
                                @foreach ($direccoes as $key => $direccao)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $direccao->documento }}</td>
                                    <td><a href="{{ route('direccoes-municipais.show', Crypt::encrypt($direccao->id)) }}">{{ $direccao->nome }}</a></td>
                                    <td>{{ $direccao->director }}</td>
                                    <td>{{ $direccao->provincia->nome }}</td>
                                    <td>{{ $direccao->municipio->nome }}</td>
                                    <td>{{ $direccao->distrito->nome }}</td>
                                    <td>{{ $direccao->status }}</td>
                                    <td>

                                    <div class="btn-group">
                                    <button type="button" class="btn btn-info">Opções</button>
                                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                        <div class="dropdown-menu" role="menu">
                                          {{-- @if (Auth::user()->can('read: direccao')) --}}
                                          <a href="{{ route('direccoes-municipais.show', Crypt::encrypt($direccao->id)) }}" title="Visualizar direccao" id="" class="dropdown-item "><i class=""></i>Visualizar</a>
                                          {{-- @endif --}}
                                          {{-- @if (Auth::user()->can('delete: direccao')) --}}
                                          <a href="{{ route('direccoes-municipais.edit', Crypt::encrypt($direccao->id)) }}" value="{{ $direccao->id }}" id="{{ $direccao->id }}" title="Eliminar escola" id="" class="dropdown-item delete_escola"><i class=""></i>Editar</a>
                                          {{-- @endif --}}
                                        
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Outros</a>
                                    </div>
                                    </div>

                                    </td>
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
          @endif
        </div>  
          
        </div>
      </div>
      
@endsection




@section('scripts')
  <script>
    $(function () {
      $("#carregarEscolas").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
  </script>
@endsection
