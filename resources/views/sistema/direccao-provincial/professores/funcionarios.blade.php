@extends('layouts.provinciais')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Funcionários</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">Voltar</li>
            <li class="breadcrumb-item active">Funcionários</li>
          </ol>

        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <section class="content">
    <div class="container-fluid">
      {{-- <div class="row">
        <div class="col-12 col-md-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Cadastrar, listar, editar, eliminar e Mais informações dos funcionários. Busca avançada para melhorar na navegação do software.</h5>
            </div>
        </div>
      </div> --}}
      
      <div class="row">
        <div class="col-12 col-md-12">
          <form action="{{ route('web.funcionarios-provincial') }}" method="get">
            @csrf
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="form-group mb-3 col-md-2 col-12">
                    <label for="status" class="form-label">Funcionários</label>
                    <select name="status" id="status" class="form-control status select2">
                      <option value="">Todos</option>
                      <option value="activo" {{ $requests['status']=='activo' ? 'selected' : '' }}>Activo</option>
                      <option value="desactivo" {{ $requests['status']=='desactivo' ? 'selected' : '' }}>Desactivo
                      </option>
                    </select>
                    @error('status')
                    <span class="text-danger error-text">{{ $message }}</span>
                    @enderror
                  </div>
  
                  <div class="form-group mb-3 col-md-2 col-12">
                    <label for="universidade_id" class="form-label">Universidade</label>
                    <select name="universidade_id" id="universidade_id" class="form-control universidade_id select2">
                      <option value="">Todos</option>
                      @foreach ($universidades as $item)
                      <option value="{{ $item->id }}" {{ $requests['universidade_id']==$item->id ? 'selected' : '' }}>{{
                        $item->nome }}</option>
                      @endforeach
                    </select>
                    @error('universidade_id')
                    <span class="text-danger error-text">{{ $message }}</span>
                    @enderror
                  </div>
                  
                  <div class="form-group mb-3 col-md-2 col-12">
                    <label for="especialidade_id" class="form-label">Especialidades</label>
                    <select name="especialidade_id" id="especialidade_id" class="form-control especialidade_id select2">
                      <option value="">Todos</option>
                      @foreach ($especialidades as $item)
                      <option value="{{ $item->id }}" {{ $requests['especialidade_id']==$item->id ? 'selected' : '' }}>{{
                        $item->nome }}</option>
                      @endforeach
                    </select>
                    @error('especialidade_id')
                    <span class="text-danger error-text">{{ $message }}</span>
                    @enderror
                  </div>
                  
                  <div class="form-group mb-3 col-md-2 col-12">
                    <label for="categora_id" class="form-label">Categorias</label>
                    <select name="categora_id" id="categora_id" class="form-control categora_id select2">
                      <option value="">Todos</option>
                      @foreach ($categorias as $item)
                      <option value="{{ $item->id }}" {{ $requests['categora_id']==$item->id ? 'selected' : '' }}>{{
                        $item->nome }}</option>
                      @endforeach
                    </select>
                    @error('categora_id')
                    <span class="text-danger error-text">{{ $message }}</span>
                    @enderror
                  </div>
                  
                  
                  <div class="form-group mb-3 col-md-2 col-12">
                    <label for="escolaridade_id" class="form-label">Nível Academico</label>
                    <select name="escolaridade_id" id="escolaridade_id" class="form-control escolaridade_id select2">
                      <option value="">Todos</option>
                      @foreach ($escolaridade as $item)
                      <option value="{{ $item->id }}" {{ $requests['escolaridade_id']==$item->id ? 'selected' : '' }}>{{
                        $item->nome }}</option>
                      @endforeach
                    </select>
                    @error('escolaridade_id')
                    <span class="text-danger error-text">{{ $message }}</span>
                    @enderror
                  </div>
                  
                  <div class="form-group mb-3 col-md-2 col-12">
                    <label for="formacao_id" class="form-label">Formação</label>
                    <select name="formacao_id" id="formacao_id" class="form-control formacao_id select2">
                      <option value="">Todos</option>
                      @foreach ($formacao_academicos as $item)
                      <option value="{{ $item->id }}" {{ $requests['formacao_id']==$item->id ? 'selected' : '' }}>{{
                        $item->nome }}</option>
                      @endforeach
                    </select>
                    @error('formacao_id')
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
                  {{-- @if ($escola->categoria == 'Privado') --}}
                    @if (Auth::user()->can('read: professores'))
                     <a href="{{ route('web.funcionarios-provincial-create') }}" class="btn btn-primary float-end mx-2" >Novo Funcionário</a>
                    @endif
                  {{-- @endif --}}
                  <a href="{{ route('funcionarios-imprmir-municipal-pdf', ['instituicao' => 3,  'status' => $requests['status'] ?? "", 'universidade_id' => $requests['universidade_id'] ?? "", 'especialidade_id' => $requests['especialidade_id'] ?? "", 'categora_id' => $requests['categora_id'] ?? "", 'escolaridade_id' => $requests['escolaridade_id'] ?? "", 'formacao_id' => $requests['formacao_id'] ?? ""]) }}" target="_blink"
                    class="btn-danger btn float-end mx-1">Imprimir PDF</a> 
                  
                  <a href="{{ route('funcionarios-imprmir-municipal-excel', ['instituicao' => 3,  'status' => $requests['status'] ?? "", 'universidade_id' => $requests['universidade_id'] ?? "", 'especialidade_id' => $requests['especialidade_id'] ?? "", 'categora_id' => $requests['categora_id'] ?? "", 'escolaridade_id' => $requests['escolaridade_id'] ?? "", 'formacao_id' => $requests['formacao_id'] ?? ""]) }}" target="_blink"
                    class="btn-success btn float-end mx-1">Imprimir Excel</a> 
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <table id="carregarTabelaFuncionarios"  style="width: 100%" class="table table-bordered  ">
                    <thead>
                      <tr>
                        <th>Nº Doc</th>
                        <th>Nome Completo</th>
                        <th>Nascimento</th>
                        <th>Idade</th>
                        <th>Genero</th>
                        <th>Status</th>
                        <th>Bilhete</th>
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
                              <td>{{ $item->nome }} {{ $item->sobre_nome }}</td>
                              <td>{{ $item->nascimento }}</td>
                              <td>{{ $item->idade($item->nascimento) }}</td>
                              <td>{{ $item->genero }}</td>
                              <td>{{ $item->status }}</td>
                              <td>{{ $item->bilheite }}</td>
                              <td>{{ $item->academico->especialidade->nome }}</td>
                              <td>{{ $item->academico->categoria->nome }}</td>
                              <td>{{ $item->academico->escolaridade->nome }}</td>
                              <td>{{ $item->academico->universidade->nome }}</td>
                              <td>
                                <div class="btn-group">
                                  <button type="button" class="btn btn-info">Opções</button>
                                  <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    <span class="sr-only">Toggle Dropdown</span>
                                  </button>
                                  <div class="dropdown-menu" role="menu">
                                    
                                    @if (Auth::user()->can('read: professores'))
                                    <a href="{{ route('web.funcionarios-provincial-show', Crypt::encrypt($item->id)) }}" title="Visualizar Funcionarios" id="{{ $item->id }}" class="dropdown-item"><i class="fa fa-eye"></i> Visualizar</a>
                                    @endif
                                    
                                    @if (Auth::user()->can('create: professores'))
                                    <a href="{{ route('web.funcionarios-provincial-duplicar', Crypt::encrypt($item->id)) }}" title="Duplicar Registro Funcionarios" id="{{ $item->id }}" class="dropdown-item"><i class="fa fa-eye"></i> Duplicar Registro</a>
                                    @endif
                                    
                                    @if (Auth::user()->can('update: professores'))
                                      <a href="{{ route('web.funcionarios-provincial-edit', Crypt::encrypt($item->id)) }}" title="Editar Funcionarios"  class="dropdown-item"><i class="fa fa-edit"></i> Editar </a>
                                    @endif
                                    
                                    @if (Auth::user()->can('delete: professores'))
                                      <a href="{{ route('web.funcionarios-provincial-destroy', Crypt::encrypt($item->id)) }}" title="excluir Funcionarios" id="{{ $item->id }}" class="dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
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
    <!-- /.container-fluid -->
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

