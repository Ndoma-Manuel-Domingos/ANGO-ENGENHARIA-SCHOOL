@extends('layouts.provinciais')

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
                            <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
                            <li class="breadcrumb-item active">Transferências</li>
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
                            <h5><i class="fas fa-info"></i> Listagem geral das transferências dos profesores.</h5>
                        </div>
                    </div>
                </div>


                {{-- <div class="row">
                    <div class="col-6">
                        <form action="{{ route('web.transferencia-escola-estudante-store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body row">
                                    <div class="form-group col-12 mb-2">
                                        <label for="" class="form-label">Escolas</label>
                                        <select name="escola_id" class="form-control select2" style="width: 100%" required>
                                            <option value="">Selecione a Escola</option>
                                            @foreach ($escolas as $item)
                                                <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('escola_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-12 mb-2">
                                        <label for="" class="form-label">Motivo</label>
                                        <textarea name="motivo" class="form-control" required rows="2" cols="12" placeholder="Informe os motivos para transferência do estudante"></textarea>
                                        @error('motivo')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-12 mb-2">
                                        <label for="" class="form-label">Documento comprovativo (PDF)</label>
                                        <input type="file" name="documento" accept=".pdf" class="form-control" required/>
                                        @error('documento')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="estudante_id" value="{{ $estudante->id }}" />
                                    
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Confirmar Transferência</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> --}}


                <div class="row">
                    <div class="col-12 col-md-12">
                        @if(session()->has('danger'))
                            <div class="alert alert-warning">
                                {{ session()->get('danger') }}
                            </div>
                        @endif

                        @if(session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="{{ route('estudantes-matriculas-imprmir') }}"
                                    class="btn btn-primary float-end mx-2" target="_blink">Imprimir</a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive">
                                <table id="carregarTabelaMatricula"
                                     style="width: 100%" class="table  table-bordered table-striped table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nº Trans</th>
                                            <th>Professor</th>
                                            <th>Escola Destino</th>
                                            <th>Escola Origem</th>
                                            <th>Motivo</th>
                                            <th>Documento</th>
                                            <th>Usuário</th>
                                            <th>Status Em</th>
                                            <th>Acções </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transferencias as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td><a href="">{{ $item->professor->nome }} {{ $item->professor->sobre_nome }} </a></td>
                                                <td>{{ $item->destino->nome }}</td>
                                                <td>{{ $item->origem->nome }}</td>
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
                                                                <a title="Rejeitar Transferencia Matricula" id="{{ $item->id }}" class="dropdown-item editar_estudantes_id"><i class="fa fa-edit"></i> Rejeitar</a>
                                                            @endif
                                                            
                                                            @if (Auth::user()->can('delete: transeferencia professor'))
                                                                <a title="Excluir Transferencia" id="{{ $item->id }}" class="dropdown-item delete_estudantes"><i class="fa fa-trash"></i> Excluir</a>
                                                            @endif
                                                            
                                                            @if (Auth::user()->can('read: transeferencia professor'))
                                                                <a title="Visualizar Transferencia" id="{{ $item->id }}" class="dropdown-item ver_dados"><i class="fa fa-eye"></i> Visualizar </a>
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
