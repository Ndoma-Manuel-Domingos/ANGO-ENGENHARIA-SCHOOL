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
                <h1 class="m-0 text-dark">Criar documentos</h1>
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
                <div class="card">
                    <form action="{{ route('web.facturar-pagamento-servico-create') }}" method="post">
                        @csrf
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <div class="row" id="myForm">

                                <div class="form-group col-12  col-md-3">
                                    <label for="destino_factura">Destino da Factura</label>
                                    <select name="destino_factura" id="destino_factura" class="form-control destino_factura">
                                        <option value="">Selecione</option>
                                        <option value="estudante">Cliente/Estudante</option>
                                        <option value="escola">Escola</option>
                                    </select>
                                    <span class="text-danger error-text destino_factura_error"></span>
                                </div>

                                <div class="form-group col-12  col-md-3">
                                    <label for="estudantes_id">Cliente/Estudante</label>
                                    <select name="estudantes_id" id="estudantes_id" class="form-control ref_cliente select2">
                                        <option value="">Selecione</option>
                                    </select>
                                    <span class="text-danger error-text servico_error"></span>
                                </div>

                                <div class="form-group col-12  col-md-3">
                                    <label for="servico">Serviço</label>
                                    <select name="servico" id="servico" class="form-control servico select2">
                                        <option value="">Selecione</option>
                                        @if ($servicos)
                                        @foreach ($servicos as $servico)
                                        <option value="{{ $servico->id }}">{{ $servico->servico }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <span class="text-danger error-text servico_error"></span>
                                </div>

                                <div class="form-group col-12  col-md-3">
                                    <label for="factura">Tipo de Documento <span class="text-danger">*</span></label>
                                    <select name="factura" id="factura" class="form-control factura">
                                        <option value="FT" {{ old('factura') == "FT" ? 'selected' : '' }}>Factura</option>
                                        <option value="FP" {{ old('factura') == "FP" ? 'selected' : '' }}>Factura Pró-forma</option>
                                    </select>
                                    <span class="text-danger error-text factura_error"></span>
                                </div>

                                <div class="form-group col-12  col-md-3">
                                    <label for="forma_pagamento">Pagamento</label>
                                    <select name="forma_pagamento" id="forma_pagamento" class="form-control forma_pagamento">
                                        @foreach ($forma_pagamentos as $item)
                                        <option value="{{ $item->sigla_tipo_pagamento }}" {{ $item->sigla_tipo_pagamento == "NU" ? "selected" : "" }}>{{ $item->descricao }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text forma_pagamento_error"></span>
                                </div>

                                <div class="form-group col-12  col-md-3">
                                    <label for="data_vencimento">Data Vencimento</label>
                                    <select name="data_vencimento" id="data_vencimento" class="form-control data_vencimento">
                                        <option value="0">A Pronto</option>
                                        <option value="15">A Pronto de 15 Dias</option>
                                        <option value="30">A Pronto de 30 Dias</option>
                                        <option value="45">A Pronto de 45 Dias</option>
                                        <option value="60">A Pronto de 60 Dias</option>
                                        <option value="90">A Pronto de 90 Dias</option>
                                    </select>
                                    <span class="text-danger error-text data_vencimento_error"></span>
                                </div>

                                <div class="form-group col-12 col-md-3">
                                    <label for="data_desponibilizacao">Data disponibilização</label>
                                    <input type="date" name="data_desponibilizacao" class="form-control data_desponibilizacao" value="{{ date("Y-m-d") }}">
                                    <span class="text-danger error-text data_desponibilizacao_error"></span>
                                </div>

                                <div class="form-group col-12  col-md-3">
                                    <label for="caixa">Caixas</label>
                                    <select name="caixa" id="caixa" class="form-control caixa">
                                        @if ($caixas)
                                        @foreach ($caixas as $caixa)
                                        <option value="{{ $caixa->id }}">{{ $caixa->conta }} - {{ $caixa->caixa }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <span class="text-danger error-text caixa_error"></span>
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
                                    <label for="valor">Valor Total a Pagar</label>
                                    <input type="text" name="valor" class="form-control valor" value="" disabled placeholder="Valor do Pagamento">
                                    <span class="text-danger error-text valor_error"></span>
                                </div>

                                <input type="hidden" name="valor_a_pagar" value="" class="valor">

                                <div class="form-group col-12  col-md-3">
                                    <label for="desconto">Desconto</label>
                                    <input type="text" name="desconto" class="form-control desconto" value="0" placeholder="Informe o Desconto %">
                                    <span class="text-danger error-text desconto_error"></span>
                                </div>

                                <div class="form-group col-12  col-md-3">
                                    <label for="multa">Total de Multa</label>
                                    <input type="text" name="multa" class="form-control multa" placeholder="Informe a multa a pagar" disabled>
                                    <span class="text-danger error-text multa_error"></span>
                                </div>

                                <input type="hidden" name="valor_multa" value="" class="multa">

                                <div class="form-group col-12  col-md-3" id="form_campo_multa" style="display: none;">
                                    <label for="aplicacao_multa">Aplicar Multa</label>
                                    <select name="pagamento" id="pagamento" class="form-control aplicacao_multa">
                                        <option value="nao">Não</option>
                                        <option value="sim">Sim</option>
                                    </select>
                                    <span class="text-danger error-text aplicacao_multa_error"></span>
                                </div>

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

        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div id="carregarMesesPagamaentoPropinaMomento">
                        </div>
                    </div>
                </div>
                {{-- carregamento automatica --}}
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-footer">
                        <div class="form-group col-12 col-md-12" id="form_campo_quantidade" style="display: none">
                            <label for="quantidade">Quantidade</label>
                            <input type="number" name="quantidade" value="1" id="quantidade" class="form-control quantidade" placeholder="Informe o Desconto a quantidade a pagar">
                            <span class="text-danger error-text quantidade_error"></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="carregarMesesPagamaentoPropinaAdicionar">
                        </div>
                    </div>
                </div>
                {{-- carregamento automatico --}}
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
        $(document).on('click', '.pagamentoPropinaAJAXs', function(e) {
            e.preventDefault();

            var data = {
                'factura': $('.factura').val()
                , 'pagamento': $('.pagamento').val()
                , 'valor_total_pagar': $('.valor').val()
                , 'servico': $('.servico').val()
                , 'desconto': $('.desconto').val()
                , 'data_vencimento': $('.data_vencimento').val()
                , 'data_desponibilizacao': $('.data_desponibilizacao').val()
                , 'caixa': $('.caixa').val()
                , 'estudantes_id': $('.ref_cliente').val()
                , 'multa': $('.multa').val()
                , 'turma': $('.turmas_id_seleciona').val()
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
                , url: "{{ route('web.facturar-pagamento-servico-create') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    if (response.tipo_factura == "FP") {
                        window.open(`../download/factura-proforma/${response.ficha}/ORGINAL`, "_blank");
                    } else if (response.tipo_factura == "FT") {
                        window.open(`../download/factura-factura/${response.ficha}/ORGINAL`, "_blank");
                    }
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        $("#destino_factura").change(() => {
            let id = $("#destino_factura").val();
            $.get(`carregar-clientes/${id}`, function(data) {
                $("#estudantes_id").html("")
                $("#estudantes_id").html(data)
            })
        })

        $(document).on('change', '.servico', function(e) {
            e.preventDefault();

            var servico = $(this).val();
            var destino_factura = $('.destino_factura').val()
            var ref_cliente = $('.ref_cliente').val()

            $.ajax({
                type: "GET", 
                url: `carregar-servico/${destino_factura}/${ref_cliente}/${servico}`, 
                beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }, 
                success: function(response) {
                    Swal.close();
                    $('.valor').val(response.totalAPagar);
                    $('.desconto').val(response.totalDesconto);
                    $('.multa').val(response.somaMulta);
                    $('.status_servico_pagar').val(response.servico.pagamento);
                    var soma_total_a_pagar_e_multa = parseFloat(response.totalAPagar);

                    $('#form_campo_quantidade').css({
                        display: "block"
                    });

                    // meses ue pretendo adcionar
                    $("#carregarMesesPagamaentoPropinaAdicionar").html("");
                    $("#carregarMesesPagamaentoPropinaAdicionar").append(`<h5 class="fs-6">Listagem dos Meses ${response.servico.servico} Não pago</h5>
                        <table id="example1" style="width: 100%" class="table table-bordered  ">
                          <thead>
                                <tr>
                                  <th>Mês</th>
                                  <th>status</th>
                                  <th>Preço</th>
                                  <th>Valor Em Falta</th>
                                  <th>Desconto</th>
                                  <th>Multa</th>
                                  <th>Acção</th>
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
                              <th>Serviço</th>
                              <th>Referente</th>
                              <th>Quantidade</th>
                              <th>Multa</th>
                              <th>Valor Unitário</th>
                              <th>Valor a Pagar</th>
                              <th>Desconto</th>
                              <th>status</th>
                              <th>Acções</th>
                            </tr>
                        </thead>
                        <tbody class="body_carregar_meses_pagar_momento">
                        </tbody>
                        <tfoot>
                        <tr class="text-center">
                          <th rowspan="2">Total</th>
                          <th>------</th>
                          <th>${response.somaQuantidade}</th>
                          <th>${response.somaMulta}<small> kz</small></th>
                          <th>${response.somaVolores}<small> kz</small></th>
                          <th>${response.totalAPagar}<small> kz</small></th>
                          <th>------</th>
                          <th>------</th>
                          <th>------</th>
                        </tr>
                        <tr class="text-center">
                          <th>-----</th>
                          <th>-----</th>
                          <th>-----</th>
                          <th>-----</th>
                          <th>------</th>
                          <th>------</th>
                          <th>TOTAL A PAGAR</th>
                          <th><h4>${soma_total_a_pagar_e_multa}</h4></th>
                        </tr>
                      </tfoot>
                      </table>`);

                    $('.body_carregar_meses_pagar_momento').html("");

                    if (response.bolseiro == null) {
                        for (let index = 0; index < response.mesesAdd.length; index++) {
                            $('.body_carregar_meses_pagar_momento').append(`<tr>
                                <td>${response.mesesAdd[index].servico.servico}</td>
                                <td>${response.mesesAdd[index].mes}</td>
                                <td class="text-center">${response.mesesAdd[index].quantidade}</td>
                                <td>${response.mesesAdd[index].multa}<small> kz</small></td>
                                <td>${response.mesesAdd[index].preco}<small> kz</small></td>
                                <td>${response.mesesAdd[index].total_pagar}<small> kz</small></td>
                                <td>${response.mesesAdd[index].desconto}<small> kz</small></td>
                                <td>${response.mesesAdd[index].status}</td>
                                <td>
                                  <a href="#" id="${response.mesesAdd[index].id}" class="remover_mes_pagar_momento">
                                    <i class="fas fa-times text-danger"></i>
                                  </a>
                                </td>
                            </tr>`);
                        }
                    } else
                    if (response.bolseiro.instituicao_bolsa.desconto == 100) {
                        for (let index = 0; index < response.mesesAdd.length; index++) {
                            $('.body_carregar_meses_pagar_momento').append(`<tr>
                                <td>${response.mesesAdd[index].servico.servico}</td>
                                <td>${response.mesesAdd[index].mes}</td>
                                <td class="text-center">${response.mesesAdd[index].quantidade}</td>
                                <td>${response.mesesAdd[index].multa}<small> kz</small></td>
                                <td>${response.mesesAdd[index].preco}<small> kz</small></td>
                                <td>${response.mesesAdd[index].total_pagar}<small> kz</small></td>
                                <td>${response.mesesAdd[index].total_pagar}<small> kz</small></td>
                                <td>${response.mesesAdd[index].desconto}<small> kz</small></td>
                                <td>${response.mesesAdd[index].status}</td>
                                <td>
                                  <a href="#" id="${response.mesesAdd[index].id}" class="remover_mes_pagar_momento">
                                    <i class="fas fa-times text-danger"></i>
                                  </a>
                                </td>
                            </tr>`);
                        }
                    } else

                    if (response.bolseiro.instituicao_bolsa.desconto != 100) {
                        for (let index = 0; index < response.mesesAdd.length; index++) {
                            $('.body_carregar_meses_pagar_momento').append(`<tr>
                              <td>${response.mesesAdd[index].servico.servico}</td>
                              <td>${response.mesesAdd[index].mes}</td>
                              <td class="text-center">${response.mesesAdd[index].quantidade}</td>
                              <td>${response.mesesAdd[index].multa}<small> kz</small></td>
                              <td>${response.mesesAdd[index].preco}<small> kz</small></td>
                              <td>${response.mesesAdd[index].total_pagar}<small> kz</small></td>
                              <td>${response.mesesAdd[index].desconto} %</td>
                              <td>${response.mesesAdd[index].status}</td>
                              <td>
                                <a href="#" id="${response.mesesAdd[index].id}" class="remover_mes_pagar_momento">
                                  <i class="fas fa-times text-danger"></i>
                                </a>
                              </td>
                            </tr>`);
                        }
                    }
                    
                    renderTabelaMeses(response)
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        $(document).on('click', '.class_adicionar_mes', function(e) {
            e.preventDefault();
            var id = $(this).attr("id");

            var servico = $('.servico').val();
            var estudante = $('.ref_cliente').val();
            var quantidade = $('.quantidade').val();

            $.ajax({
                type: "GET", 
                url: `../../estudantes/detalhes-pagamento-propina/${id}/${estudante}/${servico}/${quantidade}`
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.valor').val(response.totalAPagar);
                    $('.desconto').val(response.totalDesconto);
                    $('.multa').val(response.somaMulta);

                    var soma_total_a_pagar_e_multa = parseFloat(response.totalAPagar);

                    $("#carregarMesesPagamaentoPropinaMomento").html("");
                    $("#carregarMesesPagamaentoPropinaMomento").append(`<h1 class="fs-3">Meses a pagar no momento</h1>
                      <table id="example1" style="width: 100%" class="table table-bordered">
                        <thead>
                            <tr>
                              <th>Serviço</th>
                              <th>Referente</th>
                              <th>Quantidade</th>
                              <th>Multa</th>
                              <th>Valor Unitário</th>
                              <th>Valor a Pagar</th>
                              <th>Desconto</th>
                              <th>status</th>
                              <th>Acções</th>
                            </tr>
                        </thead>
                        <tbody class="body_carregar_meses_pagar_momento">
                        </tbody>
                        <tfoot>
                          <tr class="text-center">
                          <th rowspan="2">Total</th>
                          <th>-----</th>
                          <th>${response.somaQuantidade}</th>
                          <th>${response.somaMulta}<small> kz</small></th>
                          <th>${response.somaVolores}<small> kz</small></th>
                          <th>${response.totalAPagar}<small> kz</small></th>
                          <th>------</th>
                          <th>------</th>
                          <th>------</th>
                        </tr>
                        <tr class="text-center">
                          <th>-----</th>
                          <th>-----</th>
                          <th>-----</th>
                          <th>-----</th>
                          <th>------</th>
                          <th>------</th>
                          <th>TOTAL A PAGAR</th>
                          <th><h4>${soma_total_a_pagar_e_multa}</h4></th>
                        </tr>
                      </tfoot>
                    </table>`);

                    $('.body_carregar_meses_pagar_momento').html("");
                    
                    for (let index = 0; index < response.mesesAdd.length; index++) {
                        $('.body_carregar_meses_pagar_momento').append(`<tr>
                            <td>${response.mesesAdd[index].servico.servico}</td>
                            <td>${response.mesesAdd[index].mes}</td>
                            <td class="text-center">${response.mesesAdd[index].quantidade}</td>
                            <td>${response.mesesAdd[index].multa}<small> kz</small></td>
                            <td>${response.mesesAdd[index].preco}<small> kz</small></td>
                            <td>${response.mesesAdd[index].total_pagar}<small> kz</small></td>
                            <td>${response.mesesAdd[index].desconto} %</td>
                            <td>${response.mesesAdd[index].status}</td>
                            <td>
                                <a href="#" id="${response.mesesAdd[index].id}" class="remover_mes_pagar_momento">
                                    <i class="fas fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>`);
                    }

                    $('.body_carregar_meses_pagar').html("");
                    
                    renderTabelaMeses(response)

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
            var servico = $('.servico').val();
            var estudante = $('.ref_cliente').val();
            var quantidade = $('.quantidade').val();

            $.ajax({
                type: "GET", 
                url: `../../estudantes/detalhes-pagamento-propina-remover-mes/${id}/${estudante}/${servico}/${quantidade}`, 
                beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }, 
                success: function(response) {
                    Swal.close();

                    $('.valor').val(response.totalAPagar);
                    $('.multa').val(response.somaMulta);
                    $('.desconto').val(response.totalDesconto);

                    var soma_total_a_pagar_e_multa = parseFloat(response.totalAPagar);
                    $("#carregarMesesPagamaentoPropinaMomento").html("");
                    
                    $("#carregarMesesPagamaentoPropinaMomento").append(`<h1 class="fs-3">Meses a pagar no momento</h1>
                      <table id="example1" style="width: 100%" class="table table-bordered">
                          <thead>
                              <tr>
                                <th>Serviço</th>
                                <th>Referente</th>
                                <th>Quantidade</th>
                                <th>Multa</th>
                                <th>Valor Unitário</th>
                                <th>Valor a Pagar</th>
                                <th>Desconto</th>
                                <th>status</th>
                                <th>Acções</th>
                              </tr>
                          </thead>
                          <tbody class="body_carregar_meses_pagar_momento">
                          </tbody>
                          <tfoot>
                              <tr class="text-center">
                              <th rowspan="2">Total</th>
                              <th>------</th>
                              <th>${response.somaQuantidade}</th>
                              <th>${response.somaMulta}<small> kz</small></th>
                              <th>${response.somaVolores}<small> kz</small></th>
                              <th>${response.totalAPagar}<small> kz</small></th>
                              <th>------</th>
                              <th>------</th>
                              <th>------</th>
                            </tr>
                            <tr class="text-center">
                              <th>-----</th>
                              <th>-----</th>
                              <th>-----</th>
                              <th>-----</th>
                              <th>------</th>
                              <th>------</th>
                              <th>TOTAL A PAGAR</th>
                              <th><h4>${soma_total_a_pagar_e_multa}</h4></th>
                            </tr>
                        </tfoot>
                      </table>`);

                    $('.body_carregar_meses_pagar_momento').html("");
                    for (let index = 0; index < response.mesesAdd.length; index++) {
                        $('.body_carregar_meses_pagar_momento').append(`<tr>
                            <td>${response.mesesAdd[index].servico.servico}</td>
                            <td>${response.mesesAdd[index].mes}</td>
                            <td class="text-center">${response.mesesAdd[index].quantidade}</td>
                            <td>${response.mesesAdd[index].multa}<small> kz</small></td>
                            <td>${response.mesesAdd[index].preco}<small> kz</small></td>
                            <td>${response.mesesAdd[index].total_pagar}<small> kz</small></td>
                            <td>${response.mesesAdd[index].desconto} %</td>
                            <td>${response.mesesAdd[index].status}</td>
                            <td>
                                <a href="#" id="${response.mesesAdd[index].id}" class="remover_mes_pagar_momento">
                                    <i class="fas fa-times text-danger"></i>
                                </a>
                            </td>
                        </tr>`);
                    }
                    
                    renderTabelaMeses(response)
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

    });

    function renderTabelaMeses(response) {
        $('.body_carregar_meses_pagar').html("");
    
        if (response.bolseiro == null) {
            for (let index = 0; index < response.cartao.length; index++) {
                if (response.cartao[index].status == 'divida' || response.cartao[index].status == 'Nao Pago') {
                    var btn = 'display: inline-block';
                } else {
                    var btn = 'display: none';
                }
    
                if (response.cartao[index].multa1 == "Y") {
                    var btn_adicionar_multa1 = 'display: none'
                    var btn_remover_multa1 = 'display: inline-block'
                } else {
                    var btn_adicionar_multa1 = 'display: inline-block'
                    var btn_remover_multa1 = 'display: none'
                }
    
                if (response.cartao[index].multa2 == "Y") {
                    var btn_adicionar_multa2 = 'display: none'
                    var btn_remover_multa2 = 'display: inline-block'
                } else {
                    var btn_adicionar_multa2 = 'display: inline-block'
                    var btn_remover_multa2 = 'display: none'
                }
    
                if (response.cartao[index].multa3 == "Y") {
                    var btn_adicionar_multa3 = 'display: none'
                    var btn_remover_multa3 = 'display: inline-block'
                } else {
                    var btn_adicionar_multa3 = 'display: inline-block'
                    var btn_remover_multa3 = 'display: none'
                }
                   
                $('.body_carregar_meses_pagar').append(`<tr>
                  <td>${response.cartao[index].month_name}</td>
                  <td>${response.cartao[index].status}</td>
                  <td>${response.servico_turma.preco}</td>
                  <td>${response.servico_turma.preco}</td>
                  <td>${response.cartao[index].desconto}</td>
                  <td>${response.cartao[index].multa}</td>
                  <td class="text-center">
                    <a href="#" id="${response.cartao[index].id}" class="btn btn-dark class_adicionar_mes" style="${btn}"><i class="fas fa-plus "></i></a>
                  </td>
                </tr>`);
            }
        } else
        if (response.bolseiro.instituicao_bolsa.desconto == 100) {
            for (let index = 0; index < response.cartao.length; index++) {
                if (response.cartao[index].status == 'divida' || response.cartao[index].status == 'Nao Pago') {
                    var btn = 'display: inline-block';
                } else {
                    var btn = 'display: none';
                }
    
                if (response.cartao[index].multa1 == "Y") {
                    var btn_adicionar_multa1 = 'display: none'
                    var btn_remover_multa1 = 'display: inline-block'
                } else {
                    var btn_adicionar_multa1 = 'display: inline-block'
                    var btn_remover_multa1 = 'display: none'
                }
    
                if (response.cartao[index].multa2 == "Y") {
                    var btn_adicionar_multa2 = 'display: none'
                    var btn_remover_multa2 = 'display: inline-block'
                } else {
                    var btn_adicionar_multa2 = 'display: inline-block'
                    var btn_remover_multa2 = 'display: none'
                }
    
                if (response.cartao[index].multa3 == "Y") {
                    var btn_adicionar_multa3 = 'display: none'
                    var btn_remover_multa3 = 'display: inline-block'
                } else {
                    var btn_adicionar_multa3 = 'display: inline-block'
                    var btn_remover_multa3 = 'display: none'
                }
    
                $('.body_carregar_meses_pagar').append(`<tr>
                  <td>${response.cartao[index].month_name}</td>
                  <td>${response.cartao[index].status}</td>
                  <td>${response.servico_turma.preco}</td>
                  <td>${response.servico_turma.preco}</td>
                  <td>0%</td>
                  <td>${response.cartao[index].multa}</td>
                  <td class="text-center">
                      <a href="#" id="${response.cartao[index].id}" class="btn btn-dark class_adicionar_mes" style="${btn}"><i class="fas fa-plus "></i></a>
                  </td>
                </tr>`);
            }
        } else
        if (response.bolseiro.instituicao_bolsa.desconto != 100) {
            for (let index = 0; index < response.cartao.length; index++) {
                if (response.cartao[index].status == 'Pago' || response.cartao[index].status == 'Nao Pago') {
                    var btn = 'display: inline-block';
                } else {
                    var btn = 'display: none';
                }
    
                if (response.cartao[index].multa1 == "Y") {
                    var btn_adicionar_multa1 = 'display: none'
                    var btn_remover_multa1 = 'display: inline-block'
                } else {
                    var btn_adicionar_multa1 = 'display: inline-block'
                    var btn_remover_multa1 = 'display: none'
                }
    
                if (response.cartao[index].multa2 == "Y") {
                    var btn_adicionar_multa2 = 'display: none'
                    var btn_remover_multa2 = 'display: inline-block'
                } else {
                    var btn_adicionar_multa2 = 'display: inline-block'
                    var btn_remover_multa2 = 'display: none'
                }
    
                if (response.cartao[index].multa3 == "Y") {
                    var btn_adicionar_multa3 = 'display: none'
                    var btn_remover_multa3 = 'display: inline-block'
                } else {
                    var btn_adicionar_multa3 = 'display: inline-block'
                    var btn_remover_multa3 = 'display: none'
                }
    
                var valor_em_falta;
                var desconto;
    
                if (response.bolseiro.periodo.trimestre == "Iª Trimestre") {
                    if (response.cartao[index].trimestral == "1º Trimestre") {
                        valor_em_falta = (response.servico_turma.preco - (response.servico_turma.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                        desconto = response.bolseiro.instituicao_bolsa.desconto;
                    } else {
                        valor_em_falta = response.servico_turma.preco;
                        desconto = 0;
                    }
                }
    
                if (response.bolseiro.periodo.trimestre == "IIª Trimestre") {
                    if (response.cartao[index].trimestral == "2º Trimestre") {
                        valor_em_falta = (response.servico_turma.preco - (response.servico_turma.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                        desconto = response.bolseiro.instituicao_bolsa.desconto;
                    } else {
                        valor_em_falta = response.servico_turma.preco;
                        desconto = 0;
                    }
                }
    
                if (response.bolseiro.periodo.trimestre == "IIIª Trimestre") {
    
                    if (response.cartao[index].trimestral == "3º Trimestre") {
                        valor_em_falta = (response.servico_turma.preco - (response.servico_turma.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                        desconto = response.bolseiro.instituicao_bolsa.desconto;
                    } else {
                        valor_em_falta = response.servico_turma.preco;
                        desconto = 0;
                    }
    
                }
    
                if (response.bolseiro.periodo.trimestre == "Geral") {
    
                    valor_em_falta = (response.servico_turma.preco - (response.servico_turma.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                    desconto = response.bolseiro.instituicao_bolsa.desconto;
    
                }
    
                if (response.bolseiro.periodo.trimestre == "Iª Simestre") {
    
                    if (response.cartao[index].semestral == "1º Semestre") {
                        valor_em_falta = (response.servico_turma.preco - (response.servico_turma.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                        desconto = response.bolseiro.instituicao_bolsa.desconto;
                    } else {
                        valor_em_falta = response.servico_turma.preco;
                        desconto = 0;
                    }
    
                }
    
                if (response.bolseiro.periodo.trimestre == "IIª Simestre") {
                    if (response.cartao[index].semestral == "2º Semestre") {
                        valor_em_falta = (response.servico_turma.preco - (response.servico_turma.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                        desconto = response.bolseiro.instituicao_bolsa.desconto;
                    } else {
                        valor_em_falta = response.servico_turma.preco;
                        desconto = 0;
                    }
                }
    
                if (response.bolseiro.periodo.trimestre == "Anual") {
                    valor_em_falta = (response.servico_turma.preco - (response.servico_turma.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                    desconto = response.bolseiro.instituicao_bolsa.desconto;
                }
                                      
                                      
                $('.body_carregar_meses_pagar').append(`<tr>
                  <td>${response.cartao[index].month_name}</td>
                  <td>${response.cartao[index].status}</td>
                  <td>${response.servico_turma.preco}</td>
                  <td>${valor_em_falta}</td>
                  <td>${desconto} %</td>
                  <td>${response.cartao[index].multa}</td>
                  <td class="text-center">
                        <a href="#" id="${response.cartao[index].id}" class="btn btn-dark class_adicionar_mes" style="${btn}"><i class="fas fa-plus "></i></a>
                  </td>
                </tr>`);
            }
        }
    }


</script>
@endsection
