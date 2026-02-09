@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Listagem dos Serviços</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Serviços</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('financeiros.listagem-servicos') }}" method="GET">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12 col-md-3">
                                    <label for="ano_lectivos_id" class="form-label">Anos Lectivos</label>
                                    <select name="ano_lectivos_id" class="form-control ano_lectivos_id select2" id="ano_lectivos_id">
                                        <option value="">Todos</option>
                                        @foreach ($anos_lectivos as $item)
                                            <option value="{{ $item->id }}" {{ $requests['ano_lectivos_id'] == $item->id ? 'selected' : '' }}>{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                </div>
            
                                <div class="form-group col-12 col-md-3">
                                    <label for="servico_id" class="form-label">Serviços</label>
                                    <select name="servico_id" id="servico_id" class="form-control servico_id select2">
                                        <option value="">Todos</option>
                                        @if (count($servicos) != 0)
                                        @foreach ($servicos as $item)
                                        <option value="{{ $item->id }}" {{ $requests['servico_id'] == $item->id ? 'selected' : '' }}>{{ $item->servico }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                
                                <div class="form-group col-12 col-md-3">
                                    <label for="turma_id" class="form-label">Turmas</label>
                                    <select name="turma_id" id="turma_id" class="form-control turma_id select2">
                                        <option value="">Todos</option>
                                        @foreach ($turmas as $item)
                                        <option value="{{ $item->id }}" {{ $requests['turma_id'] == $item->id ? 'selected' : '' }}>{{ $item->turma }}</option>
                                        @endforeach
                                    </select>
                                </div>
                           
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary imprimir_lista"><i class="fas fa-filter"></i> Filtra</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        @if (count($servicosTurmas) != 0)
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('financeiros.listagem-servicos-imprmir', ['ano_lectivos_id' => $requests['ano_lectivos_id'] ?? "", 'servico_id' => $requests['servico_id'] ?? "", 'turma_id' => $requests['turma_id'] ?? ""]) }}" target="_blink" class="btn btn-primary float-right"><i class="fas fa-print"></i> Imprimir</a>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="carregarTabela" style="width: 100%" class="table  table-bordered table-striped table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2">Entidade</th>
                                    <th rowspan="2">Serviço</th>
                                    <th rowspan="2">Pagamento</th>
                                    <th rowspan="2">Preço</th>
                                    <th rowspan="2">Multa</th>
                                    <th rowspan="2">Desconto</th>
                                    <th rowspan="2">Data Inicio</th>
                                    <th rowspan="2">Data Final</th>
                                    <th rowspan="2">Prestações</th>
                                    <th rowspan="2">Dia Inicio Pag.</th>
                                    <th rowspan="2">Dia Final Pag.</th>
                                    
                                    <th colspan="3" class="text-center">Taxas das Multas</th>
                                    <th colspan="3" class="text-center">Dias para aplicação das Multas</th>
                                    <th rowspan="2">Acções</th>
                                </tr>
                                <tr>
                                    <th class="text-center">A</th>
                                    <th class="text-center">B</th>
                                    <th class="text-center">C</th>
                                    <th class="text-center">A</th>
                                    <th class="text-center">B</th>
                                    <th class="text-center">C</th>
                                </tr>
                            </thead>
        
                            <tbody>
                                @foreach ($servicosTurmas as $item)
                                    <tr>
                                        @if ($item->model == "turmas")
                                            <td><a href="{{ route('web.apresentar-turma-informacoes', Crypt::encrypt($item->entidade($item->model, $item->turmas_id)->id)) }}">{{ $item->entidade($item->model, $item->turmas_id)->turma }}</a></td>
                                        @else
                                            @if ($item->model == "escola")
                                            <td>{{ $item->entidade($item->model, $item->turmas_id)->nome }}</td>
                                            @endif
                                        @endif
                                        <td>{{ $item->servico->servico ?? "" }}</td>
                                        <td>{{ $item->pagamento }}</td>
                                        <td>{{ number_format($item->preco, 2, ',', '.') }} Kz</td>
                                        <td>{{ number_format($item->multa, 2, ',', '.') }} Kz</td>
                                        <td>{{ number_format($item->desconto, 2, ',', '.') }} Kz</td>
                                        <td>{{ $item->data_inicio }}</td>
                                        <td>{{ $item->data_final }}</td>
                                        <td>{{ $item->total_vezes }}</td>
                                        <td>{{ $item->intervalo_pagamento_inicio }}</td>
                                        <td>{{ $item->intervalo_pagamento_final }}</td>
                                        <td class="text-center">{{ $item->taxa_multa1 }}%</td>
                                        <td class="text-center">{{ $item->taxa_multa2 }}%</td>
                                        <td class="text-center">{{ $item->taxa_multa3 }}%</td>
                                        <td class="text-center">{{ $item->taxa_multa1_dia }}</td>
                                        <td class="text-center">{{ $item->taxa_multa2_dia }}</td>
                                        <td class="text-center">{{ $item->taxa_multa3_dia }}</td>
                                        <td>
                                            @if (Auth::user()->can('delete: servicos'))
                                            <a href="#" title="Remover serviço turma" id="{{ $item->id }}" class="deleteModal text-danger btn-sm"><i class="fa fa-times"></i></a>
                                            @endif
                                            @if (Auth::user()->can('update: servicos'))
                                            <a href="{{ route('financeiros.listagem-servicos-edit', Crypt::encrypt($item->id)) }}" title="Editar serviço turma" class="text-success btn-sm"><i class="fa fa-edit"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection


@section('scripts')
  <script>
    const tabelas = [
        "#carregarTabela", 
        "#carregarTabela1", ];
    tabelas.forEach(inicializarTabela);    
    
    excluirRegistro('.deleteModal', `{{ route('web.remover-servico-turma', ':id') }}`);
  </script>
@endsection