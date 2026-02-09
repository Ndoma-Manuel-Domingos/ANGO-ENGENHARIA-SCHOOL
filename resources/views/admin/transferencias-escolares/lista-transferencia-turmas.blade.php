@extends('layouts.escolas')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Transferências Entre turmas</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="">Voltar</a></li>
                            <li class="breadcrumb-item active">turmas</li>
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
                        <div class="card">
                            <div class="card-header">
                                @if (Auth::user()->can('create: transeferencia estudante'))
                                    <a href="{{ route('web.transferencia-turma-estudante', Crypt::encrypt(null)) }}" class="btn-success btn float-start mx-2">Nova Transferência</a>
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
                                            <th>Turma Destino</th>
                                            <th>Turma Origem</th>
                                            <th>Operação</th>
                                            <th>Motivo</th>
                                            <th>Documento</th>
                                            <th>Usuário</th>
                                            <th>Estado</th>
                                            <th>Acções </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transferencias as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td><a href="">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</a></td>
                                                <td>{{ $item->destino->turma }}</td>
                                                <td>{{ $item->origem->turma }}</td>
                                                <td>Internas</td>
                                                <td>{{ $item->motivo }}</td>
                                                <td><a href="{{ asset("assets/arquivos/$item->documento") }}" target="_blink">{{ $item->documento }}</a></td>
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
                                                            
                                                            @if (Auth::user()->can('update: estado'))
                                                            <a title="Regeitar Transferência" href="{{ route('web.transferencia-turma-rejeitar', $item->id) }}" class="dropdown-item"><i class="fa fa-edit"></i> Rejeitar</a>
                                                            @endif
                                                            
                                                            @if (Auth::user()->can('delete: transeferencia estudante'))
                                                                <a href="{{ route('web.transferencia-turma-estudante', Crypt::encrypt(null)) }}" class="btn-success btn float-start mx-2">Nova Transferência</a>
                                                            <a title="Excluir Transferência" href="{{ route('web.transferencia-turma-eliminar', $item->id) }}" class="dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
                                                            @endif
                                                            
                                                            @if (Auth::user()->can('read: transeferencia estudante'))
                                                                <a title="Visualizar Transferência" href="{{ route('web.transferencia-turma-visualizar', $item->id) }}" class="dropdown-item"><i class="fa fa-eye"></i> Visualizar </a>
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
