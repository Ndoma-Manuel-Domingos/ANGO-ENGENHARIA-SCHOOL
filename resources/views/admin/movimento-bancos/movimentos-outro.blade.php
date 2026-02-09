@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Movimentos do Banco</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Movimento do Banco</li>
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
                    <form action="{{ route('web.movimentos-bancos-outro') }}" method="get">
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-2 col-12">
                                    <label for="banco_id">Bancos</label>
                                    <select name="banco_id" id="banco_id" class="form-control select2">
                                        <option value="">Todos</option>
                                        @foreach ($bancos as $item)
                                        <option value="{{ $item->id }}">{{ $item->conta }} - {{ $item->banco }}</option>
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
                        <a href="{{ route('web.imprimir-movimento-banco2', ['data_inicio' => Crypt::encrypt($requests['data_inicio'] ?? ""), 'data_final' => Crypt::encrypt($requests['data_final'] ?? ""), 'operador_id' => Crypt::encrypt($requests['operador_id'] ?? ""),'banco_id' => Crypt::encrypt($requests['banco_id'] ?? "")]) }}" target="_blink" class="btn btn-danger"><i class="fas fa-file-pdf"></i> PDF</a>
                        <a href="{{ route('web.imprimir-movimento-banco2', ['data_inicio' => Crypt::encrypt($requests['data_inicio'] ?? ""), 'data_final' => Crypt::encrypt($requests['data_final'] ?? ""), 'operador_id' => Crypt::encrypt($requests['operador_id'] ?? ""),'banco_id' => Crypt::encrypt($requests['banco_id'] ?? "")]) }}" target="_blink" class="btn btn-success"><i class="fas fa-file-excel"></i> EXCEL</a>
                    </div>
                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Banco</th>
                                    <th>Status</th>
                                    <th>Valor TPA</th>
                                    {{-- <th>Valor Cache</th> --}}
                                    <th>Valor Retirado</th>
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
                                    <td>{{ $movimento->banco->conta }} - {{ $movimento->banco->banco }}</td>
                                    <td>{{ $movimento->status }}</td>
                                    <td>{{ number_format($movimento->valor_tpa , '2', ',', '.') }} Kz</td>
                                    {{-- <td>{{ number_format($movimento->valor_cache , '2', ',', '.') }} Kz</td> --}}
                                    <td>{{ number_format($movimento->valor_retirado1 + $movimento->valor_retirado2 + $movimento->valor_retirado3 , '2', ',', '.') }} Kz</td>
                                    <td>{{ $movimento->data_abrir }}</td>
                                    <td>{{ number_format($movimento->valor_abrir, '2', ',', '.') }} Kz</td>
                                    <td>{{ number_format((($movimento->valor_cache + $movimento->valor_abrir + $movimento->valor_tpa) - ($movimento->valor_retirado1 - $movimento->valor_retirado2 - $movimento->valor_retirado3))  , '2', ',', '.') }} Kz</td>
                                    <td>{{ $movimento->user_abrir->nome }}</td>

                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a title="Activar ou desactivar Sala" target="_blink" href="{{ route('web.imprimir-movimento-banco', Crypt::encrypt($movimento->id)) }}" class="activar_salas_id dropdown-item"><i class="fa fa-print"></i> Imprimir</a>
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
