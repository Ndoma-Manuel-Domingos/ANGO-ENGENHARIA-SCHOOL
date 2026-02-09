@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Instituições de Estagios</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Estagios</a></li>
                    <li class="breadcrumb-item active">Instituições</li>
                </ol>
            </div><!-- /.col -->
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
                    @if (Auth::user()->can('create: instituicao'))
                    <a href="{{ route('web.cadastrar-instituicao-estagio') }}" class="float-end btn-primary btn">Nova Instituição</a>
                    @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="tabelasPermissions" style="width: 100%" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Id</th>
                                    <th width="">Instituição</th>
                                    <th width="">Director</th>
                                    <th width="">Documento</th>
                                    <th width="">E-mail</th>
                                    <th width="">Estado</th>
                                    <th width="">Tipo</th>
                                    <th width="10%" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($instituicoes as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->nome }}</td>
                                        <td>{{ $item->director }}</td>
                                        <td>{{ $item->nif }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ $item->tipo }}</td>
                                        <td class="text-end">
                                            
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info">Opções</button>
                                                <button type="button"
                                                    class="btn btn-info dropdown-toggle dropdown-icon"
                                                    data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                
                                                    @if (Auth::user()->can('update: instituicao')) 
                                                    <a href="{{ route('web.editar-instituicao-estagio', Crypt::encrypt($item->id)) }}" title="Editar Instituição" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                    @endif
                                                    @if (Auth::user()->can('read: instituicao')) 
                                                    <a href="{{ route('web.show-instituicao-estagio', Crypt::encrypt($item->id)) }}" title="Visualizar Instituição" class="dropdown-item"><i class="fa fa-edit"></i> Visualizar</a>
                                                    @endif
                                                    @if (Auth::user()->can('delete: instituicao')) 
                                                    <a href="{{ route('web.delete-instituicao-estagio', Crypt::encrypt($item->id)) }}" title="Eliminar Instituição" class="dropdown-item text-danger"><i class="fa fa-trash"></i> Eliminar</a>
                                                    @endif

                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#"><i class="fas fa-outdent"></i> Outros</a>
                                                </div>
                                            </div>
                                        
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
<!-- /.content-header -->

@endsection


@section('scripts')

  <script>

    $(function () {
      $("#tabelasPermissions").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });

  </script>

@endsection