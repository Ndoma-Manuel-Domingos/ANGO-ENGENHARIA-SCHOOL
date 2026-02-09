@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Classes</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Classes</li>
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
                <h5><i class="fas fa-info"></i> Listar todos os classes e Visualizar todos os estudantes das mesmas classes, para imprimir a lista dos mesmos. Busca avança para melhor navegar no software.</h5>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-md-12">
            <div class="card">
           
                <div class="card-body">
                <table id="carregarTabelaClasses"  style="width: 100%" class="table table-bordered  ">
                    <thead>
                        <tr>
                            <th>Cod</th>
                            <th>Classes</th>
                            <th>Status</th>
                            <th>Categoria</th>
                            <th>Acções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classes as $classe)
                            <tr>
                                <td>{{ $classe->id }}</td>
                                <td>{{ $classe->classes }}</td>
                                <td>{{ $classe->status }}</td>
                                <td>{{ $classe->categoria }}</td>
                                <td>
                                    <a href="{{ route('web.lista-estudantes-classe', $classe->id) }}" title="Visualizar Estudantes" class="btn-info btn"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>    
                        @endforeach
                        
                    </tbody>
                    <tfoot>
                        {{-- ================================== --}}
                    </tfoot>
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
      $("#carregarTabelaClasses").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
  </script>
@endsection