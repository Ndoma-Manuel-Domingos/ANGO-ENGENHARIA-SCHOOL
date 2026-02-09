@extends('layouts.estudantes')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Painel Financeiro</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route("est.home-estudante") }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Financeiro</li>
                </ol>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-12">
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> controle do Cartão dos Pagamentos do estudante {{ $estudante->nome }} {{ $estudante->sobre_nome }}.</h5>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-12">
            @if ($servicosTurma)
            @foreach ($servicosTurma as $item)
            @if ($item->pagamento == "mensal")
            @php
            $servicos =(new App\Models\web\estudantes\CartaoEstudante())::where([
            ['servicos_id', '=', $item->servicos_id],
            ['estudantes_id', '=', $estudante->id],
            ['ano_lectivos_id', '=', $ano->id],
            ])
            ->join('tb_servicos', 'tb_cartao_estudantes.servicos_id', '=', 'tb_servicos.id')
            ->select('tb_cartao_estudantes.month_name', 'tb_cartao_estudantes.data_exp', 'tb_servicos.servico','tb_cartao_estudantes.status_2','tb_cartao_estudantes.status','tb_cartao_estudantes.id')
            ->get();
            @endphp
            <div class="card">
                <div class="card-header">
                    <h6>Pagamento do serviço de {{ $item->servico }}</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Meses</th>
                                <th>Data Final Pagamento</th>
                                <th>Valor Unitário</th>
                                @if ($estudante->bolseiro($estudante->id))
                                <th>Percentagem Bolsa</th>
                                <th>Desconto Bolsa</th>
                                <th>Valor a Pagar</th>
                                <th>Pagamento Feito</th>
                                @endif
                                <th>Multa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($servicos as $item2)
                            <tr>
                                <td>oo{{ $item2->id }}</td>
                                <td>{{ $item2->mes($item2->month_name) }}</td>
                                <td>{{ $item2->data_exp }}</td>
                                <td>{{ number_format($item->preco, '2', ',', '.')  }} kz</td>
                                @if ($estudante->bolseiro($estudante->id))
                                <td>{{ number_format( $estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto, '0', ',', '.')  }} %</td>
                                <td>{{ number_format( ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) ), '2', ',', '.')  }} kz</td>
                                <td>{{ number_format( ($item->preco - ($item->preco - ($item->preco * ($estudante->bolseiro($estudante->id)->instituicao_bolsa->desconto) / 100) )) , '2', ',', '.')  }} kz</td>
                                <td>{{ $item2->status_2 }}</td>
                                @endif
                                <td>{{ number_format($item->multa, '2', ',', '.')  }} kz</td>
                                @if ($item2->status == "Pago")
                                <td class="text-success text-uppercase">{{ $item2->status }}</td>
                                @else
                                @if ($item2->status == "Nao Pago")
                                <td class="text-danger text-uppercase">{{ $item2->status }}</td>
                                @else
                                <td class="text-secondary text-uppercase">{{ $item2->status }}</td>
                                @endif
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('NULL'), 'servico' => Crypt::encrypt($item->servicos_id),'ano' => Crypt::encrypt($ano->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Extrato</a>
                            <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Pago'), 'servico' => Crypt::encrypt($item->servicos_id),'ano' => Crypt::encrypt($ano->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Pago</a>
                            <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Nao_Pago'), 'servico' => Crypt::encrypt($item->servicos_id),'ano' => Crypt::encrypt($ano->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Não Pago</a>
                            <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Devendo'), 'servico' => Crypt::encrypt($item->servicos_id),'ano' => Crypt::encrypt($ano->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Devendo</a>
                            <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Bloqueado'), 'servico' => Crypt::encrypt($item->servicos_id),'ano' => Crypt::encrypt($ano->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Bloqueado</a>
                            <a href="{{ route('ficha-extrato-estudante', ['id' => Crypt::encrypt($estudante->id), 'cod' => Crypt::encrypt('Meses_Obrigatorios'), 'servico' => Crypt::encrypt($item->servicos_id),'ano' => Crypt::encrypt($ano->id)] ) }}" target="_blank" class="btn btn-primary mb-2"><i class="fas fa-print"></i> Meses Obrigatórios</a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            @endif
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 col-md-12">
          <div class="row">
            @if ($servicosTurma)
            @foreach ($servicosTurma as $item)
            @if ($item->pagamento == "unico")
            @php
              $servicos =(new App\Models\web\estudantes\CartaoEstudante())::where([
              ['servicos_id', '=', $item->servicos_id],
              ['estudantes_id', '=', $estudante->id],
              ['ano_lectivos_id', '=', $ano->id],
              ])->select('tb_cartao_estudantes.status', 'tb_cartao_estudantes.id', 'tb_cartao_estudantes.data_exp')
              ->get();
            @endphp
              <div class="col-12 col-md-3">
                <div class="card card-dark">
                  <div class="card-header">
                      <h6>Pagamento do serviço de {{ $item->servico }}</h6>
                  </div>
                  <div class="card-body bg-dark">
                      <table class="text-center table">
                          <thead>
                              <tr>
                                  <th>Codigo</th>
                                  <th title="Data Final do Pagamento">Data</th>
                                  <th>Valor</th>
                                  <th>Multa</th>
                                  <th>status</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($servicos as $item2)
                              <tr>
                                  <td>oo{{ $item2->id }}</td>
                                  <td>{{ $item2->data_exp }}</td>
                                  <td>{{ number_format($item->preco, '2', ',', '.') }} Kz</td>
                                  <td>{{ number_format($item->multa, '2', ',', '.') }} Kz</td>
                                  <td>{{ $item2->status }}</td>
                              </tr>
                              @endforeach
                          </tbody>
                      </table>
                  </div>
                </div>
              </div>
              @endif
              @endforeach
              @endif
            </div>
        </div>
    </div>
    
</div>

@endsection
