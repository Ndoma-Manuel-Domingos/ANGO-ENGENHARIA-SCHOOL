@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Adicionar Estudantes na Turma <span class="text-secondary">{{ $turma->turma }}</span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.turmas') }}">Turmas</a></li>
                    <li class="breadcrumb-item active">Adicionar Estudante</li>
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
                <form action="{{ route('web.adicionar-estuantes-turmas-store') }}" method="POST" id="add-students-form">
                    @csrf
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">

                            <table id="carregarTabela" style="width: 100%" class="table  table-bordered table-striped table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cand. Nº</th>
                                        <th>Nome</th>
                                        <th>Bilhete</th>
                                        <th>Status</th>
                                        <th>Genero</th>
                                        <th>Curso</th>
                                        <th>Classe</th>
                                        <th>Turno</th>
                                        <th>Média</th>
                                        <th>Idade</th>
                                    </tr>
                                </thead>
                                <tbody class="tableEstudanes">
                                    @if (count($matriculas) != 0)
                                    @foreach ($matriculas as $item)
                                    <tr>
                                        <td><input type="checkbox" name="estudantes_id[]" class="estudantes_id" style="cursor: pointer;" value="{{ $item->id }}"></td>
                                        <td>
                                            @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                            <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->documento }} </a>
                                            @else
                                            {{ $item->numero_estudante }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                            <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }} </a>
                                            @else
                                            {{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}
                                            @endif
                                        </td>
                                        <td>{{ $item->estudante->bilheite }} </td>
                                        @if ($item->status_matricula == 'confirmado')
                                        <td class="text-success">Confirmado</td>
                                        @else
                                        <td class="text-danger">Não Confirmado</td>
                                        @endif
                                        <td>{{ $item->estudante->genero }}</td>
                                        <td>{{ $item->curso->curso }}</td>
                                        <td>{{ $item->classe->classes }}</td>
                                        <td>{{ $item->turno->turno }}</td>
                                        <td>{{ $item->media }}</td>
                                        <td>{{ $item->estudante->idade($item->estudante->nascimento) }}</td>
                                    </tr>
                                    @endforeach
                                    @endif

                                </tbody>
                            </table>

                            <input type="hidden" name="turma_id" class="turma_id" value="{{ $turma->id }}">
                        </div>

                        <div class="card-footer">
                            <button type="submit" id="submit-button" class="btn btn-primary">Adicionar à Turma</button>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection

@section('scripts')

<script>
    const tabelas = [
        "#carregarTabela"
    , ];
    tabelas.forEach(inicializarTabela);

</script>
@endsection
