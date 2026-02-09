@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Converter Factura <span class="text-danger">{{ $pagamento->next_factura }}</span> </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="">Voltar</a></li>
                    <li class="breadcrumb-item active">informativos</li>
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
                    <div class="card-footer">
                        <form class="row">
                            <div class="form-group col-12 col-md-2">
                                <label for="" class="form-label">NIF cliente</label>
                                <input type="text" class="form-control" value="{{ $pagamento->nif_cliente }}" disabled>
                            </div>
                            <div class="form-group col-12 col-md-2">
                                <label for="" class="form-label">Cliente</label>
                                <input type="text" class="form-control" value="{{ $pagamento->model($pagamento->model, $pagamento->estudantes_id) }}" disabled>
                            </div>
                            <div class="form-group col-12 col-md-2">
                                <label for="" class="form-label">Referência Factura</label>
                                <input type="text" class="form-control" value="{{ $pagamento->ficha }}" disabled>
                            </div>
                            <div class="form-group col-12 col-md-2">
                                <label for="" class="form-label">Tipo Factura</label>
                                <input type="text" class="form-control" value="{{ $pagamento->next_factura }}" disabled>
                            </div>
                            <div class="form-group col-12 col-md-2">
                                <label for="" class="form-label">Valor Cash</label>
                                <input type="text" class="form-control" value="{{ $pagamento->valor_cash }}" disabled>
                            </div>
                            <div class="form-group col-12 col-md-2">
                                <label for="" class="form-label">Valor Multicaixa</label>
                                <input type="text" class="form-control" value="{{ $pagamento->valor_multicaixa }}" disabled>
                            </div>
                            <div class="form-group col-12 col-md-2">
                                <label for="" class="form-label">Valor Total</label>
                                <input type="text" class="form-control" value="{{ ($pagamento->valor * $pagamento->quantidade) + $pagamento->multa }}" disabled>
                            </div>
                            <div class="form-group col-12 col-md-2">
                                <label for="" class="form-label">Serviço</label>
                                <input type="text" class="form-control" value="{{ $pagamento->servico->servico }}" disabled>
                            </div>
                            <div class="form-group col-12 col-md-2">
                                <label for="" class="form-label">Operador</label>
                                <input type="text" class="form-control" value="{{ $pagamento->operador->nome }}" disabled>
                            </div>

                            <div class="form-group col-12 col-md-2">
                                <label for="" class="form-label">Data</label>
                                <input type="text" class="form-control" value="{{ $pagamento->data_at }}" disabled>
                            </div>

                            <div class="form-group col-12 col-md-2">
                                <label for="" class="form-label">Data Vencimento</label>
                                <input type="text" class="form-control" value="{{ $pagamento->data_vencimento }}" disabled>
                            </div>

                            <div class="form-group col-12 col-md-2">
                                <label for="" class="form-label">Moeda</label>
                                <input type="text" class="form-control" value="{{ $pagamento->moeda }}" disabled>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('web.converter-facturas-create') }}" method="GET" class="row" id="formulario">
                            @csrf
                            <div class="form-group col-12 col-md-2">
                                <label for="" class="form-label">Selecione o Tipo de Factura</label>
                                <select type="text" class="form-control" name="tipo_factura">
                                    <option value="FR">FACTURAS RECIBO</option>
                                    <option value="FP">FACTURAS PRÓ-FORMA</option>
                                    <option value="FT">FACTURAS</option>
                                    <option value="RG">RECIBO</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-md-2">
                                <label for="" class="form-label">Forma de Pagamento</label>
                                <select type="text" class="form-control" name="forma_pagamento">
                                    <option value="NUMERARIO">NUMERÁRIO</option>
                                    <option value="MULTICAIXA">MULTICAIXA</option>
                                </select>
                            </div>
                            <input type="hidden" name="ficha_factura" value="{{ $pagamento->ficha }}">
                            <input type="hidden" class="form-control" name="preco_factura" value="{{ ($pagamento->valor * $pagamento->quantidade) + $pagamento->multa}}">
                            <div class="col-12 col-md-2">
                                <label for="" class="form-label">Valor Entregue</label>
                                <input type="number" class="form-control" name="valor_entregue">
                            </div>
                        </form>
                    </div>

                    <div class="card-footer">
                        <button form="formulario" class="btn btn-primary float-right" type="submit" disabled>
                            Converter Factura
                        </button>
                    </div>
                </div>

            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
