@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Facturas Anuladas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.financeiro-pagamentos') }}">Voltas</a></li>
                    <li class="breadcrumb-item active">Detalhes</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="container-fluid">
    <section class="content">

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5 class="text-warning"><i class="fas fa-info"></i>
                        Colocar a referência da Factura, Para poder Cancelar a Factura, Anular um Pagamento ou Recuperar
                        á Factura.
                    </h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <form action="{{ route('web.cancelar-facturas-search') }}" method="POST" class="row p-4">
                        @csrf
                        <div class="form-group col-12 col-md-6">
                            <input type="text" placeholder="Informe a referência da Factura" name="factura" class="form-control">
                        </div>

                        <div class="form-group col-12 col-md-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Factura</th>
                                    <th>Referente À</th>
                                    <th>Pagamento</th>
                                    <th>Status</th>
                                    <th>Nome Completo</th>
                                    <th title="Funcionário">Operador</th>
                                    <th>Data</th>
                                    <th class="text-end">Total do documento</th>
                                    <th>Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pagamentos as $item)
                                <tr>
                                    <td><a href="{{ route('web.ficha-matricula', Crypt::encrypt($item->ficha)) }}">{{ $item->next_factura ?? '' }}</a></td>
                                    <td>{{ $item->numeracao_proforma ?? '' }}</td>
                                    <td>{{ $item->servico->servico ?? '' }}</td>
                                    <td class="text-uppercase">{{ $item->status ?? '' }}</td>
                                    <td>{{ $item->model($item->model, $item->estudantes_id ) }}</td>
                                    <td>{{ $item->operador->nome ?? "" }}</td>
                                    <td>{{ $item->data_at }}</td>
                                    <td class="text-end">{{ number_format( ($item->valor * $item->quantidade) - $item->desconto + $item->multa , 2, ',', '.') }} <small>kz</small></td>
                                    <td class="text-end">
                                        {{-- printer --}}
                                        @if ($item->tipo_factura == 'NC')
                                        <a href='{{ route('comprovativo-factura-nota-credito', $item->ficha) }}' title="IMPRIMIR FACTURA ANULADA" class="btn btn-secondary">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @endif
                                        {{-- printer --}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

</div><!-- /.row -->
</section>
</div>
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
