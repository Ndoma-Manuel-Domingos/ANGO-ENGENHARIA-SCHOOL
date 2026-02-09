@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Movimentos Diário</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Movimento Diário</li>
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
                    <h5><i class="fas fa-info"></i> Cadastrar, listar, editar e eliminar de Caixas. Busca avançada para melhorar na navegação do software.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Caixa</th>
                                    <th>Status</th>
                                    <th>Valor Inicial</th>
                                    <th>Valor TPA</th>
                                    <th>Valor Cache</th>
                                    <th>Valor Retirado</th>
                                    <th>Data Abertura</th>
                                    <th>Operador</th>
                                    <th style="width: 170px;">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($caixas)
                                <tr>
                                    <td>{{ $caixas->id }}</td>
                                    <td>{{ $caixas->caixa->caixa }}</td>
                                    <td>{{ $caixas->status }}</td>
                                    <td>{{ number_format($caixas->valor_abrir, '2', ',', '.') }} Kz</td>
                                    <td>{{ number_format($caixas->valor_tpa , '2', ',', '.') }} Kz</td>
                                    <td>{{ number_format($caixas->valor_cache , '2', ',', '.') }} Kz</td>
                                    <td>{{ number_format($caixas->valor_retirado1 + $caixas->valor_retirado2 + $caixas->valor_retirado3 , '2', ',', '.') }} Kz</td>
                                    <td>{{ $caixas->data_abrir }}</td>
                                    <td>{{ $caixas->user_abrir->nome }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('fecho caixa'))
                                                <a title="Fechamento do Caixa" href="{{ route('operacoes-caixas.fechamento-caixas') }}" class="dropdown-item"><i class="fa fa-edit"></i> Fechar caixa</a>
                                                @endif

                                                @if (Auth::user()->can('retirar valores caixa'))
                                                <a title="Fechamento do Caixa" href="{{ route('operacoes-caixas.retirar-valores-caixa', Crypt::encrypt($caixas->id)) }}" class="dropdown-item"><i class="fa fa-edit"></i> Retirar Valor</a>
                                                @endif
                                                <a title="Activar ou desactivar Sala" target="_blink" href="{{ route('operacoes-caixas.imprimir-movimento-caixa', Crypt::encrypt($caixas->id)) }}" class="activar_salas_id dropdown-item"><i class="fa fa-print"></i> Imprimir</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Outros</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <tfoot>
                                <th colspan="5" class="text-danger">Total Retirado <strong>{{ number_format(($caixas->valor_retirado1 + $caixas->valor_retirado2 + $caixas->valor_retirado3)  , '2', ',', '.') }} Kz</strong></th>
                                <th colspan="5" class="text-success">Saldo Final <strong>{{ number_format( (($caixas->valor_cache + $caixas->valor_abrir + $caixas->valor_tpa) - ($caixas->valor_retirado1 + $caixas->valor_retirado2 + $caixas->valor_retirado3))  , '2', ',', '.') }} Kz</strong></th>
                            </tfoot>
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
