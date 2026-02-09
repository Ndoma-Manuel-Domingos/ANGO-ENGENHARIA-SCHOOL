@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Todos as Facturas</h1>
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
                <h5><i class="fas fa-info"></i> Listagem de todas as facturas independemente do seu tipo. Ex: Facturas Performa, Factura Recibo e Factura </h5>
            </div>
        </div>
    </div>

    <div class="row px-2">
        <div class="col-12 card">
            <form action="{{ route('web.facturas') }}" method="GET" class="row py-4">
                @csrf
                <div class="form-group col-12 col-md-3">
                    <input type="date" name="data1" class="form-control">
                </div>

                <div class="form-group col-12 col-md-3">
                    <input type="date" name="data2" class="form-control">
                </div>

                <div class="form-group col-12 col-md-2">
                    <select name="factura" class="form-control select2">
                        <option value="">TODAS FACTURAS</option>
                        <option value="FT">FACTURAS</option>
                        <option value="FR">FACTURAS RECIBOS</option>
                        <option value="FP">FACTURAS PROFORMA</option>
                        <option value="todas">TODAS FACTURAS</option>
                    </select>
                </div>

                <div class="form-group col-12 col-md-2">
                    <select name="filtro" class="form-control select2">
                        <option value="todas">Filtrar</option>
                        @if (count($servicos) != 0)
                        @foreach ($servicos as $item)
                        <option value="{{ $item->id }}">{{ $item->servico }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group col-12 col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </div>

            </form>
        </div>
    </div>
</div>

@if ( isset($pagamentos) OR !empty($pagamentos) )
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('ficha-pagamentos-receber', ['data_inicio'=> $_GET['data1'] ?? "", 'data_final' => $_GET['data2'] ?? "", 'servico' => $_GET['filtro'] ?? "", 'factura' => $_GET['factura'] ?? ""]) }}" class="btn btn-primary" target="_blink">Imprimir <i class="fas fa-print"></i></a>
                </div>
                <div class="card-body table-responsive">
                    <table id="carregarTabelaEstudantes" style="width: 100%" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Factura</th>
                                <th>Pagamento</th>
                                <th>Status</th>
                                <th>Nome Completo</th>
                                <th title="Valores" class="text-right">Val.</th>
                                <th>Qtd.</th>
                                <th class="text-right">Total</th>
                                <th title="Funcionário">Func.</th>
                                <th>Data</th>
                                <th>Acções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $totalArrecadado = 0;
                            $totalArrecadadoUnico = 0;
                            $totalQuantidade = 0;
                            @endphp
                            @foreach ($pagamentos as $item)
                            <tr>
                                <td>{{ $item->next_factura ?? '' }}</td>
                                <td>{{ $item->servico->servico ?? '' }}</td>
                                <td class="text-uppercase">{{ $item->status ?? '' }}</td>
                                <td>{{ $item->model($item->model, $item->estudantes_id) }}</td>
                                <td class="text-right">{{ number_format($item->valor, 2, ',', '.')  }} <small>kz</small></td>
                                <td>{{ $item->quantidade ?? '' }}</td>
                                <td class="text-right">{{ number_format( ($item->valor * $item->quantidade) - $item->desconto , 2, ',', '.') }} <small>kz</small></td>
                                <td>{{ $item->operador->nome ?? '' }}</td>
                                <td>{{ $item->data_at ?? '' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('web.ficha-matricula', Crypt::encrypt($item->ficha)) }}" class="btn-primary btn">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </td>
                            </tr>

                            @php
                            $totalArrecadado = $totalArrecadado + ($item->valor * $item->quantidade);
                            $totalArrecadadoUnico = $totalArrecadadoUnico + $item->valor;
                            $totalQuantidade = $totalQuantidade + $item->quantidade;
                            @endphp
                            @endforeach

                        <tfoot class="bg-dark">
                            <th>-----</th>
                            <th>-----</th>
                            <th>-----</th>
                            <th>-----</th>
                            <th class="text-right">{{ number_format($totalArrecadadoUnico, 2, ',', '.') }} Kz</th>
                            <th>{{ number_format($totalQuantidade, 2, ',', '.') }}</th>
                            <th class="text-right">{{ number_format($totalArrecadado  , 2, ',', '.') }} Kz</th>
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
</section>
@endif
@endsection


@section('scripts')

<script>
    $(function() {
        $("#carregarTabelaEstudantes").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');

    });

</script>
@endsection
