@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Listagem de Rupes</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel de controle</a></li>
                    <li class="breadcrumb-item active">Rupes</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <form action="{{ route('web.rupes.index') }}" method="get">
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                <div class="form-group col-md-3 col-12">
                                    <label for="status">Estado <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control editar_status select2" id="status">
                                        <option value="">Todos</option>
                                        <option value="1">Validados</option>
                                        <option value="0">Pendentes</option>
                                    </select>
                                    @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3 col-12">
                                    <label for="estudantes_id">Estudante <span class="text-danger">*</span></label>
                                    <select name="estudantes_id" class="form-control editar_estudantes_id select2" id="estudantes_id">
                                        <option value="">Todos</option>
                                        @foreach ($estudantes as $item)
                                        <option value="{{ $item->id }}" {{ old('estudantes_id') == $item->id ? : '' }}>{{ $item->nome }} {{ $item->sobre_nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('estudantes_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="servicos_id" class="form-label">Serviços</label>
                                    <select name="servicos_id" id="servicos_id" class="form-control servicos_id select2" style="width: 100%">
                                        <option value="">Todos</option>
                                        @foreach ($servicos as $item)
                                        <option value="{{ $item->id }}" {{ old('servicos_id') == $item->id ? : '' }}>{{ $item->servico }}</option>
                                        @endforeach
                                    </select>
                                    @error('servicos_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="ano_lectivos_id" class="form-label">Anos Lectivos</label>
                                    <select name="ano_lectivos_id" id="ano_lectivos_id" class="select2 form-control ano_lectivos_id" style="width: 100%">
                                        <option value="">Todos</option>
                                        @foreach ($anos as $item)
                                        <option value="{{ $item->id }}" {{ old('ano_lectivos_id') == $item->id ? : '' }}>{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                    @error('ano_lectivos_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                

                            </div>
                        </div>
                        <div class="card-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        @if (Auth::user()->can('create: ano lectivo'))
                        <a href="{{ route('web.rupes.create') }}" class="btn btn-primary float-end">Registrar Rupe</a>
                        @endif
                        <a href="{{ route('ano-lectivo-imprmir') }}" class="btn-danger btn float-end mx-1" target="_blink">Imprimir PDF</a>
                        <a href="{{ route('ano-lectivo-excel') }}" class="btn-success btn float-end mx-1" target="_blink">Imprimir Excel</a>
                    </div>

                    <div class="card-body table-responsive">
                        <table id="tabela_id" style="width: 100%" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Rupe</th>
                                    <th>Nº Proc</th>
                                    <th>Estudante</th>
                                    <th>Serviço</th>
                                    <th>Estado</th>
                                    <th>Data</th>
                                    <th>Operador</th>
                                    <th>Ano Lectivo</th>
                                    <th nowrap class="text-right">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rupes as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->rupe_id }}</td>
                                    <td>{{ $item->estudante->numero_processo }}</td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{$item->estudante->nome}} {{$item->estudante->sobre_nome}} </a></td>
                                    <td>{{ $item->servico->servico ?? "" }}</td>
                                    <td>{{ $item->status == false ? "Pendente" : "Validado" }}</td> 
                                    <td>{{ $item->created_at }}</td> 
                                    <td>{{ $item->user->usuario }}</td> 
                                    <td>{{ $item->ano_lectivo->ano }}</td> 
                                    
                                    <td class="text-right">
                                      <div class="btn-group">
                                        <button type="button" class="btn btn-info">Opções</button>
                                        <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                          <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                          {{-- @if (Auth::user()->can('read: ano lectivo')) --}}
                                          @if ($item->status == false)
                                          <a href="{{ route('web.rupes.show', Crypt::encrypt($item->id)) }}" title="Visualizar o Ano Lectivo" class="dropdown-item"><i class="fa fa-check"></i> Validar</a>
                                          @else
                                          <a href="{{ route('web.rupes.show', Crypt::encrypt($item->id)) }}" title="Visualizar o Ano Lectivo" class="dropdown-item"><i class="fa fa-times"></i> Rejeitar</a>
                                          @endif
                                          {{-- @endif --}}

                                          <div class="dropdown-divider"></div>
                                          <a class="dropdown-item" href="#">Outros</a>
                                        </div>
                                      </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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




@section('scripts')
  <script>
    $(function () {
      $("#tabela_id").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
  </script>
@endsection

