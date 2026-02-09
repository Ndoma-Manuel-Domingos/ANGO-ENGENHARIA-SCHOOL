@extends('layouts.provinciais')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Professores Cadastrados</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
                  <li class="breadcrumb-item active">Professores</li>
                </ol>
            </div><!-- /.col -->
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('app.provincial-gestao-professores-index') }}" method="get">
                    @csrf
                    <div class="card">
                        <div class="card-body row">
                        
                            <div class="col-12 col-md-2 mb-3">
                                <label for="" class="form-label">Províncias</label>
                                <select name="provincia_id" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach ($provincias as $item)
                                        <option value="{{ $item->id }}" {{ $requests['provincia_id'] == $item->id ? 'selected' : ''  }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-2 mb-3">
                                <label for="" class="form-label">Estado dos Professores</label>
                                <select name="status" class="form-control select2">
                                    <option value="">Todos</option>
                                    <option value="activo" {{ $requests['status'] == 'activo' ? 'selected' : ''  }}>Activos</option>
                                    <option value="desactivo" {{ $requests['status'] == 'desactivo' ? 'selected' : ''  }}>Desactivos</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-2 mb-3">
                                <label for="" class="form-label">Generos</label>
                                <select name="genero" class="form-control select2">
                                    <option value="">Todos</option>
                                    <option value="Masculino" {{ $requests['genero'] == 'Masculino' ? 'selected' : ''  }}>Masculino</option>
                                    <option value="Femenino" {{ $requests['genero'] == 'Femenino' ? 'selected' : ''  }}>Femenino</option>
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-2 mb-3">
                                <label for="" class="form-label">Categorias</label>
                                <select name="categoria_id" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach ($categorias as $item)
                                        <option value="{{ $item->id }}" {{ $requests['categoria_id'] == $item->id ? 'selected' : ''  }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-2 mb-3">
                                <label for="" class="form-label">Níveis</label>
                                <select name="nivel_id" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach ($niveis as $item)
                                        <option value="{{ $item->id }}" {{ $requests['nivel_id'] == $item->id ? 'selected' : ''  }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-2 mb-3">
                                <label for="" class="form-label">Especialidade</label>
                                <select name="especialidade_id" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach ($especialidades as $item)
                                        <option value="{{ $item->id }}" {{ $requests['especialidade_id'] == $item->id ? 'selected' : ''  }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-2 mb-3">
                                <label for="" class="form-label">Ano de Nascimento Maior do que</label>
                                <select name="ano_nascimento_maior" class="form-control select2">
                                    <option value="">Todos</option>
                                    @for ($i = 1960; $i < $count; $i++)
                                        <option value="{{ $i }}" {{ $requests['ano_nascimento_maior'] == $i ? 'selected' : ''  }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-2 mb-3">
                                <label for="" class="form-label">Ano de Nascimento Menor do que</label>
                                <select name="ano_nascimento_menor" class="form-control select2">
                                    <option value="">Todos</option>
                                    @for ($i = 1960; $i < $count; $i++)
                                        <option value="{{ $i }}" {{ $requests['ano_nascimento_menor'] == $i ? 'selected' : ''  }}>{{ $i }}</option>
                                    @endfor
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
                        <a href="{{ route('print.listagem-professores-provincial-imprmir', ['ano_nascimento_maior' => $requests['ano_nascimento_maior'] ?? '', 'ano_nascimento_menor' => $requests['ano_nascimento_menor'] ?? '', 'especialidade_id' => $requests['especialidade_id'] ?? '', 'categoria_id' => $requests['categoria_id'] ?? '', 'genero' => $requests['genero'] ?? '', 'provincia_id' => $requests['provincia_id'] ?? '', 'status' => $requests['status'] ?? '']) }}" target="_blink" class="btn btn-primary">Imprimir</a>
                        <h5 class="text-info  float-right">Registro Encontrados Total: {{ count($professores) }}</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaPrpfessores"  style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Id</th>
                                    <th width="">Nome</th>
                                    <th width="10%">Data Nascimento</th>
                                    <th width="">Genero</th>
                                    <th width="">Idade</th>
                                    <th width="">Bilhete</th>
                                    <th width="">Estado</th>
                                    <th width="">Especialidade</th>
                                    <th width="">Categoria</th>
                                    <th width="">Nível Academico</th>
                                    <th width="">Província</th>
                                    <th>Total Escola</th>
                                    <th style="width: 80px">Acções</th>
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
                                            <td>{{ $professor->academico->especialidade->nome }}</td>
                                            <td>{{ $professor->academico->categoria->nome }}</td>
                                            <td>{{ $professor->academico->formacao_academica->nome }}</td>
                                            <td>{{ $professor->provincia->nome }}</td>
                                            <td>{{ $professor->total_escola($professor->id) }}</td>
                                            <td>
                                                <div class="btn-group">
                                                  <button type="button" class="btn btn-info">Opções</button>
                                                  <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                  </button>
                                                  <div class="dropdown-menu" role="menu">
                                                    
                                                    @if (Auth::user()->can('read: professores'))
                                                    <a href="{{ route('app.informacao-professores-provincial', Crypt::encrypt($professor->id)) }}" title="Visualizar Perfil do professor" class="dropdown-item"><i class="fa fa-eye"></i> Perfil</a>
                                                    @endif
                                                    
                                                    @if (Auth::user()->can('create: professores'))
                                                    <a href="{{ route('web.professores-provincial-duplicar', $professor->id) }}" title="Duplicar Registro professor" class="dropdown-item"><i class="fa fa-eye"></i> Duplicar Registro</a>
                                                    @endif
                                                    
                                                    @if (Auth::user()->can('update: professores'))
                                                      <a href="{{ route('web.professores-provincial-edit', $professor->id) }}" title="Editar professor"  class="dropdown-item"><i class="fa fa-edit"></i> Editar </a>
                                                    @endif
                                                    
                                                    @if (Auth::user()->can('create: professores'))
                                                        @if ($professor->total_escola($professor->id) == 0)
                                                            <a href="{{ route('app.dispanho-professores-provincial-index', $professor->id) }}" title="Distribuir professor" class="dropdown-item"><i class="fas fa-send"></i> Distribuir</a>
                                                        @endif
                                                    @endif
                                                                             
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