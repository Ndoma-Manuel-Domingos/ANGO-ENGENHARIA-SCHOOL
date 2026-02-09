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
                <h1 class="m-0 text-dark">Efecturar Pagamentos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('tesourarias.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($estudantes->id)) }}">Estudante</a></li>
                    <li class="breadcrumb-item active">Pagamentos</li>
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
                <div class="callout callout-info">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <h5><i class="fas fa-info"></i> Pagamento de serviços. Ex: Propinas, Uniformes etc. Pode adicionar os meses e remove-los da lista dos meses a se pagar.</h5>
                        </div>
                        <div class="col-12 col-md-8">
                            <h5 class="text-uppercase"><i class="fas fa-info"></i>
                                Nº: <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($estudantes->id)) }}" class="text-primary">{{ $estudantes->numero_processo ?? ""  }}</a> |
                                Nome Completo: <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($estudantes->id)) }}" class="text-primary">{{ $estudantes->nome ?? ""  }} {{ $estudantes->sobre_nome ?? ""  }}</a> |
                                Curso: <a href="{{ route('web.apresentar-turma-informacoes', Crypt::encrypt($turma->turmas_id)) }}" class="text-primary">{{ $turma->turma->curso->curso ?? "" }}</a> |
                                Classe: <a href="{{ route('web.apresentar-turma-informacoes', Crypt::encrypt($turma->turmas_id)) }}" class="text-primary">{{ $turma->turma->classe->classes ?? "" }}</a> |
                                Turno: <a href="{{ route('web.apresentar-turma-informacoes', Crypt::encrypt($turma->turmas_id)) }}" class="text-primary">{{ $turma->turma->turno->turno ?? "" }}</a> |
                                Valor Propina: <a href="{{ route('web.financeiro-isentar-pagamento', Crypt::encrypt($estudantes->id)) }}" class="text-primary">{{ number_format($estudantes->valor_propinas($turma->turma->classes_id, $turma->turma->cursos_id, $turma->turma->ano_lectivos_id), 2, ',', '.')  }}</a> |
                                Cartão: <a href="{{ route('web.sistuacao-financeiro', Crypt::encrypt($estudantes->id)) }}" class="text-primary">Painel Financeiro</a> |
                                Desconto:
                                <a href="{{ route('web.financeiro-isentar-pagamento', Crypt::encrypt($estudantes->id)) }}" class="text-primary">
                                    @if ($estudantes->desconto($estudantes->id, $turma->turma->ano_lectivos_id) != false)
                                    @php
                                    $des = $estudantes->desconto($estudantes->id, $turma->turma->ano_lectivos_id);
                                    @endphp
                                    {{ number_format($des->desconto->desconto, 1, ',', '.')  }} %
                                    @else
                                    0
                                    @endif
                                </a> |
                                Outros Estudante: <a href="{{ route('web.estudantes') }}" class="text-primary">AQUI</a> |
                            </h5>
                        </div>

                        <div class="col-12 col-md-4">
                            <h5 class="text-uppercase">
                                @if ($estudantes->bolseiro($estudantes->id))
                                Bolsa: <strong style="border-bottom: 1px solid #005467"> {{ $estudantes->bolseiro($estudantes->id)->bolsa->nome ?? ''  }}</strong>
                                * desconto de: <strong style="border-bottom: 1px solid #005467"> {{ $estudantes->bolseiro($estudantes->id)->instituicao_bolsa->desconto ?? ''  }}% </strong>
                                * <strong style="border-bottom: 1px solid #005467"> {{ $estudantes->bolseiro($estudantes->id)->periodo->trimestre ?? ''  }}. </strong>
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row" id="myForm">

                            <div class="form-group col-12 col-md-3">
                                <label for="ano_lectivos_id">Anos Lectivos</label>
                                <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control ano_lectivos_id select2">
                                    @if ($anos_lectivos)
                                    @foreach ($anos_lectivos as $ano_lectivo)
                                    <option value="{{ $ano_lectivo->id }}" {{ $verAnoLectivoActivo->id == $ano_lectivo->id ? 'selected' : "" }}>{{ $ano_lectivo->ano }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span class="text-danger error-text ano_lectivos_id_error"></span>
                            </div>

                            <div class="form-group col-12 col-md-3">
                                <label for="servico">Serviço</label>
                                <select name="servico" id="servico" class="form-control servico">
                                    <option value="">Selecione</option>
                                    @if ($servicos)
                                    @foreach ($servicos as $servico)
                                    <option value="{{ $servico->servico->id }}">{{ $servico->servico->servico ?? "" }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span class="text-danger error-text servico_error"></span>
                            </div>

                            <div class="form-group col-12 col-md-3">
                                <label for="valor">Valor Total a Pagar</label>
                                <input type="text" name="valor" id="valor" class="form-control valor" disabled="" placeholder="Valor do Pagamento">
                                <input type="hidden" name="valor_aguardado" id="valor2" class="form-control valor valor_total_a_pagar" value="">
                                <span class="text-danger error-text valor_error"></span>
                            </div>

                            <input type="hidden" value="{{ $turma->turmas_id }}" class="turmas_id_seleciona" name="turmas_id_seleciona">

                            <div class="form-group col-12 col-md-3">
                                <label for="valor_entregue">Digite o Valor Entregue <span class="text-danger">*</span></label>
                                <input type="text" name="valor_entregue" id="valor_entregue" class="form-control valor_entregue" value="0" onchange="calcularTroco()" placeholder="Digite o Valor que o Estudante te Entregou">
                                <span class="text-danger error-text valor_entregue_error"></span>
                            </div>

                            <div class="form-group col-12 col-md-3" id="valor_entregue_multicaixa_id">
                                <label for="valor_entregue_multicaixa">Digite o Valor Entregue por Multicaixa(TPA) <span class="text-danger">*</span></label>
                                <input type="text" name="valor_entregue_multicaixa" id="valor_entregue_multicaixa" class="form-control valor_entregue_multicaixa" value="0" onchange="calcularTroco()" placeholder="Digite o Valor que o Estudante te Entregou">
                                <span class="text-danger error-text valor_entregue_multicaixa_error"></span>
                            </div>

                            <div class="form-group col-12 col-md-3">
                                <label for="saldo_actual_do_estudante_id">Saldo Actual do Estudante</label>
                                <input type="text" name="saldo_actual_do_estudante_id" disabled id="saldo_actual_do_estudante_id" class="form-control saldo_actual_do_estudante_id" value="{{ $estudantes->saldo }}" placeholder="Digite o Valor que o Estudante te Entregou">
                                <span class="text-danger error-text saldo_actual_do_estudante_id_error"></span>
                            </div>


                            <div class="form-group col-12  col-md-3">
                                <label for="tipo_pagamento">Tipo Pagamento</label>
                                <select name="tipo_pagamento" id="tipo_pagamento" class="form-control tipo_pagamento" onchange="calcularTroco()">
                                    @foreach ($formas_pagamento as $item)
                                    <option value="{{ $item->sigla_tipo_pagamento }}" {{ $item->sigla_tipo_pagamento == "MB" ? "selected" : "NU" }}>{{ $item->descricao }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text tipo_pagamento_error"></span>
                            </div>

                            <div class="form-group col-12  col-md-3" id="form_valor_muticaixa" style="display: none;">
                                <label for="valor_muticaixa">Valor Multicaixa</label>
                                <input type="text" name="valor_muticaixa" id="valor_muticaixa" class="form-control valor_muticaixa" onchange="calcularTrocoMulticaixa()" placeholder="Digite o Valor que o Estudante te Entregou Multicaixa">
                                <span class="text-danger error-text valor_muticaixa_error"></span>
                            </div>

                            <div class="form-group col-md-3 col-12">
                                <label for="documento">Tipo de Documento <span class="text-danger">*</span></label>
                                <select name="documento" id="documento" class="form-control documento select2 @error('documento') is-invalid @enderror">
                                    <option value="">Selecione o Pagamento</option>
                                    <option value="FR" {{ old('documento') == "FR" ? 'selected' : 'selected' }}>Factura Recibo</option>
                                    <option value="FP" {{ old('documento') == "FP" ? 'selected' : '' }}>Factura Pró-forma</option>
                                    <option value="FT" {{ old('documento') == "FT" ? 'selected' : '' }}>Factura</option>
                                </select>
                                @error('documento')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-12  col-md-3">
                                <label for="desconto">Desconto<span class="text-danger"></span></label>
                                <input type="text" name="desconto" disabled value="0" class="form-control desconto" placeholder="Informe o Desconto %">
                                <input type="hidden" name="desconto_aguardado" id="desconto2" class="form-control desconto total_desconto" value="">
                                <span class="text-danger error-text desconto_error"></span>
                            </div>

                            <div class="form-group col-12  col-md-3">
                                <label for="multa">Multa</label>
                                <input type="text" name="multa" class="form-control multa" placeholder="Informe a multa a pagar" disabled>
                                <span class="text-danger error-text multa_error"></span>
                            </div>

                            <div class="form-group col-12  col-md-3" id="form_campo_multa" style="display: none;">
                                <label for="aplicacao_multa">Aplicar Multa</label>
                                <select name="pagamento" id="pagamento" class="form-control aplicacao_multa">
                                    <option value="nao">Não</option>
                                    <option value="sim">Sim</option>
                                </select>
                                <span class="text-danger error-text aplicacao_multa_error"></span>
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

                            <div class="form-group col-12 col-md-2">
                                <label for="sobre_nome">Número de Transição</label>
                                <input type="text" name="numero_transicao" class="form-control numero_transicao" placeholder="Número da seríe Bancaria">
                                <span class="text-danger error-text numero_transicao_error"></span>
                            </div>

                            <div class="form-group col-12 col-md-2">
                                <label for="data_pagamento">Data Pagamento</label>
                                <input type="date" name="data_pagamento" id="data_pagamento" value="{{ old('data_pagamento') ?? date("Y-m-d") }}" class="form-control data_pagamento">
                                <span class="text-danger error-text data_pagamento_error"></span>
                            </div>

                            <div class="form-group col-12 col-md-3" id="saldo_a_descontar_do_estudante_campo">
                                <label for="saldo_a_descontar_do_estudante_id">Quanto Pretendes descontar do Saldo do estudante?</label>
                                <input type="input" name="saldo_a_descontar_do_estudante_id" class="saldo_a_descontar_do_estudante_id form-control" placeholder="Digita o saldo a Descontar" id="saldo_a_descontar_do_estudante_id">
                                <span class="text-danger error-text saldo_a_descontar_do_estudante_id_error"></span>
                            </div>

                            <div class="form-group col-12 col-md-2">
                                <label for="salvar_troco">Salvar Troco como Saldo?</label>
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" name="salvar_troco" value="1" class="salvar_troco form-control form-control-lg" id="salvar_troco">
                                        <label for="salvar_troco"></label>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="estudantes_id" class="estudantes_id" value="{{ $estudantes->id }}">
                            <input type="hidden" name="saldo_do_estudante" class="saldo_do_estudante" value="{{ $estudantes->saldo }}">
                            {{-- unico ou mensal --}}
                            <input type="hidden" name="status_servico_pagar" class="status_servico_pagar">

                        </div>
                    </div>
                    <div class="card-footer">
                        <p class=" float-left" style="font-size: 15pt"><span class="text-danger">Troco <strong id="valor_troco_apresenta">0</strong></span></p>

                        @if (Auth::user()->can('create: pagamento'))
                        <button type="submit" class="btn btn-primary float-right pagamentoPropinaAJAX">Finalizar Pagamento</button>
                        <button type="button" class="btn btn-danger  float-right mx-2" id="btn_descontar_saldo_estudante">Descontar o Pagamento no Saldo do estudante</button>
                        @endif

                    </div>
                </div>
            </div>

            <div class="col-md-7 col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="carregarMesesPagamaentoPropinaMomento"></div>
                    </div>
                </div>
                {{-- carregamento automatica --}}
            </div>

            <div class="col-md-5 col-12">
                <div class="card">
                    <div class="card-footer">
                        <div class="form-group col-12 col-md-12" id="form_campo_quantidade" style="display: none">
                            <label for="quantidade">Quantidade</label>
                            <input type="number" name="quantidade" value="1" id="quantidade" onchange="calcularTroco()" class="form-control quantidade" placeholder="Informe o Desconto a quantidade a pagar">
                            <span class="text-danger error-text quantidade_error"></span>
                        </div>
                    </div>
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
@endsection


@section('scripts')
<script>
    let servicoPagar = null;
    var pagamento_com_reserva_saldo = false;


    $('#valor_entregue_multicaixa_id').css("display", "none");
    $('#saldo_a_descontar_do_estudante_campo').css("display", "none");


    $(function() {

        // selecionar tipo pagamento
        $(document).on('change', '.saldo_a_descontar_do_estudante_id', function(e) {

            var saldo = parseFloat($('.saldo_do_estudante').val());
            var valor_a_pagar = parseFloat($('.valor_total_a_pagar').val());

            if (this.checked) {
                // Calcular o troco
                var troco = saldo - valor_a_pagar;
                // var f = troco.toLocaleString('pt-br',{style: 'currency', currency: 'AOA'});
                var f2 = troco.toLocaleString('pt-br', {
                    minimumFractionDigits: 2
                });

                $("#valor_troco_apresenta").html("");
                $("#valor_troco_apresenta").append(f2);

                $('.valor_entregue').val(saldo);
                $('.salvar_troco').val(true);

            } else {
                $('.valor_entregue').val(0);
            }
        });

        // selecionar tipo pagamento
        $(document).on('change', '.tipo_pagamento', function(e) {
            e.preventDefault();
            var id = $(this).val();

            if (id == "OU") {
                $('#valor_entregue_multicaixa_id').css("display", "block");
                $('#form_campo_bancos').css("display", "block");
            } else if (id == "MB") {
                $('#form_campo_bancos').css("display", "block");
            } else if (id == "TT" || id == "DD") {
                $('#form_campo_bancos').css("display", "block");
            } else {
                $('.valor_entregue_multicaixa').val(0);
                $('#valor_entregue_multicaixa_id').css("display", "none");
                $('#form_campo_bancos').css("display", "none");
            }
        });

        $('.valor_entregue').on('input', function(e) {
            e.preventDefault();
            if ($(this).val() > 0) {
                // valor total a pagar
                var valor_total = parseFloat($('.valor_total_a_pagar').val()) || 0;
                var valor_entregue = parseFloat($(this).val()) || 0;
                var valor_entregue_multicaixa = parseFloat($('.valor_entregue_multicaixa').val()) || 0;
                var valor_descontar_saldo = parseFloat($('.saldo_a_descontar_do_estudante_id').val()) || 0;

                // Calcular o troco
                var troco = (valor_entregue + valor_entregue_multicaixa + valor_descontar_saldo) - valor_total;

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

        $('.valor_entregue_multicaixa').on('input', function(e) {
            e.preventDefault();
            if ($(this).val() > 0) {
                // valor total a pagar
                var valor_total = parseFloat($('.valor_total_a_pagar').val()) || 0;
                var valor_entregue_multicaixa = parseFloat($(this).val()) || 0;
                var valor_entregue = parseFloat($('.valor_entregue').val()) || 0;
                var valor_descontar_saldo = parseFloat($('.saldo_a_descontar_do_estudante_id').val()) || 0;

                // Calcular o troco
                var troco = (valor_entregue + valor_entregue_multicaixa + valor_descontar_saldo) - valor_total;

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

        // selecionar tipo pagamento
        $(document).on('click', '#btn_descontar_saldo_estudante', function(e) {
            e.preventDefault();
            var saldo = $('.saldo_do_estudante').val();
            if (saldo > 0) {
                $('#saldo_a_descontar_do_estudante_campo').css("display", "block");
            } else {
                $('#saldo_a_descontar_do_estudante_campo').css("display", "none");
                showMessage('Alerta!', 'Saldo do estudante insuficiente para realizar esta operação!', 'warning');
            }
        });

        $('.saldo_a_descontar_do_estudante_id').on('input', function(e) {
            e.preventDefault();
            if ($(this).val() > 0) {

                pagamento_com_reserva_saldo = true

                var saldo_a_descontar = parseFloat($(this).val()) || 0;
                var valor_entregue_multicaixa = parseFloat($('.valor_entregue_multicaixa').val()) || 0;
                var valor_entregue = parseFloat($('.valor_entregue').val()) || 0;

                var total_a_pagar_da_factura = parseFloat($('#valor2').val()) || 0;

                var saldo_actual_do_estudante = parseFloat($('.saldo_do_estudante').val()) || 0;
                // var saldo_actual = parseFloat($('.saldo_actual_do_estudante_id').val()) || 0;

                if (saldo_actual_do_estudante >= saldo_a_descontar) {

                    if (total_a_pagar_da_factura >= saldo_a_descontar) {
                        var saldo_restante = saldo_actual_do_estudante - saldo_a_descontar;
                        // apresentavel
                        $('.saldo_actual_do_estudante_id').val(saldo_restante);
                    } else {
                        $('.saldo_a_descontar_do_estudante_id').val(total_a_pagar_da_factura)
                    }
                } else {
                    console.log("false")
                }

            } else {
                console.log("false")
            }
        })

        $(document).on('change', '.ano_lectivos_id', function(e) {
            e.preventDefault();
            let ano_lectivo = $(this).val();
            let estudanteId = $('.estudantes_id').val();
            let turmaId = $('.turmas_id_seleciona').val();
            let servicoId = $('.servico').val();
            servicoPagar = servicoId;

            $.ajax({
                type: "GET"
                , url: `../carregar-servicos-cartao?estudante_id=${estudanteId}&turma_id=${turmaId}&ano_lectivo_id=${ano_lectivo}&servico_id=${servicoId}`
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {

                    Swal.close();
                    var iconeStatus;

                    $('#form_campo_multa').css({
                        display: "inline-block"
                    });

                    let result = '';
                    result += `<option value="">Selecione Serviços</option>`;
                    response.servicos.forEach(item => {
                        const servico = item.servico;
                        result += `<option value="${servico.id}">${servico.servico}</option>`;
                    });
                    $("#servico").html(result);

                    $('.valor').val(response.totalAPagar);
                    $('.desconto').val(response.totalDesconto);
                    $('.multa').val(response.somaMulta);
                    $('.status_servico_pagar').val(response.servico_turma.pagamento);
                    var soma_total_a_pagar_e_multa = parseFloat(response.totalAPagar);

                    // meses ue pretendo adcionar
                    $("#carregarMesesPagamaentoPropinaAdicionar").html("");
                    $("#carregarMesesPagamaentoPropinaAdicionar").append(`<h5 class="fs-6">Listagem dos Meses ${response.servico_turma.servico} Não pago</h5>
                      <table id="example1"  style="width: 100%" class="table table-bordered">
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

        $(document).on('change', '.servico', function(e) {
            e.preventDefault();

            let servicoId = $(this).val();
            let ano_lectivo = $('.ano_lectivos_id').val();
            let estudanteId = $('.estudantes_id').val();
            let turmaId = $('.turmas_id_seleciona').val();
            servicoPagar = servicoId;

            $.ajax({
                type: "GET"
                , url: `../carregar-servicos-cartao?estudante_id=${estudanteId}&turma_id=${turmaId}&ano_lectivo_id=${ano_lectivo}&servico_id=${servicoId}`
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    var iconeStatus;

                    $('#form_campo_multa').css({
                        display: "inline-block"
                    });
                    // if (response.servico_turma.pagamento == 'mensal') {
                    $('.valor').val(response.totalAPagar);
                    $('.desconto').val(response.totalDesconto);
                    $('.multa').val(response.somaMulta);
                    $('.status_servico_pagar').val(response.servico_turma.pagamento);
                    var soma_total_a_pagar_e_multa = parseFloat(response.totalAPagar);

                    $('#form_campo_quantidade').css({
                        display: "block"
                    });

                    // meses ue pretendo adcionar
                    $("#carregarMesesPagamaentoPropinaAdicionar").html("");
                    $("#carregarMesesPagamaentoPropinaAdicionar").append(`<h5 class="fs-6">Listagem dos Meses ${response.servico_turma.servico} Não pago</h5>
                      <table id="example1" style="width: 100%" class="table table-bordered">
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
                          <table id="example1"  style="width: 100%" class="table table-bordered  ">
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
                            <th>${response.somaVolores}<small> %</small></th>
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
                                  <td>${response.mesesAdd[index].desconto}<small> %</small></td>
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
                              <td>${response.mesesAdd[index].desconto}<small> %</small></td>
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
            let id = $(this).attr("id");
            let ano_lectivo = $('.ano_lectivos_id').val();
            let quantidade = $('.quantidade').val();
            let estudanteId = $('.estudantes_id').val();

            $.ajax({
                type: "GET"
                , url: `../detalhes-pagamento-propina/${id}/${estudanteId}/${servicoPagar}/${quantidade}/${ano_lectivo}`
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    if (response.status == 200) {

                        $('.valor').val(response.totalAPagar);
                        $('.desconto').val(response.totalDesconto);
                        $('.multa').val(response.somaMulta);

                        var soma_total_a_pagar_e_multa = parseFloat(response.totalAPagar);

                        $("#carregarMesesPagamaentoPropinaMomento").html("");
                        $("#carregarMesesPagamaentoPropinaMomento").append(`<h1 class="fs-3">Meses a pagar no momento</h1>
                        <table id="example1"  style="width: 100%" class="table table-bordered">
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
                        renderTabelaMeses(response)
                    }

                    if (response.status == 401) {
                        Swal.fire({
                            title: "Atenção"
                            , text: response.message
                            , icon: "warning"
                            , button: "Great!"
                        , });
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
            let ano_lectivo = $('.ano_lectivos_id').val();
            let quantidade = $('.quantidade').val();
            let estudanteId = $('.estudantes_id').val();

            $.ajax({
                type: "GET"
                , url: `../detalhes-pagamento-propina-remover-mes/${id}/${estudanteId}/${servicoPagar}/${quantidade}/${ano_lectivo}`
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    if (response.status == 200) {

                        $('.valor').val(response.totalAPagar);
                        $('.multa').val(response.somaMulta);
                        $('.desconto').val(response.totalDesconto);

                        var soma_total_a_pagar_e_multa = parseFloat(response.totalAPagar);

                        $("#carregarMesesPagamaentoPropinaMomento").html("");
                        $("#carregarMesesPagamaentoPropinaMomento").append(`<h1 class="fs-3">Meses a pagar no momento</h1>
                            <table id="example1"  style="width: 100%" class="table table-bordered  ">
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
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        $(document).on('click', '.class_remover_multa_3', function(e) {
            e.preventDefault();
            var id = $(this).attr("id");
            $.ajax({
                type: "GET"
                , url: `../remover-multa3-propina/${id}`
                , beforeSend: function() {
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
                        <table id="example1"  style="width: 100%" class="table table-bordered">
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

                    renderTabelaMeses(response)

                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        $(document).on('click', '.class_adicionar_multa_3', function(e) {
            e.preventDefault();
            var id = $(this).attr("id");
            $.ajax({
                type: "GET"
                , url: `../adicionar-multa3-propina/${id}`
                , beforeSend: function() {
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

                    renderTabelaMeses(response)

                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        $(document).on('click', '.class_remover_multa_2', function(e) {
            e.preventDefault();
            var id = $(this).attr("id");
            $.ajax({
                type: "GET"
                , url: `../remover-multa2-propina/${id}`
                , beforeSend: function() {
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
                    <table id="example1"  style="width: 100%" class="table table-bordered  ">
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

                    renderTabelaMeses(response)

                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        $(document).on('click', '.class_adicionar_multa_2', function(e) {
            e.preventDefault();
            var id = $(this).attr("id");
            $.ajax({
                type: "GET"
                , url: `../adicionar-multa2-propina/${id}`
                , beforeSend: function() {
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
                    <table id="example1"  style="width: 100%" class="table table-bordered">
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

                    renderTabelaMeses(response)

                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        $(document).on('click', '.class_remover_multa_1', function(e) {
            e.preventDefault();
            var id = $(this).attr("id");
            $.ajax({
                type: "GET"
                , url: `../remover-multa1-propina/${id}`
                , beforeSend: function() {
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
                    <table id="example1"  style="width: 100%" class="table table-bordered">
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

                    renderTabelaMeses(response)

                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        $(document).on('click', '.class_adicionar_multa_1', function(e) {
            e.preventDefault();
            var id = $(this).attr("id");
            $.ajax({
                type: "GET"
                , url: `../adicionar-multa1-propina/${id}`
                , beforeSend: function() {
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
                        <table id="example1"  style="width: 100%" class="table table-bordered  ">
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

                    renderTabelaMeses(response)

                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        // COLLETAR PAGAMENTO
        $(document).on('click', '.pagamentoPropinaAJAX', function(e) {
            e.preventDefault();

            var data = {
                'valor': $('.valor').val()
                , 'valor_entregue': $('.valor_entregue').val()
                , 'ano_lectivos_id': $('.ano_lectivos_id').val()
                , 'valor_entregue_multicaixa': $('.valor_entregue_multicaixa').val()
                , 'desconto': $('.desconto').val()
                , 'servico': $('.servico').val()
                , 'data_pagamento': $('.data_pagamento').val()
                , 'tipo_pagamento': $('.tipo_pagamento').val()
                , 'banco_id': $('.banco_id').val()
                , 'caixa_id': $('.caixa_id').val()
                , 'salvar_troco': $('#salvar_troco').is(':checked') ? 1 : 0
                , 'saldo_a_descontar_do_estudante_id': $('#saldo_a_descontar_do_estudante_id').val()
                , 'pagamento_com_reserva_saldo': pagamento_com_reserva_saldo
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
                , url: "{{ route('web.estudantes-pagamento-propina-create') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.open(`../../download/factura-recibo/${response.ficha}/ORGINAL`, "_blank");
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

    });

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
        const valor_descontar_saldo = document.getElementById("saldo_a_descontar_do_estudante_id").value;

        // pagmaento numerario
        if (tipo_pagamento == "NU") {
            $('#form_valor_muticaixa').css({
                display: "none"
            });
            document.getElementById("valor_muticaixa").value = 0;
        } else if (tipo_pagamento == "OU") {
            $('#form_valor_muticaixa').css({
                display: "inline-block"
            });
        } else if (tipo_pagamento == "MB") {
            $('#form_valor_muticaixa').css({
                display: "none"
            });
            document.getElementById("valor_muticaixa").value = 0;
        } else if (tipo_pagamento == "CC") {
            // pagamento credito
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
            const troco = ((total + valor_descontar_saldo) - pago * result_quantidade).toFixed(2);
            document.getElementById("troco").value = troco;
            document.getElementById("valor").value = pago * result_quantidade;
        }
    }

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
                        @if (Auth::user()->can("create: isentar multa") || Auth::user()->can("update: isentar multa"))
                            <a href="#" id="${response.cartao[index].id}" class="btn btn-dark class_adicionar_multa_1" style="${btn_adicionar_multa1}"><i class="fas fa-plus ">20%</i></a>
                            <a href="#" id="${response.cartao[index].id}" class="btn btn-dark class_adicionar_multa_2" style="${btn_adicionar_multa2}"><i class="fas fa-plus ">40%</i></a>
                            <a href="#" id="${response.cartao[index].id}" class="btn btn-dark class_adicionar_multa_3" style="${btn_adicionar_multa3}"><i class="fas fa-plus ">45%</i></a>
                            <a href="#" id="${response.cartao[index].id}" class="btn btn-danger class_remover_multa_1" style="${btn_remover_multa1}"><i class="fas fa-times ">20%</i></a>
                            <a href="#" id="${response.cartao[index].id}" class="btn btn-danger class_remover_multa_2" style="${btn_remover_multa2}"><i class="fas fa-times ">40%</i></a>
                            <a href="#" id="${response.cartao[index].id}" class="btn btn-danger class_remover_multa_3" style="${btn_remover_multa3}"><i class="fas fa-times ">45%</i></a>
                        @endif
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
                        @if (Auth::user()->can("create: isentar multa") || Auth::user()->can("update: isentar multa"))
                           <a href="#" id="${response.cartao[index].id}" class="btn btn-dark class_adicionar_multa_1" style="${btn_adicionar_multa1}"><i class="fas fa-plus ">20% sdssd</i></a>
                           <a href="#" id="${response.cartao[index].id}" class="btn btn-dark class_adicionar_multa_2" style="${btn_adicionar_multa2}"><i class="fas fa-plus ">40%</i></a>
                           <a href="#" id="${response.cartao[index].id}" class="btn btn-dark class_adicionar_multa_3" style="${btn_adicionar_multa3}"><i class="fas fa-plus ">45%</i></a>
                          <a href="#" id="${response.cartao[index].id}" class="btn btn-danger class_remover_multa_1" style="${btn_remover_multa1}"><i class="fas fa-times ">20%</i></a>
                          <a href="#" id="${response.cartao[index].id}" class="btn btn-danger class_remover_multa_2" style="${btn_remover_multa2}"><i class="fas fa-times ">40%</i></a>
                          <a href="#" id="${response.cartao[index].id}" class="btn btn-danger class_remover_multa_3" style="${btn_remover_multa3}"><i class="fas fa-times ">45%</i></a>
                        @endif
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
                        @if (Auth::user()->can("create: isentar multa") || Auth::user()->can("update: isentar multa"))
                            <a href="#" id="${response.cartao[index].id}" class="btn btn-dark class_adicionar_multa_1" style="${btn_adicionar_multa1}"><i class="fas fa-plus ">20%</i></a>
                            <a href="#" id="${response.cartao[index].id}" class="btn btn-dark class_adicionar_multa_2" style="${btn_adicionar_multa2}"><i class="fas fa-plus ">40%</i></a>
                            <a href="#" id="${response.cartao[index].id}" class="btn btn-dark class_adicionar_multa_3" style="${btn_adicionar_multa3}"><i class="fas fa-plus ">45%</i></a>
                            <a href="#" id="${response.cartao[index].id}" class="btn btn-danger class_remover_multa_1" style="${btn_remover_multa1}"><i class="fas fa-times ">20%</i></a>
                            <a href="#" id="${response.cartao[index].id}" class="btn btn-danger class_remover_multa_2" style="${btn_remover_multa2}"><i class="fas fa-times ">40%</i></a>
                            <a href="#" id="${response.cartao[index].id}" class="btn btn-danger class_remover_multa_3" style="${btn_remover_multa3}"><i class="fas fa-times ">45%</i></a>
                        @endif
                  </td>
                </tr>`);
            }
        }
    }

</script>
@endsection
