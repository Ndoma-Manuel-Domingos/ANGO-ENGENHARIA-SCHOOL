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

<!-- Main content -->
<section class="content pt-4">
    <div class="container-fluid">

        <div class="row">
            <div class="alert alert-info col-12">
                <p> <strong>NOTA: </strong> Pagamento de Uniforme, o Pagamento é feito para cada mês,
                    evitar selecionar duas vezes um mês, e sempre seguir as instruções do desenvolvedor.
                    Caso adicionar um mês e não for o desejado, podes simplesmente remover.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-12  bg-white p-4 mb-3">
                <div class="row" id="myForm">

                    <div class="form-group col-md-3">
                        <label for="valor">Valor Unitário</label>
                        <input type="text" name="valor" class="form-control valor" placeholder="Valor do Pagamento">
                        <span class="text-danger error-text valor_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="quantidade">Quantidade</label>
                        <input type="number" name="quantidade" max="5" min="1" class="form-control quantidade" placeholder="Informe a quantidade de Uniforme">
                        <span class="text-danger error-text quantidade_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="desconto">Desconto</label>
                        <input type="text" name="desconto" class="form-control desconto" placeholder="Informe o Desconto %">
                        <span class="text-danger error-text desconto_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="pagamento">Pagamento</label>
                        <select name="pagamento" id="pagamento" class="form-control pagamento">
                            <option value="">Selecione Genero</option>
                            <option value="Confirmado">Confirmado</option>
                            <option value="Aprazo">Aprazo</option>
                            <option value="Outro">Outro</option>
                        </select>
                        <span class="text-danger error-text pagamento_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="tipo_pagamento">Tipo Pagamento</label>
                        <select name="tipo_pagamento" id="tipo_pagamento" class="form-control tipo_pagamento">
                            <option value="">Selecione Genero</option>
                            <option value="NUMERARIO">NUMERARIO</option>
                            <option value="CARTÃO">CARTÃO</option>
                            <option value="TRANSFERÊNCIA">TRANSFERÊNCIA</option>
                        </select>
                        <span class="text-danger error-text tipo_pagamento_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="banco">Banco</label>
                        <select name="banco" id="banco" class="form-control banco">
                            <option value="">Selecione</option>
                            <option value="BFA">BFA</option>
                            <option value="BPC">BPC</option>
                            <option value="BIC">BIC</option>
                            <option value="BAI">BAI</option>
                            <option value="ATLANTICO">ATLANTICO</option>
                        </select>
                        <span class="text-danger error-text banco_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="sobre_nome">Número de Transição</label>
                        <input type="text" name="numero_transicao" class="form-control numero_transicao" placeholder="Número da seríe Bancaria">
                        <span class="text-danger error-text numero_transicao_error"></span>
                    </div>

                    <input type="hidden" name="estudantes_id" class="estudantes_id" value="{{ $estudantes->id }}">
                    <input type="hidden" name="pagamentodoque" class="pagamentodoque" value="uniforme">

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary pagamentoPropinaAJAX">Finalizar Pagamento</button>
                    </div>
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
        // COLLETAR PAGAMENTO
        $(document).on('click', '.pagamentoPropinaAJAX', function(e) {
            e.preventDefault();

            var data = {
                'valor': $('.valor').val()
                , 'desconto': $('.desconto').val()
                , 'tipo_pagamento': $('.tipo_pagamento').val()
                , 'pagamento': $('.pagamento').val()
                , 'banco': $('.banco').val()
                , 'numero_transicao': $('.numero_transicao').val()
                , 'quantidade': $('.quantidade').val()
                , 'estudantes_id': $('.estudantes_id').val()
                , 'pagamentodoque': $('.pagamentodoque').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.estudantes-pagamento-outros-create') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                },

                , success: function(response) {
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.open('../../download/ficha-pagamento-uniforme/' + response.ficha, "_blank");
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });
    });

</script>
@endsection
