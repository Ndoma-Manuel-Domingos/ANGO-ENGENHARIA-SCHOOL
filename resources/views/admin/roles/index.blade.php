@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Perfil</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Painel Principal</a></li>
                    <li class="breadcrumb-item active">Perfil</li>
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
                        @if (Auth::user()->can('create: role'))
                        <a href="{{ route('roles-escola.create') }}" class="btn btn-primary float-end">Novo Perfil</a>
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabelasRole" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Id</th>
                                    <th width="">Nome</th>
                                    <th width="">Criação</th>
                                    <th width="">Actualização</th>
                                    <th width="15%" class="text-end">Acções</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @if ($roles)
                                    @foreach ($roles as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>{{ $item->updated_at }}</td>
                                            <td class="text-end">
                                                @if (Auth::user()->can('update: role'))
                                                <a href="{{ route('roles-escola.edit', Crypt::encrypt($item->id)) }}" class="btn btn-primary">Editar</a>
                                                @endif 
                                                @if (Auth::user()->can('update: role'))
                                                <a href="{{ route('app.roles-escola.delete', Crypt::encrypt($item->id)) }}" class="btn btn-danger">Apagar</a>
                                                @endif
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
      $("#tabelasRole").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });

  </script>

@endsection