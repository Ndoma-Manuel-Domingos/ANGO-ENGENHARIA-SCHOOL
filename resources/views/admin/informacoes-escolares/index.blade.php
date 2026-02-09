@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Informações Gerais de Escola</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Painel Principal</a></li>
                    <li class="breadcrumb-item active">Informações</li>
                </ol>
            </div><!-- /.col -->
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body table-responsive">
                        <table style="width: 100%" class="table  table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <td colspan="6" class="bg-light">Informações Director</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Nome do Director: <br> <strong>{{ $director->nome ?? '' }}</strong></td>
                                    <td>Bilhete Identidade: <br><strong>{{ $director->bilheite ?? '' }}</strong> </td>
                                    <td colspan="2">Genero: <br><strong>{{ $director->genero ?? '' }}</strong> </td>
                                    <td>Estado Cívil: <br><strong>{{ $director->estado_civil ?? '' }}</strong></td>
                                </tr>

                                <tr>
                                    <td>Especialidade: <br><strong>{{ $director->especialidade ?? '' }}</strong></td>
                                    <td>Curso: <br><strong>{{ $director->curso ?? '' }}</strong></td>
                                    <td colspan="4">Perfil: <br><strong>{{ $director->descricao ?? '' }}</strong></td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="bg-light">Informações Gerais</td>
                                </tr>
                                <tr>
                                    <td>Nome da Escola: <br><strong>{{ $escola->nome ?? "----------------" }}</strong> </td>
                                    <td>Número da Escola: <br><strong>{{ $escola->numero_escola ?? "----------------"}}</strong> </td>
                                    <td>Decreto de Criação: <br><strong>{{ $escola->decreto ?? "----------------" }}</strong></td>
                                    <td>Número de Idetificação Fiscal: <br><strong>{{ $escola->documento ?? "----------------" }}</strong> </td>
                                    <td>Sector: <br><strong>{{ $escola->categoria ?? "----------------" }}</strong> </td>

                                    <td>Tipo Regime do IVA:
                                        <br>
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
                                    <td>Sigla: <br><strong>{{ $escola->sigla ?? "---------------" }}</strong> </td>
                                    <td>Região: <br> <strong>{{ $escola->pais_escola ?? "---------------" }}</strong> </td>
                                    <td>Subsistema ou sistema de Ensino: <br><strong>{{ $escola->ensino->nome ?? "---------------" }}</strong> </td>
                                    <td>Processo de Admissão de Estudante: <br><strong>{{ $escola->processo_admissao_estudante ?? "---------------" }}</strong> </td>
                                    <td>Site ou Website: <br><strong>{{ $escola->site ?? "---------------" }}</strong> </td>
                                    <td>E-mail: <br><strong>{{ $escola->email ?? "---------------" }}</strong> </td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="bg-light">Contactos</td>
                                </tr>
                                <tr>
                                    <td>Telefone 1: <br><strong>{{ $escola->telefone1 ?? "" }}</strong> </td>
                                    <td>Telefone 2: <br><strong>{{ $escola->telefone2 ?? "" }}</strong> </td>
                                    <td>Telefone 4: <br><strong>{{ $escola->telefone3 ?? "" }}</strong></td>
                                    <td colspan="3"> <br> <strong>------------</strong></td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="bg-light">Localização</td>
                                </tr>
                                <tr>
                                    <td>Provincia: <br><strong>{{ $escola->provincia->nome ?? ""?? "" }}</strong> </td>
                                    <td>Municipio: <br><strong>{{ $escola->municipio->nome ?? "" ?? "" }}</strong> </td>
                                    <td>Distrito: <br><strong>{{ $escola->distrito->nome ?? "" ?? "" }}</strong></td>
                                    <td colspan="3">Endereço: <br><strong>{{ $escola->endereco ?? "" }}</strong></td>
                                </tr>


                                <tr>
                                    <td colspan="6" class="bg-light">Infraestrutura</td>
                                </tr>
                                <tr>
                                    <td>Cantina: <br><strong>{{ $escola->cantina ?? "" }}</strong> </td>
                                    <td>Campo Disportivo: <br><strong>{{ $escola->campo_desportivo ?? "" }}</strong> </td>
                                    <td>Computadores: <br><strong>{{ $escola->computadores ?? "" }}</strong> </td>
                                    <td>Água Potavel: <br><strong>{{ $escola->agua ?? "" }}</strong></td>
                                    <td>Electricidade: <br><strong>{{ $escola->electricidade ?? "" }}</strong></td>
                                    <td>Transporte: <br><strong>{{ $escola->transporte ?? "" }}</strong></td>
                                </tr>

                                <tr>
                                    <td colspan="">Internet: <br><strong>{{ $escola->internet ?? "" }}</strong> </td>
                                    <td colspan="">Bibioteca: <br><strong>{{ $escola->biblioteca  ?? ""}}</strong> </td>
                                    <td colspan="">Farmácia: <br><strong>{{ $escola->farmacia ?? "" }}</strong> </td>
                                    <td colspan="">Laboratório: <br><strong>{{ $escola->laboratorio ?? "" }}</strong> </td>
                                    <td colspan="">ZIP: <br><strong>{{ $escola->zip ?? "" }}</strong> </td>
                                    <td colspan="">Casas de Banhos: <br><strong>{{ $escola->casas_banho ?? "" }}</strong></td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="bg-light">Coodernadas bancarias</td>
                                </tr>

                                <tr>
                                    <td colspan="2">Banco: <br><strong>{{ $escola->banco ?? "" }}</strong> </td>
                                    <td colspan="2">Nº Conta: <br><strong>{{ $escola->conta ?? "" }}</strong> </td>
                                    <td colspan="2">Nº IBAN: <br><strong>{{ $escola->iban ?? "" }}</strong> </td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="bg-light">Intervalo de Pagamentos de Mensalidade. Taxa de Multa para atraso de mensalidades</td>
                                </tr>

                                <tr>
                                    <td>Dia Inicial: <br><strong>{{ $escola->intervalo_pagamento_inicio ?? "" }}</strong> </td>
                                    <td>Dia Final: <br><strong>{{ $escola->intervalo_pagamento_final ?? "" }}</strong> </td>
                                    <td>Taxa da Multa 1º: <br><strong>{{ $escola->taxa_multa1 ?? "" }}%</strong> </td>
                                    <td>Taxa da Multa 2º: <br><strong>{{ $escola->taxa_multa2 ?? "" }}%</strong> </td>
                                    <td colspan="2">Taxa da Multa 3º: <br><strong>{{ $escola->taxa_multa3 ?? "" }}%</strong> </td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="bg-light">Tipo de Impressão utilizada na instituição</td>
                                </tr>

                                <tr>
                                    @if ($escola->impressora == "Normal")
                                    <td colspan="2">Impressão: <br><strong>{{ $escola->impressora ?? "" }} ou A4</strong> </td>
                                    @endif
                                    @if ($escola->impressora == "Ticket")
                                    <td colspan="2">Impressão: <br><strong>{{ $escola->impressora ?? "" }}</strong> </td>
                                    @endif
                                    <td colspan="2"> <br> ----------------</td>
                                    <td colspan="2"> <br> ----------------</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        @if (Auth::user()->can('update: escola'))
                        <a href="{{ route('informacoes-escolares.editar', Crypt::encrypt($escola->id)) }}" class="btn btn-primary">Editar as Informações da Escola</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

@endsection
