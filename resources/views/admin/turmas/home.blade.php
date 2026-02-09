@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Turmas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel de controle</a></li>
                    <li class="breadcrumb-item active">Turmas</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

 {{--
 id="formUpdate" --}}

{{-- cadastrar principal cuross --}}
<div class="modal fade" id="modalFormCadastraTurmas">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('web.cadastrar-turmas') }}" method="post" id="formCreate">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cadastrar Turma</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-3 col-12">
                            <label for="nome_turmas">Nome Turma <span class="text-danger">*</span></label>
                            <input type="text" name="nome_turmas" placeholder="Nome Turma" class="form-control nome_turmas">
                            <span class="text-danger error-text nome_turmas_error"></span>
                        </div>
    
                        <div class="form-group col-md-3 col-12">
                            <label for="numero_maximo">Maximo <span class="text-danger">*</span></label>
                            <input type="text" name="numero_maximo" value="40" class="form-control numero_maximo" placeholder="Número Maximo de Alunos">
                            <span class="text-danger error-text numero_maximo_error"></span>
                        </div>
    
                        <div class="form-group col-md-3 col-12">
                            <label for="status_turmas">Status <span class="text-danger">*</span></label>
                            <select name="status_turmas" id="status_turmas" class="form-control status_turmas " style="width: 100%">
                                <option value="">Selecione Status</option>
                                <option value="activo" selected>Activo</option>
                                <option value="desactivo">Desactivo</option>
                            </select>
                            <span class="text-danger error-text status_turmas_error"></span>
                        </div>
    
                        <div class="form-group col-md-3 col-12">
                            <label for="classes_id">Classe <span class="text-danger">*</span></label>
                            <select name="classes_id" id="classes_id" class="form-control classes_id " style="width: 100%">
                                {{-- <option value="">Selecione Classe</option> --}}
                                @if ($classes)
                                @foreach ($classes as $classe)
                                <option value="{{ $classe->classe->id }}">{{ $classe->classe->classes }}</option>
                                @endforeach
                                @else
                                <option value="">Sem Nenhum Curso cadastrado</option>
                                @endif
                            </select>
                            <span class="text-danger error-text classes_id_error"></span>
                        </div>
    
                        <div class="form-group col-md-3 col-12">
                            <label for="turnos_id">Turno <span class="text-danger">*</span></label>
                            <select name="turnos_id" id="turnos_id" class="form-control turnos_id " style="width: 100%">
                                {{-- <option value="">Selecione Turno</option> --}}
                                @if ($turnos)
                                @foreach ($turnos as $turno)
                                <option value="{{ $turno->turno->id }}">{{ $turno->turno->turno }}</option>
                                @endforeach
                                @else
                                <option value="">Sem Nenhum Turno cadastrado</option>
                                @endif
                            </select>
                            <span class="text-danger error-text turnos_id_error"></span>
                        </div>
    
                        <div class="form-group col-md-3 col-12">
                            <label for="cursos_id">Curso <span class="text-danger">*</span></label>
                            <select name="cursos_id" id="cursos_id" class="form-control cursos_id " style="width: 100%">
                                {{-- <option value="">Selecione Curso</option> --}}
                                @if ($cursos)
                                @foreach ($cursos as $curso)
                                <option value="{{ $curso->curso->id }}">{{ $curso->curso->curso }}</option>
                                @endforeach
                                @else
                                <option value="">Sem Nenhum Curso cadastrado</option>
                                @endif
                            </select>
                            <span class="text-danger error-text cursos_id_error"></span>
                        </div>
    
                        <div class="form-group col-md-3  col-12">
                            <label for="salas_id">Sala <span class="text-danger">*</span></label>
                            <select name="salas_id" id="salas_id" class="form-control salas_id " style="width: 100%">
                                {{-- <option value="">Selecione Sala</option> --}}
                                @if ($salas)
                                @foreach ($salas as $sala)
                                <option value="{{ $sala->sala->id }}">{{ $sala->sala->salas }}</option>
                                @endforeach
                                @else
                                <option value="">Sem Nenhum Sala cadastrado</option>
                                @endif
                            </select>
                            <span class="text-danger error-text salas_id_error"></span>
                        </div>
    
                        <div class="form-group col-md-3 col-12">
                            <label for="ano_lectivos_id">Ano Lectivo <span class="text-danger">*</span></label>
                            <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control ano_lectivos_id " style="width: 100%">
                                {{-- <option value="">Selecione Ano Lectivo</option> --}}
                                @if ($anolectivos)
                                @foreach ($anolectivos as $anolectivo)
                                <option value="{{ $anolectivo->id }}">{{ $anolectivo->ano }}</option>
                                @endforeach
                                @else
                                <option value="">Sem Nenhum Ano Lectivo cadastrado</option>
                                @endif
                            </select>
                            <span class="text-danger error-text ano_lectivos_id_error"></span>
                        </div>
    
                    </div>
                </div>
    
                @if ($escola->categoria == 'Privado')
    
                <div class="modal-body">
                    <h5 class="bg-light py-3 px-2">Definições Financeiras</h5>
                    <hr class="">
                    <div class="row">
                        <div class="form-group col-md-3 col-12">
                            <label for="valor_confirmacao">Valor Confirmação <span class="text-danger">*</span></label>
                            <input type="text" name="valor_confirmacao" class="form-control valor_confirmacao" placeholder="Definir valor da confirmação" value="0">
                            <span class="text-danger error-text valor_confirmacao_error"></span>
                        </div>
    
                        <div class="form-group col-md-3 col-12">
                            <label for="valor_matricula">Valor Matricula <span class="text-danger">*</span></label>
                            <input type="text" name="valor_matricula" class="form-control valor_matricula" placeholder="Definir valor da matricula" value="0">
                            <span class="text-danger error-text valor_matricula_error"></span>
                        </div>
    
                        <div class="form-group col-md-3 col-12">
                            <label for="valor_propina">Valor Propina <span class="text-danger">*</span></label>
                            <input type="text" name="valor_propina" class="form-control valor_propina" placeholder="Definir valor da propina" value="0">
                            <span class="text-danger error-text valor_propina_error"></span>
                        </div>
                    </div>
                </div>
    
                <div class="modal-body">
                    <h5 class="bg-light py-3 px-2">Intervalo dos dias para se efectuar o pagamento de mensalidades. Taxa de Multa para atraso de mensalidades.</h5>
                    <hr class="">
                    <div class="row">
                        <div class="form-group mb-3 col-md-3 col-12">
                            <label for="intervalo_pagamento_inicio" class="form-label">Dia Inicial para o pagamento <span class="text-danger">*</span></label>
                            <input type="number" value="0" name="intervalo_pagamento_inicio" placeholder="Dia inicial Ex: 1" value="{{ old('intervalo_pagamento_inicio') ?? $escola->intervalo_pagamento_inicio }}" id="intervalo_pagamento_inicio" class="form-control intervalo_pagamento_inicio">
                            @error('intervalo_pagamento_inicio')
                            <span class="text-danger error-text">{{ $message }}</span>
                            @enderror
                        </div>
    
                        <div class="form-group mb-3 col-md-3 col-12">
                            <label for="intervalo_pagamento_final" class="form-label">Dia Final para o pagamento <span class="text-danger">*</span></label>
                            <input type="number" value="0" name="intervalo_pagamento_final" placeholder="Dia final Ex: 15" value="{{ old('intervalo_pagamento_final') ?? $escola->intervalo_pagamento_final }}" id="intervalo_pagamento_final" class="form-control intervalo_pagamento_final">
                            @error('intervalo_pagamento_final')
                            <span class="text-danger error-text">{{ $message }}</span>
                            @enderror
                        </div>
    
                        <div class="form-group mb-3 col-md-3 col-12">
                            <label for="taxa_multa1_dia" class="form-label">Dia de Atraso para aplica 1º Taxa <span class="text-danger">*</span></label>
                            <input type="number" value="0" name="taxa_multa1_dia" placeholder="Dias de Atraso para primeira taxa" value="{{ old('taxa_multa1_dia') ?? $escola->taxa_multa1_dia }}" id="taxa_multa1_dia" class="form-control taxa_multa1_dia">
                            @error('taxa_multa1_dia')
                            <span class="text-danger error-text">{{ $message }}</span>
                            @enderror
                        </div>
    
                        <div class="form-group mb-3 col-md-3 col-12">
                            <label for="taxa_multa1" class="form-label">Valor 1º Taxa (%) <span class="text-danger">*</span></label>
                            <input type="number" value="0" name="taxa_multa1" placeholder="Dia final Ex: 15" value="{{ old('taxa_multa1') ?? $escola->taxa_multa1 }}" id="taxa_multa1" class="form-control taxa_multa1">
                            @error('taxa_multa1')
                            <span class="text-danger error-text">{{ $message }}</span>
                            @enderror
                        </div>
    
                        <div class="form-group mb-3 col-md-3 col-12">
                            <label for="taxa_multa2_dia" class="form-label">Dia de Atraso para aplica 2º Taxa <span class="text-danger">*</span></label>
                            <input type="number" value="0" name="taxa_multa2_dia" placeholder="Dias de Atraso para segunda taxa" value="{{ old('taxa_multa2_dia') ?? $escola->taxa_multa2_dia }}" id="taxa_multa2_dia" class="form-control taxa_multa2_dia">
                            @error('taxa_multa2_dia')
                            <span class="text-danger error-text">{{ $message }}</span>
                            @enderror
                        </div>
    
                        <div class="form-group mb-3 col-md-3 col-12">
                            <label for="taxa_multa2" class="form-label">Valor 2º Taxa (%) <span class="text-danger">*</span></label>
                            <input type="number" value="0" name="taxa_multa2" placeholder="Dia final Ex: 15" value="{{ old('taxa_multa2') ?? $escola->taxa_multa2 }}" id="taxa_multa2" class="form-control taxa_multa2">
                            @error('taxa_multa2')
                            <span class="text-danger error-text">{{ $message }}</span>
                            @enderror
                        </div>
    
                        <div class="form-group mb-3 col-md-3 col-12">
                            <label for="taxa_multa3_dia" class="form-label">Dia de Atraso para aplica 3º Taxa <span class="text-danger">*</span></label>
                            <input type="number" value="0" name="taxa_multa3_dia" placeholder="Dias de Atraso para terceira taxa" value="{{ old('taxa_multa3_dia') ?? $escola->taxa_multa3_dia }}" id="taxa_multa3_dia" class="form-control taxa_multa3_dia">
                            @error('taxa_multa3_dia')
                            <span class="text-danger error-text">{{ $message }}</span>
                            @enderror
                        </div>
    
                        <div class="form-group mb-3 col-md-3 col-12">
                            <label for="taxa_multa3" class="form-label">Valor 3º Taxa (%) <span class="text-danger">*</span></label>
                            <input type="number" value="0" name="taxa_multa3" placeholder="Dia final Ex: 15" value="{{ old('taxa_multa3') ?? $escola->taxa_multa3 }}" id="taxa_multa3" class="form-control taxa_multa3">
                            @error('taxa_multa3')
                            <span class="text-danger error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
    
                @endif
    
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary cadastrar_turmas">Salvar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </form>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

{{-- editar principal cursos --}}
<div class="modal fade" id="modalFormEditarTurmas">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Turmas</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <input type="hidden" class="editar_turma_id" name="editar_turma_id" id="editar_turma_id">

                    <div class="form-group col-md-3">
                        <label for="nome_turmas">Nome Turma <span class="text-danger">*</span></label>
                        <input type="text" name="nome_turmas" class="form-control editar_nome_turmas" placeholder="Nome turma">
                        <span class="text-danger error-text nome_turmas_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="numero_maximo">Maximo <span class="text-danger">*</span></label>
                        <input type="text" name="numero_maximo" value="40" class="form-control editar_numero_maximo" placeholder="Número Maximo de Alunos">
                        <span class="text-danger error-text numero_maximo_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="status_turmas">Status <span class="text-danger">*</span></label>
                        <select name="status_turmas" id="status_turmas" class="form-control editar_status_turmas " style="width: 100%">
                            <option value="">Selecione Status</option>
                            <option value="activo">Activo</option>
                            <option value="desactivo">Desactivo</option>
                        </select>
                        <span class="text-danger error-text status_turmas_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="classes_id">Classe <span class="text-danger">*</span></label>
                        <select name="classes_id" id="classes_id" class="form-control editar_classes_id " style="width: 100%">
                            <option value="">Selecione Classe</option>
                            @if ($classes)
                            @foreach ($classes as $classe)
                            <option value="{{ $classe->classe->id }}">{{ $classe->classe->classes }}</option>
                            @endforeach
                            @else
                            <option value="">Sem Nenhum Curso cadastrado</option>
                            @endif
                        </select>
                        <span class="text-danger error-text classes_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="turnos_id">Turno <span class="text-danger">*</span></label>
                        <select name="turnos_id" id="turnos_id" class="form-control editar_turnos_id " style="width: 100%">
                            <option value="">Selecione Turno</option>
                            @if ($turnos)
                            @foreach ($turnos as $turno)
                            <option value="{{ $turno->turno->id }}">{{ $turno->turno->turno }}</option>
                            @endforeach
                            @else
                            <option value="">Sem Nenhum Turno cadastrado</option>
                            @endif
                        </select>
                        <span class="text-danger error-text turnos_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="cursos_id">Curso <span class="text-danger">*</span></label>
                        <select name="cursos_id" id="cursos_id" class="form-control editar_cursos_id " style="width: 100%">
                            <option value="">Selecione Curso</option>
                            @if ($cursos)
                            @foreach ($cursos as $curso)
                            <option value="{{ $curso->curso->id }}">{{ $curso->curso->curso }}</option>
                            @endforeach
                            @else
                            <option value="">Sem Nenhum Curso cadastrado</option>
                            @endif
                        </select>
                        <span class="text-danger error-text cursos_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="salas_id">Sala <span class="text-danger">*</span></label>
                        <select name="salas_id" id="salas_id" class="form-control editar_salas_id " style="width: 100%">
                            <option value="">Selecione Sala</option>
                            @if ($salas)
                            @foreach ($salas as $sala)
                            <option value="{{ $sala->sala->id }}">{{ $sala->sala->salas }}</option>
                            @endforeach
                            @else
                            <option value="">Sem Nenhum Sala cadastrado</option>
                            @endif
                        </select>
                        <span class="text-danger error-text salas_id_error"></span>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="ano_lectivos_id">Ano Lectivo <span class="text-danger">*</span></label>
                        <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control editar_ano_lectivos_id " style="width: 100%">
                            <option value="">Selecione Ano Lectivo</option>
                            @if ($anolectivos)
                            @foreach ($anolectivos as $anolectivo)
                            <option value="{{ $anolectivo->id }}">{{ $anolectivo->ano }}</option>
                            @endforeach
                            @else
                            <option value="">Sem Nenhum Ano Lectivo cadastrado</option>
                            @endif
                        </select>
                        <span class="text-danger error-text ano_lectivos_id_error"></span>
                    </div>

                </div>
            </div>

            @if ($escola->categoria == 'Privado')

            <div class="modal-body">
                <h5 class="bg-light py-3 px-2">Definições Financeiras</h5>
                <hr class="">
                <div class="row">
                    <div class="form-group col-md-3 col-12">
                        <label for="valor_confirmacao">Valor Confirmação <span class="text-danger">*</span></label>
                        <input type="text" name="valor_confirmacao" class="form-control editar_valor_confirmacao" placeholder="Definir valor da confirmação" value="0">
                        <span class="text-danger error-text valor_confirmacao_error"></span>
                    </div>

                    <div class="form-group col-md-3 col-12">
                        <label for="valor_matricula">Valor Matricula <span class="text-danger">*</span></label>
                        <input type="text" name="valor_matricula" class="form-control editar_valor_matricula" placeholder="Definir valor da matricula" value="0">
                        <span class="text-danger error-text valor_matricula_error"></span>
                    </div>

                    <div class="form-group col-md-3 col-12">
                        <label for="valor_propina">Valor Propina <span class="text-danger">*</span></label>
                        <input type="text" name="valor_propina" class="form-control editar_valor_propina" placeholder="Definir valor da propina" value="0">
                        <span class="text-danger error-text valor_propina_error"></span>
                    </div>
                </div>
            </div>

            <div class="modal-body">
                <h5 class="bg-light py-3 px-2">Intervalo dos dias para se efectuar o pagamento de mensalidades. Taxa de Multa para atraso de mensalidades.</h5>
                <hr class="">
                <div class="row">
                    <div class="form-group mb-3 col-md-3 col-12">
                        <label for="intervalo_pagamento_inicio" class="form-label">Dia Inicial para o pagamento <span class="text-danger">*</span></label>
                        <input type="number" name="intervalo_pagamento_inicio" value="0" placeholder="Dia inicial Ex: 1" id="intervalo_pagamento_inicio" class="form-control editar_intervalo_pagamento_inicio">
                        @error('intervalo_pagamento_inicio')
                        <span class="text-danger error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3 col-md-3 col-12">
                        <label for="intervalo_pagamento_final" class="form-label">Dia Final para o pagamento <span class="text-danger">*</span></label>
                        <input type="number" name="intervalo_pagamento_final" value="0" placeholder="Dia final Ex: 15" id="intervalo_pagamento_final" class="form-control editar_intervalo_pagamento_final">
                        @error('intervalo_pagamento_final')
                        <span class="text-danger error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3 col-md-3 col-12">
                        <label for="taxa_multa1_dia" class="form-label">Dia de Atraso para aplica 1º Taxa <span class="text-danger">*</span></label>
                        <input type="number" name="taxa_multa1_dia" value="0" placeholder="Dias de Atraso para primeira taxa" id="taxa_multa1_dia" class="form-control editar_taxa_multa1_dia">
                        @error('taxa_multa1_dia')
                        <span class="text-danger error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3 col-md-3 col-12">
                        <label for="taxa_multa1" class="form-label">Valor 1º Taxa (%) <span class="text-danger">*</span></label>
                        <input type="number" name="taxa_multa1" value="0" placeholder="Dia final Ex: 15" id="taxa_multa1" class="form-control editar_taxa_multa1">
                        @error('taxa_multa1')
                        <span class="text-danger error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3 col-md-3 col-12">
                        <label for="taxa_multa2_dia" class="form-label">Dia de Atraso para aplica 2º Taxa <span class="text-danger">*</span></label>
                        <input type="number" name="taxa_multa2_dia" value="0" placeholder="Dias de Atraso para segunda taxa" id="taxa_multa2_dia" class="form-control editar_taxa_multa2_dia">
                        @error('taxa_multa2_dia')
                        <span class="text-danger error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3 col-md-3 col-12">
                        <label for="taxa_multa2" class="form-label">Valor 2º Taxa (%) <span class="text-danger">*</span></label>
                        <input type="number" name="taxa_multa2" value="0" placeholder="Dia final Ex: 15" id="taxa_multa2" class="form-control editar_taxa_multa2">
                        @error('taxa_multa2')
                        <span class="text-danger error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3 col-md-3 col-12">
                        <label for="taxa_multa3_dia" class="form-label">Dia de Atraso para aplica 3º Taxa <span class="text-danger">*</span></label>
                        <input type="number" name="taxa_multa3_dia" value="0" placeholder="Dias de Atraso para terceira taxa" id="taxa_multa3_dia" class="form-control editar_taxa_multa3_dia">
                        @error('taxa_multa3_dia')
                        <span class="text-danger error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group mb-3 col-md-3 col-12">
                        <label for="taxa_multa3" class="form-label">Valor 3º Taxa (%)</label>
                        <input type="number" value="0" name="taxa_multa3" placeholder="Dia final Ex: 15" id="taxa_multa3" class="form-control editar_taxa_multa3">
                        @error('taxa_multa3')
                        <span class="text-danger error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            @endif

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success editar_turmas_form">Actualizar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        @if (Auth::user()->can('create: turma'))
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalFormCadastraTurmas">Nova Turma</a>
                        @endif
                        <a href="{{ route('turmas-imprmir') }}" class="btn-danger btn float-end mx-1" target="_blink"> Imprimir PDF</a>
                        <a href="{{ route('turmas-excel') }}" class="btn-success btn float-end mx-1" target="_blink"> Imprimir Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Turma</th>
                                    <th>Status</th>
                                    <th>Classe</th>
                                    <th>Curso</th>
                                    <th>Turno</th>
                                    <th>Sala</th>
                                    <th>Limite Estudantes</th>
                                    <th>Ano Lectivo</th>
                                    <th>Acções</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @if (count($turmas) != 0)
                                    @foreach ($turmas as $item)
                                    <tr>
                                        <td><a href="{{ route('web.apresentar-turma-informacoes', Crypt::encrypt($item->id)) }}">{{ $item->turma }}</a></td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ $item->classe->classes ?? "" }}</td>
                                        <td>{{ $item->curso->curso ?? "" }}</td>
                                        <td>{{ $item->turno->turno ?? "" }}</td>
                                        <td>{{ $item->sala->salas ?? "" }}</td>
                                        <td>{{ $item->numero_maximo }}</td>
                                        <td>{{ $item->anolectivo->ano ?? "" }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info">Opções</button>
                                                <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                    @if (Auth::user()->can('update: turma'))
                                                    <a href="#" title="Editar Turma" id="{{ $item->id }}" class="dropdown-item editar_turmas_id"><i class="fa fa-edit"></i> Editar </a>
                                                    @endif
    
                                                    @if (Auth::user()->can('delete: turma'))
                                                    <a href="#" title="Excluir Turma" id="{{ $item->id }}" class="dropdown-item deleteModal"> <i class="fa fa-trash"></i> Excluir </a>
                                                    @endif
    
                                                    @if (Auth::user()->can('read: turma'))
                                                    <a href="{{ route('web.apresentar-turma-informacoes', Crypt::encrypt($item->id)) }}" title="Visualizar Turma" class="dropdown-item"><i class="fa fa-eye"></i> Visualizar </a>
                                                    <a href="{{ route('web.turmas-configuracao', Crypt::encrypt($item->id)) }}" title="configuração da Turma" class="dropdown-item configuracao_turm"><i class="fa fa-cog"></i> Configurar </a>
                                                    @endif
    
                                                    @if (Auth::user()->can('update: estado'))
                                                    <a href="#" title="Activar ou Desactivar" id="{{ $item->id }}" class="dropdown-item update-record"><i class="fa fa-check-square-o"></i> Activar e Desactivar </a>
                                                    @endif
                                                    
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#">Outros</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
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
    var turmaselecionadaOperacao;

    // editar
    $(document).on('click', '.editar_turmas_id', function(e) {
        e.preventDefault();
        var novo_id = $(this).attr('id');
        $("#modalFormEditarTurmas").modal("show");

        $.ajax({
            type: "GET"
            , url: "editar-turmas/" + novo_id
            , beforeSend: function() {
                progressBeforeSend();
            }
            , success: function(response) {
                Swal.close();
                $('.editar_nome_turmas').val(response.turmas.turma);
                $('.editar_status_turmas').val(response.turmas.status);
                $('.editar_classes_id').val(response.turmas.classes_id);
                $('.editar_cursos_id').val(response.turmas.cursos_id);
                $('.editar_turnos_id').val(response.turmas.turnos_id);
                $('.editar_salas_id').val(response.turmas.salas_id);

                $('.editar_intervalo_pagamento_inicio').val(response.turmas.intervalo_pagamento_inicio);
                $('.editar_intervalo_pagamento_final').val(response.turmas.intervalo_pagamento_final);
                $('.editar_taxa_multa1_dia').val(response.turmas.taxa_multa1_dia);
                $('.editar_taxa_multa1').val(response.turmas.taxa_multa1);
                $('.editar_taxa_multa2_dia').val(response.turmas.taxa_multa2_dia);
                $('.editar_taxa_multa2').val(response.turmas.taxa_multa2);
                $('.editar_taxa_multa3_dia').val(response.turmas.taxa_multa3_dia);
                $('.editar_taxa_multa3').val(response.turmas.taxa_multa3);

                $('.editar_valor_propina').val(response.turmas.valor_propina);
                $('.editar_valor_matricula').val(response.turmas.valor_matricula);
                $('.editar_valor_confirmacao').val(response.turmas.valor_confirmacao);
                $('.editar_ano_lectivos_id').val(response.turmas.ano_lectivos_id);
                $('.editar_turma_id').val(response.turmas.id);
                $('.editar_numero_maximo').val(response.turmas.numero_maximo);
            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        });
    });

    // actualizar
    $(document).on('click', '.editar_turmas_form', function(e) {
        e.preventDefault();

        var id = $('.editar_turma_id').val();
        
        var data = {
            'nome_turmas': $('.editar_nome_turmas').val()
            , 'status_turmas': $('.editar_status_turmas').val()
            , 'classes_id': $('.editar_classes_id').val()
            , 'turnos_id': $('.editar_turnos_id').val()
            , 'salas_id': $('.editar_salas_id').val()
            , 'valor_propina': $('.editar_valor_propina').val()
            , 'valor_matricula': $('.editar_valor_matricula').val()
            , 'valor_confirmacao': $('.editar_valor_confirmacao').val(),

            'intervalo_pagamento_inicio': $('.editar_intervalo_pagamento_inicio').val()
            , 'intervalo_pagamento_final': $('.editar_intervalo_pagamento_final').val()
            , 'taxa_multa1_dia': $('.editar_taxa_multa1_dia').val()
            , 'taxa_multa1': $('.editar_taxa_multa1').val()
            , 'taxa_multa2_dia': $('.editar_taxa_multa2_dia').val()
            , 'taxa_multa2': $('.editar_taxa_multa2').val()
            , 'taxa_multa3_dia': $('.editar_taxa_multa3_dia').val()
            , 'taxa_multa3': $('.editar_taxa_multa3').val(),


            'cursos_id': $('.editar_cursos_id').val()
            , 'ano_lectivos_id': $('.editar_ano_lectivos_id').val()
            , 'numero_maximo': $('.editar_numero_maximo').val(),

        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "PUT"
            , url: "editar-turmas/" + id
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


  });

</script>

<script>
    $(function() {
        const tabelas = [
            "#carregarTabela"
        , ];
        tabelas.forEach(inicializarTabela);

        ajaxFormSubmit('#formCreate');
        
        bindStatusUpdate('.update-record', `{{ route('turmas.activar-turmas', ':id') }}`);
        excluirRegistro('.deleteModal', `{{ route('turmas.excluir-turmas', ':id') }}`);
    });
</script>
@endsection
