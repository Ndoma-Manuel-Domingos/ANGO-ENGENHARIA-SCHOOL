@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Informações Gerais da Escola</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Escola</a></li>
                    <li class="breadcrumb-item active">Acesso Rapido</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ $matriculas }}</h3>

                        <p>Total Estudantes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <a href="{{ route('app.listagem-estudantes', $escola->id) }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ $funcionarios }}</h3>

                        <p>Total Funcionários</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <a href="{{ route('app.listagem-professores', $escola->id) }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ $turmas }}</h3>

                        <p>Total Turmas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <a href="" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-12 col-md-12">
                <!-- small box -->
                <div class="small-box bg-light">
                    <div class="inner">
                        <h3>{{ $utilizadores }}</h3>
                        <p>Total Utilizadores</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <a href="" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body table-responsive">
                        <table style="width: 100%" class="table  table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td rowspan="3">
                                        <img src='{{ $logotipo }}' style="height: 100px; width: 100px;" class="img-circle" alt="Logotipo da instituição">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="bg-dark">Informações Gerais</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Nome da Escola: <strong>{{ $escola->nome }}</strong> </td>
                                    <td>Número da Escola: <strong>{{ $escola->numero_escola }}</strong> </td>
                                    <td>Decreto de Criação: <strong>{{ $escola->decreto }}</strong></td>
                                    <td>Número de Idetificação Fiscal: <strong>{{ $escola->documento }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Sector: <strong>{{ $escola->categoria }}</strong> </td>
                                    <td colspan="2">Subsistema ou sistema de Ensino: <strong>{{ $escola->ensino->nome ?? "" }}</strong> </td>
                                    <td colspan="2">Tipo Regime do IVA:

                                        @if($escola->tipo_regime_id == "regime_exclusao")
                                        <strong>REGIME DE EXCLUSÃO</strong>
                                        @endif
                                        @if($escola->tipo_regime_id == "regime_geral")
                                        <strong>REGIME GERAL</strong>
                                        @endif
                                        @if($escola->tipo_regime_id == "regime_simplificado")
                                        <strong>REGIME SIMPLIFICADO</strong>
                                        @endif

                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="bg-dark">Contactos</td>
                                </tr>
                                <tr>
                                    <td>Telefone 1: <strong>{{ $escola->telefone1 }}</strong> </td>
                                    <td>Telefone 2: <strong>{{ $escola->telefone2 }}</strong> </td>
                                    <td>Telefone 4: <strong>{{ $escola->telefone3 }}</strong></td>
                                    <td colspan="3">E-mail ou Site <strong>{{ $escola->site }}</strong></td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="bg-dark">Localização</td>
                                </tr>
                                <tr>
                                    <td>Provincia: <strong>{{ $escola->provincia->nome ?? ""}}</strong> </td>
                                    <td>Municipio: <strong>{{ $escola->municipio->nome ?? "" }}</strong> </td>
                                    <td>Distrito: <strong>{{ $escola->distrito->nome ?? "" }}</strong></td>
                                    <td colspan="3">Endereço: <strong>{{ $escola->endereco }}</strong></td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="bg-dark">Infraestrutura</td>
                                </tr>
                                <tr>
                                    <td>Cantina: <strong>{{ $escola->cantina }}</strong> </td>
                                    <td colspan="2">Campo Disportivo: <strong>{{ $escola->campo_desportivo }}</strong> </td>
                                    <td>Água Potavel: <strong>{{ $escola->distrito->nome ?? "" }}</strong></td>
                                    <td>Electricidade: <strong>{{ $escola->distrito->nome ?? "" }}</strong></td>
                                    <td>Transporte: <strong>{{ $escola->distrito->nome ?? "" }}</strong></td>
                                </tr>

                                <tr>
                                    <td colspan="6">Bibioteca: <strong>{{ $escola->biblioteca }}</strong> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
