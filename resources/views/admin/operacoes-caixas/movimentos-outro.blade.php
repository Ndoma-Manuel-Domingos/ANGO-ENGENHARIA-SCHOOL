@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Movimentos do Caixas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Movimento do Caixa</li>
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
                    <form action="{{ route('operacoes-caixas.movimentos-caixas-outro') }}" method="get">
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-2 col-12">
                                    <label for="caixa_id">Caixas</label>
                                    <select name="caixa_id" id="caixa_id" class="form-control select2">
                                        <option value="">Todos</option>
                                        @foreach ($caixas as $item)
                                        <option value="{{ $item->id }}">{{ $item->conta }} - {{ $item->caixa }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="operador_id">Operadores</label>
                                    <select name="operador_id" id="operador_id" class="form-control select2">
                                        <option value="">Todos</option>
                                        @foreach ($operadores as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="data_inicio">Data Inicio</label>
                                    <input type="date" name="data_inicio" id="data_inicio" class="form-control">
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="data_final">Data Final</label>
                                    <input type="date" name="data_final" id="data_final" class="form-control">
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a target="_blink" href="{{ route('operacoes-caixas.imprimir-movimento-caixa2', ['data_inicio' => Crypt::encrypt($requests['data_inicio'] ?? ""), 'data_final' => Crypt::encrypt($requests['data_final'] ?? ""), 'operador_id' => Crypt::encrypt($requests['operador_id'] ?? ""),'caixa_id' => Crypt::encrypt($requests['caixa_id'] ?? "")]) }}" class="btn btn-danger float-end"><i class="fas fa-file-pdf"></i> Imprimir PDF</a>
                    </div>
                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Caixa</th>
                                    <th>Status</th>
                                    <th>Valor TPA</th>
                                    <th>Valor Cache</th>
                                    <th>Valor Retirado</th>
                                    <th>Total Depositado</th>
                                    <th>Total Transferido</th>
                                    <th>Data Abertura</th>
                                    <th>Valor Inicial</th>
                                    <th>Saldo Final</th>
                                    <th>Operador</th>
                                    <th style="width: 170px;">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($movimentos)
                                @foreach ($movimentos as $movimento)
                                <tr>
                                    <td>{{ $movimento->id }}</td>
                                    <td>{{ $movimento->caixa->conta }} {{ $movimento->caixa->caixa }}</td>
                                    <td>{{ $movimento->status }}</td>
                                    <td>{{ number_format($movimento->valor_tpa , '2', ',', '.') }}</td>
                                    <td>{{ number_format($movimento->valor_cache , '2', ',', '.') }}</td>
                                    <td>{{ number_format($movimento->valor_depositado , '2', ',', '.') }}</td>
                                    <td>{{ number_format($movimento->valor_transferencia , '2', ',', '.') }}</td>
                                    <td>{{ number_format($movimento->valor_retirado1 + $movimento->valor_retirado2 + $movimento->valor_retirado3 , '2', ',', '.') }}</td>
                                    <td>{{ $movimento->data_abrir }}</td>
                                    <td>{{ number_format($movimento->valor_abrir, '2', ',', '.') }}</td>
                                    <td>{{ number_format((($movimento->valor_cache + $movimento->valor_abrir + $movimento->valor_tpa) - ($movimento->valor_retirado1 - $movimento->valor_retirado2 - $movimento->valor_retirado3))  , '2', ',', '.') }}</td>
                                    <td>{{ $movimento->user_abrir->nome }}</td>

                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a title="Activar ou desactivar Sala" target="_blink" href="{{ route('operacoes-caixas.imprimir-movimento-caixa', Crypt::encrypt($movimento->id)) }}" class="activar_salas_id dropdown-item"><i class="fa fa-print"></i> Imprimir</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Outros</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                                @endif
                            </tbody>
                        </table>
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
