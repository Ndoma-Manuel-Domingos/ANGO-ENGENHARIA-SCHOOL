@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Buscas Gerais de Pagamentos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Relatórios</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('web.financeiro-buscas-gerais') }}" method="GET">
                    @csrf
                    <div class="card">
                        <div class="card-header"></div>
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

                                <div class="form-group col-12 col-md-2">
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

                                <div class="form-group col-12 col-md-2">
                                    <label for="type_id" class="form-label">Tipo</label>
                                    <select name="type_id" id="type_id" class="form-control select2">
                                        <option value="">TODOS</option>
                                        <option value="receita" {{ $filtro['type_id'] == 'receita' ? 'selected' : '' }}>Receita</option>
                                        <option value="despesa" {{ $filtro['type_id'] == 'despesa' ? 'selected' : '' }}>Dispesas</option>
                                    </select>
                                </div>

                                <div class="form-group col-12 col-md-2">
                                    <label for="forma_pagamento_id" class="form-label">Forma de Pagamento</label>
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
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('ficha-pagamentos-receber', 
                            ['type_id'=> $_GET['type_id'] ?? "", 'data_inicio'=> $_GET['data_inicio'] ?? "", 'data_final' => $_GET['data_final'] ?? "", 'servico_id' => $_GET['servico_id'] ?? "", 'forma_pagamento_id' => $_GET['forma_pagamento_id'] ?? "", 'ano_lectivo_id' => $_GET['ano_lectivo_id'] ?? "", 'all' => "todos" ]) }}" class="btn btn-danger float-end" target="_blank">
                            Imprimir <i class="fas fa-file-pdf"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered table-striped">
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
                                @php $somaFinal = 0; @endphp
                                @foreach ($pagamentos as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->pagamento->ficha }}</td>
                                        <td>{{ $item->servico->servico ?? "" }}</td>
                                        <td>{{ $item->pagamento->next_factura }}</td>
                                        <td>{{ $item->pagamento->model($item->pagamento->model, $item->pagamento->estudantes_id) }}</td>
                                        <td>{{ $item->pagamento->operador->nome ?? "" }}</td>
                                        <td>{{ $item->pagamento->operador_pagamento }}</td>
                                        <td>{{ $item->date_att }}</td>
                                        @if ($item->pagamento->caixa_at == "receita")
                                        <td class="text-right text-success"> + {{ number_format($item->total_pagar, 2, ',', '.') }}</td>
                                        @endif
                                        @if ($item->pagamento->caixa_at == "despesa")
                                        <td class="text-right text-danger"> - {{ number_format($item->total_pagar, 2, ',', '.') }}</td>
                                        @endif
                                        <td class="text-end">
                                            <a href="{{ route('web.ficha-matricula', Crypt::encrypt($item->pagamento->ficha)) }}" class="btn-primary btn">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @php $somaFinal += $item->total_pagar; @endphp
                                @endforeach
                            </tbody>
                            
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
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    
    const tabelas = [
        "#carregarTabela"
    , ];
    tabelas.forEach(inicializarTabela);

</script>
@endsection
