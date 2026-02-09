@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Processamento de Salário</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Voltar</li>
                    <li class="breadcrumb-item active">Funcionários</li>
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
                    <form action="{{ route('web.financeiro-pagamentos-salario') }}" method="GET">
                        @csrf
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6 col-12">
                                    <label for="professores_id" class="form-label">Escolher os professores</label>
                                    <select name="professores[]" id="professores_id" class="form-control select2 professores" multiple>
                                        @foreach ($professores as $item)
                                        <option value="{{ $item->documento }}">{{ $item->funcionario->nome }} {{ $item->funcionario->sobre_nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="servico_id" class="form-label">Serviço</label>
                                    <select name="servico_id" class="form-control select2 servico_id">
                                        <option value="">Escolher</option>
                                        @foreach ($servicos as $item)
                                        <option value="{{ $item->id }}" {{ $requests['servico_id'] == $item->id ? 'selected' : '' }}>{{ $item->servico }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group col-12 col-md-3">
                                    <label for="forma_pagamento" class="form-label">Forma Pagamento</label>
                                    <select name="forma_pagamento" class="form-control select2 forma_pagamento">
                                        <option value="">Escolher</option>
                                        @foreach ($formas_pagamentos as $item)
                                        <option value="{{ $item->sigla_tipo_pagamento }}" {{ $requests['forma_pagamento'] == $item->sigla_tipo_pagamento ? 'selected' : '' }}>{{ $item->descricao }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="data_inicio" class="form-label">Data Inicio</label>
                                    <input type="date" name="data_inicio" value="{{ $requests['data_inicio'] ?? "" }}" id="data_inicio" class="form-control data_inicio">
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="data_final" class="form-label">Data Final</label>
                                    <input type="date" name="data_final" value="{{ $requests['data_final'] ?? "" }}" id="data_final" class="form-control data_final">
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="" class="form-label">Meses</label>
                                    <select name="mensal" class="form-control select2 mensal">
                                        <option value="todas" {{ $requests['mensal'] == "todas" ? 'selected' : '' }}>TODOS</option>
                                        <option value="Jan" {{ $requests['mensal'] == "Jan" ? 'selected' : '' }}>Janeiro</option>
                                        <option value="Feb" {{ $requests['mensal'] == "Feb" ? 'selected' : '' }}>Fevereiro</option>
                                        <option value="Mar" {{ $requests['mensal'] == "Mar" ? 'selected' : '' }}>Março</option>
                                        <option value="Apr" {{ $requests['mensal'] == "Apr" ? 'selected' : '' }}>Abril</option>
                                        <option value="May" {{ $requests['mensal'] == "May" ? 'selected' : '' }}>Maio</option>
                                        <option value="Jun" {{ $requests['mensal'] == "Jun" ? 'selected' : '' }}>Junho</option>
                                        <option value="Jul" {{ $requests['mensal'] == "Jul" ? 'selected' : '' }}>Julho</option>
                                        <option value="Aug" {{ $requests['mensal'] == "Aug" ? 'selected' : '' }}>Agosto</option>
                                        <option value="Sep" {{ $requests['mensal'] == "Sep" ? 'selected' : '' }}>Setembro</option>
                                        <option value="Oct" {{ $requests['mensal'] == "Oct" ? 'selected' : '' }}>Outubro</option>
                                        <option value="Nov" {{ $requests['mensal'] == "Nov" ? 'selected' : '' }}>Novembro</option>
                                        <option value="Dec" {{ $requests['mensal'] == "Dec" ? 'selected' : '' }}>Dezembro</option>
                                    </select>
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="estado_pagamento" class="form-label">Estado Pagamento</label>
                                    <select name="estado_pagamento" class="form-control select2 estado_pagamento">
                                        <option value="Confirmado" {{ $requests['estado_pagamento'] == "Confirmado" ? 'selected' : '' }}>Confirmado</option>
                                        <option value="Pendente" {{ $requests['estado_pagamento'] == "Pendente" ? 'selected' : '' }}>Pendente</option>
                                    </select>
                                </div>

                            </div>

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Pesquisar os Professores</button>
                            <a href="{{ route('web.financeiro-pagamentos-salario') }}" class="btn btn-success"><i class="fas fa-broom"></i> Limpar a pesquisa</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <table id="carregarTabelaTurmas" style="width: 100%" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th style="width: 300px" colspan="31">Funcionário</th>
                                </tr>
                            </thead>
                            @foreach ($professores as $key => $item)

                            <tr>
                                <td rowspan="5">{{ $key + 1 }}</td>
                                <td rowspan="5" style="width: 300px">{{ $item->funcionario->nome }} {{ $item->funcionario->sobre_nome }}</td>

                                @php
                                $mapas = App\Models\web\calendarios\MapaEfectividade::where('funcionarios_id', $item->funcionario->id)->when($requests['data_inicio'], function($query, $value){
                                $query->where('created_at', '>=', Carbon\Carbon::createFromDate($value));
                                })->when($requests['data_final'], function($query, $value){
                                $query->where('created_at', '<=', Carbon\Carbon::createFromDate($value)); })->get();

                                    $total_presenca = 0;
                                    $total_ausencia = 0;
                                    $total_justificada = 0;
                                    $total_indefinida = 0;
                                    @endphp

                                    @foreach ($mapas as $map)

                                    @if ($map->status == 'Presente')
                                    @php
                                    $total_presenca = $total_presenca + $map->faltas;
                                    @endphp
                                    @endif
                                    @if ($map->status == 'Ausente')
                                    @php
                                    $total_ausencia = $total_ausencia + $map->faltas;
                                    @endphp
                                    @endif
                                    @if ($map->status == 'Justitificado')
                                    @php
                                    $total_justificada = $total_justificada + $map->faltas;
                                    @endphp
                                    @endif
                                    @if ($map->status == 'Indefinido')
                                    @php
                                    $total_indefinida = $total_indefinida + $map->faltas;
                                    @endphp
                                    @endif

                                    @if ($map->status == 'Presente' )
                                    <td class="bg-success">
                                        <small><strong>{{ $map->dia }}</strong>/{{ $map->mes }}</small><br>
                                        <small>{{ $map->dia_semana }}</small>
                                    </td>
                                    @endif
                                    @if ($map->status == 'Ausente' )
                                    <td class="bg-danger"><small><strong>{{ $map->dia }}</strong>/{{ $map->mes }}</small><br>
                                        <small>{{ $map->dia_semana }}</small>
                                    </td>
                                    @endif
                                    @if ($map->status == 'Justitificado' )
                                    <td class="bg-info"><small><strong>{{ $map->dia }}</strong>/{{ $map->mes }}</small><br>
                                        <small>{{ $map->dia_semana }}</small>
                                    </td>
                                    @endif
                                    @if ($map->status == 'Indefinido' )
                                    <td class="bg-warning"><small><strong>{{ $map->dia }}</strong>/{{ $map->mes }}</small><br>
                                        <small>{{ $map->dia_semana }}</small>
                                    </td>
                                    @endif
                                    @endforeach
                            </tr>

                            <tr>
                                <th class="text-capitalize"></th>
                                <th class="text-capitalize"></th>
                                <th class="text-capitalize"></th>
                                <th class="text-capitalize"></th>
                                <th class="text-capitalize"></th>
                                <th class="text-capitalize"></th>
                            </tr>

                            <tr>
                                <th class="text-capitalize">Tatal Presença: {{ $total_presenca }}</th>
                                <th class="text-capitalize">Tatal Ausências: {{ $total_ausencia }}</th>
                                <th class="text-capitalize">Tatal Faltas Justificada: {{ $total_justificada }}</th>
                                <th class="text-capitalize">Tatal indefinida: {{ $total_indefinida }}</th>
                                <th class="text-capitalize">Tatal de Tempos Semanal: {{ $item->funcionario->total_tempos_professor($item->funcionario->id) }}</th>
                                <th class="text-capitalize">Tatal de Tempos Mensal: {{ $item->funcionario->total_tempos_professor($item->funcionario->id) * 4 }}</th>
                            </tr>

                            <tr>
                                <th class="text-capitalize">Salário por Tempo: {{ number_format($item->salario, 2, ',', '.')  }} Kz</th>
                                <th class="text-capitalize">Desconto por Tempo: {{ number_format($item->falta_por_dia, 2, ',', '.') }} Kz</th>
                                <th class="text-capitalize">Salário Bruto: {{ number_format($item->salario * $item->funcionario->total_tempos_professor($item->funcionario->id) * 4, 2, ',', '.') }} Kz</th>
                                <th class="text-capitalize">Valor Desconto: {{ number_format($item->falta_por_dia * $total_ausencia, 2, ',', '.') }}</th>
                                <th class="text-capitalize">Valor a Receber {{ number_format($total_presenca * $item->salario, 2, ',', '.') }} Kz</th>
                                <th class="text-capitalize"></th>
                            </tr>

                            <tr>
                                <th class="text-capitalize">Subcídio de Alimentação: {{ number_format($item->subcidio_alimentacao, 2, ',', '.')  }} Kz</th>
                                <th class="text-capitalize">Subcídio de Transporte: {{ number_format($item->subcidio_transporte, 2, ',', '.') }} Kz</th>
                                <th class="text-capitalize"></th>
                                <th class="text-capitalize"></th>
                                <th class="text-capitalize"></th>
                                <th class="text-capitalize"></th>
                            </tr>

                            <tr>
                                <th class="text-capitalize"></th>
                                <th class="text-capitalize"></th>
                                <th class="text-capitalize"></th>
                                <th class="text-capitalize"></th>
                                <th class="text-capitalize"></th>
                                <th class="text-capitalize"></th>
                            </tr>
                            @endforeach
                        </table>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right btn_finalizar_processamento_salario">Finalizar Processamento de Salário</button>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {

        $(document).on('click', '.btn_finalizar_processamento_salario', function(e) {
            e.preventDefault();

            var data = {
                'data_inicio': $('.data_inicio').val()
                , 'data_final': $('.data_final').val()
                , 'mensal': $('.mensal').val()
                , 'professores': $('.professores').val()
                , 'servico_id': $('.servico_id').val()
                , 'forma_pagamento': $('.forma_pagamento').val()
                , 'estado_pagamento': $('.estado_pagamento').val()
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.financeiro-pagamentos-salario-create') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    // // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.location.reload();
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });
    });

</script>
@endsection
