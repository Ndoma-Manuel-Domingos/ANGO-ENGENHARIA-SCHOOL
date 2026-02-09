@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0 text-dark">Lançamento de Notas na turma: <span class="text-secondary">{{ $turma->turma }}</span> </h1>
                </div>
                {{-- <div class="col-sm-3"> --}}
                {{-- <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="">Voltar</a></li>
            <li class="breadcrumb-item active">Perfil</li>
          </ol> --}}
                {{-- </div> --}}
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
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('app.informacao-professores-lancamento-nota', [$professor->id, $turma->id]) }}" method="get" class="row" id="formulario">
                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Disciplinas</label>
                                    <select name="disciplina_id" id="" class="form-control select2">
                                        <option value="">Selecione</option>
                                        @foreach ($disciplinas as $item)
                                        <option value="{{ $item->disciplina->id }}">{{ $item->disciplina->disciplina }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Trimestre</label>
                                    <select name="trimestre_id" id="" class="form-control select2">
                                        <option value=""></option>
                                        @foreach ($trimestres as $item)
                                        <option value="{{ $item->id }}">{{ $item->trimestre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <button type="submit" form="formulario" class="btn btn-primary">Pesquisar</button>
                        </div>
                    </div>
                </div>
            </div>

            @if ($notas != null)
            <div class="row mt-3">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            Turma: <span class="text-danger">{{ $turma->turma }}</span>.
                            Classe: <span class="text-danger">{{ $classe->classes }}</span>.
                            Turno: <span class="text-danger">{{ $turno->turno }}</span>.
                            Sala Nº: <span class="text-danger">{{ $sala->salas }}</span>.
                            Disciplina: <span class="text-danger">{{ $disciplina->disciplina }}</span>.
                            Período: <span class="text-danger">{{ $trimestre->trimestre }}</span>.
                            Ano Lectivo: <span class="text-danger">{{ $ano->ano }}</span>.
                        </div>
                        <div class="card-body">
                            <table style="width: 100%" class="table projects  ">
                                <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Nome</th>
                                        <th>Sexo</th>
                                        <th>MAC</th>
                                        <th>NPT</th>
                                        <th>MT</th>
                                        <th>Resultado</th>
                                        <th>Acções</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($notas as $nota)
                                    <tr>
                                        <td> {{ $nota->id }}</td>
                                        <td> {{ $nota->estudante->nome }} {{ $nota->estudante->sobre_nome }}</td>
                                        <td> {{ $nota->estudante->genero }}</td>
                                        <td> {{ $nota->mac }}</td>
                                        <td> {{ $nota->npt }}</td>
                                        <td> {{ $nota->mt }}</td>
                                        <td> {{ $nota->obs }}</td>
                                        <td><a href="{{ route('app.professores-lancamento-nota-estudante', [$professor->id, $nota->id]) }}" title="Lançar notas" class="btn btn-primary">Editar <i class="fas edit"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>

                        <div class="card-footer">
                            Listagem das notas
                        </div>
                    </div>

                </div>
            </div>
            @endif
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- /.content -->
@endsection
