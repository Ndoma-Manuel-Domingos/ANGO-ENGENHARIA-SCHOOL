@extends('layouts.provinciais')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-10">
              <h1 class="m-0 text-dark">Detalhes Direcção Municipal <span class="text-dark">{{ $data->nome }}</span> </h1>
            </div><!-- /.col -->
            <div class="col-sm-2">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('direccoes-municipais.index') }}">Voltar em direcções</a></li>
                <li class="breadcrumb-item active">Detalhes</li>
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
                                        <td  rowspan="4">
                                            <img src='{{ public_path("assets/images/user.png") }}' style="height: 100px; width: 100px;" class="img-circle" alt="Logotipo da instituição">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="bg-light">Informações Director</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Nome do Director: <strong>{{ $director->nome ?? '' }}</strong> </td>
                                        <td>Bilheite Identidade: <strong>{{ $director->bilheite ?? ''  }}</strong> </td>
                                        <td colspan="1">Genero: <strong>{{ $director->genero ?? '' }}</strong> </td>
                                        <td>Estado Cívil: <strong>{{ $director->estado_civil ?? ''  }}</strong></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Especialidade: <strong>{{ $director->especialidade ?? ''  }}</strong></td>
                                        <td>Curso: <strong>{{ $director->curso ?? ''  }}</strong></td>
                                        <td colspan="3">Perfil: <strong>{{ $director->descricao ?? ''  }}</strong></td>
                                    </tr>
                                
                                    <tr>
                                        <td colspan="6" class="bg-light">Informações Gerais</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">Nome da Escola: <strong>{{ $data->nome }}</strong> </td>
                                        <td>Sigla: <strong>{{ $data->sigla }}</strong> </td>
                                        <td>Decreto de Criação: <strong>{{ $data->decreto }}</strong></td>
                                        <td>Número de Idetificação Fiscal: <strong>{{ $data->documento }}</strong></td>
                                    </tr>

                                    <tr>
                                        <td colspan="6" class="bg-light">Contactos</td>
                                    </tr>
                                    <tr>
                                        <td>Telefone 1: <strong>{{ $data->telefone1 }}</strong> </td>
                                        <td>Telefone 2: <strong>{{ $data->telefone2 }}</strong> </td>
                                        <td>Telefone 4: <strong>{{ $data->telefone3 }}</strong></td>
                                        <td colspan="3">E-mail ou Site <strong>{{ $data->site }}</strong></td>
                                    </tr>

                                    <tr>
                                        <td colspan="6" class="bg-light">Localização</td>
                                    </tr>
                                    <tr>
                                        <td>Provincia: <strong>{{ $data->provincia->nome }}</strong> </td>
                                        <td>Municipio: <strong>{{ $data->municipio->nome }}</strong> </td>
                                        <td>Distrito: <strong>{{ $data->distrito->nome }}</strong></td>
                                        <td colspan="3">Endereço: <strong>{{ $data->endereco }}</strong></td>
                                    </tr>


                                    <tr>
                                        <td colspan="6" class="bg-light">Infraestrutura</td>
                                    </tr>
                                    <tr>
                                        <td>Cantina: <strong>{{ $data->cantina }}</strong> </td>
                                        <td colspan="">Campo Disportivo: <strong>{{ $data->campo_desportivo }}</strong> </td>
                                        <td colspan="">Computadores: <strong>{{ $data->computadores }}</strong> </td>
                                        <td>Água Potavel: <strong>{{ $data->agua }}</strong></td>
                                        <td>Electricidade: <strong>{{ $data->electricidade }}</strong></td>
                                        <td>Transporte: <strong>{{ $data->transporte }}</strong></td>
                                    </tr>

                                    <tr>
                                        <td colspan="">Internet: <strong>{{ $data->internet }}</strong> </td>
                                        <td colspan="">Bibioteca: <strong>{{ $data->biblioteca }}</strong> </td>
                                        <td colspan="">Farmácia: <strong>{{ $data->farmacia }}</strong> </td>
                                        <td colspan="">Laboratório: <strong>{{ $data->laboratorio }}</strong> </td>
                                        <td colspan="">ZIP: <strong>{{ $data->zip }}</strong> </td>
                                        <td colspan="">Casas de Banhos: <strong>{{ $data->casas_banho }}</strong> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>  
            </div> 

        </div>
      </div>
       <div class="card-footer">
                            <a href="{{ route('direccoes-municipais.edit', Crypt::encrypt($data->id)) }}" class="btn btn-primary">Editar as Informações da Direcção</a>
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
