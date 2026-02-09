@extends('layouts.admin')

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
      <div class="row">

        <div class="col-12 col-md-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Cadastrar, listar, editar, eliminar e Mais informações dos funcionários. Busca avançada para melhorar na navegação do software.</h5>
            </div>
        </div>
    </div>

      <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
                <div class="card-header">
                  {{-- @if ($escola->categoria == 'Privado') --}}
                    @if (Auth::user()->can('read: professores'))
                     <a href="{{ route('web.funcionarios-ministerio-create') }}" class="btn btn-primary float-end mx-2" >Novo Funcionário</a>
                    @endif
                  {{-- @endif --}}
                  <a href="{{ route('funcionarios-imprmir') }}" target="_blink" class="btn btn-primary float-end mx-2" >Imprimir</a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <table id="carregarTabelaFuncionarios"  style="width: 100%" class="table table-bordered  ">
                    <thead>
                        <tr>
                          <th>Nº Doc</th>
                          <th>Nome</th>
                          <th>Sobrenome</th>
                          <th>Nascimento</th>
                          <th>Genero</th>
                          <th>Status</th>
                          <th>Bilhete</th>
                          <th>Telefone</th>
                          <th>E-mail</th>
                          <th>Acções</th>
                        </tr>
                    </thead>
                    <tbody>
                      @if (count($funcionarios) != 0)
                          @foreach ($funcionarios as $key => $item)
                            <tr>
                              <td>{{ $key + 1 }}</td>
                              <td>{{ $item->nome }}</td>
                              <td>{{ $item->sobre_nome }}</td>
                              <td>{{ $item->nascimento }}</td>
                              <td>{{ $item->genero }}</td>
                              <td>{{ $item->status }}</td>
                              <td>{{ $item->bilheite }}</td>
                              <td>{{ $item->telefone }}</td>
                              <td>{{ $item->email }}</td>
                              <td>
                                <div class="btn-group">
                                  <button type="button" class="btn btn-info">Opções</button>
                                  <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    <span class="sr-only">Toggle Dropdown</span>
                                  </button>
                                  <div class="dropdown-menu" role="menu">
                                    
                                    @if (Auth::user()->can('update: professores'))
                                      <a href="{{ route('web.funcionarios-ministerio-edit', $item->id) }}" title="Editar Funcionarios"  class="dropdown-item"><i class="fa fa-edit"></i> Editar </a>
                                    @endif
                                    
                                    @if (Auth::user()->can('delete: professores'))
                                      <a href="{{ route('web.funcionarios-ministerio-destroy', $item->id) }}" title="excluir Funcionarios" id="{{ $item->id }}" class="delete_funcionarios dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
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

