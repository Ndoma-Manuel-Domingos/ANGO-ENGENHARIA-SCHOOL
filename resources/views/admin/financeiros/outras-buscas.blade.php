@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Buscas Mensais dos pagamentos (Recebimentos)</h1>
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
                <form action="{{ route('web.financeiro-outras-buascas') }}" method="GET">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12 col-md-3">
                                    <label for="servico_id" class="form-label">Serviços</label>
                                    <select name="servico_id" id="servico_id" class="form-control select2">
                                        <option value="">TODOS</option>
                                        @if (count($servicos) != 0)
                                            @foreach ($servicos as $item)
                                                <option value="{{ $item->id }}" {{ $requests['servico_id'] == $item->id ? 'selected' : '' }}>{{ $item->servico }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label for="mensal" class="form-label">Meses</label>
                                    <select name="mensal" id="mensal" class="form-control select2">
                                        <option value="">TODOS</option>
                                        <option value="January" {{ $requests['mensal'] == "January" ? 'selected' : '' }}>Janeiro</option>
                                        <option value="February" {{ $requests['mensal'] == "February" ? 'selected' : '' }}>Fevereiro</option>
                                        <option value="March" {{ $requests['mensal'] == "March" ? 'selected' : '' }}>Março</option>
                                        <option value="April" {{ $requests['mensal'] == "April" ? 'selected' : '' }}>Abril</option>
                                        <option value="May" {{ $requests['mensal'] == "May" ? 'selected' : '' }}>Maio</option>
                                        <option value="June" {{ $requests['mensal'] == "June" ? 'selected' : '' }}>Junho</option>
                                        <option value="July" {{ $requests['mensal'] == "July" ? 'selected' : '' }}>Julho</option>
                                        <option value="August" {{ $requests['mensal'] == "August" ? 'selected' : '' }}>Agosto</option>
                                        <option value="September" {{ $requests['mensal'] == "September" ? 'selected' : '' }}>Setembro</option>
                                        <option value="October" {{ $requests['mensal'] == "October" ? 'selected' : '' }}>Outubro</option>
                                        <option value="November" {{ $requests['mensal'] == "November" ? 'selected' : '' }}>Novembro</option>
                                        <option value="December" {{ $requests['mensal'] == "December" ? 'selected' : '' }}>Dezembro</option>
                                    </select>
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="estado_pagamento" class="form-label">Formas de Recebimento</label>
                                    <select name="estado_pagamento" id="estado_pagamento" class="form-control select2">
                                        <option value="">TODOS</option>
                                        @if (count($formas_pagamento) != 0)
                                            @foreach ($formas_pagamento as $item)
                                                <option value="{{ $item->id }}" {{ $requests['estado_pagamento'] == $item->id ? 'selected' : '' }}>{{ $item->descricao }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="ano_lectivo_id" class="form-label">Ano Lectivo</label>
                                    <select name="ano_lectivo_id" id="ano_lectivo_id" class="form-control select2">
                                        <option value="">TODOS</option>
                                        @if (count($listasanolectivo) != 0)
                                            @foreach ($listasanolectivo as $item2)
                                                <option value="{{ $item2->id }}" {{ $requests['ano_lectivo_id'] == $item->id ? 'selected' : '' }}>{{ $item2->ano }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        @if ( isset($pagamentos) OR !empty($pagamentos) )
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('outras-baixa', ['servico_id' => $_GET['servico_id'] ?? "", 'ano_lectivo_id' => $_GET['ano_lectivo_id'] ?? "" , 'mensals' => $_GET['mensal'] ?? "" ]) }}" class="btn btn-danger" target="_blink">Imprimir <i class="fas fa-file-pdf"></i></a>
                    </div>
                    <div class="card-body">
                        <table id="tabelaOutrasBuscas" style="width: 100%" class="table table-bordered ">
                            <thead>
                                <tr>
                                    @if (isset($_GET['mensal']) AND $_GET['mensal'] != "todas")
                                    <th colspan="10"> Todos Pagamento Mês de
                                        @if ($_GET['mensal'] == "January")
                                        Janeiro Referente ao Serviço de {{ $servico->servico ?? '' }}
                                        @endif
                                        @if ($_GET['mensal'] == "February")
                                        Fevereiro Referente ao Serviço de {{ $servico->servico ?? '' }}
                                        @endif
                                        @if ($_GET['mensal'] == "March")
                                        Março Referente ao Serviço de {{ $servico->servico ?? '' }}
                                        @endif
                                        @if ($_GET['mensal'] == "April")
                                        Abril Referente ao Serviço de {{ $servico->servico ?? '' }}
                                        @endif
                                        @if ($_GET['mensal'] == "May")
                                        Maio Referente ao Serviço de {{ $servico->servico ?? '' }}
                                        @endif
                                        @if ($_GET['mensal'] == "June")
                                        Junho Referente ao Serviço de {{ $servico->servico ?? '' }}
                                        @endif
                                        @if ($_GET['mensal'] == "July")
                                        Julho Referente ao Serviço de {{ $servico->servico ?? '' }}
                                        @endif
                                        @if ($_GET['mensal'] == "August")
                                        Agosto Referente ao Serviço de {{ $servico->servico ?? '' }}
                                        @endif
                                        @if ($_GET['mensal'] == "September")
                                        Setembro Referente ao Serviço de {{ $servico->servico ?? '' }}
                                        @endif
                                        @if ($_GET['mensal'] == "October")
                                        Outubro Referente ao Serviço de {{ $servico->servico ?? '' }}
                                        @endif
                                        @if ($_GET['mensal'] == "November")
                                        Novembro Referente ao Serviço de {{ $servico->servico ?? '' }}
                                        @endif
                                        @if ($_GET['mensal'] == "December")
                                        Dezembro Referente ao Serviço de {{ $servico->servico ?? '' }}
                                        @endif
                                    </th>
                                    @else
                                    <th colspan="10">Todos os Meses</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th>Nº Factura</th>
                                    <th>Nome Completo</th>
                                    <th>Serviço</th>
                                    <th>Mês</th>
                                    <th class="text-center">Qtd.</th>
                                    <th title="Funcionário">Func.</th>
                                    <th>Data</th>
                                    <th class="text-right">Total</th>
                                    <th class="text-right">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalArrecadado = 0;
                                @endphp
                                @foreach ($pagamentos as $item)
                                <tr>
                                    <td>{{ $item->next_factura }}</td>
                                    <td>{{ $item->model($item->model, $item->estudantes_id) }}</td>
                                    <td>{{ $item->servico->servico ?? "" }}</td>
                                    <td>{{ $item->descricao_mes_completo($item->mensal) }}</td>
                                    <td class="text-center">{{ $item->quantidade }}</td>
                                    <td>{{ $item->operador->nome ?? "" }}</td>
                                    <td>{{ $item->data_at }}</td>
                                    <td class="text-right">{{ number_format($item->valor2, 2, ',', '.') }} <small>kz</small></td>
                                    <td class="text-end">
                                        <a href="{{ route('web.ficha-matricula', Crypt::encrypt($item->ficha)) }}" class="btn-primary btn">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </td>
                                </tr>
                                @php
                                $totalArrecadado += $item->valor2;
                                @endphp
                                @endforeach


                            </tbody>
                            <tfoot class="bg-dark">
                                <th>------</th>
                                <th>------</th>
                                <th>------</th>
                                <th>------</th>
                                <th>------</th>
                                <th>------</th>
                                <th>------</th>
                                <th class="text-right">{{ number_format( $totalArrecadado , 2, ',', '.') }} Kz</th>
                                <th>------</th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        @endif

    </div>
</section>

@endsection


@section('scripts')
<script>
    $(function() {
        $("#tabelaOutrasBuscas").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tabelaOutrasBuscas_wrapper .col-md-6:eq(0)');

    });

</script>
@endsection
