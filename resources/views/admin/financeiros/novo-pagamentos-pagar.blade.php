@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Novo Pagamento</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financeiros.financeiro-contas-pagar') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Outro Pagamentos</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Pagamento de outros serviços por parte de escola. Ex: serviços de internet, luz, água, aluguer etc</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row" id="myForm">

                            <div class="form-group col-12  col-md-3">
                                <label for="servico">Serviço</label>
                                <select name="servico" id="servico" class="form-control servico">
                                    <option value="">Selecione</option>
                                    @if ($servicos)
                                    @foreach ($servicos as $servico)
                                    <option value="{{ $servico->id }}">{{ $servico->servico }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span class="text-danger error-text servico_error"></span>
                            </div>

                            <div class="form-group col-12 col-md-3">
                                <label for="valor">Valor Unitário</label>
                                <input type="text" name="valor" class="form-control valor" value="" disabled placeholder="Valor do Pagamento">
                                <span class="text-danger error-text valor_error"></span>
                            </div>

                            <input type="hidden" value="{{ $escola->id }}" class="escola_selecionada" name="escola_selecionada">

                            <div class="form-group col-12  col-md-3">
                                <label for="desconto">Desconto</label>
                                <input type="text" name="desconto" class="form-control desconto" disabled placeholder="Informe o Desconto %">
                                <span class="text-danger error-text desconto_error"></span>
                            </div>

                            <div class="form-group col-12  col-md-3">
                                <label for="multa">Multa</label>
                                <input type="text" name="multa" class="form-control multa" placeholder="Informe a multa a pagar" disabled>
                                <span class="text-danger error-text multa_error"></span>
                            </div>

                            <div class="form-group col-12  col-md-3" id="form_campo_quantidade" style="display: none;">
                                <label for="quantidade">Quantidade</label>
                                <input type="number" name="quantidade" value="1" class="form-control quantidade" placeholder="Informe o Desconto a quantidade a pagar">
                                <span class="text-danger error-text quantidade_error"></span>
                            </div>

                            <div class="form-group col-12  col-md-3" id="form_campo_multa" style="display: none;">
                                <label for="aplicacao_multa">Aplicar Multa</label>
                                <select name="pagamento" id="pagamento" class="form-control aplicacao_multa">
                                    <option value="nao">Não</option>
                                    <option value="sim">Sim</option>
                                </select>
                                <span class="text-danger error-text aplicacao_multa_error"></span>
                            </div>

                            <div class="form-group col-md-3 col-12">
                                <label for="documento">Tipo de Documento <span class="text-danger">*</span></label>
                                <select name="documento" id="documento" class="form-control documento select2 @error('documento') is-invalid @enderror">
                                    <option value="">Selecione o Pagamento</option>
                                    <option value="FR" {{ old('documento') == "FR" ? 'selected' : 'selected' }}>Factura Recibo</option>
                                    <option value="PP" {{ old('documento') == "PP" ? 'selected' : '' }}>Factura Pró-forma</option>
                                    <option value="FT" {{ old('documento') == "FT" ? 'selected' : '' }}>Factura</option>
                                </select>
                                @error('documento')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-12  col-md-3">
                                <label for="tipo_pagamento">Tipo Pagamento</label>
                                <select name="tipo_pagamento" id="tipo_pagamento" class="form-control tipo_pagamento" onchange="calcularTroco()">
                                    @foreach ($formas_pagamento as $item)
                                    <option value="{{ $item->sigla_tipo_pagamento }}">{{ $item->descricao }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text tipo_pagamento_error"></span>
                            </div>

                            <div class="form-group col-12 col-md-3" id="form_campo_caixas">
                                <label for="caixa_id">Caixas</label>
                                <select name="caixa_id" id="caixa_id" class="form-control caixa_id">
                                    @foreach ($caixas as $item)
                                    <option value="{{ $item->id }}">{{ $item->conta }} - {{ $item->caixa }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text caixa_id_error"></span>
                            </div>

                            <div class="form-group col-12 col-md-3" id="form_campo_bancos" style="display: none;">
                                <label for="banco_id">Bancos</label>
                                <select name="banco_id" id="banco_id" class="form-control banco_id">
                                    @foreach ($bancos as $item)
                                    <option value="{{ $item->id }}">{{ $item->conta }} - {{ $item->banco }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text banco_id_error"></span>
                            </div>

                            <div class="form-group col-12 col-md-3">
                                <label for="valor_pago">Valor Pago <span class="text-danger">Substituir Vírgulas por ponto</span></label>
                                <input type="number" name="valor_pago" class="form-control valor_pago" value="" placeholder="Informe o Valor que foi pago neste serviço">
                                <span class="text-danger error-text valor_pago_error"></span>
                            </div>

                            <div class="form-group col-12 col-md-3">
                                <label for="observacao">Descrição (Opcional) max 2000 caracter</label>
                                <input type="text" name="observacao" class="form-control observacao" placeholder="Faça ou escreva uma descrição">
                                <span class="text-danger error-text observacao_error"></span>
                            </div>

                            {{-- unico ou mensal --}}
                            <input type="hidden" name="status_servico_pagar" class="status_servico_pagar">

                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary pagamentoPropinaAJAX">Finalizar Pagamento</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div id="carregarMesesPagamaentoPropinaMomento">
                                    {{-- carregamento automatica --}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div id="carregarMesesPagamaentoPropinaAdicionar">
                                    {{-- carregamento automatico --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->

    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('scripts')
<script>
    function calcularSoma() {
        var inputs = document.querySelectorAll('.contas_a_movimentar');
        var soma = 0;

        inputs.forEach(function(input) {
            var valor = parseFloat(input.value);
            if (!isNaN(valor)) {
                soma += valor;
            }
        });
        document.getElementById('resultado').textContent = "Soma: " + soma.toFixed(2); // Exibe a soma com duas casas decimais
    }

    function formatarValorMonetario(valor) {

        // Converter o número para uma string e separar parte inteira da parte decimal
        let partes = String(valor).split('.');
        let parteInteira = partes[0];
        let parteDecimal = partes.length > 1 ? '.' + partes[1] : '';

        // Adicionar separadores de milhar
        parteInteira = parteInteira.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        // Retornar o valor formatado
        return parteInteira + parteDecimal;
    }

    $(function() {

        var servicoPagar;
        // COLLETAR PAGAMENTO
        $(document).on('click', '.pagamentoPropinaAJAX', function(e) {
            e.preventDefault();

            var data = {
                'valor': $('.valor').val()
                , 'desconto': $('.desconto').val()
                , 'caixa_id': $('.caixa_id').val()
                , 'banco_id': $('.banco_id').val()
                , 'valor_pago': $('.valor_pago').val()
                , 'servico': $('.servico').val()
                , 'tipo_pagamento': $('.tipo_pagamento').val()
                , 'documento': $('.documento').val(),
                // 'banco': $('.banco').val(), 
                'observacao': $('.observacao').val()
                , 'multa': $('.multa').val()
                , 'escola': $('.escola_selecionada').val()
                , 'quantidade': $('.quantidade').val()
                , 'aplicacao_multa': $('.aplicacao_multa').val(),
                // unico ou mensal
                'status_servico_pagar': $('.status_servico_pagar').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.escola-pagamento-servico-create') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.open(`../download/ficha-pagamento-servico/${response.ficha}`, "_blank");

                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });

        });

        $(document).on('change', '.tipo_pagamento', function(e) {
            e.preventDefault();
            var id = $(this).val();

            if (id == "NU") {
                $('#form_campo_caixas').css({
                    display: "inline-block"
                });

                $('#form_campo_bancos').css({
                    display: "none"
                });
            }

            if (id == "OU") {
                $('#form_campo_caixas').css({
                    display: "inline-block"
                });

                $('#form_campo_bancos').css({
                    display: "inline-block"
                });
            }

            if (id == "TT") {
                $('#form_campo_caixas').css({
                    display: "none"
                });

                $('#form_campo_bancos').css({
                    display: "inline-block"
                });
            }

            if (id == "MB") {
                $('#form_campo_caixas').css({
                    display: "none"
                });

                $('#form_campo_bancos').css({
                    display: "inline-block"
                });
            }

            if (id == "DD") {
                $('#form_campo_caixas').css({
                    display: "none"
                });

                $('#form_campo_bancos').css({
                    display: "inline-block"
                });
            }

        })

        $(document).on('change', '.servico', function(e) {
            e.preventDefault();
            var id = $(this).val();
            servicoPagar = id;
            $.ajax({
                type: "GET", 
                url: `carregar-servicos-cartao/${id}`, 
                beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }, 
                success: function(response) {
                    Swal.close();
                    var iconeStatus;
                    $('#form_campo_multa').css({
                        display: "inline-block"
                    });
                    if (response.servico.pagamento == 'mensal') {
                        $('.valor').val(response.somaVolores);
                        $('.desconto').val(response.servico.desconto);
                        $('.multa').val(response.somaMulta);
                        $('.status_servico_pagar').val(response.servico.pagamento);

                        $('#form_campo_quantidade').css({
                            display: "none"
                        });
                        // meses ue pretendo adcionar
                        $("#carregarMesesPagamaentoPropinaAdicionar").html("");
                        $("#carregarMesesPagamaentoPropinaAdicionar").append(`<h5 class="fs-6">Listagem dos Meses ${response.servico.servico.servico} Não pago</h5>
                        <table id="example1" style="width: 100%" class="table table-bordered">
                          <thead>
                                <tr>
                                  <th>Mês</th>
                                  <th>status</th>
                                  <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="body_carregar_meses_pagar">
                            </tbody>
                        </table>`);
                        // meses a pagar no memento
                        $("#carregarMesesPagamaentoPropinaMomento").html("");
                        $("#carregarMesesPagamaentoPropinaMomento").append(`<h5 class="fs-6">Meses a pagar no momento</h5>
                          <table id="example1" style="width: 100%" class="table table-bordered">
                          <thead>
                              <tr>
                                <th>Mês</th>
                                <th>Qtd</th>
                                <th>Preço Unitário</th>
                                <th>IVA</th>
                                <th>Multa</th>
                                <th>status</th>
                                <th>Total a Pagar</th>
                                <th>Acções</th>
                              </tr>
                          </thead>
                          <tbody class="body_carregar_meses_pagar_momento">
                          </tbody>
                          <tfoot>
                          <tr class="text-center">
                            <th>Total</th>
                            <th>${formatarValorMonetario(response.somaQuantidade)}</th>
                            <th></th>
                            <th></th>
                            <th>${formatarValorMonetario(response.somaMulta)}<small> kz</small></th>
                            <th></th>
                            <th>${formatarValorMonetario(response.somaVolores)}<small> kz</small></th>
                            <th></th>
                          </tr>
                        </tfoot>
                        </table>`);

                        $('.body_carregar_meses_pagar_momento').html("");
                        for (let index = 0; index < response.mesesAdd.length; index++) {
                            $('.body_carregar_meses_pagar_momento').append(`<tr>
                              <td>${response.mesesAdd[index].mes}</td>
                              <td class="text-center">${response.mesesAdd[index].quantidade}</td>
                              <td>${formatarValorMonetario(response.mesesAdd[index].preco)}<small> kz</small></td>
                              <td>${response.mesesAdd[index].taxa_id}<small> %</small></td>
                              <td>${formatarValorMonetario(response.mesesAdd[index].multa)}<small> kz</small></td>
                              <td>${response.mesesAdd[index].status}</td>
                              <td>${formatarValorMonetario(response.mesesAdd[index].total_pagar)}<small> kz</small></td>
                              <td>
                                <a href="#" id="${response.mesesAdd[index].id}" class="remover_mes_pagar_momento">
                                  <i class="fas fa-times text-danger"></i>
                                </a>
                              </td>
                            </tr>`);
                        }

                        $('.body_carregar_meses_pagar').html("");

                        for (let index = 0; index < response.cartao.length; index++) {
                            console.log(response.cartao[index].status)
                            if (response.cartao[index].status == 'divida' || response.cartao[index].status == 'Nao Pago') {
                                btn = 'display: inline-block';
                            } else {
                                btn = 'display: none';
                            }

                            $('.body_carregar_meses_pagar').append(`<tr>
                              <td>${nome_completo_mes(response.cartao[index].month_name)}</td>
                              <td>${response.cartao[index].status}</td>
                              <td class="text-center">
                                  <a href="#" id="${response.cartao[index].id}" class="btn btn-warning class_adicionar_mes" style="${btn}"><i class="fas fa-times text-danger"></i></a>
                              </td>
                            </tr>`);
                        }

                    } else if (response.servico.pagamento == 'unico') {

                        $('.valor').val(response.servico.preco);
                        $('.desconto').val(response.servico.desconto);
                        $('.multa').val(response.servico.multa);
                        $('.status_servico_pagar').val(response.servico.pagamento);

                        $("#carregarMesesPagamaentoPropinaAdicionar").html("");
                        $("#carregarMesesPagamaentoPropinaMomento").html("");

                        $('#form_campo_quantidade').css({
                            display: "inline-block"
                        });
                    }
                }, 
                error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        function nome_completo_mes(mes) {
            if (mes == "Sep") {
                return "Setembro";
            }

            if (mes == "Mar") {
                return "Março";
            }
            if (mes == "Apr") {
                return "Abril";
            }
            if (mes == "May") {
                return "Maio";
            }
            if (mes == "Jun") {
                return "Junho";
            }
            if (mes == "Jul") {
                return "Julho";
            }
            if (mes == "Aug") {
                return "Agosto";
            }
            if (mes == "Oct") {
                return "Outubro";
            }
            if (mes == "Nov") {
                return "Novembro";
            }
            if (mes == "Dec") {
                return "Dezembro";
            }
            if (mes == "Jan") {
                return "Janeiro";
            }
            if (mes == "Feb") {
                return "Fevereiro";
            } else {
                return mes
            }
        }

        $(document).on('click', '.class_adicionar_mes', function(e) {
            e.preventDefault();
            var id = $(this).attr("id");

            $.ajax({
                type: "GET"
                , url: `detalhes-pagamento-servico/${id}/${servicoPagar}` 
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    
                    $('.valor').val(response.somaVolores);
                    $('.multa').val(response.somaMulta);

                    $("#carregarMesesPagamaentoPropinaMomento").html("");
                    $("#carregarMesesPagamaentoPropinaMomento").append(`<h5 class="fs-6">Meses a pagar no momento</h5>
                    <table id="example1"  style="width: 100%" class="table table-bordered">
                        <thead>
                            <tr>
                              <th>Mês</th>
                              <th>Qtd</th>
                              <th>IVA</th>
                              <th>Multa</th>
                              <th>Preço Unitário</th>
                              <th>status</th>
                              <th>Total a Pagar</th>
                              <th>Acções</th>
                            </tr>
                        </thead>
                        <tbody class="body_carregar_meses_pagar_momento">
                        </tbody>
                        <tfoot>
                        <tr class="text-center">
                            <th>Total</th>
                            <th>${formatarValorMonetario(response.somaQuantidade)}</th>
                            <th></th>
                            <th></th>
                            <th>${formatarValorMonetario(response.somaMulta)}<small> kz</small></th>
                            <th></th>
                            <th>${formatarValorMonetario(response.somaVolores)}<small> kz</small></th>
                            <th></th>
                        </tr>
                      </tfoot>
                    </table>`);
                    
                    $('.body_carregar_meses_pagar_momento').html("");
                    
                    for (let index = 0; index < response.mesesAdd.length; index++) {
                        $('.body_carregar_meses_pagar_momento').append(`<tr>
                          <td>${response.mesesAdd[index].mes}</td>
                          <td class="text-center">${response.mesesAdd[index].quantidade}</td>
                          <td>${formatarValorMonetario(response.mesesAdd[index].preco)}<small> kz</small></td>
                          <td>${response.mesesAdd[index].taxa_id}<small> %</small></td>
                          <td>${formatarValorMonetario(response.mesesAdd[index].multa)}<small> kz</small></td>
                          <td>${response.mesesAdd[index].status}</td>
                          <td>${formatarValorMonetario(response.mesesAdd[index].total_pagar)}<small> kz</small></td>
                          <td>
                            <a href="#" id="${response.mesesAdd[index].id}" class="remover_mes_pagar_momento">
                              <i class="fas fa-times text-danger"></i>
                            </a>
                          </td>
                        </tr>`);
                    }


                    $('.body_carregar_meses_pagar').html("");
                    for (let index = 0; index < response.cartao.length; index++) {
                        if (response.cartao[index].status == 'divida' || response.cartao[index].status == 'Nao Pago') {
                            btn = 'display: inline-block';
                        } else {
                            btn = 'display: none';
                        }
                        $('.body_carregar_meses_pagar').append(`<tr>
                            <td>${response.cartao[index].month_name}</td>
                            <td>${response.cartao[index].status}</td>
                            <td class="text-center">
                                <a href="#" id="${response.cartao[index].id}" class="btn btn-warning class_adicionar_mes" style="${btn}"><i class="fas fa-times text-danger"></i></a>
                            </td>
                        </tr>`);
                    }
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        $(document).on('click', '.remover_mes_pagar_momento', function(e) {
            e.preventDefault();
            var id = $(this).attr("id");

            $.ajax({
                type: "GET"
                , url: `detalhes-pagamento-servico-remover-mes/${id}/${servicoPagar}`
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();

                    $('.valor').val(response.somaVolores);
                    $('.multa').val(response.somaMulta);

                    $("#carregarMesesPagamaentoPropinaMomento").html("");
                    $("#carregarMesesPagamaentoPropinaMomento").append(`<h5 class="fs-6">Meses a pagar no momento</h5>
                        <table id="example1" style="width: 100%" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mês</th>
                                <th>Qtd</th>
                                <th>IVA</th>
                                <th>Multa</th>
                                <th>Preço Unitário</th>
                                <th>status</th>
                                <th>Total a Pagar</th>
                                <th>Acções</th>
                            </tr>
                        </thead>
                        <tbody class="body_carregar_meses_pagar_momento">
                        </tbody>
                        <tfoot>
                        <tr class="text-center">
                            <th>Total</th>
                            <th>${formatarValorMonetario(response.somaQuantidade)}</th>
                            <th></th>
                            <th></th>
                            <th>${formatarValorMonetario(response.somaMulta)}<small> kz</small></th>
                            <th></th>
                            <th>${formatarValorMonetario(response.somaVolores)}<small> kz</small></th>
                            <th></th>
                        </tr>
                      </tfoot>
                      </table>`);

                    $('.body_carregar_meses_pagar_momento').html("");
                    for (let index = 0; index < response.mesesAdd.length; index++) {
                        $('.body_carregar_meses_pagar_momento').append(`<tr>
                            <td>${response.mesesAdd[index].mes}</td>
                            <td class="text-center">${response.mesesAdd[index].quantidade}</td>
                            <td>${formatarValorMonetario(response.mesesAdd[index].preco)}<small> kz</small></td>
                            <td>${response.mesesAdd[index].taxa_id}<small> %</small></td>
                            <td>${formatarValorMonetario(response.mesesAdd[index].multa)}<small> kz</small></td>
                            <td>${response.mesesAdd[index].status}</td>
                            <td>${formatarValorMonetario(response.mesesAdd[index].total_pagar)}<small> kz</small></td>
                            <td>
                              <a href="#" id="${response.mesesAdd[index].id}" class="remover_mes_pagar_momento">
                                <i class="fas fa-times text-danger"></i>
                              </a>
                            </td>
                        </tr>`);
                    }

                    $('.body_carregar_meses_pagar').html("");
                    for (let index = 0; index < response.cartao.length; index++) {
                        if (response.cartao[index].status == 'divida' || response.cartao[index].status == 'Nao Pago') {
                            btn = 'display: inline-block';
                        } else {
                            btn = 'display: none';
                        }
                        $('.body_carregar_meses_pagar').append(`<tr>
                            <td>${response.cartao[index].month_name}</td>
                            <td>${response.cartao[index].status}</td>
                            <td class="text-center">
                                <a href="#" id="${response.cartao[index].id}" class="btn btn-warning class_adicionar_mes" style="${btn}"><i class="fas fa-times text-danger"></i></a>
                            </td>
                        </tr>`);
                    }
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });
        /**/

    });

</script>
@endsection
