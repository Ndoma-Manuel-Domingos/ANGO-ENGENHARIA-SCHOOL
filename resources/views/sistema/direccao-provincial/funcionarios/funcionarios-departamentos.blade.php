@extends('layouts.provinciais')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Funcionários Por Departamentos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.funcionarios-provincial-controlo') }}">Voltar painel RH</a></li>
                    <li class="breadcrumb-item active">Funcionário</li>
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
                <form action="{{ route('web.funcionarios-provincial-departamentos') }}" method="get">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="status" class="form-label">Funcionários</label>
                                    <select name="status" id="status" class="form-control status select2">
                                        <option value="">Todos</option>
                                        <option value="activo" {{ $requests['status']=='activo' ? 'selected' : '' }}>
                                            Activo</option>
                                        <option value="desactivo" {{ $requests['status']=='desactivo' ? 'selected' : ''
                                            }}>Desactivo
                                        </option>
                                    </select>
                                    @error('status')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="departamento_id" class="form-label">Departamento</label>
                                    <select name="departamento_id" id="departamento_id" class="form-control departamento_id select2">
                                        <option value="">Todos</option>
                                        @foreach ($departamentos as $item)
                                        <option value="{{ $item->id }}" {{ $requests['departamento_id']==$item->id ? 'selected' : '' }}>{{ $item->departamento }}</option>
                                        @endforeach
                                    </select>
                                    @error('departamento_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="tempo_trabalho" class="form-label">Anos de trabalho</label>
                                    <select name="tempo_trabalho" id="tempo_trabalho"
                                        class="form-control tempo_trabalho select2">
                                        <option value="">Todos</option>
                                        @for ($i = 0; $i < 61; $i++) <option value="{{ $i }}" {{
                                            $requests['tempo_trabalho']==$i ? 'selected' : '' }}>{{ $i }} Anos</option>
                                            @endfor
                                            </option>
                                    </select>
                                    @error('tempo_trabalho')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="genero" class="form-label">Generos</label>
                                    <select name="genero" id="genero" class="form-control genero select2">
                                        <option value="">Todos</option>
                                        <option value="Masculino" {{ $requests['genero']=='Masculino' ? 'selected' : ''
                                            }}>Masculino</option>
                                        <option value="Femenino" {{ $requests['genero']=='Femenino' ? 'selected' : ''
                                            }}>Femenino</option>
                                    </select>
                                    @error('genero')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary">Filtra</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6>Total registro : {{ count($funcionarios) }}
                            <a href="{{ route('funcionarios-imprmir-departamentos-municipal-pdf', ['instituicao' => 2,  'status' => $requests['status'] ?? "", 'genero' => $requests['genero'] ?? "", 'departamento_id' => $requests['departamento_id'] ?? "", 'tempo_trabalho' => $requests['tempo_trabalho'] ?? ""]) }}"
                                target="_blink" class="btn-danger btn float-end mx-1">Imprimir PDF</a>

                            <a href="{{ route('funcionarios-imprmir-departamentos-municipal-excel', ['instituicao' => 2,  'status' => $requests['status'] ?? "", 'genero' => $requests['genero'] ?? "", 'departamento_id' => $requests['departamento_id'] ?? "", 'tempo_trabalho' => $requests['tempo_trabalho'] ?? ""]) }}"
                                target="_blink" class="btn-success btn float-end mx-1">Imprimir Excel</a>
                        </h6>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaFuncionarios" style="width: 100%"
                            class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Nº Doc</th>
                                    <th>Nome Completo</th>
                                    {{-- <th>Nascimento</th> --}}
                                    <th>Idade</th>
                                    <th>Genero</th>
                                    <th>Status</th>
                                    <th>Bilhete</th>
                                    <th>Departamento</th>
                                    <th>Cargo</th>
                                    <th>Especialidade</th>
                                    <th>Categoria</th>
                                    <th>Nível Academico</th>
                                    <th>Universidade</th>
                                    <th style="width: 70px">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($funcionarios) != 0)
                                @foreach ($funcionarios as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><a href="{{ route('web.funcionarios-provincial-show', Crypt::encrypt($item->id)) }}">{{
                                            $item->nome ?? '' }} {{ $item->sobre_nome ?? '' }}</a></td>
                                    {{-- <td>{{ $item->nascimento ?? '' }}</td> --}}
                                    <td>{{ $item->idade($item->nascimento) }}</td>
                                    <td>{{ $item->genero ?? '' }}</td>
                                    <td>{{ $item->status ?? '' }}</td>
                                    <td>{{ $item->bilheite ?? '' }}</td>
                                    <td>{{ $item->contrato->departamento->departamento ?? '' }}</td>
                                    <td>{{ $item->contrato->cargos->cargo ?? '' }}</td>
                                    <td>{{ $item->academico->especialidade->nome ?? '' }}</td>
                                    <td>{{ $item->academico->categoria->nome ?? '' }}</td>
                                    <td>{{ $item->academico->escolaridade->nome ?? '' }}</td>
                                    <td>{{ $item->academico->universidade->nome ?? '' }}</td>

                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon"
                                                data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">

                                                @if (Auth::user()->can('read: professores'))
                                                <a href="{{ route('web.funcionarios-provincial-show', Crypt::encrypt($item->id)) }}"
                                                    title="excluir Funcionarios"
                                                    class="delete_funcionarios dropdown-item"><i class="fa fa-eye"></i>
                                                    Visualizar</a>
                                                @endif

                                                @if (Auth::user()->can('update: professores'))
                                                <a href="{{ route('web.funcionarios-provincial-edit', Crypt::encrypt($item->id)) }}"
                                                    title="Editar Funcionarios" class="dropdown-item"><i
                                                        class="fa fa-edit"></i> Editar </a>
                                                @endif

                                                @if (Auth::user()->can('delete: professores'))
                                                <a href="{{ route('web.funcionarios-provincial-destroy', Crypt::encrypt($item->id)) }}"
                                                    title="excluir Funcionarios" id="{{ $item->id }}"
                                                    class="delete_funcionarios dropdown-item"><i
                                                        class="fa fa-trash"></i> Excluir</a>
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
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
</section>
<!-- /.content -->
@endsection


@section('scripts')
<script>
    $(function () {
      $("#carregarTabelaFuncionarios").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
</script>
@endsection