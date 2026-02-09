@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Mapas de pagamentos de Propinas Referente ao Mês {{$requests['mes_id'] ?? date("m")}} de 2025</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('tesourarias.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Propinas</li>
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
                <form action="{{ route('web.financeiro-propinas-por-cursos') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <label for="anos_lectivos_id">Anos Lectivos</label>
                                    <select name="anos_lectivos_id" id="anos_lectivos_id" class="form-control select2">
                                        @foreach ($ano_lectivos as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $requests['anos_lectivos_id'] ? 'selected' : '' }}>{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="mes_id">Meses</label>
                                    <select name="mes_id" id="mes_id" class="form-control select2">
                                        <option value="Jan" {{ "Jan" == $requests['mes_id'] ? 'selected' : '' }}>Janeiro</option>
                                        <option value="Feb" {{ "Feb" == $requests['mes_id'] ? 'selected' : '' }}>Fevereiro</option>
                                        <option value="Mar" {{ "Mar" == $requests['mes_id'] ? 'selected' : '' }}>Março</option>
                                        <option value="Apr" {{ "Apr" == $requests['mes_id'] ? 'selected' : '' }}>Abril</option>
                                        <option value="May" {{ "May" == $requests['mes_id'] ? 'selected' : '' }}>Maio</option>
                                        <option value="Jun" {{ "Jun" == $requests['mes_id'] ? 'selected' : '' }}>Junho</option>
                                        <option value="Jul" {{ "Jul" == $requests['mes_id'] ? 'selected' : '' }}>Julho</option>
                                        <option value="Aug" {{ "Aug" == $requests['mes_id'] ? 'selected' : '' }}>Agosto</option>
                                        <option value="Sep" {{ "Sep" == $requests['mes_id'] ? 'selected' : '' }}>Setembro</option>
                                        <option value="Oct" {{ "Oct" == $requests['mes_id'] ? 'selected' : '' }}>Outubro</option>
                                        <option value="Nov" {{ "Nov" == $requests['mes_id'] ? 'selected' : '' }}>Novembro</option>
                                        <option value="Dec" {{ "Dec" == $requests['mes_id'] ? 'selected' : '' }}>Dezembro</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-primary"><i class="fas fa-filter"></i>Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('pdf.propinas-por-cursos', ["anos_lectivos_id" => $requests['anos_lectivos_id'] ?? "", "mes_id" => $requests['mes_id'] ?? ""]) }}" class="btn btn-danger" target="_blink">Imprimir <i class="fas fa-file-pdf"></i></a>
                    </div>
                    <div class="card-body table-responsive">
                        <table style="width: 100%" class="table table-bordered">
                            <tbody>
                                @php
                                $total_alunos_curso_geral = 0;
                                $total_alunos_com_propinas_pagas_geral = 0;
                                $total_alunos_com_propinas_nao_pagas_geral = 0;
                                $total_receita_a_arrecadar_geral = 0;
                                $total_receita_arrecadadas_geral = 0;
                                $total_receita__nao_arrecadadas_geral = 0;
                                @endphp

                                @foreach ($cursos as $curso)
                                <tr>
                                    <th colspan="8" class="text-uppercase bg-secondary">CURSOS: {{ $curso->curso }} </th>
                                </tr>
                                <tr>
                                    <th class="text-uppercase bg-light">Classes</th>
                                    <th class="text-uppercase bg-light">Valor Proprinas</th>
                                    <th class="text-uppercase bg-light">Nº de Alunos</th>
                                    <th class="text-uppercase bg-light">Alunos com propinas pagas</th>
                                    <th class="text-uppercase bg-light">Alunos com propinas não pagas</th>
                                    <th class="text-uppercase bg-light">Receitas A Arrecadar</th>
                                    <th class="text-uppercase bg-light">Receitas Arrecadadas</th>
                                    <th class="text-uppercase bg-light">Receitas Não Arrecadadas</th>
                                </tr>
                                @php
                                $total_receita_a_arrecadar = 0;
                                $total_receita_arrecadadas = 0;
                                $total_receita__nao_arrecadadas = 0;
                                @endphp
                                @foreach ($curso->classes as $classe)
                                <tr>
                                    <td class="text-center">{{ $classe->classes }}</td>
                                    <td class="text-center">{{ number_format($classe->valor_propina, 2, ",", ".") }}</td>
                                    <td class="text-center">{{ $classe->total_estudantes }}</td>
                                    <td class="text-center">{{ $classe->total_pago }}</td>
                                    <td class="text-center">{{ $classe->total_nao_pago }}</td>
                                    <td class="text-center">{{ number_format($classe->valor_propina * $classe->total_estudantes, 2, ",", ".") }}</td>
                                    <td class="text-center">{{ number_format($classe->valor_propina * $classe->total_pago, 2, ",", ".") }}</td>
                                    <td class="text-center">{{ number_format($classe->valor_propina * $classe->total_nao_pago, 2, ",", ".") }}</td>
                                </tr>
                                @php
                                $total_receita_a_arrecadar += ($classe->valor_propina * $classe->total_estudantes);
                                $total_receita_arrecadadas += ($classe->valor_propina * $classe->total_pago);
                                $total_receita__nao_arrecadadas += ($classe->valor_propina * $classe->total_nao_pago);
                                @endphp
                                @endforeach

                                @php
                                $total_alunos_curso_geral += $curso->total_geral;
                                $total_alunos_com_propinas_pagas_geral += $curso->total_pago;
                                $total_alunos_com_propinas_nao_pagas_geral += $curso->total_nao_pago;
                                $total_receita_a_arrecadar_geral += $total_receita_a_arrecadar;
                                $total_receita_arrecadadas_geral += $total_receita_arrecadadas;
                                $total_receita__nao_arrecadadas_geral += $total_receita__nao_arrecadadas;
                                @endphp

                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center">{{ $curso->total_geral }}</td>
                                    <td class="text-center">{{ $curso->total_pago }}</td>
                                    <td class="text-center">{{ $curso->total_nao_pago }}</td>
                                    <td class="text-center">{{ number_format($total_receita_a_arrecadar, 2, ",", ".") }}</td>
                                    <td class="text-center">{{ number_format($total_receita_arrecadadas, 2, ",", ".") }}</td>
                                    <td class="text-center">{{ number_format($total_receita__nao_arrecadadas, 2, ",", ".") }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">-</th>
                                    <th class="text-center">-</th>
                                    <th class="text-center">{{ number_format($total_alunos_curso_geral, 2, ',', '.') }}</th>
                                    <th class="text-center">{{ number_format($total_alunos_com_propinas_pagas_geral, 2, ',', '.') }}</th>
                                    <th class="text-center">{{ number_format($total_alunos_com_propinas_nao_pagas_geral, 2, ',', '.') }}</th>
                                    <th class="text-center">{{ number_format($total_receita_a_arrecadar_geral, 2, ',', '.') }}</th>
                                    <th class="text-center">{{ number_format($total_receita_arrecadadas_geral, 2, ',', '.') }}</th>
                                    <th class="text-center">{{ number_format($total_receita__nao_arrecadadas_geral, 2, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>
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
