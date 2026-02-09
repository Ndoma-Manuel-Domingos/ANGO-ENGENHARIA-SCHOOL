@extends('layouts.escolas')

@section('content')

<style>
    address span {
        border: 1px solid rgb(197, 197, 197);
        padding: 5px 10px;
        display: inline-block;
        margin-bottom: 2px;
        border-radius: 5px;
        width: 100%;
    }

</style>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-novos-pagamentos') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Facturas</li>
                </ol>

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content pt-4">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Liquidar Factura Nº {{ $pagamento->next_factura }}</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <form action="{{ route('web.liquidar-facturar-store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-12  col-md-3">
                                    <label for="valor_entregue">Digite o Valor Entregue</label>
                                    <input type="text" name="valor_entregue" class="form-control valor_entregue" placeholder="Digite o Valor que o Estudante te Entregou">
                                    @error('valor_entregue')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12  col-md-3">
                                    <label for="servico">Serviço</label>
                                    <select name="servico" id="servico" class="form-control servico">
                                        <option value="{{ $servicos->id }}">{{ $servicos->servico }}</option>
                                    </select>
                                    @error('servico')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="valor">Valor Total Iliquido</label>
                                    <input type="text" name="valor" class="form-control valor" value="{{ $pagamento->valor * $pagamento->quantidade }}" disabled placeholder="Valor do Pagamento">
                                    @error('valor')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="multa">Total Multa</label>
                                    <input type="text" name="multa" class="form-control multa" value="{{ $pagamento->multa }}" disabled placeholder="multa do Pagamento">
                                    @error('multa')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="total_a_pagar">Total a Pagar</label>
                                    <input type="text" name="total_a_pagar" class="form-control total_a_pagar valor_total_a_pagar" value="{{ ($pagamento->valor * $pagamento->quantidade) + $pagamento->multa }}" disabled placeholder="Valor geral a pagar">
                                    <span class="text-danger error-text total_a_pagar_error"></span>
                                    @error('total_a_pagar')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>


                                <input type="hidden" value="{{ ($pagamento->valor * $pagamento->quantidade) + $pagamento->multa }}" class="total_a_pagar" name="total_a_pagar">
                                <input type="hidden" value="{{ $turma->turmas_id }}" class="turmas_id_seleciona" name="turmas_id_seleciona">

                                <div class="form-group col-12  col-md-3">
                                    <label for="pagamento">Status Pagamento</label>
                                    <select name="pagamento" id="pagamento" class="form-control pagamento">
                                        <option value="{{ $pagamento->status }}">{{ $pagamento->status }}</option>
                                        <option value="Confirmado">Confirmado</option>
                                    </select>
                                    @error('pagamento')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12  col-md-3">
                                    <label for="tipo_pagamento">Forma Pagamento</label>
                                    <select name="tipo_pagamento" id="tipo_pagamento" class="form-control tipo_pagamento">
                                        <option value="NU">NUMERARIO</option>
                                        <option value="MB">MULTICAIXA</option>
                                    </select>
                                    @error('tipo_pagamento')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12  col-md-3">
                                    <label for="banco">Banco</label>
                                    <select name="banco" id="banco" class="form-control banco">
                                        <option value="">Selecione</option>
                                        <option value="Nenhum">Nenhum</option>
                                        <option value="BFA">BFA</option>
                                        <option value="BPC">BPC</option>
                                        <option value="BIC">BIC</option>
                                        <option value="BAI">BAI</option>
                                        <option value="BAI">BCA</option>
                                        <option value="ATLANTICO">ATLANTICO</option>
                                        <option value="CAIXA ANGOLA">CAIXA ANGOLA</option>
                                        <option value="OUTROS">OUTROS</option>
                                    </select>
                                    @error('banco')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12  col-md-3">
                                    <label for="numero_transicao">Número de Transição</label>
                                    <input type="text" name="numero_transicao" class="form-control numero_transicao" placeholder="Número da seríe Bancaria">
                                    @error('numero_transicao')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-md-3 mt-4 pt-2">
                                    <h4><span class="text-danger">Troco <strong id="valor_troco_apresenta">0</strong></span></h4>
                                    @error('valor_troco_apresenta')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <input type="hidden" name="estudantes_id" class="estudantes_id" value="{{ $estudantes->id }}">
                                <input type="hidden" name="ficha_factura" class="ficha_factura" value="{{ $pagamento->ficha }}">
                                {{-- unico ou mensal --}}
                                <input type="hidden" name="status_servico_pagar" class="status_servico_pagar">

                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary pagamentoPropinaAJAX">Finalizar Pagamento</button>
                        </div>
                    </form>
                </div>
            </div>

        </div><!-- /.row -->

    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@section('scripts')
<script>
    $(function() {

        var servicoPagar;
        // COLLETAR PAGAMENTO
        $(document).on('click', '.pagamentoPropinaAJAXa', function(e) {
            e.preventDefault();

            var data = {
                'valor': $('.valor').val()
                , 'valor_entregue': $('.valor_entregue').val()
                , 'servico': $('.servico').val()
                , 'tipo_pagamento': $('.tipo_pagamento').val()
                , 'pagamento': $('.pagamento').val()
                , 'multa': $('.multa').val()
                , 'total_a_pagar': $('.total_a_pagar').val()
                , 'banco': $('.banco').val()
                , 'numero_transicao': $('.numero_transicao').val()
                , 'estudantes_id': $('.estudantes_id').val()
                , 'turma': $('.turmas_id_seleciona').val()
                , 'ficha_factura': $('.ficha_factura').val()
            , }

            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.liquidar-facturar-store') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    window.open('../../download/ficha-pagamento-propina/' + response.ficha, "_blank");
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        $('.valor_entregue').on('input', function(e) {
            e.preventDefault();
            if ($(this).val() > 0) {
              // valor total a pagar
              var valor_total = $('.valor_total_a_pagar').val();
              var valor_entregue = $(this).val();

              var troco = valor_entregue - valor_total;

              // var f = troco.toLocaleString('pt-br',{style: 'currency', currency: 'AOA'});
              var f2 = troco.toLocaleString('pt-br', {
                  minimumFractionDigits: 2
              });

              $("#valor_troco_apresenta").html("")
              $("#valor_troco_apresenta").append(f2)

            } else {
              console.log("false")
            }
        })
    });

</script>
@endsection
