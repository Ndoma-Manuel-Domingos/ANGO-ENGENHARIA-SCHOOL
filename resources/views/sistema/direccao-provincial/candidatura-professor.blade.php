@extends('layouts.provinciais')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Activação de Candidaturas Professores</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
                  <li class="breadcrumb-item active">Activadores</li>
                </ol>
            </div><!-- /.col -->
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('web.activadores-candidatura-professores-post') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-body row">
                            <div class="col-12 col-md-3">
                                <label for="data_inicio" class="form-label">Data Inicio</label>
                                <input type="date" name="data_inicio" id="data_inicio" class="form-control">
                                @error('data_inicio')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="col-12 col-md-3">
                                <label for="data_final" class="form-label">Data Final</label>
                                <input type="date" name="data_final" id="data_final" class="form-control">
                                @error('data_final')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaPrpfessores"  style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Data Inicio</th>
                                    <th>Data Final</th>
                                    <th>Estado</th>
                                    <th>Utilizador</th>
                                    <th>Acções</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @foreach ($dados as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->inicio }}</td>
                                        <td>{{ $item->final }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ $item->user->nome ?? '' }}</td>
                                        <td>
                                            @if ($item->status == 'activo')
                                            <a href="{{ route('web.activadores-candidatura-professores-status', $item->id) }}" class="btn btn-danger">Dasctivar</a>
                                            @endif
                                            @if ($item->status == 'desactivo')
                                            <a href="{{ route('web.activadores-candidatura-professores-status', $item->id) }}" class="btn btn-success">Activar</a>    
                                            @endif
                                        </td>
                                    </tr>   
                                @endforeach                          
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