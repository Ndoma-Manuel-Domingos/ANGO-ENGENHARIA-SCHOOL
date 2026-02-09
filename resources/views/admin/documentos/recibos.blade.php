@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Facturas Recibos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.documento') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Documentos</li>
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
                        <form action="{{ route('web.documento.recibos') }}" method="GET" class="row" id="formulario">
                            @csrf
                            <div class="col-12 col-md-3 mb3">
                                <label for="" class="form-label">Data Inicio</label>
                                <input type="date" class="form-control" name="data_inicio" value="{{ $filtros['data_inicio'] ?? '' }}">
                            </div>
                            <div class="col-12 col-md-3 mb3">
                                <label for="" class="form-label">Data Final</label>
                                <input type="date" class="form-control" name="data_final" value="{{ $filtros['data_final'] ?? '' }}">
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
                    <div class="card-header">
                        <a href="{{ route('ficha-pagamentos-receber', ['factura' => 'FP', 'data_inicio' => $filtros['data_inicio'] ?? '', 'data_final' => $filtros['data_final'] ?? '']) }}" class="btn btn-primary" target="_blink">Imprimir <i class="fas fa-print"></i></a>
                    </div>
                    @php
                    $totalValorUnitario = 0;
                    $totalQuantidade = 0;
                    $totalValorGeral = 0;
                    @endphp

                    <div class="card-body table-responsive">
                        @if ($pagamentos)
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Factura</th>
                                    <th>Conforme</th>
                                    <th>Nome Completo</th>
                                    <th>Total</th>
                                    <th>Data</th>
                                    <th class="text-right">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pagamentos as $item)
                                <tr>
                                    <td><a href="{{ route('web.ficha-matricula', [Crypt::encrypt($item->pagamento->ficha), "RG"]) }}">{{ $item->next_factura ?? '' }}</a></td>
                                    <td><a href="{{ route('web.ficha-matricula', Crypt::encrypt($item->pagamento->ficha)) }}">{{ $item->numeracao_proforma ?? '' }}</a></td>
                                    <td>{{ $item->model($item->model, $item->estudantes_id) }}</td>
                                    <td>{{ number_format( ($item->valor * $item->quantidade) - $item->desconto + $item->multa , 2, ',', '.') }} <small>kz</small></td>
                                    <td>{{ $item->data_at }}</td>
                                    <td class="text-end">
                                        @if ($item->status == "cancelado" OR $item->anulado == "Y")
                                        <a href='{{ route('web.recuperar-facturas-create', $item->ficha) }}' title="Recuperada Factura" class="btn btn-warning mx-2">
                                            <i class="fas fa-undo-alt"></i>
                                        </a>
                                        @else
                                        <a href='{{ route('web.cancelar-facturas-create', $item->ficha) }}' title="Anular Factura" class="btn-danger mx-2">
                                            <i class="fas fa-ban"></i>
                                        </a>
                                        @endif
                                        {{-- printer --}}
                                        @if ($item->tipo_factura == 'RG')
                                        <a href='{{ route('comprovativo-factura-recibo-recibo', $item->ficha) }}' title="IMPRIMIR RECIBO" class="btn btn-secondary mx-2">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @endif

                                        @if ($item->tipo_factura == 'FT')
                                        <a href='{{ route('comprovativo-factura-factura', $item->ficha) }}' title="IMPRIMIR FACTURA" class="btn btn-secondary mx-2">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @endif

                                        @if ($item->tipo_factura == 'FR')
                                        <a href='{{ route('comprovativo-factura-recibo', $item->ficha) }}' title="IMPRIMIR FACTURA RECIBO" class="btn btn-secondary mx-2">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @endif

                                        @if ($item->tipo_factura == 'NC')
                                        <a href='{{ route('comprovativo-factura-nota-credito', $item->ficha) }}' title="IMPRIMIR FACTURA ANULADA" class="btn btn-secondary mx-2">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @endif

                                        @if ($item->tipo_factura == 'FP')
                                        <a href='{{ route('comprovativo-factura-proforma', $item->ficha) }}' title="IMPRIMIR FACTURA PRO-FORMA" class="btn btn-secondary mx-2">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @endif
                                        {{-- printer --}}
                                    </td>
                                </tr>

                                @php
                                $totalValorUnitario = $totalValorUnitario + $item->valor;
                                $totalQuantidade = $totalQuantidade + $item->quantidade;
                                $totalValorGeral = $totalValorGeral + ((($item->valor * $item->quantidade) - $item->desconto) + $item->multa);
                                @endphp

                                @endforeach
                            </tbody>
                            <tfoot>
                                <th class="bg-success">--------------</th>
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
        <!-- /.row -->
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
