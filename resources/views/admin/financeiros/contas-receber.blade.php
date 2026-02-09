@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Contas a Receber</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Contas Receber</li>
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
                <form action="{{ route('financeiros.financeiro-contas-receber') }}" method="GET">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12 col-md-2">
                                    <label for="ano_lectivo_id" class="form-label">Ano Lectivo</label>
                                    <select name="ano_lectivo_id" id="ano_lectivo_id" class="form-control select2">
                                        <option value="">TODOS</option>
                                        @if (count($listasanolectivo) != 0)
                                        @foreach ($listasanolectivo as $item2)
                                        <option value="{{ $item2->id }}" {{ $filtro['ano_lectivo_id'] == $item2->id ? 'selected' : '' }}>{{ $item2->ano }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
    
                                <div class="form-group col-12 col-md-3">
                                    <label for="servico_id" class="form-label">Serviços</label>
                                    <select name="servico_id" id="servico_id" class="form-control select2">
                                        <option value="">TODOS</option>
                                        @if (count($servicos) != 0)
                                        @foreach ($servicos as $item)
                                        <option value="{{ $item->id }}" {{ $filtro['servico_id'] == $item->id ? 'selected' : '' }}>{{ $item->servico }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
    
                                <div class="form-group col-12 col-md-3">
                                    <label for="forma_pagamento_id" class="form-label">Forma Recebimento</label>
                                    <select name="forma_pagamento_id" id="forma_pagamento_id" class="form-control select2">
                                        <option value="">TODOS</option>
                                        @if (count($formas_pagamento) != 0)
                                        @foreach ($formas_pagamento as $item)
                                        <option value="{{ $item->id }}" {{ $filtro['forma_pagamento_id'] == $item->id ? 'selected' : '' }}>{{ $item->descricao }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
    
                                <div class="form-group col-12 col-md-2">
                                    <label for="data_inicio" class="form-label">Data Inicio</label>
                                    <input type="date" id="data_inicio" placeholder="Data de Inicio da de Inicial" value="{{ $filtro['data_inicio'] ?? "" }}" name="data_inicio" class="form-control">
                                </div>
    
                                <div class="form-group col-12 col-md-2">
                                    <label for="data_final" class="form-label">Data Final</label>
                                    <input type="date" id="data_final" placeholder="Data de Inicio da de FInal" value="{{ $filtro['data_final'] ?? "" }}" name="data_final" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary"><i class="fas fa-search"></i> Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('ficha-pagamentos-receber', [
                            'type' => "receita", 
                            'data_inicio' => $filtro['data_inicio'] ?? "", 
                            'data_final' => $filtro['data_final'] ?? "",
                            'forma_pagamento_id' => $filtro['forma_pagamento_id'] ?? "",
                            'servico_id' => $filtro['servico_id'] ?? "",
                            'ano_lectivo_id' => $filtro['ano_lectivo_id'] ?? ""
                        ]) }}" class="btn btn-danger" target="_blink">Imprimir <i class="fas fa-file-pdf"></i></a>
                    </div>
                    @php $totalValorUnitario = 0; $totalQuantidade = 0; $totalValorGeral = 0; $totalValorMulta = 0; @endphp
                    <div class="card-body table-responsive">
                        @if ($pagamentos)
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Factura</th>
                                    <th>Serviço</th>
                                    <th>Nome Completo</th>
                                    <th class="text-right" title="Valores">Val.</th>
                                    <th class="text-right" title="Quantidade">Qtd.</th>
                                    <th class="text-right" title="Descontos">Des.</th>
                                    <th class="text-right" title="Multa">Multa</th>
                                    <th class="text-right">Total</th>
                                    <th title="Funcionário">Func.</th>
                                    <th>Data</th>
                                    <th>Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pagamentos as $item)
                                <tr>
                                    <td>{{ $item->pagamento->next_factura }}</td>
                                    <td>{{ $item->servico->servico ?? "" }}</td>
                                    <td>{{ $item->pagamento->model($item->pagamento->model, $item->pagamento->estudantes_id) }}</td>
                                    <td class="text-right">{{ number_format($item->preco, 2, ',', '.')  }} </td>
                                    <td class="text-right">{{ number_format($item->quantidade, 2, ',', '.') }} </td>
                                    <td class="text-right">{{ number_format($item->desconto, 2, ',', '.') }} </td>
                                    <td class="text-right">{{ number_format($item->multa, 2, ',', '.') }} </td>
                                    <td class="text-right">{{ number_format( ($item->preco * $item->quantidade) - $item->desconto + $item->multa , 2, ',', '.') }} </td>
                                    <td>{{  $item->pagamento->operador->nome ?? "" }}</td>
                                    <td>{{ $item->date_att }}</td>
                                    <td class="text-end" style="width: 200px">
                                        @if (Auth::user()->can('delete: pagamento'))
                                        <a href='{{ route('web.financeiro-limpar-pagamento', Crypt::encrypt($item->pagamento->id) ) }}' class="btn-danger btn mx-2">
                                            <i class="fas fa-broom"></i>
                                        </a>
                                        @endif
                                        <a href='{{ route('web.ficha-matricula', Crypt::encrypt($item->pagamento->ficha) ) }}' class="btn btn-primary mx-2">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                        <a href='{{ route('ficha-pagamento-propina', $item->pagamento->ficha) }}' target="_blink" class="btn btn-primary mx-2">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </td> {{-- --}}
                                </tr>

                                @php
                                $totalValorUnitario += $item->preco;
                                $totalQuantidade += $item->quantidade;
                                $totalValorGeral += $item->total_pagar;
                                $totalValorMulta += $item->multa;
                                @endphp

                                @endforeach
                            </tbody>
                            <tfoot>
                                <th class="text-right">-----</th>
                                <th class="text-right">-----</th>
                                <th class="text-right">-----</th>
                                <th class="text-right">{{ number_format($totalValorUnitario, 2, ',', '.') }}</th>
                                <th class="text-right">------</th>
                                <th class="text-right">----------</th>
                                <th class="text-right">{{ number_format($totalValorMulta, 2, ',', '.') }}</th>
                                <th class="text-right">{{ number_format($totalValorGeral, 2, ',', '.') }}</th>
                                <th class="text-right">----------</th>
                                <th class="text-right">------</th>
                                <th class="text-right">------</th>
                                {{-- ==================================== --}}
                            </tfoot>
                        </table>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
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
