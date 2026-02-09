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
                    <h5><i class="fas fa-info"></i> Factuar para Serviços. Ex: Uniforme, Cartão, Boletins, declarações etc. Pode adicionar os meses e remove-los da lista dos meses a se pagar.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
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

                            <div class="form-group col-12  col-md-3">
                                <label for="factura">Selecione Factura</label>
                                <select name="factura" id="factura" class="form-control factura">
                                    <option value="FT">FACTURA</option>
                                    <option value="FP">FACTURA PRO-FORMA</option>
                                </select>
                                <span class="text-danger error-text factura_error"></span>
                            </div>

                            <div class="form-group col-12  col-md-3">
                                <label for="pagamento">Status Pagamento</label>
                                <select name="pagamento" id="pagamento" class="form-control pagamento">
                                    <option value="Pendente">Pendente</option>
                                </select>
                                <span class="text-danger error-text pagamento_error"></span>
                            </div>

                            <div class="form-group col-12 col-md-3">
                                <label for="valor">Valor Total a Pagar</label>
                                <input type="text" name="valor" class="form-control valor" value="" disabled placeholder="Valor do Pagamento">
                                <span class="text-danger error-text valor_error"></span>
                            </div>

                            <input type="hidden" value="{{ $turma->turmas_id ?? '' }}" class="turmas_id_seleciona" name="turmas_id_seleciona">

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

                            <div class="form-group col-12  col-md-3">
                                <label for="tipo_pagamento">Tipo Pagamento</label>
                                <select name="tipo_pagamento" id="tipo_pagamento" class="form-control tipo_pagamento" onchange="calcularTroco()">
                                    @foreach ($formas_pagamento as $item)
                                    <option value="{{ $item->sigla_tipo_pagamento }}">{{ $item->descricao }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text tipo_pagamento_error"></span>
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
                                <input type="date" name="data_desponibilizacao" class="form-control data_desponibilizacao" value="{{ date(" Y-m-d") }}">
                                <span class="text-danger error-text data_desponibilizacao_error"></span>
                            </div>

                            <div class="form-group col-12  col-md-3">
                                <label for="caixa">Caixa Principal</label>
                                <select name="caixa" id="caixa" class="form-control caixa">
                                    @if ($caixas)
                                    @foreach ($caixas as $caixa)
                                    <option value="{{ $caixa->id }}">{{ $caixa->caixa }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span class="text-danger error-text caixa_error"></span>
                            </div>

                            <div class="form-group col-12  col-md-3" id="form_campo_multa" style="display: none;">
                                <label for="aplicacao_multa">Aplicar Multa</label>
                                <select name="pagamento" id="pagamento" class="form-control aplicacao_multa">
                                    <option value="nao">Não</option>
                                    <option value="sim">Sim</option>
                                </select>
                                <span class="text-danger error-text aplicacao_multa_error"></span>
                            </div>

                            <input type="hidden" name="estudantes_id" class="estudantes_id" value="{{ $estudantes->id }}">
                            {{-- unico ou mensal --}}
                            <input type="hidden" name="status_servico_pagar" class="status_servico_pagar">

                            <div class="form-group col-12 col-md-12">
                                <label for="observacao">Observação (opcional)</label>
                                <textarea type="number" name="observacao" class="form-control observacao" rows="3" placeholder="Informe uma descrição:"></textarea>
                                <span class="text-danger error-text observacao_error"></span>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary pagamentoPropinaAJAX">Finalizar Pagamento</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="carregarMesesPagamaentoPropinaMomento"></div>
                    </div>
                </div>
                {{-- carregamento automatica --}}
            </div>

            <div class="col-md-4 col-12">
                {{-- carregamento automatico --}}
                <div class="card">
                    <div class="card-body">
                        <div id="carregarMesesPagamaentoPropinaAdicionar"></div>
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
    var servicoPagar;
    // COLLETAR PAGAMENTO
    $(document).on('click', '.pagamentoPropinaAJAX', function(e) {
        e.preventDefault();

        var data = {
            'factura': $('.factura').val()
            , 'pagamento': $('.pagamento').val()
            , 'tipo_pagamento': $('.tipo_pagamento').val()
            , 'valor_total_pagar': $('.valor').val()
            , 'servico': $('.servico').val()
            , 'estudantes_id': $('.estudantes_id').val()
            , 'data_vencimento': $('.data_vencimento').val()
            , 'data_desponibilizacao': $('.data_desponibilizacao').val()
            , 'caixa': $('.caixa').val()
            , 'multa': $('.multa').val()
            , 'turma': $('.turmas_id_seleciona').val()
            , 'quantidade': $('.quantidade').val()
            , 'aplicacao_multa': $('.aplicacao_multa').val()
            , 'observacao': $('.observacao').val(),
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
                // // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {
                Swal.close();
                window.open('../../download/factura-pagamento-servico/' + response.ficha, "_blank");
            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        });
    });


    $(document).on('change', '.servico', function(e) {
        e.preventDefault();
        var servicoId = $(this).val();
        servicoPagar = servicoId;
        let estudanteId = $('.estudantes_id').val();
        let turma_id = $('.turmas_id_seleciona').val();

        $.ajax({
            type: "GET"
            , url: `../../estudantes/carregar-servicos-cartao?estudante_id=${estudanteId}&turma_id=${turmaId}&servico_id=${servicoId}`
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {
                Swal.close();
                var iconeStatus;
                $('#form_campo_multa').css({
                    display: "inline-block"
                });

                if (response.servico.pagamento == 'mensal') {

                    $('.valor').val(response.totalAPagar + response.somaMulta);
                    $('.desconto').val(response.totalDesconto);
                    $('.multa').val(response.somaMulta);
                    $('.status_servico_pagar').val(response.servico.pagamento);
                    var soma_total_a_pagar_e_multa = parseFloat(response.totalAPagar) + parseFloat(response.somaMulta);

                    $('#form_campo_quantidade').css({
                        display: "none"
                    });
                    // meses ue pretendo adcionar
                    $("#carregarMesesPagamaentoPropinaAdicionar").html("");
                    $("#carregarMesesPagamaentoPropinaAdicionar").append('<h5 class="fs-6">Listagem dos Meses ' + response.servico.servico + ' Não pago</h5>\
                        <table id="example1"  style="width: 100%" class="table table-bordered  ">\
                          <thead>\
                                <tr>\
                                  <th>Mês</th>\
                                  <th>status</th>\
                                  <th>Preço</th>\
                                  <th>Valor Em Falta</th>\
                                  <th>Desconto</th>\
                                  <th>Multa</th>\
                                  <th>Acção</th>\
                                </tr>\
                            </thead>\
                            <tbody class="body_carregar_meses_pagar">\
                            </tbody>\
                      </table>');

                    // meses a pagar no memento
                    $("#carregarMesesPagamaentoPropinaMomento").html("");
                    $("#carregarMesesPagamaentoPropinaMomento").append('<h5 class="fs-6">Meses a pagar no momento</h5>\
                          <table id="example1"  style="width: 100%" class="table table-bordered  ">\
                          <thead>\
                              <tr>\
                                <th>Mês</th>\
                                <th>Quantidade</th>\
                                <th>Multa</th>\
                                <th>Valor Unitário</th>\
                                <th>Valor a Pagar</th>\
                                <th>Desconto</th>\
                                <th>status</th>\
                                <th>Acções</th>\
                              </tr>\
                          </thead>\
                          <tbody class="body_carregar_meses_pagar_momento">\
                          </tbody>\
                          <tfoot>\
                          <tr class="text-center">\
                            <th rowspan="2">Total</th>\
                            <th>' + response.somaQuantidade + '</th>\
                            <th>' + response.somaMulta + '<small> kz</small></th>\
                            <th>' + response.somaVolores + '<small> kz</small></th>\
                            <th>' + response.totalAPagar + '<small> kz</small></th>\
                            <th>------</th>\
                            <th>------</th>\
                            <th>------</th>\
                          </tr>\
                          <tr class="text-center">\
                            <th>-----</th>\
                            <th>-----</th>\
                            <th>-----</th>\
                            <th>------</th>\
                            <th>------</th>\
                            <th>TOTAL A PAGAR</th>\
                            <th><h4>' + soma_total_a_pagar_e_multa + '</h4></th>\
                          </tr>\
                        </tfoot>\
                    </table>');

                    $('.body_carregar_meses_pagar_momento').html("");

                    if (response.bolseiro == null) {
                        for (let index = 0; index < response.mesesAdd.length; index++) {
                            $('.body_carregar_meses_pagar_momento').append('<tr>\
                        <td>' + response.mesesAdd[index].mes + '</td>\
                        <td class="text-center">' + response.mesesAdd[index].quantidade + '</td>\
                        <td>' + response.mesesAdd[index].multa + '<small> kz</small></td>\
                        <td>' + response.mesesAdd[index].preco + '<small> kz</small></td>\
                        <td>' + response.mesesAdd[index].total_pagar + '<small> kz</small></td>\
                        <td>' + response.mesesAdd[index].desconto + '<small> kz</small></td>\
                        <td>' + response.mesesAdd[index].status + '</td>\
                        <td>\
                          <a href="#" id="' + response.mesesAdd[index].id + '" class="remover_mes_pagar_momento">\
                            <i class="fas fa-times text-danger"></i>\
                          </a>\
                        </td>\
                      </tr> ');
                        }
                    } else

                    if (response.bolseiro.instituicao_bolsa.desconto == 100) {
                        for (let index = 0; index < response.mesesAdd.length; index++) {
                            $('.body_carregar_meses_pagar_momento').append('<tr>\
                                  <td>' + response.mesesAdd[index].mes + '</td>\
                                  <td class="text-center">' + response.mesesAdd[index].quantidade + '</td>\
                                  <td>' + response.mesesAdd[index].multa + '<small> kz</small></td>\
                                  <td>' + response.mesesAdd[index].preco + '<small> kz</small></td>\
                                  <td>' + response.mesesAdd[index].total_pagar + '<small> kz</small></td>\
                                  <td>' + response.mesesAdd[index].total_pagar + '<small> kz</small></td>\
                                  <td>' + response.mesesAdd[index].desconto + '<small> kz</small></td>\
                                  <td>' + response.mesesAdd[index].status + '</td>\
                                  <td>\
                                    <a href="#" id="' + response.mesesAdd[index].id + '" class="remover_mes_pagar_momento">\
                                      <i class="fas fa-times text-danger"></i>\
                                    </a>\
                                  </td>\
                              </tr> ');
                        }
                    } else

                    if (response.bolseiro.instituicao_bolsa.desconto != 100) {
                        for (let index = 0; index < response.mesesAdd.length; index++) {
                            $('.body_carregar_meses_pagar_momento').append('<tr>\
                            <td>' + response.mesesAdd[index].mes + '</td>\
                            <td class="text-center">' + response.mesesAdd[index].quantidade + '</td>\
                            <td>' + response.mesesAdd[index].multa + '<small> kz</small></td>\
                            <td>' + response.mesesAdd[index].preco + '<small> kz</small></td>\
                            <td>' + response.mesesAdd[index].total_pagar + '<small> kz</small></td>\
                            <td>' + response.mesesAdd[index].desconto + ' %</td>\
                            <td>' + response.mesesAdd[index].status + '</td>\
                            <td>\
                              <a href="#" id="' + response.mesesAdd[index].id + '" class="remover_mes_pagar_momento">\
                                <i class="fas fa-times text-danger"></i>\
                              </a>\
                            </td>\
                        </tr> ');
                        }
                    }

                    $('.body_carregar_meses_pagar').html("");

                    if (response.bolseiro == null) {
                        for (let index = 0; index < response.cartao.length; index++) {
                            if (response.cartao[index].status == 'divida' || response.cartao[index].status == 'Nao Pago') {
                                var btn = 'display: inline-block';
                            } else {
                                var btn = 'display: none';
                            }
                            $('.body_carregar_meses_pagar').append('<tr>\
                                <td>' + response.cartao[index].month_name + '</td>\
                                <td>' + response.cartao[index].status + '</td>\
                                <td>' + response.servico.preco + '</td>\
                                <td>' + response.servico.preco + '</td>\
                                <td> 0% </td>\
                                <td>' + response.cartao[index].multa + '</td>\
                                <td class="text-center">\
                                    <a href="#" id="' + response.cartao[index].id + '" class="btn btn-warning class_adicionar_mes" style="' + btn + '"><i class="fas fa-times text-danger"></i></a>\
                                </td>\
                            </tr>');
                        }
                    } else if (response.bolseiro.instituicao_bolsa.desconto == 100) {
                        for (let index = 0; index < response.cartao.length; index++) {
                            if (response.cartao[index].status == 'divida' || response.cartao[index].status == 'Nao Pago') {
                                var btn = 'display: inline-block';
                            } else {
                                var btn = 'display: none';
                            }
                            $('.body_carregar_meses_pagar').append('<tr>\
                                <td>' + response.cartao[index].month_name + '</td>\
                                <td>' + response.cartao[index].status + '</td>\
                                <td>' + response.servico.preco + '</td>\
                                <td>' + response.servico.preco + '</td>\
                                <td>0%</td>\
                                <td>' + response.cartao[index].multa + '</td>\
                                <td class="text-center">\
                                    <a href="#" id="' + response.cartao[index].id + '" class="btn btn-warning class_adicionar_mes" style="' + btn + '"><i class="fas fa-times text-danger"></i></a>\
                                </td>\
                            </tr>');
                        }
                    } else if (response.bolseiro.instituicao_bolsa.desconto != 100) {
                        for (let index = 0; index < response.cartao.length; index++) {
                            if (response.cartao[index].status == 'Pago' || response.cartao[index].status == 'Nao Pago') {
                                var btn = 'display: inline-block';
                            } else {
                                var btn = 'display: none';
                            }

                            var valor_em_falta;
                            var desconto;

                            if (response.bolseiro.periodo.trimestre == "Iª Trimestre") {

                                if (response.cartao[index].trimestral == "1º Trimestre") {
                                    valor_em_falta = (response.servico.preco - (response.servico.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                                    desconto = response.bolseiro.instituicao_bolsa.desconto;
                                } else {
                                    valor_em_falta = response.servico.preco;
                                    desconto = 0;
                                }

                            }

                            if (response.bolseiro.periodo.trimestre == "IIª Trimestre") {
                                if (response.cartao[index].trimestral == "2º Trimestre") {
                                    valor_em_falta = (response.servico.preco - (response.servico.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                                    desconto = response.bolseiro.instituicao_bolsa.desconto;
                                } else {
                                    valor_em_falta = response.servico.preco;
                                    desconto = 0;
                                }
                            }

                            if (response.bolseiro.periodo.trimestre == "IIIª Trimestre") {
                                if (response.cartao[index].trimestral == "3º Trimestre") {
                                    valor_em_falta = (response.servico.preco - (response.servico.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                                    desconto = response.bolseiro.instituicao_bolsa.desconto;
                                } else {
                                    valor_em_falta = response.servico.preco;
                                    desconto = 0;
                                }
                            }

                            if (response.bolseiro.periodo.trimestre == "Geral") {
                                valor_em_falta = (response.servico.preco - (response.servico.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                                desconto = response.bolseiro.instituicao_bolsa.desconto;
                            }

                            if (response.bolseiro.periodo.trimestre == "Iª Simestre") {
                                if (response.cartao[index].semestral == "1º Semestre") {
                                    valor_em_falta = (response.servico.preco - (response.servico.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                                    desconto = response.bolseiro.instituicao_bolsa.desconto;
                                } else {
                                    valor_em_falta = response.servico.preco;
                                    desconto = 0;
                                }
                            }

                            if (response.bolseiro.periodo.trimestre == "IIª Simestre") {

                                if (response.cartao[index].semestral == "2º Semestre") {
                                    valor_em_falta = (response.servico.preco - (response.servico.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                                    desconto = response.bolseiro.instituicao_bolsa.desconto;
                                } else {
                                    valor_em_falta = response.servico.preco;
                                    desconto = 0;
                                }

                            }

                            if (response.bolseiro.periodo.trimestre == "Anual") {
                                valor_em_falta = (response.servico.preco - (response.servico.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                                desconto = response.bolseiro.instituicao_bolsa.desconto;
                            }

                            $('.body_carregar_meses_pagar').append('<tr>\
                                <td>' + response.cartao[index].month_name + '</td>\
                                <td>' + response.cartao[index].status + '</td>\
                                <td>' + response.servico.preco + '</td>\
                                <td>' + valor_em_falta + '</td>\
                                <td>' + desconto + ' %</td>\
                                <td>' + response.cartao[index].multa + '</td>\
                                <td class="text-center">\
                                    <a href="#" id="' + response.cartao[index].id + '" class="btn btn-warning class_adicionar_mes" style="' + btn + '"><i class="fas fa-times text-danger"></i></a>\
                                </td>\
                            </tr>');
                        }
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
        let estudanteId = $('.estudantes_id').val();

        $.ajax({
            type: "GET"
            , url: `../../estudantes/detalhes-pagamento-propina/${id}/${estudanteId}/${servicoPagar}`
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {
                Swal.close();
                if (response.status == 200) {

                    $('.valor').val(response.totalAPagar + response.somaMulta);
                    $('.desconto').val(response.totalDesconto);
                    $('.multa').val(response.somaMulta);

                    var soma_total_a_pagar_e_multa = parseFloat(response.totalAPagar) + parseFloat(response.somaMulta);


                    $("#carregarMesesPagamaentoPropinaMomento").html("");
                    $("#carregarMesesPagamaentoPropinaMomento").append('<h1 class="fs-3">Meses a pagar no momento</h1>\
                      <table id="example1"  style="width: 100%" class="table table-bordered  ">\
                        <thead>\
                            <tr>\
                              <th>Mês</th>\
                              <th>Quantidade</th>\
                              <th>Multa</th>\
                              <th>Valor Unitário</th>\
                              <th>Valor a Pagar</th>\
                              <th>Desconto</th>\
                              <th>status</th>\
                              <th>Acções</th>\
                            </tr>\
                        </thead>\
                        <tbody class="body_carregar_meses_pagar_momento">\
                        </tbody>\
                        <tfoot>\
                          <tr class="text-center">\
                              <th rowspan="2">Total</th>\
                              <th>' + response.somaQuantidade + '</th>\
                              <th>' + response.somaMulta + '<small> kz</small></th>\
                              <th>' + response.somaVolores + '<small> kz</small></th>\
                              <th>' + response.totalAPagar + '<small> kz</small></th>\
                              <th>------</th>\
                              <th>------</th>\
                              <th>------</th>\
                            </tr>\
                            <tr class="text-center">\
                              <th>-----</th>\
                              <th>-----</th>\
                              <th>-----</th>\
                              <th>------</th>\
                              <th>------</th>\
                              <th>TOTAL A PAGAR</th>\
                              <th><h4>' + soma_total_a_pagar_e_multa + '</h4></th>\
                            </tr>\
                      </tfoot>\
                    </table>');


                    $('.body_carregar_meses_pagar_momento').html("");
                    for (let index = 0; index < response.mesesAdd.length; index++) {
                        $('.body_carregar_meses_pagar_momento').append('<tr>\
                          <td>' + response.mesesAdd[index].mes + '</td>\
                          <td class="text-center">' + response.mesesAdd[index].quantidade + '</td>\
                          <td>' + response.mesesAdd[index].multa + '<small> kz</small></td>\
                          <td>' + response.mesesAdd[index].preco + '<small> kz</small></td>\
                          <td>' + response.mesesAdd[index].total_pagar + '<small> kz</small></td>\
                          <td>' + response.mesesAdd[index].desconto + ' %</td>\
                          <td>' + response.mesesAdd[index].status + '</td>\
                          <td>\
                            <a href="#" id="' + response.mesesAdd[index].id + '" class="remover_mes_pagar_momento">\
                              <i class="fas fa-times text-danger"></i>\
                            </a>\
                          </td>\
                        </tr> ');
                    }

                    $('.body_carregar_meses_pagar').html("");

                    if (response.bolseiro == null) {

                        for (let index = 0; index < response.cartao.length; index++) {
                            if (response.cartao[index].status == 'divida' || response.cartao[index].status == 'Nao Pago') {
                                var btn = 'display: inline-block';
                            } else {
                                var btn = 'display: none';
                            }
                            $('.body_carregar_meses_pagar').append('<tr>\
                              <td>' + response.cartao[index].month_name + '</td>\
                              <td>' + response.cartao[index].status + '</td>\
                              <td>' + response.servico_turma.preco + '</td>\
                              <td>' + response.servico_turma.preco + '</td>\
                              <td>0 %</td>\
                              <td>' + response.cartao[index].multa + '</td>\
                              <td class="text-center">\
                                  <a href="#" id="' + response.cartao[index].id + '" class="btn btn-warning class_adicionar_mes" style="' + btn + '"><i class="fas fa-times text-danger"></i></a>\
                              </td>\
                            </tr>');
                        }
                    } else
                    if (response.bolseiro.instituicao_bolsa.desconto == 100) {

                        for (let index = 0; index < response.cartao.length; index++) {
                            if (response.cartao[index].status == 'divida' || response.cartao[index].status == 'Nao Pago') {
                                var btn = 'display: inline-block';
                            } else {
                                var btn = 'display: none';
                            }
                            $('.body_carregar_meses_pagar').append('<tr>\
                              <td>' + response.cartao[index].month_name + '</td>\
                              <td>' + response.cartao[index].status + '</td>\
                              <td>' + response.servico_turma.preco + '</td>\
                              <td>' + response.servico_turma.preco + '</td>\
                              <td>0%</td>\
                              <td>' + response.cartao[index].multa + '</td>\
                              <td class="text-center">\
                                  <a href="#" id="' + response.cartao[index].id + '" class="btn btn-warning class_adicionar_mes" style="' + btn + '"><i class="fas fa-times text-danger"></i></a>\
                              </td>\
                            </tr>');
                        }
                    } else
                    if (response.bolseiro.instituicao_bolsa.desconto != 100) {
                        for (let index = 0; index < response.cartao.length; index++) {
                            if (response.cartao[index].status == 'Pago' || response.cartao[index].status == 'Nao Pago') {
                                var btn = 'display: inline-block';
                            } else {
                                var btn = 'display: none';
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

                            if (response.bolseiro.periodo.trimestre == "Geral") {

                                valor_em_falta = (response.servico_turma.preco - (response.servico_turma.preco * (response.bolseiro.instituicao_bolsa.desconto / 100)));
                                desconto = response.bolseiro.instituicao_bolsa.desconto;

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


                            $('.body_carregar_meses_pagar').append('<tr>\
                              <td>' + response.cartao[index].month_name + '</td>\
                              <td>' + response.cartao[index].status + '</td>\
                              <td>' + response.servico_turma.preco + '</td>\
                              <td>' + valor_em_falta + '</td>\
                              <td>' + desconto + ' %</td>\
                              <td>' + response.cartao[index].multa + '</td>\
                              <td class="text-center">\
                                  <a href="#" id="' + response.cartao[index].id + '" class="btn btn-warning class_adicionar_mes" style="' + btn + '"><i class="fas fa-times text-danger"></i></a>\
                              </td>\
                            </tr>');
                        }

                    }

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
        let estudanteId = $('.estudantes_id').val();

        $.ajax({
            type: "GET"
            , url: `../../estudantes/detalhes-pagamento-propina-remover-mes/${id}/${estudanteId}/${servicoPagar}`
            , beforeSend: function() {
                // Você pode adicionar um loader aqui, se necessário
                progressBeforeSend();
            }
            , success: function(response) {
                Swal.close();
                if (response.status == 200) {

                    $('.valor').val(response.totalAPagar + response.somaMulta);
                    $('.multa').val(response.somaMulta);
                    $('.desconto').val(response.totalDesconto);

                    var soma_total_a_pagar_e_multa = parseFloat(response.totalAPagar) + parseFloat(response.somaMulta);

                    $("#carregarMesesPagamaentoPropinaMomento").html("");
                    $("#carregarMesesPagamaentoPropinaMomento").append('<h1 class="fs-3">Meses a pagar no momento</h1>\
                      <table id="example1"  style="width: 100%" class="table table-bordered  ">\
                          <thead>\
                              <tr>\
                                <th>Mês</th>\
                                <th>Quantidade</th>\
                                <th>Multa</th>\
                                <th>Valor Unitário</th>\
                                <th>Valor a Pagar</th>\
                                <th>Desconto</th>\
                                <th>status</th>\
                                <th>Acções</th>\
                              </tr>\
                          </thead>\
                          <tbody class="body_carregar_meses_pagar_momento">\
                          </tbody>\
                          <tfoot>\
                              <tr class="text-center">\
                              <th rowspan="2">Total</th>\
                              <th>' + response.somaQuantidade + '</th>\
                              <th>' + response.somaMulta + '<small> kz</small></th>\
                              <th>' + response.somaVolores + '<small> kz</small></th>\
                              <th>' + response.totalAPagar + '<small> kz</small></th>\
                              <th>------</th>\
                              <th>------</th>\
                              <th>------</th>\
                            </tr>\
                            <tr class="text-center">\
                              <th>-----</th>\
                              <th>-----</th>\
                              <th>-----</th>\
                              <th>------</th>\
                              <th>------</th>\
                              <th>TOTAL A PAGAR</th>\
                              <th><h4>' + soma_total_a_pagar_e_multa + '</h4></th>\
                            </tr>\
                        </tfoot>\
                      </table>');

                    $('.body_carregar_meses_pagar_momento').html("");
                    for (let index = 0; index < response.mesesAdd.length; index++) {
                        $('.body_carregar_meses_pagar_momento').append('<tr>\
                      <td>' + response.mesesAdd[index].mes + '</td>\
                      <td class="text-center">' + response.mesesAdd[index].quantidade + '</td>\
                      <td>' + response.mesesAdd[index].multa + '<small> kz</small></td>\
                      <td>' + response.mesesAdd[index].preco + '<small> kz</small></td>\
                      <td>' + response.mesesAdd[index].total_pagar + '<small> kz</small></td>\
                      <td>' + response.mesesAdd[index].desconto + ' %</td>\
                      <td>' + response.mesesAdd[index].status + '</td>\
                      <td>\
                        <a href="#" id="' + response.mesesAdd[index].id + '" class="remover_mes_pagar_momento">\
                          <i class="fas fa-times text-danger"></i>\
                        </a>\
                      </td>\
                    </tr> ');
                    }

                    $('.body_carregar_meses_pagar').html("");

                    if (response.bolseiro == null) {
                        for (let index = 0; index < response.cartao.length; index++) {
                            if (response.cartao[index].status == 'divida' || response.cartao[index].status == 'Nao Pago') {
                                var btn = 'display: inline-block';
                            } else {
                                var btn = 'display: none';
                            }
                            $('.body_carregar_meses_pagar').append('<tr>\
                            <td>' + response.cartao[index].month_name + '</td>\
                            <td>' + response.cartao[index].status + '</td>\
                            <td>' + response.servico_turma.preco + '</td>\
                            <td>' + response.servico_turma.preco + '</td>\
                            <td>0</td>\
                            <td>' + response.cartao[index].multa + '</td>\
                            <td class="text-center">\
                                <a href="#" id="' + response.cartao[index].id + '" class="btn btn-warning class_adicionar_mes" style="' + btn + '"><i class="fas fa-times text-danger"></i></a>\
                            </td>\
                          </tr>');
                        }
                    }
                    if (response.bolseiro.instituicao_bolsa.desconto == 100) {
                        for (let index = 0; index < response.cartao.length; index++) {
                            if (response.cartao[index].status == 'divida' || response.cartao[index].status == 'Nao Pago') {
                                var btn = 'display: inline-block';
                            } else {
                                var btn = 'display: none';
                            }
                            $('.body_carregar_meses_pagar').append('<tr>\
                              <td>' + response.cartao[index].month_name + '</td>\
                              <td>' + response.cartao[index].status + '</td>\
                              <td>' + response.servico_turma.preco + '</td>\
                              <td>' + response.servico_turma.preco + '</td>\
                              <td>0%</td>\
                              <td>' + response.cartao[index].multa + '</td>\
                              <td class="text-center">\
                                  <a href="#" id="' + response.cartao[index].id + '" class="btn btn-warning class_adicionar_mes" style="' + btn + '"><i class="fas fa-times text-danger"></i></a>\
                              </td>\
                            </tr>');
                        }
                    }
                    if (response.bolseiro.instituicao_bolsa.desconto != 100) {
                        for (let index = 0; index < response.cartao.length; index++) {
                            if (response.cartao[index].status == 'Pago' || response.cartao[index].status == 'Nao Pago') {
                                var btn = 'display: inline-block';
                            } else {
                                var btn = 'display: none';
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


                            $('.body_carregar_meses_pagar').append('<tr>\
                              <td>' + response.cartao[index].month_name + '</td>\
                              <td>' + response.cartao[index].status + '</td>\
                              <td>' + response.servico_turma.preco + '</td>\
                              <td>' + valor_em_falta + '</td>\
                              <td>' + desconto + ' %</td>\
                              <td>' + response.cartao[index].multa + '</td>\
                              <td class="text-center">\
                                  <a href="#" id="' + response.cartao[index].id + '" class="btn btn-warning class_adicionar_mes" style="' + btn + '"><i class="fas fa-times text-danger"></i></a>\
                              </td>\
                            </tr>');
                        }
                    }
                }
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
