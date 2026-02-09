@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Detalhe do Contrato</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.funcionarios-contrato') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">contratos</li>
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
                    <div class="card-body">
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="nome">Nome</label>
                                <input type="text" disabled name="nome" value="{{ $funcionario->nome }}" class="form-control nome" placeholder="Nome do Funcionário">
                                <span class="text-danger error-text nome_error"></span>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="sobre_nome">Sobrenome</label>
                                <input type="text" disabled name="sobre_nome" value="{{ $funcionario->sobre_nome }}" class="form-control sobre_nome" placeholder="Sobrenome do Funcionário">
                                <span class="text-danger error-text sobre_nome_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="salario">Salário</label>
                                <input type="text" disabled name="salario" value="{{ number_format($contrato->salario, 2, ',', '.') }} Kz" class="form-control salario" placeholder="Salário">
                                <span class="text-danger error-text salario_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="subcidio_transporte">Subcídio Transporte</label>
                                <input type="text" disabled name="subcidio_transporte" value="{{ number_format($contrato->subcidio_transporte, 2, ',', '.') }} Kz" class="form-control subcidio_transporte" placeholder="Subcídio de Transporte">
                                <span class="text-danger error-text subcidio_transporte_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="subcidio_alimentacao">Subsídio Alimentação</label>
                                <input type="text" disabled name="subcidio_alimentacao" value="{{ number_format($contrato->subcidio_alimentacao, 2, ',', '.') }} Kz" class="form-control subcidio_alimentacao" placeholder="Subcídio de Alimentação">
                                <span class="text-danger error-text subcidio_alimentacao_error"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="subcidio_ferias">Subsídio de Férias</label>
                                <input type="text" disabled name="subcidio_ferias" class="form-control subcidio_ferias" placeholder="Subsídio de Férias" value="{{ number_format($contrato->subcidio_ferias, 2, ',', '.') }} Kz ">
                                <span class="text-danger error-text subcidio_ferias_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="subcidio_natal">Subcídio de Natal</label>
                                <input type="text" disabled name="subcidio_natal" value="{{ number_format($contrato->subcidio_natal, 2, ',', '.') }} Kz" class="form-control subcidio_natal" placeholder="Subsídio de Natal">
                                <span class="text-danger error-text subcidio_natal_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="subcidio_abono_familia">Subcídios Abono de Família</label>
                                <input type="text" disabled name="subcidio_abono_familia" class="form-control subcidio_abono_familia" placeholder="Subsídios de Abono de Familas" value="{{ number_format($contrato->subcidio_abono_familiar, 2, ',', '.') }} Kz">
                                <span class="text-danger error-text subcidio_abono_familia_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="subcidio">Outros Subsídios</label>
                                <input type="text" disabled name="subcidio" value="{{ number_format($contrato->subcidio, 2, ',', '.') }} Kz" class="form-control subcidio" placeholder="Outros Subcídios">
                                <span class="text-danger error-text subcidio_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="falta_por_dia">Falta Por dia</label>
                                <input type="text" disabled name="falta_por_dia" value="{{ number_format($contrato->falta_por_dia, 2, ',', '.') }} Kz" class="form-control falta_por_dia" placeholder="desconto por dia faltas">
                                <span class="text-danger error-text falta_por_dia_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="data_inicio_contrato">Inicio Contrato</label>
                                <input type="date" disabled name="data_inicio_contrato" value="{{ $contrato->data_inicio_contrato }}" class="form-control data_inicio_contrato" placeholder="Da ccontrato">
                                <span class="text-danger error-text desconto_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="data_final_contrato">Final Contrato</label>
                                <input type="date" disabled name="data_final_contrato" value="{{ $contrato->data_final_contrato }}" class="form-control data_final_contrato" placeholder="Final">
                                <span class="text-danger error-text data_final_contrato_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="desconto">Hora Entrada</label>
                                <input type="time" disabled name="hora_entrada_contrato" value="{{ $contrato->hora_entrada_contrato }}" class="form-control hora_entrada_contrato" placeholder="Hora Entrada">
                                <span class="text-danger error-text desconto_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="hora_saida_contrato">Hora Saída</label>
                                <input type="time" disabled name="hora_saida_contrato" value="{{ $contrato->hora_saida_contrato }}" class="form-control hora_saida_contrato" placeholder="Hora Saida">
                                <span class="text-danger error-text hora_saida_contrato_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="conta_bancaria">Conta Bancária</label>
                                <input type="text" disabled name="conta_bancaria" value="{{ $contrato->conta_bancaria }}" class="form-control conta_bancaria" placeholder="Conta Bancária">
                                <span class="text-danger error-text conta_bancaria_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="iban">IBAN</label>
                                <input type="text" disabled name="iban" value="{{ $contrato->iban }}" class="form-control iban" placeholder="Informa o IBAN">
                                <span class="text-danger error-text iban_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="nif">NIF</label>
                                <input type="text" disabled name="nif" value="{{ $contrato->nif }}" class="form-control nif">
                                <span class="text-danger error-text nif_error"></span>
                            </div>


                            <div class="form-group col-md-3 col-12">
                                <label for="tempo_contrato">Tempo do Contrato</label>
                                <select name="tempo_contrato" id="tempo_contrato" disabled class="form-control tempo_contrato">
                                    <option value="">Selecione o Tempo</option>
                                    <option value="Determinado" {{ $contrato->tempo_contrato == "Determinado" ? 'selected' : ''}}>Determinado</option>
                                    <option value="Indeterminado" {{ $contrato->tempo_contrato == "Indeterminado" ? 'selected' : ''}}>Indeterminado</option>
                                </select>
                                @error('tempo_contrato')
                                <span class="text-danger error-text nome_error">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group col-md-6">
                                <label for="status_contrato">Status Contrato</label>
                                <select name="status_contrato" disabled id="status_contrato" class="form-control status_contrato">
                                    <option value="">Selecione o Status</option>
                                    <option value="Novo" {{ $contrato->status_contrato == "Novo" ? 'selected' : ''}}>Novo</option>
                                    <option value="Renovado" {{ $contrato->status_contrato == "Renovado" ? 'selected' : ''}}>Renovado</option>
                                    <option value="Outros" {{ $contrato->status_contrato == "Outros" ? 'selected' : ''}}>Outros</option>
                                </select>
                                <span class="text-danger error-text status_contrato_error"></span>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="status">Status</label>
                                <select name="status" disabled id="status" class="form-control status">
                                    <option value="">Selecione o Status</option>
                                    <option value="Activo" {{ $contrato->status == "Activo" ? 'selected' : ''}}>Activo</option>
                                    <option value="Desactivo" {{ $contrato->status == "Desactivo" ? 'selected' : '' }}>Desactivo</option>
                                </select>
                                <span class="text-danger error-text status_error"></span>
                            </div>

                            <div class="mb-3 col-md-12">
                                <label for="endereco" class="form-label">Clausula do Contrato</label>
                                <textarea class="form-control clausula" id="endereco" rows="3" disabled>{{ $contrato->clausula }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
