@extends('layouts.escolas')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0 text-dark">Listagem Solicitações</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Voltar</a></li>
                <li class="breadcrumb-item active">Solicitações</li>
            </ol>

            </div><!-- /.col -->
        </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            {{-- <div class="row">
                <div class="col-12 col-md-12">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info"></i> Cadastro, listar, editar e eliminar de Encarregados, visualizar estudantes e adicionar estudantes aos encarregados. Busca avançada para melhorar na navegação do software.</h5>
                    </div>
                </div>
            </div> --}}

            <div class="row">
            
                <div class="col-12 col-md-12">
                    
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('solicitacao-documentos.index') }}" method="get" id="formulario">
                                <div class="row">
                                    <div class="col-12 col-md-3 mb3">
                                        <label for="" class="form-label">Processos</label>   
                                        <select name="processo" class="form-control">
                                            <option value="">TODOS</option>
                                            <option value="EM PROCESSO">EM PROCESSO</option>
                                            <option value="ENCAMINHADO">ENCAMINHADO</option>
                                            <option value="CONCLUIDO">CONCLUIDO</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" form="formulario">Filtrar</button>
                        </div>
                    </div>
                
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('encarregados-imprmir') }}" target="_blink" class="btn btn-primary mx-2 float-end">Imprimir</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="carregarTabelaEncarregado"  style="width: 100%" class="table  table-bordered table-striped  ">
                                <thead>
                                    <tr>
                                    <th>Nº</th>
                                    <th>Estudante</th>
                                    <th>Genero</th>
                                    <th>Estado</th>
                                    <th>Tipo Documento</th>
                                    <th>Efeito</th>
                                    <th>Documento</th>
                                    <th>Data Enviada</th>
                                    <th style="width: 100px">Acções</th>
                                    </tr>
                                </thead>
                                <tbody class="tableEncarregados">
                                    @if (count($documentos) != 0)
                                        @foreach ($documentos as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                                            <td>{{ $item->estudante->genero }}</td>
                                            
                                            @if ($item->processo == 'EM PROCESSO')
                                            <td class="text-info">{{ $item->processo }}</td>    
                                            @endif
                                            @if ($item->processo == 'ENCAMINHADO')
                                            <td class="text-warning">{{ $item->processo }}</td>    
                                            @endif
                                            @if ($item->processo == 'CONCLUIDO')
                                            <td class="text-success">{{ $item->processo }}</td>    
                                            @endif
                                            
                                            
                                            <td>{{ $item->tipo_documento }}</td>
                                            <td>{{ $item->efeito->nome }}</td>
                                            <td>{{ $item->status == 1 ? 'Concluido' : 'Não Concluido' }}</td>
                                            <td>{{ date("d-m-Y", strtotime($item->created_at)) }} ÁS {{ date("H:i:s", strtotime($item->created_at)) }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info">Opções</button>
                                                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                        @if ( $item->processo == "EM PROCESSO")
                                                            <a href="{{ route('solicitacao-documentos.edit', $item->id) }}" class="dropdown-item"><i class="fa fa-send"></i> Encaminhar do Director</a>
                                                        @endif
                                                        @if ( $item->processo == "ENCAMINHADO" AND $item->status == 0 )
                                                            <a href="{{ route('solicitacao-documentos.show', $item->id) }}" class="dropdown-item"><i class="fa fa-send"></i> Concluir Processo</a>
                                                        @endif
                                                        @if ( $item->processo == "ENCAMINHADO" AND $item->status == 1 )
                                                            <a href="{{ route('solicitacao-documentos.edit', $item->id) }}" class="dropdown-item"><i class="fa fa-send"></i> Encaminhar do Director</a>
                                                        @endif
                                                        @if ( $item->processo == "CONCLUIDO" AND $item->status == 1 )
                                                            <a href="{{ route('solicitacao-documentos.edit', $item->id) }}" class="dropdown-item"><i class="fa fa-send"></i> Encaminhar do Estudante</a>
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
                    </div>
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
        $(function () {
            $("#carregarTabelaEncarregado").DataTable({
                language: {
                    url: "{{ asset('plugins/datatables/pt_br.json') }}"
                },
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        });
    </script>
@endsection