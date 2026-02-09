@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Controle de Entradas e Saídas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('qr-code.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Presenças</li>
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
                    <div class="card-header">
                        <h3 class="card-title">Listagem dos Estudantes </h3>
                        <a href="#" class="float-end btn-danger btn mx-1" target="_blink"><i class="fas fa-file-pdf"></i> Imprimir</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="carregarTabelaEstudantes" style="width: 100%"
                            class="table  table-bordered table-striped table-striped">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Nome</th>
                                    <th>Genero</th>
                                    <th>Telefone</th>
                                    <th>Curso</th>
                                    <th>Classe</th>
                                    <th>Turno</th>
                                    <th>Entrada</th>
                                    <th>Saída</th>
                                    <th>Hora Entrada</th>
                                    <th>Hora Saída</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($listas) != 0)
                                    @foreach ($listas as $item)
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
                                                {{ $item->estudante->genero }}
                                            </td>

                                            <td>
                                                {{ $item->estudante->telefone_estudante }}
                                            </td>
                                           
                                            </td>
                                            <td>{{ $item->turma->curso->curso }}</td>
                                            <td>{{ $item->turma->classe->classes }}</td>
                                            <td>{{ $item->turma->turno->turno }}</td> 
                                            
                                            <td>{{ $item->status_entrada == 1 ? "Sim" : "Não" }}</td> 
                                            <td>{{ $item->status_saida == 1 ? "Sim" : "Não" }}</td> 
                                            <td>{{ $item->hora_entrada }}</td> 
                                            <td>{{ $item->hora_saida }}</td> 
                                            <td>{{ $item->data_entrada }}</td> 
                                        
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
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
    <script>
        $(function() {
            $("#carregarTabelaEstudantes").DataTable({
                language: {
                    url: "{{ asset('plugins/datatables/pt_br.json') }}"
                },
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');

        });
    </script>
@endsection
