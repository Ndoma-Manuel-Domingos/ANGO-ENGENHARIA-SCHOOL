@extends('layouts.escolas')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0 text-dark">Liquidar Facturas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Voltar</a></li>
                <li class="breadcrumb-item active">Facturas</li>
            </ol>

            </div><!-- /.col -->
        </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Processo que ajudar visualizar todas as facturas gerada pelo sistema para ser liquidadas. Ex: Facturas Performa e Factura </h5>
                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('web.liquidar-factura') }}" method="GET">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12 col-md-3">
                                    <input type="date" value="{{ $requests['data1'] ?? "" }}" name="data1" class="form-control">
                                </div>
            
                                <div class="form-group col-12 col-md-3">
                                    <input type="date" value="{{ $requests['data2'] ?? "" }}" name="data2" class="form-control">
                                </div>
            
                                <div class="form-group col-12 col-md-2">
                                    <select name="factura" class="form-control">
                                        <option value="">TODAS FACTURAS</option>
                                        <option value="FT" {{ $requests['factura'] == "FT" ? 'selected' : '' }}>FACTURAS</option>
                                        <option value="FP" {{ $requests['factura'] == "FP" ? 'selected' : '' }}>FACTURAS PROFORMA</option>
                                    </select>
                                </div>
            
                                <div class="form-group col-12 col-md-2">
                                    <select name="filtro" class="form-control">
                                        <option value="">Filtrar</option>
                                        @if (count($servicos) != 0)
                                            @foreach ($servicos as $item)
                                                <option value="{{ $item->id }}" {{ $requests['filtro'] == $item->id ? 'selected' : '' }}>{{ $item->servico }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i>Fitrar</button>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('factura-aliquidar-pagamentos', ['factura'=> $requests['factura'] ?? '', 'data1'=> $requests['data1'] ?? '', 'data2' => $requests['data2'] ?? '', 'filtro' => $requests['filtro'] ?? '']) }}" class="btn btn-primary" target="_blink">Imprimir <i class="fas fa-print"></i></a>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="example1"  style="width: 100%" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nº Ficha</th>
                                        <th>Pagamento</th>
                                        <th>Nome Completo</th>
                                        <th title="Valores">Val.</th>
                                        <th>Qtd.</th>
                                        <th title="Descontos">Des.</th>
                                        <th title="Multas">Mult.</th>
                                        <th>Total</th>
                                        <th>Data</th>
                                        <th>Operador</th>
                                        <th>Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @php
                                        $totalArrecadado = 0;
                                        $totalArrecadadoUnico = 0;
                                        $quantidadeArrecado = 0;
                                   @endphp
                                    @foreach ($pagamentos as $item)
                                        <tr>
                                            <td>{{ $item->next_factura }}</td>
                                            <td>{{ $item->servico->servico ?? "" }}</td>
                                            <td>{{ $item->model($item->model, $item->estudantes_id) }}</td>
                                            <td>{{ number_format($item->valor, 2, ',', '.')  }} <small>kz</small></td>
                                            <td>{{ $item->quantidade }}</td>
                                            <td>{{ number_format($item->desconto, 2, ',', '.') }} <small>kz</small></td>
                                            <td>{{ number_format($item->multa, 2, ',', '.') }} <small>kz</small></td>
            
                                            <td>{{ number_format( ($item->valor * $item->quantidade) - $item->desconto + $item->multa, 2, ',', '.') }} <small>kz</small></td>
                                            <td>{{ $item->data_at }}</td>
                                            <td>{{ $item->operador->nome ?? "" }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('web.liquidar-facturar-index', $item->ficha) }}" class="btn btn-primary mx-1" title="Liquidar Factura">
                                                    <i class="fas fa-money"></i>
                                                </a>
        
                                                <a href="{{ route('web.ficha-matricula', Crypt::encrypt($item->ficha)) }}" class="btn btn-primary mx-1" title="Visualizar  Mais informações  da factura">
                                                    <i class="fas fa-eye"></i>
                                                </a>
        
                                                @if ($item->model == "estudante")
                                                    <a href="{{ route('ficha-pagamento-propina', $item->ficha) }}" target="_blank" class="btn btn-primary mx-1" title="Imprimir esta Factura">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                @else
                                                    @if ($item->model == "funcionario")
                                                        <a href="{{ route('ficha-pagamento-salario', $pagamento->ficha) }}" target="_blank" class="btn btn-primary mx-1" title="Imprimir esta Factura">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('comprovativo-factura-pagamento-servico', $pagamento->ficha) }}" target="_blank" class="btn btn-primary mx-1" title="Imprimir esta Factura">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                    @endif
                                                @endif
        
                                            </td>
                                        </tr>
        
                                        @php
                                            $totalArrecadado = $totalArrecadado + (($item->valor * $item->quantidade) - $item->desconto + $item->multa);
                                            $totalArrecadadoUnico = $totalArrecadadoUnico + $item->valor;
                                            $quantidadeArrecado = $quantidadeArrecado + $item->quantidade;
                                        @endphp    
                                    @endforeach
        
                                    <tfoot class="bg-dark">
                                        <th>-----</th>
                                        <th>-----</th>
                                        <th>-----</th>
                                        <th>{{ number_format($totalArrecadadoUnico, 2, ',', '.') }} Kz</th>
                                        <th>{{ number_format($quantidadeArrecado, 0, ',', '.') }}</th>
                                        <th>-----</th>
                                        <th>-----</th>
                                        <th>{{ number_format($totalArrecadado  , 2, ',', '.') }} Kz</th>
                                        <th>------</th>
                                        <th>------</th>
                                        <th>------</th>
                                      </tfoot>
                                    
                                </tbody>
                            </table>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
  