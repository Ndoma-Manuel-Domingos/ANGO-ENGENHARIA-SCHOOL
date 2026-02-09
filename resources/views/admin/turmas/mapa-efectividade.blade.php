@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Gestão de efectividade</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Mapa</li>
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
                <form action="{{ route('web.mapa-efectividade') }}" method="GET">
                    <div class="card">
                        <div class="card-header">Mapa de efectividade</div>
                        <div class="card-body">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-3 col-12">
                                    <label for="data_inicio" class="form-label" id="data_inicio">Data Inicio</label>
                                    <input type="date" name="data_inicio" value="{{ $requests['data_inicio'] ?? $data_inicio }}" class="form-control">
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="data_final" class="form-label">Data Final</label>
                                    <input type="date" name="data_final" value="{{ $requests['data_final'] ?? $data_final }}" id="data_final" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn-primary btn"><i class="fas fa-search"></i> Pesquisa</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route("dow.ficha-mapa-efectividade", ["data_inicio" => $requests['data_inicio'] ?? "", "data_final" => $requests['data_final'] ?? ""]) }}" target="_blink" class="mx-1 float-right btn-danger btn"><i class="fas fa-file-pdf"></i> Imprimir PDF</a>
                        <a href="{{ route("dow.ficha-mapa-efectividade-excel", ["data_inicio" => $requests['data_inicio'] ?? "", "data_final" => $requests['data_final'] ?? ""]) }}" target="_blink" class="mx-1 float-right btn-success btn"><i class="fas fa-file-excel"></i> Imprimir EXCEL</a>
                        <h6>Mapa de efectividade</h6>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="carregarTabelaTurmas" style="width: 100%" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th style="width: 300px" colspan="31">Funcionário</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($professores as $key => $item)
                                <tr>
                                    <td rowspan="2">{{ $key + 1 }}</td>
                                    <td rowspan="2" style="width: 300px">{{ $item->funcionario->nome }} {{ $item->funcionario->sobre_nome }}</td>

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
                                        ++$total_presenca;
                                        @endphp
                                        @endif
                                        @if ($map->status == 'Ausente')
                                        @php
                                        ++$total_ausencia;
                                        @endphp
                                        @endif
                                        @if ($map->status == 'Justitificado')
                                        @php
                                        ++$total_justificada;
                                        @endphp
                                        @endif
                                        @if ($map->status == 'Indefinido')
                                        @php
                                        ++$total_indefinida;
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
                                    <th>TOTAL PRESENÇA: {{ $total_presenca }}</th>
                                    <th>TOTAL AUSENCIA: {{ $total_ausencia }}</th>
                                    <th>TOTAL FALTAS JUSTIFICADAS: {{ $total_justificada }}</th>
                                    <th>TOTAL INDEFINIDAS: {{ $total_indefinida }}</th>
                                </tr>

                                @endforeach


                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <p><span class="bg-success py-1 px-2"></span> Representa Presente</p>
                        <p><span class="bg-danger py-1 px-2"></span> Representa Ausente</p>
                        <p><span class="bg-info py-1 px-2"></span> Representa Justitificado</p>
                        <p><span class="bg-warning py-1 px-2"></span> Representa Indefinido</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')

<script>
    $(function() {
        // activar ou desactivar 
        $(document).on('click', '.justificar', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');

            $.ajax({
                type: "GET"
                , url: "faltas-funcionarios-justificar/" + id
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
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

        $(document).on('click', '.pesquisar_mapa', function(e) {
            e.preventDefault();

            $.ajax({
                type: "GET"
                , url: "pesquisar-mapa-efectividade/" + $('.meses').val()
                "/" + $('.ano_lectivos').val()
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
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
