@extends('layouts.estudantes')

@section('content')

<div class="content">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Detalhe da Factura</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route("est.meus-pagamento-estudante") }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Pagamentos</li>
                  </ol>
              </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    
    <div class="container-fluid">
        <section class="content">
          
          <div class="row">
            <div class="col-12 col-md-12">
                         
              <div class="card">
                <div class="card-header">
                  <h6 style="text-transform: uppercase">
                    @if ($pagamento->tipo_factura == "FP")
                      FACTURA PROFORMA PARA SERVIÇO {{ $pagamento->servico->servico }}
                    @endif

                    @if ($pagamento->tipo_factura == "FR")
                      FACTURA RECIBO PARA SERVIÇO {{ $pagamento->servico->servico }}
                    @endif

                    @if ($pagamento->tipo_factura == "FT")
                      FACTURA PARA SERVIÇO {{ $pagamento->servico->servico }}
                    @endif
                    
                    <small class="float-right">Date: {{ $pagamento->data_at }}</small>
                  </h6>
                </div>
                <div class="card-body">
                  <h1 class="fs-5"><strong>Dados Pessoais.</strong></h1>
                  <ul class="fs-6">
                    <li><strong>Nome: </strong>{{ $pagamento->estudante->nome }} {{ $pagamento->estudante->sobre_nome }}</li>
                    <li><strong>Genero ou Sector: </strong>{{ $pagamento->estudante->genero }} {{ $pagamento->estudante->categoria }}</li>
                    <li>
                      @if ($pagamento->model == "estudante")
                        <strong>B.I: </strong> {{ $pagamento->estudante->bilheite }}
                      @else
                        <strong>NIF: </strong> {{ $pagamento->estudante->documento }}
                      @endif
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="card">
                <div class="card-header">
                  <h5>Items do Pagamento</h5>
                </div>
                <div class="card-body">
                  <table style="width: 100%" class="table table-bordered  ">
                    <thead>
                      <tr>
                        <th width="100">Codígo</th>
                        <th width="200">Valor Unitário</th>
                        <th>Quantidade</th>
                        <th>Multa</th>
                        <th>Mês</th>
                      </tr>
                    </thead>
                    <tbody>
                                      
                      @foreach ($items_pagamentos as $key => $item)
                        <tr>
                          <td>{{ $key + 1 }}</td>
                          <td>{{ number_format($item->preco , "2", ',', '.') }} Kz</td>
                          <td>{{ number_format($item->quantidade , "1", ',', '.') }}</td>
                          <td>{{ number_format($item->multa , "2", ',', '.') }} Kz</td>
                          <td>{{ $item->mes }}</td>
                        </tr>
                      @endforeach  
                    </tbody>
                  </table>
                </div>
                <div class="card-footer">
                  .
                </div>
              </div>
            </div>
            
            <div class="col-12 col-md-6">
              <div class="card">
                <div class="card-header">
                  <h5>Detalhes do Pagamento</h5>
                </div>
                <div class="card-body">
                  <table style="width: 100%" class="table table-bordered  ">
                    <tr>
                      <th>Factura: </th>
                      <td>{{ $pagamento->next_factura }}</td>
                      <th>Referência:</th>
                      <td>{{ $pagamento->ficha }}</td>
                    </tr>
                    <tr>
                      <th>Hora: </th>
                      <td>{{ date("h:i:s", strtotime($pagamento->created_at)) }}</td>
                      <th>Data:</th>
                      <td>{{ date("d-m-Y", strtotime($pagamento->created_at)) }}</td>
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
                        <td>{{ $pagamento->operador->nome }}</td>
                        <th>Total:</th>
                        <td>{{ number_format(($pagamento->valor) * ($pagamento->quantidade), "2", ',', '.')  }} Kz</td>
                    </tr>
                  </table>
                </div>
                
                <div class="card-footer">
                  <a href="{{ route('ficha-pagamento-propina', $pagamento->ficha) }}" target="_blank" class="btn-primary btn"><i class="fas fa-print"></i> Imprirmir</a>
                </div>
              </div>
            </div>
          </div>
        
        </section>
    </div>
</div>



@endsection