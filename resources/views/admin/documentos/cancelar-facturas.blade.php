@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Anular Factura <span class="text-danger">{{ $pagamento->next_factura }}</span> </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.documento') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Facturas</li>
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
                <form action="{{ route('web.documento-cancelar-facturas-create') }}" method="post" class="row" id="formulario">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h5>Detalhes da Factura</h5>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                
                                <div class="form-group col-12 col-md-3">
                                    <label for="" class="form-label">Documento</label>
                                    <input type="text" class="form-control" value="{{ $pagamento->next_factura }}" disabled>
                                </div>
                                
                                <div class="form-group col-12 col-md-3">
                                    <label for="" class="form-label">Tipo de Documento</label>
                                    <input type="text" class="form-control" value="{{ $pagamento->tipo_factura }}" disabled>
                                </div>
                            
                                <div class="form-group col-12 col-md-3">
                                    <label for="" class="form-label">Cliente</label>
                                    <input type="text" class="form-control" value="{{ $pagamento->estudante->nome }} {{ $pagamento->estudante->sobre_nome }}" disabled>
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="" class="form-label">Operador</label>
                                    <input type="text" class="form-control" value="{{ $pagamento->operador->nome }}" disabled>
                                </div>


                                <div class="form-group col-12 col-md-3">
                                    <label for="" class="form-label">Serviço</label>
                                    <input type="text" class="form-control text-uppercase" value="{{ $pagamento->pago_at }}" disabled>
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="" class="form-label">Data de pagamento</label>
                                    <input type="text" class="form-control" value="{{ $pagamento->created_at }}" disabled>
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="" class="form-label">Forma de Pagamento</label>
                                    <input type="text" class="form-control" value="{{ $pagamento->forma_pagamento->descricao }}" disabled>
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="" class="form-label">Total da Factura</label>
                                    <input type="text" class="form-control" value="{{ number_format($pagamento->quantidade * $pagamento->valor, 2, ',', '.') }}" disabled>
                                </div>
                            </div>

                            <input type="hidden" name="ficha_factura" value="{{ $pagamento->ficha }}">
                            <input type="hidden" class="form-control" name="preco_factura" value="{{ $pagamento->valor * $pagamento->quantidade }}">

                            <div class="col-12 col-md-12">
                                <label for="" class="form-label">Descrição</label>
                                <textarea cols="30" required rows="3" name="motivo" class="form-control" placeholder="Informe o Motivo da Anulação da Factura"></textarea>
                            </div>

                        </div>

                        <div class="card-footer">
                            <button form="formulario" class="btn btn-primary float-right" type="submit">
                                Anular Factura
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
