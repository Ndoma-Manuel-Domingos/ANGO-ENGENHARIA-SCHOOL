@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Estudantes Listagem Geral</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel de Controle</a></li>
                    <li class="breadcrumb-item active">Estudantes</li>
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
                    <form action="{{ route('web.estudantes') }}" method="get">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-2 col-12">
                                    <label for="status">Estados Estudante</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="confirmado" {{ $filtros['status'] == 'confirmado' ? 'selected' : '' }}>Confirmados
                                        </option>
                                        <option value="falecido" {{ $filtros['status'] == 'falecido' ? 'selected' : '' }}>Falecidos</option>
                                        <option value="desistente" {{ $filtros['status'] == 'desistente' ? 'selected' : '' }}>Desisentes
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="finalista">Estudantes</label>
                                    <select name="finalista" id="finalista" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="Y" {{ $filtros['finalista'] == 'Y' ? 'selected' : '' }}>Finalistas </option>
                                        <option value="N" {{ $filtros['finalista'] == 'N' ? 'selected' : '' }}>Não Finalistas</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="cursos_id">Cursos</label>
                                    <select name="cursos_id" id="cursos_id" class="form-control">
                                        <option value="">Todos</option>
                                        @foreach ($cursos as $item)
                                        <option value="{{ $item->curso->id }}" {{ $filtros['cursos_id'] == $item->curso->id ? 'selected' : '' }}>
                                            {{ $item->curso->curso }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="classes_id">Classes</label>
                                    <select name="classes_id" id="classes_id" class="form-control">
                                        <option value="">Todos</option>
                                        @foreach ($classes as $item)
                                        <option value="{{ $item->classe->id }}" {{ $filtros['classes_id'] == $item->classe->id ? 'selected' : '' }}>
                                            {{ $item->classe->classes }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="turnos_id">Turnos</label>
                                    <select name="turnos_id" id="turnos_id" class="form-control">
                                        <option value="">Todos</option>
                                        @foreach ($turnos as $item)
                                        <option value="{{ $item->turno->id }}" {{ $filtros['turnos_id'] == $item->turno->id ? 'selected' : '' }}>
                                            {{ $item->turno->turno }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="genero">Generos</label>
                                    <select name="genero" id="genero" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="Masculino" {{ $filtros['genero'] == 'Masculino' ? 'selected' : '' }}>Masculino
                                        </option>
                                        <option value="Femenino" {{ $filtros['genero'] == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Listagem dos Estudantes </h3>
                        <a href="{{ route('estudantes-imprmir', ['finalista' => $filtros['finalista'], 'genero' => $filtros['genero'], 'status' => $filtros['status'], 'cursos_id' => $filtros['cursos_id'], 'classes_id' => $filtros['classes_id'], 'turnos_id' => $filtros['turnos_id']]) }}" class="float-end btn-danger btn mx-1" target="_blink"><i class="fas fa-file-pdf"></i> Imprimir</a>
                        <a href="{{ route('estudantes-imprmir-excel', ['finalista' => $filtros['finalista'], 'genero' => $filtros['genero'], 'status' => $filtros['status'], 'cursos_id' => $filtros['cursos_id'], 'classes_id' => $filtros['classes_id'], 'turnos_id' => $filtros['turnos_id']]) }}" class="float-end btn-success btn mx-1" target="_blink"><i class="fas fa-file-excel"></i> Imprimir</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="carregarTabela" style="width: 100%" class="table  table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Nome</th>
                                    <th>Bilhete</th>
                                    <th>Genero</th>
                                    <th>Telefone</th>
                                    <th>Curso</th>
                                    <th>Classe</th>
                                    <th>Turno</th>
                                    <th>Tipo Estudante</th>
                                    <th>Status</th>
                                    <th style="width: 100px">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($matriculas) != 0)
                                @foreach ($matriculas as $item)
                                <tr>
                                    <td>
                                        @if (Auth::user()->can('read: estudante'))
                                        <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->numero_processo }}</a>
                                        @else
                                        {{ $item->estudante->numero_processo }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (Auth::user()->can('read: estudante'))
                                        <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome }}
                                            {{ $item->estudante->sobre_nome }}</a>
                                        @else
                                        {{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (Auth::user()->can('read: estudante'))
                                        <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->bilheite }}</a>
                                        @else
                                        {{ $item->estudante->bilheite }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $item->estudante->genero }}
                                    </td>
                                    <td>
                                        {{ $item->estudante->telefone_estudante }}
                                    </td>
                                    </td>
                                    <td>{{ $item->curso->curso }}</td>
                                    <td>{{ $item->classe->classes }}</td>
                                    <td>{{ $item->turno->turno }}</td>
                                    <td>
                                        @if ($item->estudante->bolseiro($item->estudante->id))
                                        Bolseiro
                                        @else
                                        Normal
                                        @endif
                                    </td>

                                    @if ($item->status_matricula == 'inactivo')
                                    <td class="text-danger">Inactivo</td>
                                    @endif

                                    @if ($item->status_matricula == 'confirmado')
                                    <td class="text-success">Confirmado</td>
                                    @endif

                                    @if ($item->status_matricula == 'desistente')
                                    <td class="text-warning">Desistente</td>
                                    @endif

                                    @if ($item->status_matricula == 'falecido')
                                    <td class="text-danger">Falecido</td>
                                    @endif

                                    @if ($item->status_matricula == 'nao_confirmado')
                                    <td class="text-danger">Não Confirmado</td>
                                    @endif

                                    @if ($item->status_matricula == 'rejeitado')
                                    <td class="text-warning">Rejeitada</td>
                                    @endif

                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('read: estudante'))
                                                <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}" title="Visualizar Informações do estudante" class="dropdown-item"><i class="fa fa-eye"></i>
                                                    Visualizar</a>
                                                @endif

                                                @if (Auth::user()->can('create: documento') || Auth::user()->can('read: documento'))
                                                <a href="{{ route('web.declaracao-estudantes', Crypt::encrypt($item->estudante->id)) }}" title="declaração do estudante" class="dropdown-item"><i class="fa fa-book"></i> Emitir Declarações</a>
                                                <a href="{{ route('dow.ficha-tecnica-estudante', ['code' => Crypt::encrypt($item->estudante->id), 'ano' => Crypt::encrypt($verAnoLectivoActivo->id)]) }}" target="_blank" title="ficha técnica do estudante" class="dropdown-item"><i class="fa fa-user"></i> Ficha Técnica</a>
                                                <a title="Imprimir Ficha de matricula" href="{{ route('ficha-matricula-segunda-via', Crypt::encrypt($item->ficha)) }}" target="_blank" class="dropdown-item"><i class="fa fa-print"></i> Imprimir ficha de Matricula</a>
                                                @endif

                                                @if (Auth::user()->can('update: estudante'))
                                                <a href="{{ route('web.estudantes-matricula-edit', Crypt::encrypt($item->id)) }}" title="Editar Estudante" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                @endif

                                                @if (Auth::user()->can('delete: estudante'))
                                                <a href="#" title="Excluir Estudante" id="{{ $item->estudante->id }}" class="dropdown-item deleteModal"><i class="fa fa-trash"></i> Excluir</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
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
      
    const tabelas = [
        "#carregarTabela"
    , ];
    tabelas.forEach(inicializarTabela);

    $(function() {
    
        excluirRegistro('.deleteModal', `{{ route('estudantes.excluir-estudantes', ':id') }}`);

        // activar ou desactivar 
        $(document).on('click', '.activar_estudantes_id', function(e) {
            e.preventDefault();
            var novo_id = $(this).val();

            $.ajax({
                type: "GET"
                , url: `activar-estudantes/${novo_id}`
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.location.reload();
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });

        });

    });
  
</script>
@endsection
