@extends('layouts.escolas')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Transferências</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="">Voltar</a></li>
                            <li class="breadcrumb-item active">Escolas</li>
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
                        <div class="callout callout-info">
                            <h5><i class="fas fa-info"></i> Listagem geral das transferências enviadas e recebidas de
                                estudantes.</h5>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12 col-md-12">
                        <form action="{{ route('web.transferencia-escolares') }}" method="get" >
                            @csrf
                            <div class="card">
                                <div class="card-body row">
                                    <div class="form-group col-12 col-md-3 mb-2">
                                        <label for="" class="form-label">Transferências</label>
                                        <select name="transferencias" class="form-control select2" style="width: 100%">
                                            <option value="" {{ $requests['transferencias'] == "" ? 'selected': '' }}>Todas</option>
                                            <option value="enviadas" {{ $requests['transferencias'] == "enviadas" ? 'selected': '' }}>Enviadas</option>
                                            <option value="recebidas" {{ $requests['transferencias'] == "recebidas" ? 'selected': '' }}>Recebidas</option>
                                        </select>
                                        @error('transferencias')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-12 col-md-3 mb-2">
                                        <label for="" class="form-label">Status</label>
                                        <select name="status" class="form-control select2" style="width: 100%">
                                            <option value="" {{ $requests['status'] == "" ? 'selected': '' }}>Todas</option>
                                            <option value="processo" {{ $requests['status'] == "processo" ? 'selected': '' }}>Em Processo</option>
                                            <option value="rejeitada" {{ $requests['status'] == "rejeitada" ? 'selected': '' }}>Rejeitadas</option>
                                            <option value="aceite" {{ $requests['status'] == "aceite" ? 'selected': '' }}>Aceites</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Pesquisar Transferência</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="{{ route('estudantes-matriculas-imprmir') }}" class="btn btn-primary float-end mx-2" target="_blink">Imprimir</a>
                                @if (Auth::user()->can('create: distribuicao de estudante'))
                                <a href="{{ route('web.transferencia-escola-estudante', Crypt::encrypt(null)) }}" class="btn-success btn float-start mx-2">Nova Transferência</a>
                                @endif
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive">
                                <table id="carregarTabelaMatricula"
                                     style="width: 100%" class="table  table-bordered table-striped  ">
                                    <thead>
                                        <tr>
                                            <th>Nº Trans</th>
                                            <th>Estudante</th>
                                            <th>Escola Destino</th>
                                            <th>Escola Origem</th>
                                            <th>Operação</th>
                                            <th>Condição</th>
                                            <th>Classe</th>
                                            <th>Turno</th>
                                            <th>Curso</th>
                                            <th>Usuário</th>
                                            <th>Estado</th>
                                            <th>Acções </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transferencias as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            @if (Auth::user()->can('create: distribuicao de estudante'))
                                                <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</a></td>
                                            @else
                                                {{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}
                                            @endif
                                            
                                            <td>{{ $item->destino->nome }}</td>
                                            <td>{{ $item->origem->nome }}</td>
                                            @if ($item->origem->id == $escola)
                                                <td>Enviada</td>
                                            @else
                                                <td>Recebida</td>
                                            @endif

                                            @if ($item->condicao == "concluir_ano_lectivo")
                                                <td>Para Concluir o Ano</td>
                                            @else
                                                <td>Para Estudar Novo Ano</td>
                                            @endif
                                            <td>{{ $item->classe->classes }}</td>
                                            <td>{{ $item->turno->turno }}</td>
                                            <td>{{ $item->curso->curso }}</td>
                                            <td>{{ $item->user->nome ?? ''}}</td>
                                            <td>{{ $item->status }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info">Opções</button>
                                                    <button type="button"
                                                        class="btn btn-info dropdown-toggle dropdown-icon"
                                                        data-toggle="dropdown">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                    
                                                        @if (Auth::user()->can('read: distribuicao de estudante') || Auth::user()->can('update: estado'))
                                                        <a title="Regeitar Transferência" href="{{ route('web.transferencia-escolares-rejeitar', $item->id) }}" class="dropdown-item"><i class="fa fa-edit"></i> Rejeitar</a>
                                                        @endif
                                                        
                                                        @if (Auth::user()->can('delete: distribuicao de estudante'))
                                                        <a title="Excluir Transferência" href="{{ route('web.transferencia-escolares-eliminar', $item->id) }}" class="dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
                                                        @endif
                                                    
                                                        @if (Auth::user()->can('read: distribuicao de estudante'))
                                                        <a title="Visualizar Transferência" href="{{ route('web.transferencia-escolares-visualizar', $item->id) }}" class="dropdown-item"><i class="fa fa-eye"></i> Visualizar </a>
                                                        @endif
                                                    
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#">Outros</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
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
        $(function() {
            $("#carregarTabelaMatricula").DataTable({
                language: {
                    url: "{{ asset('plugins/datatables/pt_br.json') }}"
                },
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        });
    </script>
@endsection
