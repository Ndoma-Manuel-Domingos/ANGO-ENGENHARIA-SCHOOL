@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Detalhe da Factura <strong class="text-danger">REF: {{ $pagamento->ficha }}</strong> </h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Voltas</a></li>
            <li class="breadcrumb-item active">Detalhes</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  
    <div class="container-fluid">
        <section class="content">
          <div class="row">
            <div class="col-12 col-md-12">
                  
              <!-- Main content -->
              <div class="invoice p-3 mb-3">
                <!-- title row -->
                <div class="row">
                  <div class="col-12 col-md-12">
                    <h4 style="text-transform: uppercase">
                      @if ($pagamento->tipo_factura == "FP")
                        FACTURA PROFORMA PARA SERVIÇO {{ $pagamento->servico }}
                      @endif

                      @if ($pagamento->tipo_factura == "FR")
                        FACTURA RECIBO PARA SERVIÇO {{ $pagamento->servico }}
                      @endif

                      @if ($pagamento->tipo_factura == "FT")
                        FACTURA PARA SERVIÇO {{ $pagamento->servico }}
                      @endif
                      
                      <small class="float-right">Date: {{ $pagamento->data_at }}</small>
                    </h4>
                  </div>
                  <!-- /.col -->
                </div>
                <hr>
                <!-- info row -->
                <div class="row invoice-info">
                  <!-- /.col -->
                  <div class="col-sm-5 col-12 invoice-col">
                    <h1 class="fs-4"><strong>Dados Pessoais.</strong></h1>
                    <ul class="fs-5">
                        <li><strong>Nome: </strong>{{ $dados->nome }} {{ $dados->sobre_nome }}</li>
                        <li><strong>Genero ou Sector: </strong>{{ $dados->genero }} {{ $dados->categoria }}</li>
                        <li>
                            @if ($pagamento->model == "estudante")
                              <strong>B.I: </strong> {{ $dados->bilheite }}
                            @else
                              <strong>NIF: </strong> {{ $dados->documento }}
                            @endif
                          </li>
                    </ul>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-5 col-12 invoice-col text-start">
                    <b class="fs-4">Ficha Nº:  {{ $pagamento->next_factura }}</b><br>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-2 col-12 invoice-col">
                    <div style="width:180px;height:180px">
                      <i class="fas fa-user text-center" style="font-size: 175px;margin:auto"></i>
                    </div>
                  </div>
                </div>
                <!-- /.row -->

                @if ($pagamento->model == "estudante")
                  <div class="row">
                    <div class="col-12 col-md-12">
                      <table  style="width: 100%" class="table projects  ">
                        <tbody>
                        <tr>
                          <td><strong>Matricula</strong></td>
                          <td>{{ $matricula->numero_estudante }}</td>
                          <td><strong>Classe Anterior</strong></td>
                          <td>{{ $classe_at->classes }}</td>
                          <td><strong>Classe Actual</strong></td>
                          <td>{{ $classe->classes }}</td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td><strong>Turno</strong></td>
                          <td>{{ $turno->turno }}</td>
                          <td><strong>Curso</strong></td>
                          <td>{{ $curso->curso }}</td>
                          <td><strong>Ano Lectivo</strong></td>
                          <td>{{ $ano_lectivo->ano }}</td>
                          <td></td>
                          <td></td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>                    
                @endif
                                
                @if (count($detalhesPagamento) != 0)
                  <div class="row">
                    <div class="col-12 col-md-6">
                      <table  style="width: 100%" class="table projects text-center  ">
                        <thead>
                          <tr>
                            {{-- <th width="100">Codígo</th> --}}
                            <th width="200">Valor Unitário</th>
                            <th>Quantidade</th>
                            <th>Mês</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($detalhesPagamento as $item)
                            <tr>
                              {{-- <td>{{ $item->code }}</td> --}}
                              <td>{{ number_format($item->preco , "2", ',', '.') }} Kz</td>
                              <td>{{ $item->quantidade }}</td>
                              <td>{{ $item->mes }}</td>
                            </tr>
                          @endforeach  
                        </tbody>
                      </table>
                    </div>
                  </div>
                @endif

                <div class="row">
                  <div class="col-12 col-md-12">
                    <div class="col-12 col-md-6">
                      <table  style="width: 100%" class="table projects  ">
                        <tr>
                          <th>Hora: </th>
                          <td>{{ date("h:i:s", strtotime($pagamento->created_at)) }}</td>
                          <th>Data:</th>
                          <td>{{ date("Y-m-d", strtotime($pagamento->created_at)) }}</td>
                        </tr>
                        <tr>
                          <th>Valor Unitário</th>
                          <td>{{ number_format($pagamento->valor, "2", ',', '.') }} Kz</td>
                          <th>Multa:</th>
                          <td>{{ number_format($pagamento->multa, "2", ',', '.') }} Kz</td>
                        </tr>
                        <tr>
                          <th>Desconto:</th>
                          <td>{{ number_format($pagamento->desconto, "2", ',', '.') }} Kz</td>
                          <th>Pagamento:</th>
                          <td class="text-danger text-uppercase">{{ $pagamento->status }}</td>
                        </tr>

                        <tr>
                            <th>Troco:</th>
                            <td>{{ number_format($pagamento->trco, "2", ',', '.') }} Kz</td>
                        </tr>

                        <tr>
                            <th>Funcionário:</th>
                            <td>{{ $funcionarioAtendente->nome ?? "" }}</td>
                            <th>Total:</th>
                            <td>{{ number_format(($pagamento->valor) * ($pagamento->quantidade), "2", ',', '.')  }} Kz</td>
                        </tr>
                      </table>
                    </div>
                  </div>
            
                  @if ($pagamento->model == "funcionario")
                    <div class="col-12 col-md-12">
                      <h3 class="lead text-center pr-4 fs-3"><strong>Outras Informações</strong></h3>
                      <h6 class=""><strong>Referência da factura {{ $pagamento->ficha }}</strong></h6>

                      <table  style="width: 100%" class="table projects  ">
                        <tr>
                          <th>Subsídio Alimentação</th>
                          <td>{{ number_format($pagamento->subcidio_alimentacao, "2", ',', '.') }} Kz</td>
                          <th>Subsídio Transporte</th>
                          <td>{{ number_format($pagamento->subcidio_transporte, "2", ',', '.') }} Kz</td>
                        </tr>

                        <tr>
                          <th>Subsídio Natal</th>
                          <td>{{ number_format($pagamento->subcidio_natal, "2", ',', '.') }} Kz</td>
                          <th>Subsídio Férias</th>
                          <td>{{ number_format($pagamento->subcidio_ferias, "2", ',', '.') }} Kz</td>
                        </tr>

                        <tr>
                          <th>Subsídio Abono Familiar</th>
                          <td>{{ number_format($pagamento->subcidio_abono_familiar, "2", ',', '.') }} Kz</td>
                          <th>Outro Subsídio</th>
                          <td>{{ number_format($pagamento->subcidio, "2", ',', '.') }} Kz</td>
                        </tr>

                        <tr>
                          <th>IRT</th>
                          <td>{{ number_format($pagamento->irt, "2", ',', '.') }} Kz</td>
                          <th>INSS</th>
                          <td>{{ number_format($pagamento->inss, "2", ',', '.') }} Kz</td>
                        </tr>

                        <tr>
                          <th>Faltas</th>
                          <td>{{ number_format($pagamento->faltas, "2", ',', '.') }} Kz</td>
                          <th>===</th>
                          <td>===</td>
                        </tr>

                      </table>
                    </div>                      
                  @endif

                </div>
                <!-- /.row -->

                <div class="col-12 col-md-12">  
                  @if ($pagamento->model == "estudante")
                    @if (count($detalhesPagamento) != 0)
                      <a href="{{ route('ficha-pagamento-propina', $pagamento->ficha) }}" target="_blank" class="btn-primary btn"><i class="fas fa-print"></i> Imprirmir</a>
                    @else
                      <a href="{{ route('ficha-matricula2', $pagamento->ficha) }}" target="_blank" class="btn-primary btn"><i class="fas fa-print"></i> Imprirmir</a>
                    @endif
                  @else
                    @if ($pagamento->model == "funcionario")
                      <a href="{{ route('ficha-pagamento-salario', $pagamento->ficha) }}" target="_blank" class="btn-primary btn"><i class="fas fa-print"></i> Imprirmir</a>
                    @else
                      <a href="{{ route('ficha-matricula', $pagamento->ficha) }}" target="_blank" class="btn-primary btn"><i class="fas fa-print"></i> Imprirmir</a>
                    @endif
                  @endif
                </div>
              </div>
              <!-- /.invoice -->
            </div><!-- /.col -->

                <!-- /.invoice -->
              </div><!-- /.col -->
          </div><!-- /.row -->
        </section>
    </div>
  <!-- /.content -->
  @endsection








