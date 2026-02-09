@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Configruaração de Serviços</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.calendarios') }}">Serviços</a></li>
                    <li class="breadcrumb-item active">Configurações</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">


        <form class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3 col-12">
                                <label for="servicos_id">Serviço <span class="text-danger">*</span></label>
                                <select name="servicos_id[]" id="servicos_id" class="form-control servicos_id servicos_all select2" multiple>
                                    <option value="">Selecione serviço</option>
                                    @if ($servicos)
                                    @foreach ($servicos as $servico)
                                    <option value="{{ $servico->id }}">{{ $servico->servico }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span class="text-danger error-text servicos_id_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="servicos_para">Serviço Para <span class="text-danger">*</span></label>
                                <select name="servicos_para" id="servicos_para" class="form-control servicos_para">
                                    <option value="">Selecione serviço</option>
                                    <option value="turmas">Turmas</option>
                                    <option value="escola">Escola</option>
                                </select>
                                <span class="text-danger error-text servicos_para_error"></span>
                            </div>

                            <div class="form-group col-md-3" id="destino_created_at">
                                <label for="servicos_desitno">Destino <span class="text-danger">*</span></label>
                                <select name="servicos_desitno[]" id="servicos_desitno" class="form-control servicos_desitno select2" style="width: 100%;" data-placeholder="Selecione um conjunto de turmas" multiple="multiple">
                                    <option value="">Selecione destino</option>
                                </select>
                                <span class="text-danger error-text servicos_desitno_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="preco">Preço sem o IVA <span class="text-danger">*</span></label>
                                <input type="number" name="preco" class="form-control preco" id="preco" placeholder="Informe o proço do serviço">
                                <span class="text-danger error-text preco_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="multa">Multa</label>
                                <input type="number" value="0" name="multa" class="form-control multa" placeholder="Informe a multa para este serviço">
                                <span class="text-danger error-text multa_error"></span>
                                <input type="hidden" name="servico_turma_editar" class="form-control servico_turma_editar">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="desconto">Desconto</label>
                                <input type="number" value="0" name="desconto" class="form-control desconto" placeholder="Informe o desconto para esse serviços">
                                <span class="text-danger error-text desconto_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="status">Status do Serviço <span class="text-danger">*</span></label>
                                <select name="status" id="status" id="status" class="form-control status">
                                    <option value="activo">Activo</option>
                                    <option value="desactivo">Desactivo</option>
                                </select>
                                <span class="text-danger error-text status_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="pagamento">Pagamento <span class="text-danger">*</span></label>
                                <select name="pagamento" id="pagamento" id="pagamento" class="form-control pagamento">
                                    <option value="">Selecione status</option>
                                    <option value="unico" selected>Pagamento Unico</option>
                                    <option value="mensal">Pagamento Mensal</option>
                                </select>
                                <span class="text-danger error-text pagamento_error"></span>
                            </div>


                            <div class="form-group mb-3 col-md-3 col-12">
                                <label for="taxa_multa1_dia" class="form-label">Dia de Atraso para aplica 1º Taxa <span class="text-danger">*</span></label>
                                <input type="number" name="taxa_multa1_dia" placeholder="Dias de Atraso para primeira taxa" value="0" id="taxa_multa1_dia" class="form-control taxa_multa1_dia">
                                @error('taxa_multa1_dia')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-3 col-12">
                                <label for="taxa_multa1" class="form-label">Valor 1º Taxa (%) <span class="text-danger">*</span></label>
                                <input type="number" name="taxa_multa1" placeholder="Dia final Ex: 5%" id="taxa_multa1" value="0" class="form-control taxa_multa1">
                                @error('taxa_multa1')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-3 col-12">
                                <label for="taxa_multa2_dia" class="form-label">Dia de Atraso para aplica 2º Taxa <span class="text-danger">*</span></label>
                                <input type="number" name="taxa_multa2_dia" placeholder="Dias de Atraso para segunda taxa" value="0" id="taxa_multa2_dia" class="form-control taxa_multa2_dia">
                                @error('taxa_multa2_dia')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-3 col-12">
                                <label for="taxa_multa2" class="form-label">Valor 2º Taxa (%) <span class="text-danger">*</span></label>
                                <input type="number" name="taxa_multa2" placeholder="Dia final Ex: 10%" id="taxa_multa2" value="0" class="form-control taxa_multa2">
                                @error('taxa_multa2')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-3 col-12">
                                <label for="taxa_multa3_dia" class="form-label">Dia de Atraso para aplica 3º Taxa <span class="text-danger">*</span></label>
                                <input type="number" name="taxa_multa3_dia" placeholder="Dias de Atraso para terceira taxa" value="0" id="taxa_multa3_dia" class="form-control taxa_multa3_dia">
                                @error('taxa_multa3_dia')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-3 col-12">
                                <label for="taxa_multa3" class="form-label">Valor 3º Taxa (%) <span class="text-danger">*</span></label>
                                <input type="number" name="taxa_multa3" placeholder="Dia final Ex: 15%" id="taxa_multa3" value="0" class="form-control taxa_multa3">
                                @error('taxa_multa3')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-2 col-12">
                                <label for="dia_inicio">Total de Parcelamento <span class="text-danger">*</span></label>
                                <input type="number" name="total_vezes" class="form-control total_vezes" id="total_vezes" placeholder="informe Informe o total de parcelamento Ex: 12">
                                <span class="text-danger error-text total_vezes_error"></span>
                            </div>

                            <div class="form-group col-md-2 col-12">
                                <label for="data_inicio">Data Inicio do pagamento <span class="text-danger">*</span></label>
                                <input type="date" name="data_inicio" value="{{ $verAnoLectivoActivo->inicio }}" class="form-control data_inicio" id="data_inicio" placeholder="informe a data final do pagamento">
                                <span class="text-danger error-text data_inicio_error"></span>
                            </div>

                            <div class="form-group col-md-2 col-12">
                                <label for="data_final">Data Final do pagamento <span class="text-danger">*</span></label>
                                <input type="date" name="data_final" value="{{ $verAnoLectivoActivo->final }}" class="form-control data_final" placeholder="informe o dia do final da cobrança">
                                <span class="text-danger error-text data_final_error"></span>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        @if (Auth::user()->can('create: servicos'))
                        <button type="button" class="btn btn-primary cadastrar_servico" id="btnCadastrar">Salvar</button>
                        @endif
                    </div>
                </div>
            </div>
        </form>

    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@section('scripts')
<script>
    // configuracao_turma
    $(document).on('change', '.servicos_para', function(e) {
        e.preventDefault();
        var novo_id = $(this).val();

        $.ajax({
            type: "GET"
            , url: "carregamento-destino-servico/" + novo_id
            , beforeSend: function() {
                progressBeforeSend();
            }
            , success: function(response) {
                Swal.close();
                if (response.entidade == "turma") {
                    $('.servicos_desitno').html("");

                    for (let index = 0; index < response.turmas.length; index++) {
                        $('.servicos_desitno').append('<option value="' + response.turmas[index].id + '">' + response.turmas[index].turma + '</option>');
                    }

                } else if (response.entidade == "escola") {
                    $('.servicos_desitno').html("");
                    $('.servicos_desitno').append('<option value="' + response.escola.id + '">' + response.escola.nome + '</option>');
                }
            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        });

    });

    $(document).on('click', '.cadastrar_servico', function(e) {
        e.preventDefault();

        var data = {
            'servicos_id': $('.servicos_id').val()
            , 'preco': $('.preco').val()
            , 'multa': $('.multa').val()
            , 'desconto': $('.desconto').val()
            , 'data_inicio': $('.data_inicio').val()
            , 'data_final': $('.data_final').val()
            , 'total_vezes': $('.total_vezes').val()
            , 'status': $('.status').val()
            , 'ano_lectivos_id': $('.ano_lectivos_id').val()
            , 'pagamento': $('.pagamento').val()
            , 'servicos_para': $('.servicos_para').val()
            , 'servicos_desitno': $('.servicos_desitno').val(),

            'taxa_multa1_dia': $('.taxa_multa1_dia').val()
            , 'taxa_multa1': $('.taxa_multa1').val()
            , 'taxa_multa2_dia': $('.taxa_multa2_dia').val()
            , 'taxa_multa2': $('.taxa_multa2').val()
            , 'taxa_multa3_dia': $('.taxa_multa3_dia').val()
            , 'taxa_multa3': $('.taxa_multa3').val()
        , }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST"
            , url: "{{ route('web.cadastrar-servico-turma') }}"
            , data: data
            , dataType: "json"
            , beforeSend: function() {
                progressBeforeSend();
            }
            , success: function(response) {
                Swal.close();
                // Exibe uma mensagem de sucesso
                showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                window.location.reload();
            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        });
    });

    excluirRegistro('.deleteModal', `{{ route('web.remover-servico-turma', ':id') }}`);

    const tabelas = [
        "#carregarTabela"
    , ];
    tabelas.forEach(inicializarTabela);

</script>
@endsection
