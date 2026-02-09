@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editar Contrato</h1>
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
                <form action="{{ route('web.funcionarios-editar-contrato-update', Crypt::encrypt($contrato->id)) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-3 col-12">
                                    <label for="nome">Nome <span class="text-danger">*</span></label>
                                    <input type="text" disabled name="nome" value="{{ $funcionario->nome }}" class="form-control nome" placeholder="Nome do Funcionário">
                                    @error('nome')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="sobre_nome">Sobrenome <span class="text-danger">*</span></label>
                                    <input type="text" disabled name="sobre_nome" value="{{ $funcionario->sobre_nome }}" class="form-control sobre_nome" placeholder="Sobrenome do Funcionário">
                                    @error('sobre_nome')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="genero">Genero <span class="text-danger">*</span></label>
                                    <input type="text" disabled name="genero" value="{{ $funcionario->genero }}" class="form-control genero" placeholder="Sobrenome do Funcionário">
                                    @error('genero')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="nascimento">Idade <span class="text-danger">*</span></label>
                                    <input type="text" disabled name="nascimento" value="{{ $funcionario->idade($funcionario->nascimento) }}" class="form-control nascimento" placeholder="Sobrenome do Funcionário">
                                    @error('nascimento')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="salario">Salário Base <span class="text-danger">*</span></label>
                                    <input type="text" name="salario" value="{{ $contrato->salario }}" class="form-control salario" placeholder="Salário">
                                    @error('salario')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="subcidio_transporte">Subcídio Transporte <span class="text-danger">*</span></label>
                                    <input type="text" name="subcidio_transporte" value="{{ $contrato->subcidio_transporte }}" class="form-control subcidio_transporte" placeholder="Subcídio de Transporte">
                                    @error('subcidio_transporte')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="subcidio_alimentacao">Subsídio Alimentação <span class="text-danger">*</span></label>
                                    <input type="text" name="subcidio_alimentacao" value="{{ $contrato->subcidio_alimentacao }}" class="form-control subcidio_alimentacao" placeholder="Subcídio de Alimentação">

                                    @error('subcidio_alimentacao')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="subcidio_ferias">Subsídio de Férias <span class="text-danger">*</span></label>
                                    <input type="text" name="subcidio_ferias" class="form-control subcidio_ferias" placeholder="Subsídio de Férias" value="{{ $contrato->subcidio_ferias }}">
                                    @error('subcidio_ferias')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="subcidio_natal">Subcídio de Natal <span class="text-danger">*</span></label>
                                    <input type="text" name="subcidio_natal" value="{{ $contrato->subcidio_natal }}" class="form-control subcidio_natal" placeholder="Subsídio de Natal">

                                    @error('subcidio_natal')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="subcidio_abono_familia">Subcídios Abono de Família <span class="text-danger">*</span></label>
                                    <input type="text" name="subcidio_abono_familia" class="form-control subcidio_abono_familia" placeholder="Subsídios de Abono de Familas" value="{{ $contrato->subcidio_abono_familiar }}">
                                    @error('subcidio_abono_familia')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="subcidio">Outros Subsídios</label>
                                    <input type="text" name="subcidio" value="{{ $contrato->subcidio }}" class="form-control subcidio" placeholder="Outros Subcídios">
                                    @error('subcidio')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="tempos_semanais">Total Tempos Semanais <span class="text-danger">*</span></label>
                                    <input type="text" name="tempos_semanais" value="{{ $contrato->tempos_semanais ?? 0 }}" class="form-control tempos_semanais" placeholder="Total Tempos Semanais">
                                    @error('tempos_semanais')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="falta_por_dia">Falta Por dia <span class="text-danger">*</span></label>
                                    <input type="text" name="falta_por_dia" value="{{ $contrato->falta_por_dia }}" class="form-control falta_por_dia" placeholder="desconto por dia faltas">
                                    @error('falta_por_dia')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="data_inicio_contrato">Inicio Contrato <span class="text-danger">*</span></label>
                                    <input type="date" name="data_inicio_contrato" value="{{ $contrato->data_inicio_contrato }}" class="form-control data_inicio_contrato" placeholder="Da ccontrato">
                                    @error('data_inicio_contrato')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="data_final_contrato">Final Contrato <span class="text-danger">*</span></label>
                                    <input type="date" name="data_final_contrato" value="{{ $contrato->data_final_contrato }}" class="form-control data_final_contrato" placeholder="Final">
                                    @error('data_final_contrato')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="desconto">Hora Entrada <span class="text-danger">*</span></label>
                                    <input type="time" name="hora_entrada_contrato" value="{{ $contrato->hora_entrada_contrato }}" class="form-control hora_entrada_contrato" placeholder="Hora Entrada">
                                    @error('hora_entrada_contrato')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="hora_saida_contrato col-12">Hora Saída <span class="text-danger">*</span></label>
                                    <input type="time" name="hora_saida_contrato" value="{{ $contrato->hora_saida_contrato }}" class="form-control hora_saida_contrato" placeholder="Hora Saida">
                                    @error('hora_saida_contrato')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="conta_bancaria">Conta Bancária</label>
                                    <input type="text" name="conta_bancaria" value="{{ $contrato->conta_bancaria ?? '0000.0000.0000' }}" class="form-control conta_bancaria" placeholder="Conta Bancária">
                                    @error('conta_bancaria')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="iban">IBAN</label>
                                    <input type="text" name="iban" value="{{ $contrato->iban ?? '0000.0000.0000.0000.0000.0' }}" class="form-control iban" placeholder="Informa o IBAN Ex: 0055.0000.3462.2626.2623.1">
                                    @error('iban')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="nif">NIF</label>
                                    <input type="text" name="nif" value="{{ $contrato->nif }}" class="form-control nif">
                                    @error('nif')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="tempo_contrato">Tempo do Contrato</label>
                                    <select name="tempo_contrato" id="tempo_contrato" class="form-control tempo_contrato">
                                        <option value="">Selecione o Tempo</option>
                                        <option value="Determinado" {{ $contrato->tempo_contrato == "Determinado" ? 'selected' : ''}}>Determinado</option>
                                        <option value="Indeterminado" {{ $contrato->tempo_contrato == "Indeterminado" ? 'selected' : ''}}>Indeterminado</option>
                                    </select>
                                    @error('tempo_contrato')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="status_contrato">Status Contrato</label>
                                    <select name="status_contrato" id="status_contrato" class="form-control status_contrato">
                                        <option value="">Selecione o Status</option>
                                        <option value="Novo" {{ $contrato->status_contrato == "Novo" ? 'selected' : ''}}>Novo</option>
                                        <option value="Renovado" {{ $contrato->status_contrato == "Renovado" ? 'selected' : ''}}>Renovado</option>
                                        <option value="Outros" {{ $contrato->status_contrato == "Outros" ? 'selected' : ''}}>Outros</option>
                                    </select>
                                    @error('status_contrato')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control status">
                                        <option value="">Selecione o Status</option>
                                        <option value="Activo" {{ $contrato->status == "Activo" ? 'selected' : ''}}>Activo</option>
                                        <option value="Desactivo" {{ $contrato->status == "Desactivo" ? 'selected' : '' }}>Desactivo</option>
                                    </select>
                                    @error('status')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-12 col-12">
                                    <label for="clausula" class="form-label">Clausula do Contrato</label>
                                    <textarea class="form-control clausula" name="clausula" id="clausula" rows="3">{{ $contrato->clausula }}</textarea>
                                    @error('clausula')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
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
