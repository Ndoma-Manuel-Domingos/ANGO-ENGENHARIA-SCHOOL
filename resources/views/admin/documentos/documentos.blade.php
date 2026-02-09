@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Documentos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="">Voltar</a></li>
                    <li class="breadcrumb-item active">informativos</li>
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
                    <div class="card-body">
                        <form action="{{ route('web.documento') }}" method="GET" class="row" id="formulario">
                            @csrf

                            <div class="form-group col-12 col-md-3 mb3">
                                <label for="ano_lectivos_id" class="form-label">Anos Lectivos</label>
                                <select type="text" class="form-control select2" name="ano_lectivos_id" id="ano_lectivos_id">
                                    <option value="">TODOS</option>
                                    @foreach ($anos_lectivos as $item)
                                    <option value="{{ $item->id }}" {{ $filtros['ano_lectivos_id'] ==  $item->id ? 'selected' : '' }}>{{ $item->ano }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group col-12 col-md-3 mb3">
                                <label for="servico_id" class="form-label">Serviço</label>
                                <select type="text" class="form-control select2" name="servico_id" id="servico_id">
                                    <option value="">TODOS</option>
                                    @foreach ($servicos as $item)
                                    <option value="{{ $item->id }}" {{ $filtros['servico_id'] ==  $item->id ? 'selected' : '' }}>{{ $item->servico }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-12 col-md-2 mb3">
                                <label for="factura" class="form-label">Tipo Factura</label>
                                <select type="text" class="form-control" name="factura" id="factura">
                                    <option value="">TODOS</option>
                                    <option value="FP" {{ $filtros['factura'] ==  "FP" ? 'selected' : '' }}>FACTURAS PRÓ-FORMA</option>
                                    <option value="FR" {{ $filtros['factura'] ==  "FR" ? 'selected' : '' }}>FACTURAS RECIBO</option>
                                    <option value="RG" {{ $filtros['factura'] ==  "RG" ? 'selected' : '' }}>RECIBO</option>
                                    <option value="FT" {{ $filtros['factura'] ==  "FT" ? 'selected' : '' }}>FACTURAS</option>
                                    <option value="NC" {{ $filtros['factura'] ==  "NC" ? 'selected' : '' }}>FACTURAS ANULADAS</option>
                                </select>
                            </div>


                            <div class="form-group col-12 col-md-2 mb3">
                                <label for="data_inicio" class="form-label">Data Inicio</label>
                                <input type="date" id="data_inicio" class="form-control" name="data_inicio" value="{{ $filtros['data_inicio'] ?? '' }}">
                            </div>
                            <div class="form-group col-12 col-md-2 mb3">
                                <label for="data_final" class="form-label">Data Final</label>
                                <input type="date" id="data_final" class="form-control" name="data_final" value="{{ $filtros['data_final'] ?? '' }}">
                            </div>
                        </form>
                    </div>

                    <div class="card-footer">
                        <button form="formulario" class="btn btn-primary float-right" type="submit">
                            Buscar
                        </button>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    @php
                    $totalValorUnitario = 0;
                    $totalQuantidade = 0;
                    $totalValorGeral = 0;
                    @endphp

                    <div class="card-header">
                        <p>
                            <span style="background-color: #b5fc94;padding: 2px 40px;margin-right: 10px">{{ number_format($total_recibos, 1, ',', '.') }}</span> Recibo
                            <span style="background-color: #bd7be7;padding: 2px 40px;margin-right: 10px">{{ number_format($total_facturas, 1, ',', '.') }}</span>Factura
                            <span style="background-color: #a8b2f5;padding: 2px 40px;margin-right: 10px">{{ number_format($total_facturas_proforma, 1, ',', '.') }}</span>Factura Pró-forma
                            <span style="background-color: #71a9f7;padding: 2px 40px;margin-right: 10px">{{ number_format($total_facturas_recibo, 1, ',', '.') }}</span>Factura Recibo
                            <span style="background-color: #e8acac;padding: 2px 40px;margin-right: 10px">{{ number_format($total_facturas_anuladas, 1, ',', '.') }}</span>Factura Anuladas
                        </p>

                        <a href="{{ route('ficha-pagamentos-receber', ['factura' => $filtros['factura'], 'servico_id' => $filtros['servico_id'], 'data_inicio' => $filtros['data_inicio'] ?? '', 'data_final' => $filtros['data_final'] ?? '']) }}" class="btn btn-danger float-end" target="_blink">Imprimir <i class="fas fa-file-pdf"></i></a>
                    </div>
                    <div class="card-body table-responsive">
                        @if ($pagamentos)
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nº Ficha</th>
                                    <th>Nome Completo</th>
                                    <th>Serviço</th>
                                    <th>Operador</th>
                                    <th>Convertivo</th>
                                    <th>Anulado</th>
                                    <th>Retificado</th>
                                    <th>Total</th>
                                    <th>Data</th>
                                    <th class="text-right">Acções</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach ($pagamentos as $item)
                                    
                                    <tr style="{{ $item->tipo_factura == 'RG' ? 'background-color: #b5fc94;' : ($item->tipo_factura == 'FT' ? 'background-color: #bd7be7;' : ($item->tipo_factura == 'FR' ? 'background-color: #71a9f7;' : ($item->tipo_factura == 'NC' || $item->anulado == 'Y' ? 'background-color: #e8acac;' : ($item->tipo_factura == 'FP' ? 'background-color: #a8b2f5;' : '')))) }}">
                                        <td><a href="{{ route('web.ficha-matricula', Crypt::encrypt($item->ficha)) }}" class="text-primary">{{ $item->next_factura ?? '' }}</a></td>
                                        <td>{{ $item->model($item->model, $item->estudantes_id) }}</td>
                                        <td>{{ $item->servico->servico ?? "" }}</td>
                                        <td>{{ $item->operador->nome ?? "" }}</td>
                                        <td>{{ $item->status($item->convertido_factura) }}</td>
                                        <td>{{ $item->status($item->anulado) }}</td>
                                        <td>{{ $item->status($item->retificado) }}</td>
                                        <td class="text-right">{{ number_format( ($item->total_iva + $item->total_incidencia + $item->multa) - $item->desconto , 2, ',', '.') }}</td>
                                        <td>{{ $item->data_at }}</td>
                                        <td>
                                            {{-- @if ($item->status == "cancelado" OR $item->anulado == "Y") --}}
                                            {{-- <a href='{{ route('web.recuperar-facturas-create', $item->ficha) }}' title="Recuperada Factura" class="btn btn-warning">
                                            <i class="fas fa-undo-alt"></i>
                                            </a> --}}
                                            {{-- @else --}}
                                            <a href='{{ route('web.documento-cancelar-facturas', $item->ficha) }}' style="{{ $item->anulado == 'Y' ? 'cursor: not-allowed' : '' }};" title="Anular Factura" class="btn {{ $item->anulado == 'Y' ? 'btn-secondary' : 'btn-danger' }}  mx-2">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                            {{-- @endif --}}
                                            @if ($item->tipo_factura == "FT" && $item->status == "Pendente")
                                            <a href='{{ route('web.emitir-recibo-facturas', $item->ficha) }}' title="Emitir Recibo Liquidar a factura" class="btn btn-success mx-2">
                                                <i class="fas fa-money-bill-alt"></i>
                                            </a>
                                            @endif
    
                                            @if ($item->tipo_factura == "FP" && $item->status == "Pendente")
                                            <a href='{{ route('web.converter-facturas', $item->ficha) }}' title="Converter Factura" class="btn btn-success mx-2">
                                                <i class="fas fa-exchange-alt"></i>
                                            </a>
                                            @endif
    
                                            {{-- printer --}}
                                            @if ($item->tipo_factura == 'RG')
                                            <a href='{{ route('comprovativo-factura-recibo-recibo', $item->ficha) }}' target="_blink" title="IMPRIMIR RECIBO" class="btn btn-primary mx-2">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @endif
    
                                            @if ($item->tipo_factura == 'FT')
                                            <a href='{{ route('comprovativo-factura-factura', $item->ficha) }}' target="_blink" title="IMPRIMIR FACTURA" class="btn btn-primary mx-2">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @endif
    
                                            @if ($item->tipo_factura == 'FR')
                                            <a href='{{ route('comprovativo-factura-recibo', $item->ficha) }}' target="_blink" title="IMPRIMIR FACTURA RECIBO" class="btn btn-primary mx-2">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @endif
    
                                            @if ($item->tipo_factura == 'NC')
                                            <a href='{{ route('comprovativo-factura-nota-credito', $item->ficha) }}' target="_blink" title="IMPRIMIR FACTURA ANULADA" class="btn btn-primary mx-2">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @endif
    
                                            @if ($item->tipo_factura == 'FP')
                                            <a href='{{ route('comprovativo-factura-proforma', $item->ficha) }}' target="_blink" title="IMPRIMIR FACTURA PRO-FORMA" class="btn btn-primary mx-2">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @endif
    
                                        </td>
                                    
                                    </tr>
                                
                                    @php
                                        $totalValorUnitario = $totalValorUnitario + $item->valor;
                                        $totalQuantidade = $totalQuantidade + $item->quantidade;
                                        $totalValorGeral = $totalValorGeral + ($item->total_iva + $item->total_incidencia + $item->multa) - $item->desconto;
                                    @endphp
                                @endforeach
                            </tbody>
                            
                            
                            <tfoot>
                                <th class="bg-success">--------------</th>
                                <td class="bg-success">----------------</td>
                                <td class="bg-success">----------------</td>
                                <td class="bg-success">----------------</td>
                                <td class="bg-success">----------------</td>
                                <td class="bg-success">----------------</td>
                                <td class="bg-success">----------------</td>
                                <th class="bg-success">{{ number_format($totalValorGeral, 2, ',', '.') }} Kz</th>
                                <th class="bg-success">----------</th>
                                <th class="bg-success">------</th>
                            </tfoot>
                            
                        </table>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
    </div>
    <!-- /.container-fluid -->
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
