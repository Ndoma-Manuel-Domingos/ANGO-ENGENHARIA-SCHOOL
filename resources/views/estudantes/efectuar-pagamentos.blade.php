@extends('layouts.estudantes')

@section('content')

<div class="content">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Efectuar Pagamentos</h1>
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

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info"></i> Pagamento de Propina entre outros serviços. Ex: Uniforme, Cartão, Boletins, declarações etc. Pode adicionar os meses e remove-los da lista dos meses a se pagar.</h5>
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

                                <div class="form-group col-12 col-md-3">
                                    <label for="valor">Valor Total a Pagar</label>
                                    <input type="number" name="valor" id="valor" class="form-control valor" disabled="" placeholder="Valor do Pagamento">
                                    <input type="hidden" name="valor_aguardado" id="valor2" class="form-control valor valor_total_a_pagar" value="">
                                    <span class="text-danger error-text valor_error"></span>
                                </div>

                                <input type="hidden" value="{{ $turma->turmas_id }}" class="turmas_id_seleciona" name="turmas_id_seleciona">

                                <div class="form-group col-12  col-md-3">
                                    <label for="multa">Multa</label>
                                    <input type="text" name="multa" class="form-control multa" placeholder="Informe a multa a pagar" disabled>
                                    <span class="text-danger error-text multa_error"></span>
                                </div>

                                <div class="form-group col-12  col-md-3">
                                    <label for="valor_entregue">Saldo Disponível</label>
                                    <input type="number" name="valor_entregue_test" disabled id="valor_entregue_test" class="form-control valor_entregue_test" value="{{ $estudante->saldo }}" onchange="calcularTroco()" placeholder="Digite o Valor que o Estudante te Entregou">
                                    <input type="hidden" name="valor_entregue" id="valor_entregue" class="form-control valor_entregue" value="{{ $estudante->saldo }}" onchange="calcularTroco()" placeholder="Digite o Valor que o Estudante te Entregou">
                                    <span class="text-danger error-text valor_entregue_error"></span>
                                </div>

                                {{-- <div class="form-group col-12  col-md-3">
                      <label for="tipo_pagamento">Tipo Pagamento</label>
                      <select name="tipo_pagamento" id="tipo_pagamento" class="form-control tipo_pagamento" onchange="calcularTroco()">
                        @foreach ($formas_pagamento as $item)
                        <option value="{{ $item->sigla_tipo_pagamento }}">{{ $item->descricao }}</option>
                                @endforeach
                                </select>
                                <span class="text-danger error-text tipo_pagamento_error"></span>
                            </div> --}}


                            <div class="form-group col-12  col-md-3" id="form_valor_muticaixa" style="display: none;">
                                <label for="valor_muticaixa">Valor Multicaixa</label>
                                <input type="text" name="valor_muticaixa" id="valor_muticaixa" class="form-control valor_muticaixa" onchange="calcularTrocoMulticaixa()" placeholder="Digite o Valor que o Estudante te Entregou Multicaixa">
                                <span class="text-danger error-text valor_muticaixa_error"></span>
                            </div>

                            {{-- <div class="form-group col-md-3 col-12">
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
                        </div> --}}

                        {{-- <div class="form-group col-12  col-md-3">
                      <label for="desconto">Desconto</label>
                      <input type="text" name="desconto" class="form-control desconto" placeholder="Informe o Desconto %">
                      <span class="text-danger error-text desconto_error"></span>
                    </div> --}}


                        <div class="form-group col-12  col-md-3" id="form_campo_quantidade" style="display: none;">
                            <label for="quantidade">Quantidade</label>
                            <input type="number" name="quantidade" id="quantidade" onchange="calcularTroco()" class="form-control quantidade" placeholder="Informe o Desconto a quantidade a pagar">
                            <span class="text-danger error-text quantidade_error"></span>
                        </div>

                        {{-- <div class="form-group col-12  col-md-3" id="form_campo_multa" style="display: none;">
                      <label for="aplicacao_multa">Aplicar Multa</label>
                      <select name="pagamento" id="pagamento" class="form-control aplicacao_multa">
                        <option value="nao">Não</option>
                        <option value="sim">Sim</option>
                      </select>
                      <span class="text-danger error-text aplicacao_multa_error"></span>
                    </div> --}}


                        {{-- <div class="form-group col-12  col-md-3">
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
                      <span class="text-danger error-text banco_error"></span>
                    </div>
                    
        
                    <div class="form-group col-12  col-md-3">
                      <label for="sobre_nome">Número de Transição</label>
                      <input type="text" name="numero_transicao" class="form-control numero_transicao" placeholder="Número da seríe Bancaria">
                      <span class="text-danger error-text numero_transicao_error"></span>
                    </div> --}}

                        <div class="form-group col-12 col-md-3 mt-4 pt-2">
                            {{-- <label for="sobre_nome" class="text-danger">Troco</label> --}}
                            <h4><span class="text-danger">Troco <strong id="valor_troco_apresenta">0</strong></span></h4>
                            {{-- <input type="text" name="numero_transicao" id="troco" disabled class="form-control numero_transicao" placeholder="Número da seríe Bancaria"> --}}
                            <span class="text-danger error-text numero_transicao_error"></span>
                        </div>

                        <input type="hidden" name="estudantes_id" class="estudantes_id" value="{{ $estudante->id }}">
                        {{-- unico ou mensal --}}
                        <input type="hidden" name="status_servico_pagar" class="status_servico_pagar">


                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right pagamentoPropinaAJAX">Finalizar Pagamento</button>
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
            <div class="card">
                <div class="card-body">
                    <div id="carregarMesesPagamaentoPropinaAdicionar"></div>
                </div>
            </div>
            {{-- carregamento automatico --}}
        </div>

</div><!-- /.row -->
</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>

@endsection

@section('scripts')
<script>
    function calcularTrocoMulticaixa() {

        const total = parseFloat(document.getElementById("valor_entregue").value);
        const pago = parseFloat(document.getElementById("valor2").value);
        const quantidade = parseFloat(document.getElementById("quantidade").value);
        const tipo_pagamento = document.getElementById("tipo_pagamento").value;
        const valor_multicaixa = document.getElementById("valor_muticaixa").value;

        if (tipo_pagamento == "OU") {
            $('#form_valor_muticaixa').css({
                display: "inline-block"
            });

            if (!isNaN(total) && !isNaN(valor_multicaixa) && valor_multicaixa >= total) {
                document.getElementById("valor_entregue").value = "0.00";
            } else {
                const result = (total * quantidade - valor_multicaixa).toFixed(2);
                document.getElementById("valor_entregue").value = result;
            }

        } else {
            $('#form_valor_muticaixa').css({
                display: "none"
            });
        }
    }

    function calcularTroco() {
        const total = parseFloat(document.getElementById("valor_entregue").value);
        const pago = parseFloat(document.getElementById("valor2").value);
        const quantidade = parseFloat(document.getElementById("quantidade").value);
        const tipo_pagamento = document.getElementById("tipo_pagamento").value;
        let valor_multicaixa = document.getElementById("valor_muticaixa").value;

        // pagmaento numerario
        if (tipo_pagamento == "NU") {

            $('#form_valor_muticaixa').css({
                display: "none"
            });
            document.getElementById("valor_muticaixa").value = 0;
        } else
            // pagamento duplo
            if (tipo_pagamento == "OU") {
                $('#form_valor_muticaixa').css({
                    display: "inline-block"
                });
            } else
                // pagamento multicaixa
                if (tipo_pagamento == "MB") {
                    $('#form_valor_muticaixa').css({
                        display: "none"
                    });
                    document.getElementById("valor_muticaixa").value = 0;
                } else
                    // pagamento credito
                    if (tipo_pagamento == "CC") {
                        document.getElementById("valor_muticaixa").value = 0;
                        $('#form_valor_muticaixa').css({
                            display: "none"
                        });
                    } else {
                        document.getElementById("valor_muticaixa").value = 0;
                        $('#form_valor_muticaixa').css({
                            display: "none"
                        });
                    }

        let result_quantidade = 0;

        if (isNaN(quantidade)) {
            result_quantidade = 1;
        } else {
            result_quantidade = quantidade;
        }

        if (!isNaN(total) && !isNaN(pago) && pago >= total) {
            document.getElementById("troco").value = "0.00";
        } else {
            const troco = (total - pago * result_quantidade).toFixed(2);
            document.getElementById("troco").value = troco;
            document.getElementById("valor").value = pago * result_quantidade;
        }
    }

</script>

<script>
    $(function() {

        var servicoPagar;
        // COLLETAR PAGAMENTO
        $(document).on('click', '.pagamentoPropinaAJAX', function(e) {
            e.preventDefault();

            var data = {
                'valor': $('.valor').val()
                , 'valor_entregue': $('.valor_entregue').val()
                , 'desconto': $('.desconto').val()
                , 'servico': $('.servico').val()
                , 'tipo_pagamento': $('.tipo_pagamento').val()
                , 'banco': $('.banco').val()
                , 'numero_transicao': $('.numero_transicao').val()
                , 'estudantes_id': $('.estudantes_id').val()
                , 'multa': $('.multa').val()
                , 'turma': $('.turmas_id_seleciona').val()
                , 'quantidade': $('.quantidade').val()
                , 'documento': $('.documento').val()
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
                , url: "{{ route('est.efectuar-pagamento-estudante-store') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.open(`../../download/factura-recibo/${response.ficha}`, "_blank");
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


        $(document).on('change', '.servico', function(e) {
            e.preventDefault();
            let servicoId = $(this).val();
            servicoPagar = servicoId;
            let estudanteId = $('.estudantes_id').val();
            let turmaId = $('.turmas_id_seleciona').val();

            $.ajax({
                type: "GET"
                , url: `../estudantes/carregar-servicos-cartao?estudante_id=${estudanteId}&turma_id=${turmaId}&servico_id=${servicoId}`
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    var iconeStatus;
                    if (response.status == 200) {
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
                                  <td>' + response.servico.preco + '</td>\
                                  <td>' + response.servico.preco + '</td>\
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

                            $('.valor').val(response.servico.preco + response.servico.multa);
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
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });


        $(document).on('click', '.class_adicionar_mes', function(e) {
            e.preventDefault();
            let id = $(this).attr("id");
            let estudanteId = $('.estudantes_id').val();

            $.ajax({
                type: "GET"
                , url: `../estudantes/detalhes-pagamento-propina/${id}/${estudanteId}/${servicoPagar}`
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
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
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });


        $(document).on('click', '.remover_mes_pagar_momento', function(e) {
            e.preventDefault();
            let id = $(this).attr("id");
            let estudanteId = $('.estudantes_id').val();

            $.ajax({
                type: "GET"
                , url: `../estudantes/detalhes-pagamento-propina-remover-mes/${id}/${estudanteId}/${servicoPagar}`
                , beforeSend: function() {
                  // Você pode adicionar um loader aqui, se necessário
                  progressBeforeSend();
                }
                , success: function(response) {
                  Swal.close();
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
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });
    });

</script>
@endsection
