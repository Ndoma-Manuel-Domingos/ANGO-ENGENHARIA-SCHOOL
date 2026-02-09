@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Facturas sem Pagamentos</h1>
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
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> controle de dívidas correntes e dívidas vencidas.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Saldo Total</span>
                        <h5 class="info-box-number">{{ number_format($divCorr, 2, ',', '.') }}</h5>
                        @if (($divCorr - $divVenc) != 0)
                        <span class="info-box-text text-success">Existem saldo nas dívidas</span>
                        @else
                        <span class="info-box-text">Não existem dívidas</span>
                        @endif

                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Dívida Corrente</span>
                        <h5 class="info-box-number">{{ number_format($divCorr, 2, ',', '.') }}</h5>
                        @if ($divCorr != 0)
                        <span class="info-box-text text-success">Existem pagamentos pendentes</span>
                        @else
                        <span class="info-box-text">Não existem pagamentos pendentes</span>
                        @endif
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Dívida Vencida</span>
                        <h5 class="info-box-number">{{ number_format($divVenc, 2, ',', '.') }}</h5>
                        @if ($divVenc != 0)
                        <span class="info-box-text text-success">Existem pagamentos fora do prazo</span>
                        @else
                        <span class="info-box-text">Não existem pagamentos fora do prazo</span>
                        @endif
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('web.documento.facturas-sem-pagamento') }}" method="GET" class="row" id="formulario">
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
                        <a href="{{ route('web.documento.create') }}" class="btn btn-primary">Criar Factura</a>
                    </div>
                    <div class="card-body table-responsive">
                        @if ($pagamentos)
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th>Nº Ficha</th>
                                    <th>Estado Factura</th>
                                    <th>Nome Completo</th>
                                    <th>Total</th>
                                    <th>Data Vencimento</th>
                                    <th>Data</th>
                                    <th class="text-right">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pagamentos as $item)
                                <tr>
                                    <td><a href="{{ route('web.ficha-matricula', Crypt::encrypt($item->ficha)) }}">{{ $item->next_factura ?? '' }}</a></td>
                                    @if ($item->data_vencimento >= date("Y-m-d"))
                                    <td class="text-success">Corrente</td>
                                    @else
                                    @if($item->data_vencimento < date("Y-m-d")) <td class="text-danger">Vencida</td>
                                        @endif
                                        @endif
                                        <td>{{ $item->model($item->model, $item->estudantes_id) }}</td>
                                        <td>{{ number_format( ($item->valor * $item->quantidade) - $item->desconto + $item->multa , 2, ',', '.') }} <small>kz</small></td>
                                        <td>{{ $item->data_vencimento }}</td>
                                        <td>{{ $item->data_at }}</td>
                                        <td class="text-end">
                                            <a href='{{ route('web.documento-cancelar-facturas', $item->ficha) }}' title="Anular Factura" class="btn-danger mx-2">
                                                <i class="fas fa-ban"></i>
                                            </a>

                                            <a href='{{ route('web.emitir-recibo-facturas', $item->ficha) }}' title="Emitir Recibo Liquidar a factura" class="btn-success mx-2">
                                                <i class="fas fa-money-bill-alt"></i>
                                            </a>

                                            {{-- printer --}}
                                            @if ($item->tipo_factura == 'RG')
                                            <a href='{{ route('comprovativo-factura-recibo-recibo', $item->ficha) }}' target="_blink" title="IMPRIMIR RECIBO" class="btn btn-secondary mx-2">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @endif

                                            @if ($item->tipo_factura == 'FT')
                                            <a href='{{ route('comprovativo-factura-factura', $item->ficha) }}' target="_blink" title="IMPRIMIR FACTURA" class="btn btn-secondary mx-2">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @endif

                                            @if ($item->tipo_factura == 'FR')
                                            <a href='{{ route('comprovativo-factura-recibo', $item->ficha) }}' target="_blink" title="IMPRIMIR FACTURA RECIBO" class="btn btn-secondary mx-2">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @endif

                                            @if ($item->tipo_factura == 'NC')
                                            <a href='{{ route('comprovativo-factura-nota-credito', $item->ficha) }}' target="_blink" title="IMPRIMIR FACTURA ANULADA" class="btn btn-secondary mx-2">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @endif

                                            @if ($item->tipo_factura == 'FP')
                                            <a href='{{ route('comprovativo-factura-proforma', $item->ficha) }}' target="_blink" title="IMPRIMIR FACTURA PRO-FORMA" class="btn btn-secondary mx-2">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @endif
                                            {{-- printer --}}
                                        </td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                        @endif


                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

            <div class="col-12 mb-5">
                <a href="{{ route('web.documento.facturas-sem-pagamento-correntes') }}" class="btn btn-primary" target="_blink">Imprimir Factura Correntes <i class="fas fa-print"></i></a>
                <a href="{{ route('web.documento.facturas-sem-pagamento-vencidas') }}" class="btn btn-primary" target="_blink">Imprimir Factura Vencidas <i class="fas fa-print"></i></a>
                <a href="{{ route('web.documento.facturas-sem-pagamento-geral') }}" class="btn btn-primary" target="_blink">Imprimir Todos <i class="fas fa-print"></i></a>
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
