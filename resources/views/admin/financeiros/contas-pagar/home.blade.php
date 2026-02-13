@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Contas a Pagar</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Contas Pagar</li>
                </ol>

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('financeiros.financeiro-contas-pagar') }}" method="GET">
                    @csrf
                    <div class="card">
                        <div class="card-body row">

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
                        <div class="card-footer">
                            <button class="btn btn-primary"><i class="fas fa-search"></i> Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="row">

            @if (Auth::user()->can('read: salario'))
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h2>:</h2>
                        <p>Processamento de salário de professores</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <a href="{{ route('web.financeiro-pagamentos-salario') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            @endif

        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"></h3>
                        @if (Auth::user()->can('read: pagamento'))
                        <a href="{{ route('web.novo-pagamentos-pagar') }}" class="btn btn-primary float-end">Novo pagamentos</a>
                        @endif
                        <a href="{{ route('ficha-pagamentos-pagar', [
                            'type' => "despesa", 
                            'data_inicio' => $filtro['data_inicio'] ?? "", 
                            'data_final' => $filtro['data_final'] ?? "",
                            'forma_pagamento_id' => $filtro['forma_pagamento_id'] ?? "",
                            'servico_id' => $filtro['servico_id'] ?? "",
                            'ano_lectivo_id' => $filtro['ano_lectivo_id'] ?? ""
                        ]) }}" class="btn btn-danger" target="_blink">Imprimir <i class="fas fa-file-pdf"></i></a>
                    </div>

                    <div class="card-body table-responsive">
                        @if ($pagamentos)
                        <table id="carregarTabelaPagamentos" style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nota Saída</th>
                                    <th>Pagamento</th>
                                    <th>Nome Completo</th>
                                    <th title="Funcionário">Func.</th>
                                    <th>Data</th>
                                    <th class="text-right">Total</th>
                                    <th>Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $somaFinal = 0;
                                @endphp
                                @foreach ($pagamentos as $item)
                                <tr>
                                    <td>{{ $item->next_factura }}</td>
                                    <td class="text-capitalize">{{ $item->pago_at }}</td>
                                    <td>{{ $item->model($item->model, $item->estudantes_id) }}</td>
                                    <td>{{ $item->operador->nome ?? "" }}</td>
                                    <td>{{ $item->data_at }}</td>
                                    <td class="text-right">{{ number_format($item->valor2, 2, ',', '.') }}</td>
                                    <td class="text-end">
                                        @if (Auth::user()->can('delete: pagamento'))
                                        <a href='{{ route('web.financeiro-limpar-pagamento', Crypt::encrypt($item->id) ) }}' class="btn-danger btn mx-2">
                                            <i class="fas fa-broom"></i>
                                        </a>
                                        @endif
                                        <a href='{{ route('web.ficha-matricula', Crypt::encrypt($item->ficha)) }}' class="btn btn-primary mx-1">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                        @if ($item->type_service == 'salario')
                                        <a href='{{ route('ficha-pagamento-salario', $item->ficha) }}' target="_blink" class="btn-primary btn">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @else
                                        <a href='{{ route('ficha-pagamento-servico', $item->ficha) }}' target="_blink" class="btn-primary btn">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        @endif

                                    </td>
                                </tr>
                                @php
                                $somaFinal = $somaFinal + $item->valor2;
                                @endphp
                                @endforeach

                            <tfoot>
                                <th class="bg-danger">-----</th>
                                <th class="bg-danger">------</th>
                                <th class="bg-danger">-----</th>
                                <th class="bg-danger">------</th>
                                <th class="bg-danger">------</th>
                                <th class="bg-danger text-right">{{ number_format($somaFinal , 2, ',', '.') }} Kz</th>
                                <th class="bg-danger"></th>
                            </tfoot>

                            </tbody>
                        </table>
                        @endif


                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script>
    $(function() {
        $("#carregarTabelaPagamentos").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection
