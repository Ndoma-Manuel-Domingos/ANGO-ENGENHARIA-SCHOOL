@extends('layouts.professores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Comunicados</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="{{ route('prof.home-profs') }}">Voltar</a></li>
                      <li class="breadcrumb-item active">Comunicados</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">

                    <div class="card card-primary card-outline">
                        <div class="card-body">

                            <div class="table-responsive mailbox-messages">
                                <table class="table table-hover table-bordered table-striped" style="width: 100%"
                                    id="table_load">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>Author</th>
                                            <th>Titulo</th>
                                            <th>Descrição</th>
                                            <th>Tipo Comunicado</th>
                                            <th>Data</th>
                                            <th>Para</th>
                                            <th>Acções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($comunicados as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->user->nome }}</td>
                                            <td class="mailbox-star">{{ $item->titulo }}</td>
                                            <td class="mailbox-name">{{ Str::limit($item->descricao, 50, ' (...)') }}
                                            </td>
                                            <td class="mailbox-subject">{{ $item->tipo_comunicado }}</td>
                                            <td class="mailbox-date">{{ date("Y-m-d", strtotime($item->created_at)) }}
                                            </td>
                                            <td class="mailbox-subject">{{ $item->to_escola }}</td>
                                            <td class="">
                                                <a href="{{ route('prof.detalhe-comunicados', $item->id) }}"
                                                    class="btn btn-primary"><i class="fas fa-eye"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- /.content -->
@endsection




@section('scripts')
<script>
    $(function () {
          $("#table_load").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            },
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
          }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    
        });
</script>


@endsection