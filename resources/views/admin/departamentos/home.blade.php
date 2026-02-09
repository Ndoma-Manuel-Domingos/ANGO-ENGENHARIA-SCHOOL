@extends('layouts.escolas')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Departamentos</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Voltar</a></li>
            <li class="breadcrumb-item active">Listagem</li>
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
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Cadastrar, editar, eliminar, departamentos, pesquisar avançadas, para melhor navegação.</h5>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-12">

            <div class="card">
              <div class="card-header">
                @if (Auth::user()->can('create: departamento'))
                <a href="{{ route('web.create-departamento') }}" class="btn btn-primary float-end">Novo Departamento</a>
                @endif
                <a href="{{ route('web.departamento-pdf') }}" class="btn-danger btn float-end mx-1" target="_blink">Imprimir PDF</a>
                <a href="{{ route('web.departamento-excel') }}" class="btn-success btn float-end mx-1" target="_blink">Imprimir Excel</a>
              </div>
              
              <div class="card-body">
                <table id="carregarDepartamentos" style="width: 100%" class="table table-bordered  ">
                    <thead>
                        <tr>
                            <th>Cod</th>
                            <th>Departamento</th>
                            <th>Status</th>
                            <th nowrap class="text-right">Acções</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach ($departamentos as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->departamento }}</td>
                            <td>{{ $item->status }}</td>
                            <td class="text-right">
                              <div class="btn-group">
                                <button type="button" class="btn btn-info">Opções</button>
                                <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                  @if (Auth::user()->can('update: departamento'))
                                  <a href="{{  route('web.edit-departamento', $item->id ) }}" title="Editar Departamento" value="{{ $item->id }}" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                  @endif
                                  
                                  @if (Auth::user()->can('delete: departamento'))
                                  <a href="{{  route('web.delete-departamento', $item->id ) }}" title="Eliminar Departamento" value="{{ $item->id }}" class="dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
                                  @endif
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
      $("#carregarDepartamentos").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
  </script>
@endsection