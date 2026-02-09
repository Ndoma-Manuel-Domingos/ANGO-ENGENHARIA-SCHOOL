@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Situação Financeira (Extratos)</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($estudante->id)) }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Estudantes</li>
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
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Extrato financeiro do estudante, meses pagos e não pagos {{ $estudante->nome }} {{ $estudante->sobre_nome }}.
                        @if (Auth::user()->can('update: isentar propina') || Auth::user()->can('create: isentar propina'))
                            @if ($matricula->condicao == "Paga" || $matricula->condicao == "normal")
                            <a href="{{ route('web.sistuacao-financeiro-para-nao-isento', Crypt::encrypt($estudante->id)) }}" class="btn btn-success mb-2 float-right text-white"><i class="fas fa-file"></i> Mudar Estudante como Isento no pagamento de propinas</a>
                            @else
                            <a href="{{ route('web.sistuacao-financeiro-para-nao-isento', Crypt::encrypt($estudante->id)) }}" class="btn btn-danger mb-2 float-right text-white"><i class="fas fa-file"></i> Nudar Estudante para Fazer todos os Pagamento</a>
                            @endif
                        @endif
                    </h5>
                </div>
            </div>
        </div>
       
        <div class="row">
            <div class="col-12 col-md-12">
                @if ($servicosTurma)
                    @foreach ($servicosTurma as $item)
                        @if ($item->pagamento == "mensal")
                        <div class="card">  
                            @php
                                $servicos =(new App\Models\web\estudantes\CartaoEstudante())::with('servico')->where([
                                    ['servicos_id', '=', $item->servicos_id],
                                    ['estudantes_id', '=', $estudante->id],
                                    ['ano_lectivos_id', '=', $ano->id],
                                ])
                                ->get();
                            @endphp
                            <div class="card-header">
                                <h3 class="card-title">Ficha Extratos do serviço de {{ $item->servico }}</h3>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="text-center table table-striped">
                                    <thead>
                                        <tr class="bg-light">
                                            <th>Codigo</th>
                                            <th>Meses</th>
                                            <th>Data Inicio Pagamento</th>
                                            <th>Data Final Pagamento</th>
                                            <th>Valor Unitário</th>
                                            @if ($estudante->bolseiro($estudante->id))
                                            <th>Desconto Bolsa</th>
                                            <th>Valor a Pagar</th>
                                            <th>Periodo 1</th>
                                            <th>Periodo 2</th>
                                            <th>Pagamento Feito</th>
                                            @endif
                                            <th>Multa</th>
                                            <th>Status</th>
                                            <th>Acções</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($estudante->bolseiro($estudante->id))
                                             
                                             {{-- CASO FOR BOLSEIROS --}}
                                            @foreach ($servicos as $item2)
                                                @if ($estudante->bolseiro($estudante->id)->afectacao == "mensalidade")
                                                    @if ($item2->servico->servico == "Propinas")
                                                        <tr class="bg-light">
                                                            <td>00-{{ $item2->id }}</td>
                                                            <td>{{ $item2->month_name }}</td>
                                                            <td>{{ $item2->data_at }}</td>
                                                            <td>{{ $item2->data_exp }}</td>
                                                            <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                            
                                                            @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                                                                {{-- PRIMEIRO SIMESTRE --}}
                                                                @if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Iª Simestre")
                                                                  @if ($item2->semestral == "1º Semestre")
                                                                  <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                                                  <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                                                  @else
                                                                  <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                                  <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                                  @endif
                                                                @else  
                                                                    {{-- SEGUNDO SIMESTRE --}}
                                                                    @if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "IIª Simestre")
                                                                        @if ($item2->semestral == "2º Semestre")
                                                                        <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                                                        <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                                                        @else
                                                                        <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                                        <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                                        @endif
                                                                    @else  
                                                                        {{-- ANUAL --}}
                                                                        @if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Anual")
                                                                            @if ($item2->semestral == "1º Semestre" && $item2->semestral == "2º Semestre")
                                                                            <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                                                            <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                                                            @else
                                                                            <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                                            <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @else   
                                                                @if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Iª Trimestre")
                                                                    @if ($item2->trimestral == "1º Trimestre")
                                                                        <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                                                        <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                                                    @else
                                                                        <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                                        <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                                    @endif
                                                                @else
                                                                    @if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "IIª Trimestre")
                                                                        @if ($item2->trimestral == "2º Trimestre")
                                                                            <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                                                            <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                                                        @else
                                                                            <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                                            <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                                        @endif
                                                                    @else
                                                                        @if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "IIIª Trimestre")
                                                                            @if ($item2->trimestral == "3º Trimestre")
                                                                                <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                                                                <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                                                            @else
                                                                                <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                                                <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                                            @endif
                                                                        @else
                                                                            @if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Geral")
                                                                                @if ($item2->trimestral == "1º Trimestre" && $item2->trimestral == "2º Trimestre" && $item2->trimestral == "3º Trimestre")
                                                                                    <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                                                                    <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                                                                @else
                                                                                    <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                                                    <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endif
                                                            
                                                            <td>{{ $item2->trimestral }}</td>
                                                            <td>{{ $item2->semestral }}</td>
                                                            <td>{{ $item2->status_2 }}</td>
                                                            <td>{{ number_format($item2->multa, '2', ',', '.')  }} kz</td>
                                                            <td>{{ $item2->status }}</td>
                                                            
                                                            <td class="bg-infos">
                                                                
                                                            @if (Auth::user()->can('update: isentar propina') || Auth::user()->can('create: isentar propina'))
                                                                @if ($item2->status == "excepto")
                                                                
                                                                    <a href="{{ route('web.estudante-activar-mes-pagar', ['mes'=> $item2->id, 'est' => $estudante->id]) }}" title="Para este serviço o estudante não pode pagar" class="fa fa-times btn btn-danger"></a>
                                                                @else
                                                                    @if ($item2->status == "Nao Pago")
                                                                        <a href="{{ route('web.estudante-activar-mes-nao-pagar', ['mes'=> $item2->id, 'est' => $estudante->id]) }}" title="Para este serviço é de caracter obrigatório o estudante pagar" class="fa fa-check btn btn-danger"></a>
                                                                    @else
                                                                        @if ($item2->status == "divida")
                                                                            <a href="#" title="Serviço já pago com sucesso" class="fa fa-check btn btn-danger"></a>
                                                                        @else
                                                                        <a href="#" title="Serviço já pago com sucesso" class="fa fa-check btn btn-success"></a>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                             @endif
                                                            </td>
                                                            
                                                        </tr>
                                                    @else   
                                                        <tr class="bg-light">
                                                            <td>00-{{ $item2->id }}</td>
                                                            <td>{{ $item2->month_name }}</td>
                                                            <td>{{ $item2->data_exp }}</td>
                                                            <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                            
                                                            <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                            <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                            
                                                            <td>{{ $item2->trimestral }}</td>
                                                            <td>{{ $item2->semestral }}</td>
                                                            <td>{{ $item2->status_2 }}</td>
                                                            <td>{{ number_format($item2->multa, '2', ',', '.')  }} kz</td>
                                                            <td>{{ $item2->status }}</td>
                                                            <td class="bg-infos">
                                                            @if (Auth::user()->can('update: isentar propina') || Auth::user()->can('create: isentar propina'))
                                                                @if ($item2->status == "excepto")
                                                                    <a href="{{ route('web.estudante-activar-mes-pagar', ['mes'=> $item2->id, 'est' => $estudante->id]) }}" title="Para este serviço o estudante não pode pagar" class="fa fa-times btn btn-danger"></a>
                                                                @else
                                                                    @if ($item2->status == "Nao Pago")
                                                                    <a href="{{ route('web.estudante-activar-mes-nao-pagar', ['mes'=> $item2->id, 'est' => $estudante->id]) }}" title="Para este serviço é de caracter obrigatório o estudante pagar" class="fa fa-check btn btn-danger"></a>
                                                                    @else
                                                                        @if ($item2->status == "divida")
                                                                        <a href="#" title="Serviço já pago com sucesso" class="fa fa-check btn btn-danger"></a>
                                                                        @else
                                                                        <a href="#" title="Serviço já pago com sucesso" class="fa fa-check btn btn-success"></a>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @else 
                                                    <tr class="bg-light">
                                                        <td>00-{{ $item2->id }}</td>
                                                        <td>{{ $item2->month_name }}</td>
                                                        <td>{{ $item2->data_exp }}</td>                                        
                                                        <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                        
                                                        @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                                                            {{-- PRIMEIRO SIMESTRE --}}
                                                            @if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Iª Simestre")
                                                              @if ($item2->semestral == "1º Semestre")
                                                              <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                                              <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                                              @else
                                                              <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                              <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                              @endif
                                                            @else  
                                                                {{-- SEGUNDO SIMESTRE --}}
                                                                @if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "IIª Simestre")
                                                                    @if ($item2->semestral == "2º Semestre")
                                                                    <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                                                    <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                                                    @else
                                                                    <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                                    <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                                    @endif
                                                                @else  
                                                                    {{-- ANUAL --}}
                                                                    @if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Anual")
                                                                        @if ($item2->semestral == "1º Semestre" && $item2->semestral == "2º Semestre")
                                                                        <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                                                        <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                                                        @else
                                                                        <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                                        <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @else   
                                                            @if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Iª Trimestre")
                                                                @if ($item2->trimestral == "1º Trimestre")
                                                                    <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                                                    <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                                                @else
                                                                    <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                                    <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                                @endif
                                                            @else
                                                                @if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "IIª Trimestre")
                                                                    @if ($item2->trimestral == "2º Trimestre")
                                                                        <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                                                        <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                                                    @else
                                                                        <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                                        <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                                    @endif
                                                                @else
                                                                    @if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "IIIª Trimestre")
                                                                        @if ($item2->trimestral == "3º Trimestre")
                                                                            <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                                                            <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                                                        @else
                                                                            <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                                            <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                                        @endif
                                                                    @else
                                                                        @if ($estudante->bolseiro($estudante->id)->periodo->trimestre == "Geral")
                                                                            @if ($item2->trimestral == "1º Trimestre" && $item2->trimestral == "2º Trimestre" && $item2->trimestral == "3º Trimestre")
                                                                                <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                                                                <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                                                            @else
                                                                                <td>{{ number_format(0, '2', ',', '.')  }} kz</td>
                                                                                <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                      
                                                        <td>{{ $item2->trimestral }}</td>
                                                        <td>{{ $item2->semestral }}</td>
                                                        <td>{{ $item2->status_2 }}</td>
                                                        <td>{{ number_format($item2->multa, '2', ',', '.')  }} kz</td>
                                                        <td>{{ $item2->status }}</td>
                                                        
                                                        <td class="bg-infos">
                                                        @if (Auth::user()->can('update: isentar propina') || Auth::user()->can('create: isentar propina'))
                                                            @if ($item2->status == "excepto")
                                                                <a href="{{ route('web.estudante-activar-mes-pagar', ['mes'=> $item2->id, 'est' => $estudante->id]) }}" title="Para este serviço o estudante não pode pagar" class="fa fa-times btn btn-danger"></a>
                                                            @else
                                                                @if ($item2->status == "Nao Pago")
                                                                    <a href="{{ route('web.estudante-activar-mes-nao-pagar', ['mes'=> $item2->id, 'est' => $estudante->id]) }}" title="Para este serviço é de caracter obrigatório o estudante pagar" class="fa fa-check btn btn-danger"></a>
                                                                @else
                                                                    @if ($item2->status == "divida")
                                                                    <a href="#" title="Serviço já pago com sucesso" class="fa fa-check btn btn-danger"></a>
                                                                    @else
                                                                    <a href="#" title="Serviço já pago com sucesso" class="fa fa-check btn btn-success"></a>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                        </td>
                                                        
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            {{-- CASO NÂO FOR BOLSEIROS --}}
                                            @foreach ($servicos as $item2)
                                                <tr class="bg-light">
                                                    <td>00-{{ $item2->id }}</td>
                                                    <td>{{ $item2->month_name }}</td>
                                                    <td>{{ $item2->data_at }}</td>
                                                    <td>{{ $item2->data_exp }}</td>
                                                    <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                                    <td>{{ number_format($item2->multa, '2', ',', '.')  }} kz</td>
                                                    @if ($item2->status == "Pago")
                                                        <td><span class="badge badge-success">{{ $item2->status }}</span></td>
                                                    @endif
                                                    @if ($item2->status == "Nao Pago")
                                                        <td><span class="badge badge-danger">{{ $item2->status }}</span></td>
                                                    @endif
                                                    @if ($item2->status == "divida")
                                                        <td><span class="badge badge-warning">{{ $item2->status }}</span></td>
                                                    @endif
                                                    @if ($item2->status == "Isento")
                                                        <td><span class="badge badge-info">{{ $item2->status }}</span></td>
                                                    @endif
                                                    @if ($item2->status == "excepto")
                                                        <td><span class="badge badge-dark">{{ $item2->status }}</span></td>
                                                    @endif
                                                    <td>
                                                        @if ($item2->status == "Pago")
                                                            @if (Auth::user()->can('create: definir propina como pago'))
                                                                <a href="{{ route('web.estudante-activar-mes-pagar', ['mes'=> $item2->id, 'est' => $estudante->id]) }}" title="Definir com Não Pago" class="btn btn-success"><i class="fa fa-check "></i>DEFINIR NÃO PAGO</a>
                                                            @endif
                                                        @endif
                                                        @if ($item2->status == "Nao Pago" || $item2->status == "divida")
                                                            @if (Auth::user()->can('create: definir propina como nao pago'))
                                                            <a href="{{ route('web.estudante-activar-mes-nao-pagar', ['mes'=> $item2->id, 'est' => $estudante->id]) }}" title="Definir como Pago" class="btn btn-danger"><i class="fa fa-times "></i>DEFINIR PAGO</a>
                                                            @endif
                                                            @if (Auth::user()->can('create: definir propina como divida'))
                                                            <a href="{{ route('web.estudante-activar-mes-divida', ['mes'=> $item2->id, 'est' => $estudante->id]) }}" title="Definir como Dívida" class="btn btn-danger"><i class="fa fa-times "></i>DEFINIR DÍVIDA</a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                @if (Auth::user()->can('create: pagamento'))
                                    <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('NULL'), 'servico' => Crypt::encrypt($servicosPropina->id),'ano' => Crypt::encrypt($ano->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Extrato</a>
                                    <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Pago'), 'servico' => Crypt::encrypt($servicosPropina->id),'ano' => Crypt::encrypt($ano->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Pago</a>
                                    <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Nao_Pago'), 'servico' => Crypt::encrypt($servicosPropina->id),'ano' => Crypt::encrypt($ano->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Não Pago</a>
                                    <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Devendo'), 'servico' => Crypt::encrypt($servicosPropina->id),'ano' => Crypt::encrypt($ano->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Devendo</a>
                                    <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Bloqueado'), 'servico' => Crypt::encrypt($servicosPropina->id),'ano' => Crypt::encrypt($ano->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Bloqueado</a>
                                    <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Obrigatorios'), 'servico' => Crypt::encrypt($servicosPropina->id),'ano' => Crypt::encrypt($ano->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Obrigatórios</a>
                                @endif
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
                
        <div class="row">
            <div class="col-12 col-md-12">
                @if ($servicosTurma)
                <div class="row">
                    @foreach ($servicosTurma as $item)
                        @if ($item->pagamento == "unico")
                            @php
                            $servicos =(new App\Models\web\estudantes\CartaoEstudante())::with('servico')->where([
                                ['servicos_id', '=', $item->servicos_id],
                                ['estudantes_id', '=', $estudante->id],
                                ['ano_lectivos_id', '=', $ano->id],
                                ])
                                ->get();
                            @endphp
                            <div class="col-md-6 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Ficha Extratos do serviço de {{ $item->servico }}</h3>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table class="text-center table table-striped">
                                            <thead>
                                                <tr class="bg-light">
                                                    <th>Codigo</th>
                                                    <th>Data Inicio do Pagamento</th>
                                                    <th>Data Final do Pagamento</th>
                                                    <th>Valor</th>
                                                    <th>Multa</th>
                                                    <th>status</th>
                                                    <th>Acções</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($servicos as $item2)
                                                <tr class="bg-light">
                                                    <td>oo{{ $item2->id }}</td>
                                                    <td>{{ $item2->data_at }}</td>
                                                    <td>{{ $item2->data_exp }}</td>
                                                    <td>{{ number_format($item->preco, '2', ',', '.') }} Kz</td>
                                                    <td>{{ number_format($item->multa, '2', ',', '.') }} Kz</td>
                                                    @if ($item2->status == "Pago")
                                                        <td><span class="badge badge-success">{{ $item2->status }}</span></td>
                                                    @endif
                                                    @if ($item2->status == "Nao Pago")
                                                        <td><span class="badge badge-danger">{{ $item2->status }}</span></td>
                                                    @endif
                                                    @if ($item2->status == "divida")
                                                        <td><span class="badge badge-warning">{{ $item2->status }}</span></td>
                                                    @endif
                                                    @if ($item2->status == "Isento")
                                                        <td><span class="badge badge-info">{{ $item2->status }}</span></td>
                                                    @endif
                                                    @if ($item2->status == "excepto")
                                                        <td><span class="badge badge-dark">{{ $item2->status }}</span></td>
                                                    @endif
                                                    
                                                    <td>
                                                        @if ($item2->status == "Pago")
                                                            @if (Auth::user()->can('create: definir propina como pago'))
                                                            <a href="{{ route('web.estudante-activar-mes-pagar', ['mes'=> $item2->id, 'est' => $estudante->id]) }}" title="Definir com Não Pago" class="btn btn-success"><i class="fa fa-check "></i> DEFINIR NÃO PAGO</a>
                                                            @endif
                                                        @endif
                                                        @if ($item2->status == "Nao Pago" || $item2->status == "divida")
                                                            @if (Auth::user()->can('create: definir propina como nao pago'))
                                                            <a href="{{ route('web.estudante-activar-mes-nao-pagar', ['mes'=> $item2->id, 'est' => $estudante->id]) }}" title="Definir como Pago" class="btn btn-danger"><i class="fa fa-times "></i> DEFINIR PAGO</a>
                                                            @endif
                                                        
                                                            @if (Auth::user()->can('create: definir propina como divida'))
                                                            <a href="{{ route('web.estudante-activar-mes-divida', ['mes'=> $item2->id, 'est' => $estudante->id]) }}" title="Definir como Dívida" class="btn btn-danger"><i class="fa fa-times "></i> DEFINIR DÍVIDA</a>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        
    </div>
</section>
@endsection
