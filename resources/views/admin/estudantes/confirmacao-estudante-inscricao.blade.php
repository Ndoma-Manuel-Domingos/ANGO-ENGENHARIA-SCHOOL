@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Validar Candidaturas/Inscrição</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel Controle</a></li>
                    <li class="breadcrumb-item active">Candidaturas/Inscrições</li>
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
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="carregarTabelaInscricao" style="width: 100%" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nº Matricula</th>
                                    <th>Nome</th>
                                    <th>Genero</th>
                                    <th>Curso</th>
                                    <th>Classe</th>
                                    <th>Turno</th>
                                    <th>Ano Lectivo</th>
                                    <th>Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($matriculas_nao_confirmadas as $item)
                                <tr>
                                    <td>{{ $item->numero_estudante }}</td>
                                    <td>{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                                    <td>{{ $item->estudante->genero }}</td>
                                    <td>{{ $item->curso->curso }}</td>
                                    <td>{{ $item->classe->classes }}</td>
                                    <td>{{ $item->turno->turno }}</td>
                                    <td>{{ $item->ano_lectivo->ano }}</td>
                                    <td>
                                        <a href="{{ route('web.estudantes-efectuar-pagamento-especias', ['search' => $item->ficha]) }}" class="btn btn-primary">Validar todos processo</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
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
    $(function() {
        $("#carregarTabelaInscricao").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection
