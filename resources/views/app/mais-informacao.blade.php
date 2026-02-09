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
                                    <td rowspan="4">
                                        <img src="{{ $logotipo }}"
                                            style="height: 100px; width: 100px;" class="img-circle"
                                            alt="Logotipo da instituição">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="bg-light">Informações Director</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Nome do Director: <strong>{{ $director->nome ?? '' }}</strong>
                                    </td>
                                    <td>Bilhete Identidade: <strong>{{ $director->bilheite ?? '' }}</strong> </td>
                                    <td colspan="1">Genero: <strong>{{ $director->genero ?? '' }}</strong> </td>
                                    <td>Estado Cívil: <strong>{{ $director->estado_civil ?? '' }}</strong></td>
                                </tr>
    
                                <tr>
                                    <td>Especialidade: <strong>{{ $director->especialidade ?? '' }}</strong></td>
                                    <td>Curso: <strong>{{ $director->curso ?? '' }}</strong></td>
                                    <td colspan="3">Perfil: <strong>{{ $director->descricao ?? '' }}</strong></td>
                                </tr>
    
                                <tr>
                                    <td colspan="6" class="bg-light">Informações Gerais</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Nome da Escola: <strong>{{ $escola->nome }}</strong> </td>
                                    <td>Número da Escola: <strong>{{ $escola->numero_escola }}</strong> </td>
                                    <td>Decreto de Criação: <strong>{{ $escola->decreto }}</strong></td>
                                    <td>Número de Idetificação Fiscal: <strong>{{ $escola->documento }}</strong>
                                    </td>
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
                                    <td colspan="6" class="bg-light">Contactos</td>
                                </tr>
                                <tr>
                                    <td>Telefone 1: <strong>{{ $escola->telefone1 }}</strong> </td>
                                    <td>Telefone 2: <strong>{{ $escola->telefone2 }}</strong> </td>
                                    <td>Telefone 4: <strong>{{ $escola->telefone3 }}</strong></td>
                                    <td colspan="3">E-mail ou Site <strong>{{ $escola->site }}</strong></td>
                                </tr>
    
                                <tr>
                                    <td colspan="6" class="bg-light">Localização</td>
                                </tr>
                                <tr>
                                    <td>Provincia: <strong>{{ $escola->provincia->nome ?? ""}}</strong> </td>
                                    <td>Municipio: <strong>{{ $escola->municipio->nome ?? "" }}</strong> </td>
                                    <td>Distrito: <strong>{{ $escola->distrito->nome ?? "" }}</strong></td>
                                    <td colspan="3">Endereço: <strong>{{ $escola->endereco }}</strong></td>
                                </tr>
    
    
                                <tr>
                                    <td colspan="6" class="bg-light">Infraestrutura</td>
                                </tr>
                                <tr>
                                    <td>Cantina: <strong>{{ $escola->cantina }}</strong> </td>
                                    <td colspan="">Campo Disportivo: <strong>{{ $escola->campo_desportivo
                                            }}</strong> </td>
                                    <td colspan="">Computadores: <strong>{{ $escola->computadores }}</strong> </td>
                                    <td>Água Potavel: <strong>{{ $escola->agua }}</strong></td>
                                    <td>Electricidade: <strong>{{ $escola->electricidade }}</strong></td>
                                    <td>Transporte: <strong>{{ $escola->transporte }}</strong></td>
                                </tr>
    
                                <tr>
                                    <td colspan="">Internet: <strong>{{ $escola->internet }}</strong> </td>
                                    <td colspan="">Bibioteca: <strong>{{ $escola->biblioteca }}</strong> </td>
                                    <td colspan="">Farmácia: <strong>{{ $escola->farmacia }}</strong> </td>
                                    <td colspan="">Laboratório: <strong>{{ $escola->laboratorio }}</strong> </td>
                                    <td colspan="">ZIP: <strong>{{ $escola->zip }}</strong> </td>
                                    <td colspan="">Casas de Banhos: <strong>{{ $escola->casas_banho }}</strong></td>
                                </tr>
                                
                                <tr>
                                    <td colspan="6" class="bg-light">Coodernadas bancarias</td>
                                </tr>
                                
                                <tr>
                                    <td colspan="2">Banco: <strong>{{ $escola->banco }}</strong> </td>
                                    <td colspan="2">Nº Conta: <strong>{{ $escola->conta }}</strong> </td>
                                    <td colspan="2">Nº IBAN: <strong>{{ $escola->iban }}</strong> </td>
                                </tr>
                                
                                <tr>
                                    <td colspan="6" class="bg-light">Intervalo de Pagamentos de Mensalidade. Taxa de Multa para atraso de mensalidades</td>
                                </tr>
                                
                                <tr>
                                    <td colspan="2">Dia Inicial: <strong>{{ $escola->intervalo_pagamento_inicio }}</strong> </td>
                                    <td colspan="2">Dia Final: <strong>{{ $escola->intervalo_pagamento_final }}</strong> </td>
                                    <td colspan="2"></td>
                                </tr>
                                
                                <tr>
                                    <td colspan="6" class="bg-light">Tipo de Impressão utilizada na instituição</td>
                                </tr>
                                
                                <tr>
                                    @if ($escola->impressora == "Normal") 
                                    <td colspan="2">Impressão: <strong>{{ $escola->impressora }} ou A4</strong> </td>   
                                    @endif
                                    @if ($escola->impressora == "Ticket")
                                    <td colspan="2">Impressão: <strong>{{ $escola->impressora }}</strong> </td>   
                                    @endif
                                    <td colspan="2"></td>
                                    <td colspan="2"></td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        @if (Auth::user()->can('update: escola'))
                        <a href="{{ route('web.informacoes-escola-editar', Crypt::encrypt($escola->id)) }}" class="btn btn-primary">Editar as Informações da Escola</a>
                        @endif
                    </div>
                </div>
            </div>    
          </div>
        </div>
      </div>
      <!-- /.content-header -->
  
@endsection
