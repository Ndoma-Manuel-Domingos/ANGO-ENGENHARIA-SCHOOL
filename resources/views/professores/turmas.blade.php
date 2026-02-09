@extends('layouts.professores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Lista das Turmas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('prof.home-profs') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Turmas</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
            @endif

            <div class="row">
                <div class="col-12 col-md-12">
                    <form action="{{ route('prof.turmas') }}" method="get">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-12 col-md-3">
                                        <label for="" class="form-label">Escola</label>
                                        <select type="text" class="form-control select2" placeholder="Escola" name="escola">
                                            <option value="">TODAS</option>
                                            @foreach ($escolas as $item)
                                            <option value="{{ Crypt::encrypt($item->id) }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-body">
                            <table id="carregarTabelaMatricula" style="width: 100%" class="table table-bordered  ">
                                <thead>
                                    <tr>
                                        <th>Nª</th>
                                        <th>Nome</th>
                                        <th>Turno</th>
                                        <th>Classe</th>
                                        <th>Total Estudantes</th>
                                    </tr>
                                </thead>

                                <tbody>
                                  @if ($turmas)
                                    @foreach ($turmas as $key => $turma)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        @if (Auth::user()->can('read: turma'))
                                        <td><a href="{{ route('prof.turmas-informacoes', Crypt::encrypt($turma->turma->id)) }}">{{ $turma->turma->turma }}</a></td>
                                        @else
                                        <td>{{ $turma->turma->turma }}</td>
                                        @endif
                                        <td>{{ $turma->turma->turno->turno }}</td>
                                        <td>{{ $turma->turma->classe->classes }}</td>
                                        <td>{{ $turma->turma->total_estudantes($turma->turma->id) }}</td>
                                    </tr>
                                    @endforeach
                                  @endif
                                </tbody>

                            </table>
                        </div>

                        <div class="card-footer">
                            Listagem das turmas
                        </div>
                    </div>

                </div>
            </div>

            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- /.content -->
@endsection

@section('scripts')
<script>
    $(function() {
        $("#carregarTabelaMatricula").DataTable({
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
