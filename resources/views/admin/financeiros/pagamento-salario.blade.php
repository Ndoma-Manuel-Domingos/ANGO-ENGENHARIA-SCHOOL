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
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Pagamento de Sálario, o Pagamento é feito para cada mês,
                        evitar selecionar duas vezes um mês, e sempre seguir as instruções do desenvolvedor.
                        Caso adicionar um mês e não for o desejado, podes simplesmente remover.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12  bg-white p-4 mb-3">
                <div class="row" id="myForm">

                    <div class="form-group col-md-3">
                        <label for="valor">Valor Unitário</label>
                        <input type="text" name="valor" class="form-control valor" disabled placeholder="Valor do Pagamento" value="{{ $contratoFuncionario->salario }}">
                        <span class="text-danger error-text valor_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="subcidio_alimentacao">Subsídio Alimentação</label>
                        <input type="text" name="subcidio_alimentacao" class="form-control subcidio_alimentacao" value="{{ $contratoFuncionario->subcidio_alimentacao }}" disabled placeholder="Informe o subcídio do Alimentação">
                        <span class="text-danger error-text subcidio_alimentacao_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="subcidio_transporte">Subsídio Transporte</label>
                        <input type="text" name="subcidio_transporte" class="form-control subcidio_transporte" value="{{ $contratoFuncionario->subcidio_transporte }}" disabled placeholder="Informe o subcídio da Transporte">
                        <span class="text-danger error-text subcidio_transporte_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="subcidio_natal">Subsídio Natal</label>
                        <input type="text" name="subcidio_natal" class="form-control subcidio_natal" value="{{ $contratoFuncionario->subcidio_natal }}" disabled placeholder="Informe o subcídio do Alimentação">
                        <span class="text-danger error-text subcidio_natal_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="subcidio_ferias">Subsídio de Férias</label>
                        <input type="text" name="subcidio_ferias" class="form-control subcidio_ferias" value="{{ $contratoFuncionario->subcidio_ferias }}" disabled placeholder="Informe o subcídio do Alimentação">
                        <span class="text-danger error-text subcidio_ferias_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="subcidio_abono_familiar">Subsídio Abono Familía</label>
                        <input type="text" name="subcidio_abono_familiar" class="form-control subcidio_abono_familiar" value="{{ $contratoFuncionario->subcidio_abono_familiar }}" disabled placeholder="Informe o subcídio do Alimentação">
                        <span class="text-danger error-text subcidio_abono_familiar_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="subcidio">Outro Subsídio</label>
                        <input type="text" name="subcidio" class="form-control subcidio" value="{{ $contratoFuncionario->subcidio }}" disabled placeholder="Informe outros subcídio">
                        <span class="text-danger error-text subcidio_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="desconto">Desconto</label>
                        <input type="text" name="desconto" value="{{ $contratoFuncionario->falta_por_dia }}" class="form-control desconto" disabled placeholder="Informe o Desconto %">
                        <span class="text-danger error-text desconto_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="faltas">Número de Faltas</label>
                        <input type="text" name="faltas" class="form-control faltas" placeholder="Informe o número de faltas durante o mês">
                        <span class="text-danger error-text faltas_error"></span>
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

                    <div class="form-group col-md-6">
                        <label for="tipo_pagamento">Tipo Pagamento</label>
                        <select name="tipo_pagamento" id="tipo_pagamento" class="form-control tipo_pagamento">
                            <option value="">Selecione Genero</option>
                            <option value="NUMERARIO">NUMERARIO</option>
                            <option value="CARTÃO">CARTÃO</option>
                            <option value="TRANSFERÊNCIA">TRANSFERÊNCIA</option>
                        </select>
                        <span class="text-danger error-text tipo_pagamento_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="banco">Banco</label>
                        <select name="banco" id="banco" class="form-control banco">
                            <option value="">Selecione</option>
                            <option value="BFA">BFA</option>
                            <option value="BPC">BPC</option>
                            <option value="BIC">BIC</option>
                            <option value="BAI">BAI</option>
                            <option value="ATLANTICO">ATLANTICO</option>
                            <option value="Nunhum">Nunhum</option>
                        </select>
                        <span class="text-danger error-text banco_error"></span>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="sobre_nome">Número de Transição</label>
                        <input type="text" name="numero_transicao" class="form-control numero_transicao" placeholder="Número da seríe Bancaria">
                        <span class="text-danger error-text numero_transicao_error"></span>
                    </div>

                    <input type="hidden" name="funcionarios_id" class="funcionarios_id" value="{{ $funcionarios->id }}">

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary pagamentoSalarioAJAX">Finalizar Pagamento</button>
                    </div>
                </div>
            </div>

            <div class="col-md-6 bg-white p-4">
                @if ($mesesAdd)
                <h1 class="fs-3">Meses a pagar no momento</h1>
                <table id="example1" style="width: 100%" class="table table-bordered  ">
                    <thead>
                        <tr>
                            <th>Mês</th>
                            <th>Quantidade</th>
                            <th>Valor</th>
                            <th>status</th>
                            <th>Acções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mesesAdd as $mes)
                        <tr>
                            <td>{{ $mes->meses }}</td>
                            <td class="text-center">{{ $mes->quantidade }}</td>
                            <td>{{ number_format(($mes->preco + $contratoFuncionario->subcidio_abono_familiar + $contratoFuncionario->subcidio_ferias + $contratoFuncionario->subcidio_natal + $contratoFuncionario->subcidio_alimentacao + $contratoFuncionario->subcidio_transporte + $contratoFuncionario->subcidio), 2, '.', ', ') }} <small>kz</small></td>
                            <td>{{ $mes->status }}</td>
                            <td>
                                <a href="{{ route('web.remover-meses-pagamento-salario', [$mes->id, $funcionarios->id, $mes->meses]) }}">
                                    <i class="fas fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    <tfoot>
                        <tr class="text-center">
                            <th>Total</th>
                            <th>{{ $somaQuantidade }}</th>
                            <th>{{ number_format(($somaVolores + $contratoFuncionario->subcidio_abono_familiar + $contratoFuncionario->subcidio_ferias + $contratoFuncionario->subcidio_natal + $contratoFuncionario->subcidio_alimentacao + $contratoFuncionario->subcidio_transporte + $contratoFuncionario->subcidio), 2, '.', ', ') }} <small>kz</small></th>
                            <th></th>
                        </tr>
                    </tfoot>

                    </tbody>
                </table>
                @endif

            </div>

            <div class="col-md-6 bg-white p-4">
                @if ($cartao)
                <h1 class="fs-3">Listagem dos Meses</h1>
                <table id="example1" style="width: 100%" class="table table-bordered  ">
                    <thead>
                        <tr>
                            <th>Mês</th>
                            <th>status</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartao as $cart)
                        <tr>
                            <td>{{ $cart->meses }}</td>
                            <td>{{ $cart->status }}</td>
                            <td class="text-center">
                                @if ($cart->status == 'Nao pago' OR $cart->status == 'divida')
                                <a href="{{ route('web.adicionar-meses-pagamento-salario', [$cart->id, $funcionarios->id]) }}"><i class="fas fa-times text-danger"></i></a>
                                @else
                                @if ($cart->status == 'processo')
                                <i class="fas circle">processo...</i>
                                @else
                                <i class="fas fa-check  text-success"></i>
                                @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
                @endif

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
        $(document).on('click', '.pagamentoSalarioAJAX', function(e) {
            e.preventDefault();

            var data = {
                'valor': $('.valor').val()
                , 'desconto': $('.desconto').val()
                , 'subcidio_alimentacao': $('.subcidio_alimentacao').val()
                , 'subcidio_transporte': $('.subcidio_transporte').val(),

                'subcidio_natal': $('.subcidio_natal').val()
                , 'subcidio_ferias': $('.subcidio_ferias').val()
                , 'subcidio_abono_familiar': $('.subcidio_abono_familiar').val(),

                'subcidio': $('.subcidio').val()
                , 'faltas': $('.faltas').val()
                , 'tipo_pagamento': $('.tipo_pagamento').val()
                , 'pagamento': $('.pagamento').val()
                , 'banco': $('.banco').val()
                , 'numero_transicao': $('.numero_transicao').val()
                , 'funcionarios_id': $('.funcionarios_id').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.funcionarios-pagamento-salario-create') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    window.open('../../download/ficha-pagamento-salario/' + response.ficha, "_blank");
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
