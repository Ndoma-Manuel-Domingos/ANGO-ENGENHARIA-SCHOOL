@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Funcionários</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.funcionarios') }}">Funcionários</a></li>
                    <li class="breadcrumb-item active">Criar Contrato</li>
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
                    <h5><i class="fas fa-info"></i> Criar Contrato para funcionários</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('web.funcionarios-criar-contrato-store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-12 col-12">
                                    <label for="status">Professor <span class="text-danger">*</span></label>
                                    <select name="funcionario_id" id="funcionario_id" class="form-control status select2">
                                        <option value="">Selecione o Professor</option>
                                        @foreach ($funcionarios as $items)
                                        @if (isset($funcionario) && $funcionario != null)
                                        <option value="{{ $funcionario->id  }}" {{ $items->id == $funcionario->id ? 'selected' : ''  }}>{{ $items->nome }} {{ $items->sobre_nome }}</option>
                                        @endif
                                        <option value="{{ $items->id }}">Nº {{ $items->id }} - {{ $items->nome }} {{ $items->sobre_nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('funcionario_id')
                                    <span class="text-danger error-text status_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="salario">Salário <span class="text-danger">*</span></label>
                                    <input type="text" name="salario" value="{{ old('salario') ?? 0 }}" class="form-control salario" placeholder="Salário">
                                    @error('salario')
                                    <span class="text-danger error-text salario_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="subcidio_transporte">Subcídio Transporte <span class="text-danger">*</span></label>
                                    <input type="text" value="{{ old('subcidio_transporte') ?? 0 }}" name="subcidio_transporte" class="form-control subcidio_transporte" placeholder="Subcídio de Transporte">
                                    @error('subcidio_transporte')
                                    <span class="text-danger error-text subcidio_transporte_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="subcidio_alimentacao">Subsídio Alimentação <span class="text-danger">*</span></label>
                                    <input type="text" value="{{ old('subcidio_alimentacao') ?? 0 }}" name="subcidio_alimentacao" class="form-control subcidio_alimentacao" placeholder="Subcídio de Alimentação">
                                    @error('subcidio_alimentacao')
                                    <span class="text-danger error-text subcidio_alimentacao_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="subcidio_ferias">Subsídio de Férias <span class="text-danger">*</span></label>
                                    <input type="text" name="subcidio_ferias" value="{{ old('subcidio_ferias') ?? 0 }}" class="form-control subcidio_ferias" placeholder="Subsídio de Férias">
                                    @error('subcidio_ferias')
                                    <span class="text-danger error-text subcidio_ferias_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="subcidio_natal">Subcídio de Natal <span class="text-danger">*</span></label>
                                    <input type="text" name="subcidio_natal" value="{{ old('subcidio_natal') ?? 0 }}" class="form-control subcidio_natal" placeholder="Subsídio de Natal">
                                    <span class="text-danger error-text "></span>
                                    @error('subcidio_natal')
                                    <span class="text-danger error-text subcidio_natal_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="subcidio_abono_familia">Subcídios Abono de Família <span class="text-danger">*</span></label>
                                    <input type="text" name="subcidio_abono_familia" value="{{ old('subcidio_abono_familia') ?? 0 }}" class="form-control subcidio_abono_familia" placeholder="Subsídios de Abono de Familas">
                                    @error('subcidio_abono_familia')
                                    <span class="text-danger error-text subcidio_abono_familia">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="subcidio">Outros Subsídios <span class="text-danger">*</span></label>
                                    <input type="text" name="subcidio" value="{{ old('subcidio') ?? 0 }}" class="form-control subcidio" placeholder="Outros Subcídios">
                                    <span class="text-danger error-text subcidio_error"></span>
                                    @error('subcidio')
                                    <span class="text-danger error-text subcidio_error">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-3 col-12">
                                    <label for="tempos_semanais">Total Tempos Semanais <span class="text-danger">*</span></label>
                                    <input type="text" name="tempos_semanais" value="0" class="form-control tempos_semanais" placeholder="Total Tempos Semanais">
                                    @error('tempos_semanais')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-3">
                                    <label for="falta_por_dia">Falta Por dia <span class="text-danger">*</span></label>
                                    <input type="text" value="{{ old('falta_por_dia') ?? 0 }}" name="falta_por_dia" class="form-control falta_por_dia" placeholder="desconto por dia faltas">
                                    @error('falta_por_dia')
                                    <span class="text-danger error-text falta_por_dia_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="data_inicio_contrato">Inicio Contrato <span class="text-danger">*</span></label>
                                    <input type="date" name="data_inicio_contrato" value="{{ old('data_inicio_contrato') ?? $ano_lectivo->inicio }}" class="form-control data_inicio_contrato" placeholder="Da ccontrato">
                                    @error('data_inicio_contrato')
                                    <span class="text-danger error-text desconto_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="data_final_contrato">Final Contrato <span class="text-danger">*</span></label>
                                    <input type="date" value="{{ old('data_final_contrato') ?? $ano_lectivo->final }}" name="data_final_contrato" class="form-control data_final_contrato" placeholder="Final">
                                    <span class="text-danger error-text data_final_contrato_error"></span>
                                    @error('data_final_contrato')
                                    <span class="text-danger error-text data_final_contrato_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="desconto">Hora Entrada <span class="text-danger">*</span></label>
                                    <input type="time" value="{{ old('hora_entrada_contrato') ?? '07:30' }}" name="hora_entrada_contrato" class="form-control hora_entrada_contrato" placeholder="Hora Entrada">
                                    @error('hora_entrada_contrato')
                                    <span class="text-danger error-text desconto_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="hora_saida_contrato">Hora Saída <span class="text-danger">*</span></label>
                                    <input type="time" value="{{ old('hora_saida_contrato') ?? '18:30' }}" name="hora_saida_contrato" class="form-control hora_saida_contrato" placeholder="Hora Saida">
                                    @error('hora_saida_contrato')
                                    <span class="text-danger error-text hora_saida_contrato_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="conta_bancaria">Conta Bancária <span class="text-danger">*</span></label>
                                    <input type="text" value="{{ old('conta_bancaria') ?? '0000.0000.0000' }}" name="conta_bancaria" class="form-control conta_bancaria" placeholder="Conta Bancária">
                                    @error('conta_bancaria')
                                    <span class="text-danger error-text conta_bancaria_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="iban">IBAN <span class="text-danger">*</span></label>
                                    <input type="text" value="{{ old('iban') ?? '0000.0000.0000.0000.0000.0' }}" name="iban" class="form-control iban" placeholder="Informa o IBAN">
                                    @error('iban')
                                    <span class="text-danger error-text iban_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="nif">NIF <span class="text-danger">*</span></label>
                                    <input type="text" name="nif" class="form-control nif" value="{{ old('nif') ?? '0000.0000.0000.047' }}">
                                    @error('nif')
                                    <span class="text-danger error-text nif_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="status_contrato">Status Contrato <span class="text-danger">*</span></label>
                                    <select name="status_contrato" id="status_contrato" class="form-control status_contrato">
                                        <option value="">Selecione o Status</option>
                                        <option value="Novo" {{ old('status_contrato') == "Novo" ? 'selected' : '' }} selected>Novo</option>
                                        <option value="Renovado" {{ old('status_contrato') == "Renovado" ? 'selected' : '' }}>Renovado</option>
                                        <option value="Outros" {{ old('status_contrato') == "Outros" ? 'selected' : '' }}>Outros</option>
                                    </select>
                                    @error('status_contrato')
                                    <span class="text-danger error-text status_contrato_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="tempo_contrato">Tempo do Contrato</label>
                                    <select name="tempo_contrato" id="tempo_contrato" class="form-control tempo_contrato">
                                        <option value="">Selecione o Tempo</option>
                                        <option value="Determinado" {{ old('tempo_contrato') == "Determinado" ? 'selected' : '' }} selected>Determinado</option>
                                        <option value="Indeterminado" {{ old('tempo_contrato') == "Indeterminado" ? 'selected' : '' }}>Indeterminado</option>
                                    </select>
                                    <span class="text-danger error-text tempo_contrato_error"></span>
                                </div>


                                <div class="form-group col-md-3">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control status">
                                        <option value="">Selecione o Status</option>
                                        <option value="Activo" {{ old('status') == "Activo" ? 'selected' : '' }} selected>Activo</option>
                                        <option value="Desactivo" {{ old('status') == "Desactivo" ? 'selected' : '' }}>Desactivo</option>
                                    </select>
                                    <span class="text-danger error-text status_error"></span>
                                    @error('status')
                                    <span class="text-danger error-text status_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="departamento_id">Departamento <span class="text-danger">*</span></label>
                                    <select name="departamento_id" id="departamento_id" class="form-control departamento_id">
                                        <option value="">Selecione</option>
                                        @foreach ($departamentos as $item)
                                        <option value="{{ $item->id }}" {{ old('departamento_id') == $item->id ? 'selected' : '' }}>{{ $item->departamento }}</option>
                                        @endforeach
                                    </select>
                                    @error('departamento_id')
                                    <span class="text-danger error-text departamento_id_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="cargo_id">Cargo <span class="text-danger">*</span></label>
                                    <select name="cargo_id" id="cargo_id" class="form-control cargo_id">
                                        <option value="">Selecione</option>
                                        @foreach ($cargos as $item)
                                        <option value="{{ $item->id }}" {{ old('cargo_id') == $item->id ? 'selected' : '' }}>{{ $item->cargo }}</option>
                                        @endforeach
                                    </select>
                                    @error('cargo_id')
                                    <span class="text-danger error-text cargo_id_error">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="mb-3 col-md-12">
                                    <label for="clausula" class="form-label">Clausula do Contrato</label>
                                    <textarea class="form-control clausula" name="clausula" id="clausula" rows="3">{{ old('clausula') ?? '' }}</textarea>
                                    @error('clausula')
                                    <span class="text-danger error-text cargo_id_error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@section('scripts')
<script>
    $(function() {

        var dadosFuncionario;
        var dadosAcademicos;
        var dadosContrato;
        // COLECTAR INFORMACOES ESTUDANTES

        $(document).on('click', '.informacoes_funcinarios', function(e) {

            e.preventDefault();

            var data = {
                'nome': $('.nome').val()
                , 'sobre_nome': $('.sobre_nome').val()
                , 'nascimento': $('.nascimento').val()
                , 'genero': $('.genero').val()
                , 'estado_civil': $('.estado_civil').val()
                , 'nacionalidade': $('.nacionalidade').val()
                , 'bilheite': $('.bilheite').val()
                , 'telefone': $('.telefone').val()
                , 'endereco': $('.endereco').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.cadastrar-funcionarios-novo') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    // // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Swal.close();
                    if (response.status == 300) {
                        Swal.fire({
                            title: "Oooooops...."
                            , text: response.message
                            , icon: "error"
                            , timer: "2000"
                        , });
                    } else
                    if (response.status == 400) {
                        $.each(response.errors, function(key, err_values) {
                            $('span.' + key + '_error').text(err_values[0]);
                        });
                    } else {
                        dadosFuncionario = response.funcionarios;
                        stepper.next();
                    }
                }
            });
        });

        $(document).on('click', '.informacoes_academica', function(e) {

            var data = {
                'curso': $('.curso').val()
                , 'area_formacao': $('.area_formacao').val()
                , 'nivel_academico': $('.nivel_academico').val()
                , 'formacao_pedagogica': $('.formacao_pedagogica').val()
                , 'universidade': $('.universidade').val()
                , 'email': $('.email').val()
                , 'dadosFuncionarios': dadosFuncionario
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.cadastrar-funcionarios-academicos') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    if (response.status == 300) {
                        Swal.fire({
                            title: "Oooooops...."
                            , text: response.message
                            , icon: "error"
                            , timer: "2000"
                        , });
                    } else
                    if (response.status == 400) {
                        $.each(response.errors, function(key, err_values) {
                            $('span.' + key + '_error').text(err_values[0]);
                        });
                    } else {
                        dadosAcademicos = response.funcionariosAcademicos;
                        stepper.next();
                    }
                }
            });
        });

        $(document).on('click', '.concluir_cadastro_funcionarios', function(e) {

            var data = {
                'salario': $('.salario').val()
                , 'subcidio': $('.subcidio').val()
                , 'subcidio_transporte': $('.subcidio_transporte').val()
                , 'subcidio_alimentacao': $('.subcidio_alimentacao').val(),

                'subcidio_ferias': $('.subcidio_ferias').val()
                , 'subcidio_natal': $('.subcidio_natal').val()
                , 'subcidio_abono_familia': $('.subcidio_abono_familia').val(),

                'falta_por_dia': $('.falta_por_dia').val()
                , 'data_inicio_contrato': $('.data_inicio_contrato').val()
                , 'data_final_contrato': $('.data_final_contrato').val()
                , 'hora_entrada_contrato': $('.hora_entrada_contrato').val()
                , 'hora_saida_contrato': $('.hora_saida_contrato').val()
                , 'status_contrato': $('.status_contrato').val()
                , 'status': $('.status').val()
                , 'cargo': $('.cargo').val()
                , 'conta_bancaria': $('.conta_bancaria').val()
                , 'iban': $('.iban').val()
                , 'nif': $('.nif').val()
                , 'clausula': $('.clausula').val()
                , 'dadosFuncionarios': dadosFuncionario
                , 'dadosAcademicos': dadosAcademicos
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.concluir-cadastrar-funcionarios') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
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

        // editar
        $(document).on('click', '.editar_funcionarios_id', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $("#modalFormEditarFuncionarios").modal("show");

            $.ajax({
                type: "GET"
                , url: "editar-funcionarios/" + id
                , dataType: "json"
                , beforeSend: function() {
                    // // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();

                    $('.editar_nome').val(response.funcionario.nome);
                    $('.editar_sobre_nome').val(response.funcionario.sobre_nome);
                    $('.editar_nascimento').val(response.funcionario.nascimento);
                    $('.editar_genero').val(response.funcionario.genero);
                    $('.editar_estado_civil').val(response.funcionario.estado_civil);
                    $('.editar_nacionalidade').val(response.funcionario.nacionalidade);
                    $('.editar_bilheite').val(response.funcionario.bilheite);
                    $('.editar_telefone').val(response.funcionario.telefone);
                    $('.editar_endereco').val(response.funcionario.endereco);
                    $('.identificador_informacoes').val(response.funcionario.id);

                    // informações academicas
                    $('.editar_curso').val(response.academico.curso);
                    $('.editar_area_formacao').val(response.academico.area_formacao);
                    $('.editar_nivel_academico').val(response.academico.nivel_academico);
                    $('.editar_formacao_pedagogica').val(response.academico.formacao_pedagogica);
                    $('.editar_universidade').val(response.academico.universidade);
                    $('.editar_email').val(response.academico.email);
                    $('.identificador_academico').val(response.academico.id);

                    // informações contrato
                    $('.editar_salario').val(response.contrato.salario);
                    $('.editar_subcidio').val(response.contrato.subcidio);

                    $('.editar_subcidio_transporte').val(response.contrato.subcidio_transporte);
                    $('.editar_subcidio_alimentacao').val(response.contrato.subcidio_alimentacao);
                    $('.editar_falta_por_dia').val(response.contrato.falta_por_dia);

                    $('.editar_subcidio_ferias').val(response.contrato.subcidio_ferias);
                    $('.editar_subcidio_natal').val(response.contrato.subcidio_natal);
                    $('.editar_subcidio_abono_familia').val(response.contrato.subcidio_abono_familiar);

                    $('.editar_data_inicio_contrato').val(response.contrato.data_inicio_contrato);
                    $('.editar_data_final_contrato').val(response.contrato.data_final_contrato);
                    $('.editar_hora_entrada_contrato').val(response.contrato.hora_entrada_contrato);
                    $('.editar_hora_saida_contrato').val(response.contrato.hora_saida_contrato);
                    $('.editar_status_contrato').val(response.contrato.status_contrato);
                    $('.editar_status').val(response.contrato.status);
                    $('.editar_cargo').val(response.contrato.cargo);
                    $('.editar_conta_bancaria').val(response.contrato.conta_bancaria);
                    $('.editar_iban').val(response.contrato.iban);
                    $('.editar_nif').val(response.contrato.nif);
                    $('.editar_clausula').val(response.contrato.clausula);
                    $('.identificador_contrato').val(response.contrato.id);

                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });

        });

        // editar update
        $(document).on('click', '.editar_funcionario', function(e) {
            e.preventDefault();

            var data = {
                // informações professor
                'nome': $('.editar_nome').val()
                , 'sobre_nome': $('.editar_sobre_nome').val()
                , 'nascimento': $('.editar_nascimento').val()
                , 'genero': $('.editar_genero').val()
                , 'estado_civil': $('.editar_estado_civil').val()
                , 'nacionalidade': $('.editar_nacionalidade').val()
                , 'bilheite': $('.editar_bilheite').val()
                , 'telefone': $('.editar_telefone').val()
                , 'endereco': $('.editar_endereco').val()
                , 'id_informacoes': $('.identificador_informacoes').val(),

                // informações academicas
                'curso': $('.editar_curso').val()
                , 'area_formacao': $('.editar_area_formacao').val()
                , 'nivel_academico': $('.editar_nivel_academico').val()
                , 'formacao_pedagogica': $('.editar_formacao_pedagogica').val()
                , 'universidade': $('.editar_universidade').val()
                , 'email': $('.editar_email').val()
                , 'id_academico': $('.identificador_academico').val(),

                // informações contrato
                'salario': $('.editar_salario').val()
                , 'subcidio': $('.editar_subcidio').val(),

                'subcidio_transporte': $('.editar_subcidio_transporte').val()
                , 'subcidio_alimentacao': $('.editar_subcidio_alimentacao').val()
                , 'falta_por_dia': $('.editar_falta_por_dia').val(),

                'subcidio_ferias': $('.editar_subcidio_ferias').val()
                , 'subcidio_natal': $('.editar_subcidio_natal').val()
                , 'subcidio_abono_familia': $('.editar_subcidio_abono_familia').val(),

                'data_inicio_contrato': $('.editar_data_inicio_contrato').val()
                , 'data_final_contrato': $('.editar_data_final_contrato').val()
                , 'hora_entrada_contrato': $('.editar_hora_entrada_contrato').val()
                , 'hora_saida_contrato': $('.editar_hora_saida_contrato').val()
                , 'status_contrato': $('.editar_status_contrato').val()
                , 'status': $('.editar_status').val()
                , 'cargo': $('.editar_cargo').val()
                , 'conta_bancaria': $('.editar_conta_bancaria').val()
                , 'iban': $('.editar_iban').val()
                , 'nif': $('.editar_nif').val()
                , 'clausula': $('.editar_clausula').val()
                , 'id_contrato': $('.identificador_contrato').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.editar-funcionario') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
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

        // ver dados
        $(document).on('click', '.ver_dados', function(e) {
            e.preventDefault();
            var ano_lectivo_id = $(this).attr('id');
            window.location.href = "mais-informacao-funcionarios/" + ano_lectivo_id;
        });

    });

    $(function() {
        $("#carregarTabelaFuncionarios").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });

</script>
@endsection
