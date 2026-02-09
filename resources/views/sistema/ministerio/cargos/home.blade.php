@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Cargos</h1>
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
                <h5><i class="fas fa-info"></i> Cadastrar, editar, eliminar, cargos, pesquisar avançadas, para melhor navegação.</h5>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-12">

            <div class="card">
              <div class="card-header">
                @if (Auth::user()->can('create: cargo'))
                <a href="{{ route('web.create-cargos-ministerio') }}" class="btn btn-primary float-end">Novo Cargo</a>
                @endif
                <a href="{{ route('web.cargos-ministerio-pdf') }}" class="btn-danger btn float-end mx-1" target="_blink">Imprimir PDF</a>
                <a href="{{ route('web.cargos-ministerio-excel') }}" class="btn-success btn float-end mx-1" target="_blink">Imprimir Excel</a>
              </div>
              
              <div class="card-body">
                <table id="carregarCargos" style="width: 100%" class="table table-bordered  ">
                    <thead>
                        <tr>
                            <th>Cod</th>
                            <th>Cargo</th>
                            <th>Departamento</th>
                            <th>Salário</th>
                            <th>Status</th>
                            <th nowrap class="text-right">Acções</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach ($cargos as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->cargo }}</td>
                            <td>{{ $item->departamento->departamento }}</td>
                            <td>{{ number_format($item->salario, 2, ',', '.')  }} Kz</td>
                            <td>{{ $item->status }}</td>
                            <td class="text-right">
                              <div class="btn-group">
                                <button type="button" class="btn btn-info">Opções</button>
                                <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                
                                  @if (Auth::user()->can('update: cargo'))
                                  <a href="{{  route('web.edit-cargos-ministerio', $item->id ) }}" title="Editar Cargo" value="{{ $item->id }}" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                  @endif
                                  
                                  @if (Auth::user()->can('delete: cargo'))
                                  <a href="{{  route('web.delete-cargos-ministerio', $item->id ) }}" title="Eliminar Cargo" value="{{ $item->id }}" class="dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
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
      $("#carregarCargos").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
  </script>
@endsection