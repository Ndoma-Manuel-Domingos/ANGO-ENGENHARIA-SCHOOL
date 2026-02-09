
@extends('layouts.escolas')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Painel da Tesouraria</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('tesourarias.index') }}">Painel da Tesouraria</a></li>
                        <li class="breadcrumb-item active">Tesouraria</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                {{-- GERAL --}}
                @if (Auth::user()->can('abertura caixa'))
                    @if (!$caixas)
                        <div class="col-lg-3 col-12 col-md-12">
                            <div class="small-box bg-light">
                                <div class="inner">
                                    <h3>Abertura do Caixa</h3>
    
                                    <p>Abertura do Caixa</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-check-alt"></i>
                                </div>
                                <a href="{{ route('operacoes-caixas.abertura') }}" class="small-box-footer bg-info">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endif
                @endif
                
                @if (Auth::user()->can('fecho caixa'))
                    @if ($caixas)
                        <div class="col-lg-3 col-12 col-md-12">
                            <div class="small-box bg-light">
                                <div class="inner">
                                    <h3>{{ $caixas->caixa }}</h3>
    
                                    <p>Fechar de caixa aberto no momento</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-check-alt"></i>
                                </div>
                                <a href="{{ route('operacoes-caixas.fechamento') }}" class="small-box-footer bg-info">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endif
                @endif
                
                @if (Auth::user()->can('create: pagamento'))
                    <div class="col-lg-3 col-12 col-md-12">
                        <!-- small box -->
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h3>:</h3>

                                <p>Concluir Pagamentos</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <a href="{{ route('web.concluir-pagamentos') }}" class="small-box-footer bg-info">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endif

                @if (Auth::user()->can('read: pagamento'))
                    <div class="col-lg-3 col-12 col-md-12">
                        <!-- small box -->
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h3>Pagamentos de</h3>

                                <p>Matrículas & Confirmações</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <a href="{{ route('web.estudantes-efectuar-pagamento-especias') }}" class="small-box-footer bg-info">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endif

                @if (Auth::user()->can('read: pagamento'))
                    <div class="col-lg-3 col-12 col-md-12">
                        <!-- small box -->
                        <div class="small-box bg-light">
                            <div class="inner">
                                <h3>Pagamentos de</h3>

                                <p>Mensalidades, transportes, uniformes etc ...</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-check-alt"></i>
                            </div>
                            <a href="{{ route('financeiros.financeiro-pagamentos-propina') }}" class="small-box-footer bg-info">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endif
                
            </div>
            
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header flex">
                            <h3 class="float-left">Operações diárias</h3>
                            
                            <a href="{{ route('ficha-pagamentos-receber', [
                                'type_id'=> 'receita', 
                                'data_inicio'=> $_GET['data_inicio'] ?? date("Y-m-d"), 
                                'data_final' => $_GET['data_final'] ?? date("Y-m-d"), 
                                'servico_id' => $_GET['servico_id'] ?? "", 
                                'user_id' => $_GET['user_id'] ?? $usuario->id, 
                                'caixa_id' => $_GET['caixa_id'] ?? ($caixas ? $caixas->id : ""), 
                                'forma_pagamento_id' => $_GET['forma_pagamento_id'] ?? "", 
                                'ano_lectivo_id' => $_GET['ano_lectivo_id'] ?? $ano_lectivo_id->id,
                                'all' => "todos",
                            ]) }}" class="btn btn-danger float-right" target="_blink">Imprimir <i class="fas fa-file-pdf"></i></a>
                            
                        </div>
                        <div class="card-body">
                            <table id="tabelaBuscas" style="width: 100%" class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Referência</th>
                                        <th>Pagamento</th>
                                        <th>Factura</th>
                                        <th>Nome Completo</th>
                                        <th title="Funcionário">Func.</th>
                                        <th>Feito</th>
                                        <th>Data</th>
                                        <th class="text-right">Total</th>
                                        <th>Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $somaFinal = 0;
                                    @endphp
                                    @foreach ($pagamentos as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->ficha }}</td>
                                        <td>{{ $item->servico->servico ?? "" }}</td>
                                        <td>{{ $item->next_factura }}</td>
                                        <td>{{ $item->model($item->model, $item->estudantes_id) }}</td>
                                        <td>{{ $item->operador->nome ?? "" }}</td>
                                        <td>{{ $item->operador_pagamento }}</td>
                                        <td>{{ $item->data_at }}</td>
                                        @if ($item->caixa_at == "receita")
                                        <td class="text-right text-success"> + {{ number_format($item->valor2, 2, ',', '.') }}</td>
                                        @endif
                                        @if ($item->caixa_at == "despesa")
                                        <td class="text-right text-danger"> - {{ number_format($item->valor2, 2, ',', '.') }}</td>
                                        @endif
                                        <td class="text-end">
                                            <a href="{{ route('web.ficha-matricula', Crypt::encrypt($item->ficha)) }}" class="btn-primary btn">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @php
                                    $somaFinal += $item->valor2;
                                    @endphp
                                    @endforeach
    
                                <tfoot class="bg-dark">
                                    <th>-----</th>
                                    <th>-----</th>
                                    <th>-----</th>
                                    <th>-----</th>
                                    <th>-----</th>
                                    <th>------</th>
                                    <th>------</th>
                                    <th>------</th>
                                    <th class="text-right">{{ number_format( $somaFinal , 2, ',', '.') }}</th>
                                    <th>------</th>
                                </tfoot>
                                </tbody>
                            </table>
                        </div>
    
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection


@section('scripts')
<script>
    $(function() {
        $("#tabelaBuscas").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tabelaBuscas_wrapper .col-md-6:eq(0)');

    });

</script>
@endsection