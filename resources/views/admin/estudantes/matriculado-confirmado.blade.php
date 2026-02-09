@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Listagem dos estudantes matriculados e confirmados para o proximo ano</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel de controle</a></li>
                    <li class="breadcrumb-item active">Matriculas</li>
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
                    <form action="{{ route('web.estudantes-matriculados-confirmados') }}" method="get">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3 col-12">
                                    <label for="status">Estado Matricula</label>
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="">Todos</option>
                                        <option value="confirmado" {{ $filtros['status'] == 'confirmado' ? 'selected' : '' }}>Admitido</option>
                                        <option value="nao_confirmado" {{ $filtros['status'] == 'nao_confirmado' ? 'selected' : '' }}>Não Admitido</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="cursos_id">Cursos</label>
                                    <select name="cursos_id" id="cursos_id" class="form-control select2">
                                        <option value="">Todos</option>
                                        @foreach ($cursos as $item)
                                        <option value="{{ $item->curso->id }}" {{ $filtros['cursos_id'] == $item->curso->id ? 'selected' : '' }}>{{ $item->curso->curso }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="classes_id">Classes</label>
                                    <select name="classes_id" id="classes_id" class="form-control select2">
                                        <option value="">Todos</option>
                                        @foreach ($classes as $item)
                                        <option value="{{ $item->classe->id }}" {{ $filtros['classes_id'] == $item->classe->id ? 'selected' : '' }}>{{ $item->classe->classes }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="turnos_id">Turnos</label>
                                    <select name="turnos_id" id="turnos_id" class="form-control select2">
                                        <option value="">Todos</option>
                                        @foreach ($turnos as $item)
                                        <option value="{{ $item->turno->id }}" {{ $filtros['turnos_id'] == $item->turno->id ? 'selected' : '' }}>{{ $item->turno->turno }}</option>
                                        @endforeach
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
                        @if (Auth::user()->can('create: matricula'))
                        <a href="{{ route('web.estudantes-matricula-create') }}" class="btn btn-primary float-end">Fazer Novas Matrículas</a>
                        @endif
                        <a href="{{ route('estudantes-matriculados-confirmados-imprmir', ['status' => $filtros['status'], 'cursos_id'=>$filtros['cursos_id'], 'classes_id' => $filtros['classes_id'], 'turnos_id' => $filtros['turnos_id'] ]) }}" class="btn-danger btn float-end mx-2" target="_blink"><i class="fas fa-file-pdf"></i> Imprimir</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="carregarTabela" style="width: 100%" class="table  table-bordered table-striped  ">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Nome</th>
                                    <th>Bilhete</th>
                                    <th>Genero</th>
                                    <th>Curso</th>
                                    <th>Classe</th>
                                    <th>Turno</th>
                                    <th>Status Matricula</th>
                                    <th>Inscrição</th>
                                    <th>Serviço</th>
                                    <th>Ano Lectivo</th>
                                    <th>Data</th>
                                    <th>
                                        Acções
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($matriculas) != 0)
                                @foreach ($matriculas as $item)
                                <tr>
                                    @if ($item->status_matricula == 'inactivo')
                                    @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->numero_processo ?? 'sem processo' }}</a></td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</a></td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->bilheite }}</a></td>
                                    @else
                                    <td>{{ $item->estudante->numero_processo ?? 'sem processo' }}</td>
                                    <td>{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                                    <td>{{ $item->estudante->bilheite }}</td>
                                    @endif
                                    @endif

                                    @if ($item->status_matricula == 'confirmado')
                                    @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->numero_processo ?? 'sem processo' }}</a></td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</a></td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->bilheite }}</a></td>
                                    @else
                                    <td>{{ $item->estudante->numero_processo ?? 'sem processo' }}</td>
                                    <td>{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                                    <td>{{ $item->estudante->bilheite }}</td>
                                    @endif
                                    @endif

                                    @if ($item->status_matricula == 'falecido')
                                    @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}" class="text-danger">{{ $item->estudante->numero_processo ?? 'sem processo' }}</a></td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}" class="text-danger">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</a></td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->bilheite }}</a></td>
                                    @else
                                    <td>{{ $item->estudante->numero_processo ?? 'sem processo' }}</td>
                                    <td>{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                                    <td>{{ $item->estudante->bilheite }}</td>
                                    @endif
                                    @endif

                                    @if ($item->status_matricula == 'desistente')
                                    @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}" class="text-warning">{{ $item->estudante->numero_processo ?? 'sem processo' }}</a></td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}" class="text-warning">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</a></td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id) )}}">{{ $item->estudante->bilheite }}</a></td>
                                    @else
                                    <td>{{ $item->estudante->numero_processo ?? 'sem processo' }}</td>
                                    <td>{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                                    <td>{{ $item->estudante->bilheite }}</td>
                                    @endif
                                    @endif

                                    @if ($item->status_matricula == 'nao_confirmado')
                                    @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                    <td><a href="{{ route('web.aprovar-candidatura', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->numero_processo ?? 'sem processo' }}</a></td>
                                    <td><a href="{{ route('web.aprovar-candidatura', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</a></td>
                                    <td><a href="{{ route('web.aprovar-candidatura', Crypt::encrypt($item->estudante->id) )}}">{{ $item->estudante->bilheite }}</a></td>
                                    @else
                                    <td>{{ $item->estudante->numero_processo ?? 'sem processo' }}</td>
                                    <td>{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                                    <td>{{ $item->estudante->bilheite }}</td>
                                    @endif
                                    @endif

                                    @if ($item->status_matricula == 'rejeitado')
                                    @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                    <td><a href="{{ route('web.aprovar-candidatura', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->numero_processo ?? 'sem processo' }}</a></td>
                                    <td><a href="{{ route('web.aprovar-candidatura', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</a></td>
                                    <td><a href="{{ route('web.aprovar-candidatura', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->bilheite }}</a></td>
                                    @else
                                    <td>{{ $item->estudante->numero_processo ?? 'sem processo' }}</td>
                                    <td>{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                                    <td>{{ $item->estudante->bilheite }}</td>
                                    @endif
                                    @endif

                                    <td>{{ $item->estudante->genero }}</td>

                                    <td>{{ $item->curso->curso }}</td>
                                    <td>{{ $item->classe->classes }}</td>
                                    <td>{{ $item->turno->turno }}</td>
                                    @if ($item->status_matricula == 'inactivo')
                                    <td class="text-uppercase text-danger">Inactivo</td>
                                    @endif
                                    @if ($item->status_matricula == 'confirmado')
                                    <td class="text-uppercase text-success">Admitido</td>
                                    @endif
                                    @if ($item->status_matricula == 'falecido')
                                    <td class="text-uppercase text-danger">Falecido</td>
                                    @endif
                                    @if ($item->status_matricula == 'desistente')
                                    <td class="text-uppercase text-warning">Desistente</td>
                                    @endif
                                    @if ($item->status_matricula == 'nao_confirmado')
                                    <td class="text-uppercase text-danger">Não Admitido</td>
                                    @endif
                                    @if ($item->status_matricula == 'rejeitado')
                                    <td class="text-uppercase text-warning">Rejeitada</td>
                                    @endif

                                    @if ($item->status_inscricao == 'Admitido')
                                    <td class="text-uppercase text-success">Admitido</td>
                                    @endif

                                    @if ($item->status_inscricao == 'Nao Admitido')
                                    <td class="text-uppercase text-danger">Não Admitido</td>
                                    @endif

                                    <td class="text-capitalize">{{ $item->tipo ?? '' }}</td>
                                    <td>{{ $item->ano_lectivo->ano ?? '' }}</td>
                                    <td>{{ $item->created_at ?? '' }}</td>

                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">

                                                @if ($item->status_matricula == 'inactivo')
                                                @if (Auth::user()->can('update: estado'))
                                                <a title="Activar ou Desactivar Matricula" id="{{ $item->id }}" class="dropdown-item activar_estudantes_id"><i class="fa fa-check"></i> Activar ou Desactivar</a>
                                                @endif
                                                @if (Auth::user()->can('read: estudante'))
                                                <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}" title="Visualizar Estudante" class="dropdown-item ver_dados"><i class="fa fa-eye"></i> Visualizar </a>
                                                @endif
                                                <a title="Imprimir Ficha de matricula" href="{{ route('ficha-matricula-segunda-via', Crypt::encrypt($item->ficha)) }}" target="_blink" id="{{ $item->id }}" class="dropdown-item"><i class="fa fa-print"></i> Imprimir ficha de Matricula</a>
                                                @endif

                                                @if ($item->status_matricula == 'falecido')
                                                @if (Auth::user()->can('update: estado'))
                                                <a title="Activar ou Desactivar Matricula" id="{{ $item->id }}" class="dropdown-item activar_estudantes_id"><i class="fa fa-check"></i> Activar ou Desactivar</a>
                                                @endif
                                                @if (Auth::user()->can('read: estudante'))
                                                <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}" title="Visualizar Estudante" class="dropdown-item ver_dados"><i class="fa fa-eye"></i> Visualizar </a>
                                                @endif
                                                <a title="Imprimir Ficha de matricula" href="{{ route('ficha-matricula-segunda-via', Crypt::encrypt($item->ficha)) }}" target="_blink" id="{{ $item->id }}" class="dropdown-item"><i class="fa fa-print"></i> Imprimir ficha de Matricula</a>
                                                @endif

                                                @if ($item->status_matricula == 'desistente')
                                                @if (Auth::user()->can('update: estado'))
                                                <a title="Activar ou Desactivar Matricula" id="{{ $item->id }}" class="dropdown-item activar_estudantes_id"><i class="fa fa-check"></i> Activar ou Desactivar</a>
                                                @endif
                                                @if (Auth::user()->can('read: estudante'))
                                                <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}" title="Visualizar Estudante" class="dropdown-item ver_dados"><i class="fa fa-eye"></i> Visualizar </a>
                                                @endif
                                                <a title="Imprimir Ficha de matricula" href="{{ route('ficha-matricula-segunda-via', Crypt::encrypt($item->ficha)) }}" target="_blink" id="{{ $item->id }}" class="dropdown-item"><i class="fa fa-print"></i> Imprimir ficha de Matricula</a>
                                                @endif

                                                @if ($item->status_matricula == 'confirmado')
                                                @if (Auth::user()->can('update: estado'))
                                                <a title="Activar ou Desactivar Matricula" id="{{ $item->id }}" class="dropdown-item activar_estudantes_id"><i class="fa fa-check"></i> Activar ou Desactivar</a>
                                                @endif
                                                @if (Auth::user()->can('read: estudante'))
                                                <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}" title="Visualizar Estudante" class="dropdown-item ver_dados"><i class="fa fa-eye"></i> Visualizar </a>
                                                @endif
                                                <a title="Imprimir Ficha de matricula" href="{{ route('ficha-matricula-segunda-via', Crypt::encrypt($item->ficha)) }}" target="_blink" id="{{ $item->id }}" class="dropdown-item"><i class="fa fa-print"></i> Imprimir ficha de Matricula</a>
                                                @endif

                                                @if ($item->status_matricula == 'nao_confirmado')
                                                @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                                <a href="{{ route('web.aprovar-candidatura', Crypt::encrypt($item->estudante->id)) }}" title="Visualizar Estudante" class="dropdown-item ver_dados"><i class="fa fa-eye"></i> Visualizar</a>
                                                <a title="Imprimir 2º via da Ficha de matricula" href="{{ route('ficha-matricula2', $item->ficha) }}" target="_blink" id="{{ $item->id }}" class="dropdown-item"><i class="fa fa-print"></i> Imprimir 2º Via ficha Matricula</a>
                                                @endif
                                                @endif

                                                @if ($item->status_matricula == 'rejeitado')
                                                @if (Auth::user()->can('read: estudante') || Auth::user()->can('read: matricula'))
                                                <a href="{{ route('web.aprovar-candidatura', Crypt::encrypt($item->estudante->id)) }}" title="Visualizar Estudante" class="dropdown-item ver_dados"><i class="fa fa-eye"></i> Visualizar </a>
                                                @endif
                                                @endif

                                                @if (Auth::user()->can('update: estudante') || Auth::user()->can('update: matricula'))
                                                <a href="{{ route('web.estudantes-matricula-edit', Crypt::encrypt($item->id)) }}" title="Editar Matricula" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                @endif
                                                
                                                @if (Auth::user()->can('delete: estudante') || Auth::user()->can('delete: matricula'))
                                                <a href="#" id="{{ $item->id }}" title="Eliminar Matricula" class="dropdown-item deleteModal"><i class="fa fa-trash"></i> Excluir</a>
                                                @endif

                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Outros</a>
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

        // activar ou desactivar 
        $(document).on('click', '.activar_estudantes_id', function(e) {
            e.preventDefault();
            var novo_id = $(this).attr('id');

            Swal.fire({
                title: "Tens a certeza"
                , text: "Que desejas Mudar o estado a matricula (Activar ou Desactiva)"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Sim, Desejo!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        type: "GET"
                        , url: "activar-matricula-estudantes/" + novo_id
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
                }
            });
        });

        excluirRegistro('.deleteModal', `{{ route('estudantes.excluir-matricula-estudantes', ':id') }}`);

    });

    $("#provincia_id").change(() => {

        let id = $("#provincia_id").val();
        $.get('../carregar-municipios/' + id, function(data) {
            $("#municipio_id").html("")
            $("#municipio_id").html(data)
        })
    });

</script>

@endsection
