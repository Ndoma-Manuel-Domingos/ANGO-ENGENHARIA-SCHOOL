@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Lista dos Estudantes Turma</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Estudantes</a></li>
                    <li class="breadcrumb-item active">Listar</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<div class="container-fluid">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">

                <div class="card">
                    <!-- Main content -->
                    <div class="card-header">
                        <h4>
                            <p class="mt-2 mb-2">
                                <span class="fs-6">Curso: <strong style="border-bottom: 1px solid #fff">{{ $curso->curso }}</strong> - </span>
                                <span class="fs-6">Classe: <strong style="border-bottom: 1px solid #fff">{{ $classe->classes }}</strong> - </span>
                                <span class="fs-6">Sala: <strong style="border-bottom: 1px solid #fff">{{ $sala->salas }}</strong> - </span>
                                <span class="fs-6">Turno: <strong style="border-bottom: 1px solid #fff">{{ $turno->turno }}</strong> - </span>
                                <span class="fs-6">Turma: <strong style="border-bottom: 1px solid #fff">{{ $turma->turma }}</strong> - </span>
                                <span class="fs-6">Ano Lectivo: <strong style="border-bottom: 1px solid #fff">{{ $anolectivo->ano }}</strong></span>
                            </p>
                        </h4>
                        <a href="{{ route('dow.estudantes_turmas', Crypt::encrypt($turma->id)) }}" rel="noopener" target="_black" class="btn btn-danger float-end"><i class="fas fa-print"></i> imprimir</a>
                        
                    </div>

                    <div class="card-body">
                        @if ($estudantes)
                        <table style="width: 100%" class="table table-bordered" id="carregarTabelaTurmas">
                            <thead>
                                <tr>
                                    <th>Documento</th>
                                    <th>Estudante</th>
                                    <th>Genero</th>
                                    <th>Nascimento</th>
                                    <th>Telefone</th>
                                    <th>Data Cadastro</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estudantes as $estudante)
                                @php
                                $estuds = (new App\Models\web\estudantes\Estudante())->find($estudante->estudantes_id);
                                $documento = (new App\Models\web\calendarios\Matricula())
                                ->where([
                                ['estudantes_id', $estuds->id],
                                ['ano_lectivos_id', $anolectivo->id]
                                ])->select('documento', 'numero_estudante')
                                ->first();
                                @endphp
                                <tr>
                                    <td>{{ $estudante->ordem }}</td>
                                    <td>{{ $estuds->nome }} {{ $estuds->sobre_nome }}</td>
                                    <td>{{ $estuds->genero }}</td>
                                    <td>{{ $estuds->nascimento }}</td>
                                    <td>{{ $estuds->telefone_estudante }}</td>
                                    <td>{{ $estuds->nascimento }}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                        @endif
                    </div>

                    <div class="card-footer">
                    </div>

                </div>

                <!-- /.invoice -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- ./wrapper -->

<!-- /.content -->
@endsection

@section('scripts')
<script>
    $(function() {
        $("#carregarTabelaTurmas").DataTable({
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
