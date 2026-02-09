@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Professores</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                {{-- <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Escola</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol> --}}
            </div><!-- /.col -->
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('app.professores-index') }}" method="get">
                    @csrf
                    <div class="card">
                        <div class="card-body row">
                        
                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Províncias</label>
                                <select name="provincia_id" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach ($provincias as $item)
                                        <option value="{{ $item->id }}" {{ $requests['provincia_id'] == $item->id ? 'selected' : ''  }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Estado dos Professores</label>
                                <select name="status" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="activo" {{ $requests['status'] == 'activo' ? 'selected' : ''  }}>Activos</option>
                                    <option value="desactivo" {{ $requests['status'] == 'desactivo' ? 'selected' : ''  }}>Desactivos</option>
                                </select>
                            </div>


                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Generos</label>
                                <select name="genero" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="Masculino" {{ $requests['genero'] == 'Masculino' ? 'selected' : ''  }}>Masculino</option>
                                    <option value="Femenino" {{ $requests['genero'] == 'Femenino' ? 'selected' : ''  }}>Femenino</option>
                                </select>
                            </div>
      
      
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                @if(session()->has('danger'))
                    <div class="alert alert-warning">
                        {{ session()->get('danger') }}
                    </div>
                @endif

                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
            </div>
            
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                    
                        <a href="{{ route('web.professores-provincial-create') }}" class="btn btn-primary">Novo Funcionário</a>
                        <a href="{{  route('print.listagem-todos-professores-imprmir', ['genero' => $requests['genero'] ,'provincia_id' => $requests['provincia_id'] , 'status' => $requests['status']]) }}" target="_blink" class="btn btn-primary">Imprimir</a>
                        <h5 class="text-info  float-right">Registro Encontrados Total: {{ count($professores) }}</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaPrpfessores"  style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Id</th>
                                    <th width="">Nome</th>
                                    <th width="">Data Nascimento</th>
                                    <th width="">Genero</th>
                                    <th width="">Idade</th>
                                    <th width="">Bilhete</th>
                                    <th width="">Estado</th>
                                    <th width="">Província</th>
                                    <th>Total Escola</th>
                                    <th>Acções</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @if ($professores)
                                    @foreach ($professores as $professor)
                                        <tr>
                                            <td>{{ $professor->id }}</td>
                                            <td>
                                                @if (Auth::user()->can('read: professores'))
                                                <a href="{{ route('app.informacao-professores-provincial', Crypt::encrypt($professor->id)) }}">{{ $professor->nome }} {{ $professor->sobre_nome }}</a>
                                                @else
                                                {{ $professor->nome }} {{ $professor->sobre_nome }}
                                                @endif
                                            </td>
                                            <td>{{ $professor->nascimento }}</td>
                                            <td>{{ $professor->genero }}</td>
                                            <td>{{ $professor->idade($professor->nascimento) }}</td>
                                            <td>{{ $professor->bilheite }}</td>
                                            <td>
                                                @if ($professor->status == 'activo')
                                                   <span class="text-success">{{ $professor->status }}</span> 
                                                @endif
                                                @if ($professor->status == 'desactivo')
                                                   <span class="text-danger">{{ $professor->status }}</span> 
                                                @endif
                                            </td>
                                            <td>{{ $professor->provincia->nome }}</td>
                                            <td>{{ $professor->total_escola($professor->id) }}</td>
                                            <td>
                                                @if (Auth::user()->can('create: distribuicao de professor'))
                                                <a href="{{ route('app.Dispanho-professores-index', $professor->id) }}" title="Distribuir professor" class="btn btn-primary"><i class="fas fa-send"></i></a>
                                                <a href="{{ route('web.professores-provincial-edit', $professor->id) }}" title="Editar Professor" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('web.professores-provincial-duplicar', $professor->id) }}" title="Duplicar registro" class="btn btn-primary"><i class="fas fa-file"></i></a>
                                                @endif
                                            </td>
                                        </tr>   
                                    @endforeach                          
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">

                    </div>
              </div>
              <!-- /.card -->
            </div>


        </div>
    </div>
</div>
<!-- /.content-header -->

@endsection


@section('scripts')
  <script>
    $(function () {
      $("#carregarTabelaPrpfessores").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
  </script>
@endsection