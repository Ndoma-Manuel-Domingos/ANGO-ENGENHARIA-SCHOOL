@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-8">
              <h1 class="m-0 text-dark">Detalhes Direcção Provincial <span class="text-dark">{{ $data->nome }}</span> </h1>
            </div><!-- /.col -->
            <div class="col-sm-4">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('direccoes-provincias.index') }}">Direcções</a></li>
                <li class="breadcrumb-item active">geral</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card">
                        <div class="card-body table-responsive">
                          <table  style="width: 100%" class="table  table-bordered table-striped">
                              <tbody>
                                  <tr>
                                      <td  rowspan="3" class="text-center">
                                          @if ($data->logotipo != "NULL" && $data->logotipo != NULL)
                                              <img src='{{ public_path("assets/images/$data->logotipo") }}' class="img-circle" style="height: 100px; width: 100px;" alt="Logotipo da instituição">
                                          @else
                                              <img src='{{ public_path("assets/images/user.png") }}' class="img-circle" style="height: 100px; width: 100px;" alt="Logotipo da instituição">
                                          @endif
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="5" class="bg-dark">Informações</td>
                                  </tr>
                                  <tr>
                                      <td>Nome da Direcção: <strong>{{ $data->nome }}</strong> </td>
                                      <td>Nome do Director: <strong>{{ $data->director }}</strong> </td>
                                      <td>Decreto de Criação: <strong>{{ $data->decreto }}</strong></td>
                                      <td>Número de Idetificação Fiscal: <strong>{{ $data->documento }}</strong></td>
                                  </tr>
                             
                                  <tr>
                                      <td colspan="6" class="bg-dark">Localização</td>
                                  </tr>
                                  <tr>
                                      <td>País: <strong>{{ $data->pais->name }}</strong> </td>
                                      <td>Província: <strong>{{ $data->provincia->nome }}</strong> </td>
                                      <td>Município: <strong>{{ $data->municipio->nome }}</strong></td>
                                      <td>Distrito: <strong>{{ $data->distrito->nome }}</strong></td>
                                      <td colspan="2">Endereço: <strong>{{ $data->endereco }}</strong></td>
                                  </tr>

                                  <tr>
                                      <td colspan="6" class="bg-dark">Contactos</td>
                                  </tr>
                                  <tr>
                                      <td>Telefone 1: <strong>{{ $data->telefone1 }}</strong> </td>
                                      <td>Telefone 2: <strong>{{ $data->telefone2 }}</strong> </td>
                                      <td colspan="3">E-mail ou Site <strong>{{ $data->site }}</strong></td>
                                  </tr>

                              </tbody>
                          </table>                    
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('direccoes-provincias.edit', $data->id) }}" class="btn btn-primary">Editar as Informações da Direcção</a>
                        </div>
                    </div>
                </div>    
              </div>
        </div>
      </div>
      
@endsection

@section('scripts')
  <script>

    $(function () {
      $("#carregarEscolas").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
  </script>
@endsection
