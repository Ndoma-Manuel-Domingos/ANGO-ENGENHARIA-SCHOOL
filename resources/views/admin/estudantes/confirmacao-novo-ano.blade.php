@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Confirmação de estudantes novo ano lectivo</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.estudantes-confirmacao') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Estudantes</li>
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
                <form action="{{ route('web.estudantes-confirmacao-novo-ano-post') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header bg-light">
                            <p>Dados Academicos para confirmação</p>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-3 col-12">
                                    <label for="ano_lectivos_id">Ano Lectivo <span class="text-danger">*</span></label>
                                    <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control ano_lectivos_id select2">
                                        @foreach ($anolectivos as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $matricula->ano_lectivos_id ? 'selected' : ''  }}>{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                    @error('ano_lectivos_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="at_classes_id">Classe Anterior <span class="text-danger">*</span></label>
                                    <select name="at_classes_id" id="at_classes_id" class="form-control at_classes_id select2">
                                        <option value="">Selecione</option>
                                        @if ($classes)
                                        @foreach ($classes as $classe)
                                        <option value="{{ $classe->classe->id }}" {{ $matricula->at_classes_id ==
                                            $classe->classe->id ? 'selected' : '' }}>{{ $classe->classe->classes }}
                                        </option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Curso cadastrado</option>
                                        @endif
                                    </select>
                                    @error('at_classes_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="classes_id">Classe <span class="text-danger">*</span></label>
                                    <select name="classes_id" id="classes_id" class="form-control classes_id select2">
                                        <option value="">Selecione</option>
                                        @if ($classes)
                                        @foreach ($classes as $classe)
                                        <option value="{{ $classe->classe->id }}" {{ $matricula->classes_id ==
                                            $classe->classe->id ? 'selected' : '' }}>{{ $classe->classe->classes }}
                                        </option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Curso cadastrado</option>
                                        @endif
                                    </select>
                                    @error('classes_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="cursos_id">Curso <span class="text-danger">*</span></label>
                                    <select name="cursos_id" id="cursos_id" class="form-control cursos_id select2">
                                        <option value="">Selecione</option>
                                        @if ($cursos)
                                        @foreach ($cursos as $curso)
                                        <option value="{{ $curso->curso->id }}" {{ $matricula->cursos_id ==
                                            $curso->curso->id ? 'selected' : '' }}>{{ $curso->curso->curso }}</option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Curso cadastrado</option>
                                        @endif
                                    </select>
                                    @error('cursos_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="turnos_id">Turno <span class="text-danger">*</span></label>
                                    <select name="turnos_id" id="turnos_id" class="form-control turnos_id select2">
                                        <option value="">Selecione</option>
                                        @if ($turnos)
                                        @foreach ($turnos as $turno)
                                        <option value="{{ $turno->turno->id }}" {{ $matricula->turnos_id ==
                                            $turno->turno->id ? 'selected' : '' }}>{{ $turno->turno->turno }}</option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Turno cadastrado</option>
                                        @endif
                                    </select>
                                    @error('turnos_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="tipo_matricula">Situação <span class="text-danger">*</span></label>
                                    <select name="tipo_matricula" id="tipo_matricula" class="form-control tipo_matricula select2">
                                        <option value="">Selecione</option>
                                        <option value="confirmacao" selected>Confirmação</option>
                                    </select>
                                    <span class="text-danger error-text tipo_error"></span>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="situacao_estudante">Situação Estudante <span class="text-danger">*</span></label>
                                    <select name="situacao_estudante" id="situacao_estudante" class="form-control situacao_estudante select2">
                                        <option value="">Selecione</option>
                                        <option value="Novo">Novo</option>
                                        <option value="Transferido">Transferido</option>
                                        <option value="Destistente">Destistente</option>
                                        <option value="Antigo" selected>Antigo</option>
                                        <option value="Repitente">Repitente</option>
                                    </select>
                                    @error('situacao_estudante')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="condicao_estudante">Condição Estudante <span class="text-danger">*</span></label>
                                    <select name="condicao_estudante" id="condicao_estudante" class="form-control condicao_estudante select2">
                                        <option value="">Condição do estudante Propinas</option>
                                        <option value="Isento" {{ $matricula->condicao == 'Isento' ? 'selected' : ''
                                            }}>Isento</option>
                                        <option value="Paga" {{ $matricula->condicao == 'Paga' ? 'selected' : ''
                                            }}>Sujeito a mensalidade</option>
                                    </select>
                                    <span class="text-danger error-text condicao_estudante_error"></span>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </div>

                    @if ($escola->processo_pagamento_servico == "Secretaria")
                        @if ($escola->categoria == "Privado" && $escola->modulo != "Basico")
                            <div class="card">
                                <div class="card-header bg-light">
                                    <p>Dados dos Pagamentos <span class="float-right fs-3 text-danger">Troco <strong id="valor_troco_apresenta">0</strong></span></p>
                                </div>
        
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3 col-12">
                                            <label for="servicos_id">Selecione o Tipo de serviço <span class="text-danger">*</span></label>
                                            <select name="servicos_id" id="servicos_id" class="form-control servicos_id select2 @error('servicos_id') is-invalid @enderror">
                                                <option value="">Selecione</option>
                                            </select>
                                            @error('servicos_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
        
                                        <div class="form-group col-md-3 col-12">
                                            <label for="valor">Total a Pagar <span class="text-danger">*</span></label>
                                            <input type="text" value="{{ old('valor') }}" name="valor" class="form-control valor @error('valor') is-invalid @enderror" placeholder="Valor do Pagamento" disabled>
                                            @error('valor')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
        
                                        <input type="hidden" name="valor" class="valor">
                                        <input type="hidden" name="valor" class="valor valor_total_a_pagar" value="{{ old('valor') ?? '' }}">
        
                                        <div class="form-group col-12  col-md-3">
                                            <label for="valor_entregue">Digite o Valor Entregue <span class="text-danger">*</span></label>
                                            <input type="text" value="{{ old('valor_entregue') }}" name="valor_entregue" class="form-control valor_entregue" placeholder="Valor Entregue pelo Estudante">
                                            <span class="text-danger error-text valor_entregue_error @error('valor_entregue') is-invalid @enderror"></span>
                                            @error('valor_entregue')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
        
        
                                        <div class="form-group col-md-3 col-12">
                                            <label for="desconto">Desconto <span class="text-danger">*</span></label>
                                            <input type="number" value="{{ old('desconto') }}" min="0" max="100" name="desconto" class="form-control desconto @error('desconto') is-invalid @enderror" placeholder="Informe o Desconto %" value="0">
                                            @error('desconto')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
        
                                        <div class="form-group col-md-3 col-12">
                                            <label for="documento">Tipo de Documento <span class="text-danger">*</span></label>
                                            <select name="documento" id="documento" class="form-control documento select2 @error('documento') is-invalid @enderror">
                                                <option value="">Selecione o Pagamento</option>
                                                <option value="FR" {{ old('documento')=="FR" ? 'selected' : 'selected' }}> Factura Recibo</option>
                                                <option value="PP" {{ old('documento')=="PP" ? 'selected' : '' }}>Factura Pró-forma</option>
                                                <option value="FT" {{ old('documento')=="FT" ? 'selected' : '' }}>Factura </option>
                                            </select>
                                            @error('documento')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
        
                                        <div class="form-group col-md-3 col-12">
                                            <label for="tipo_pagamento">Forma de Pagamento <span class="text-danger">*</span></label>
                                            <select name="tipo_pagamento" id="tipo_pagamento" class="form-control tipo_pagamento select2 @error('tipo_pagamento') is-invalid @enderror">
                                                <option value="NU" {{ old('tipo_pagamento')=="NU" ? 'selected' : '' }}>NUMERARIO
                                                </option>
                                                <option value="CARTÃO" {{ old('tipo_pagamento')=="CARTÃO" ? 'selected' : '' }}> CARTÃO</option>
                                                <option value="MB" {{ old('tipo_pagamento')=="MB" ? 'selected' : '' }}> MULTICAIXA</option>
                                                <option value="CAIXA ANGOLA" {{ old('tipo_pagamento')=="CAIXA ANGOLA" ? 'selected' : '' }}>CAIXA ANGOLA</option>
                                                <option value="TPA" {{ old('tipo_pagamento')=="TPA" ? 'selected' : '' }}>TPA </option>
                                                <option value="CARTÃO CRÉDITO" {{ old('tipo_pagamento')=="CARTÃO CRÉDITO" ? 'selected' : '' }}>CARTÃO CRÉDITO</option>
                                                <option value="CARTÃO DEBITO" {{ old('tipo_pagamento')=="CARTÃO DEBITO" ? 'selected' : '' }}>CARTÃO DEBITO</option>
                                                <option value="TRANSFERÊNCIA" {{ old('tipo_pagamento')=="TRANSFERÊNCIA" ? 'selected' : '' }}>TRANSFERÊNCIA</option>
                                                <option value="OUTRO" {{ old('tipo_pagamento')=="OUTRO" ? 'selected' : '' }}> OUTRO</option>
                                            </select>
                                            @error('tipo_pagamento')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
        
                                        <div class="form-group col-md-3 col-12">
                                            <label for="banco">Banco (Opcional)</label>
                                            <select name="banco" id="banco" class="form-control banco select2 @error('banco') is-invalid @enderror">
                                                <option value="">Selecione</option>
                                                <option value="Nenhum" {{ old('banco')=="Nenhum" ? 'selected' : '' }}>Nenhum
                                                </option>
                                                <option value="BFA" {{ old('banco')=="BFA" ? 'selected' : '' }}>BFA</option>
                                                <option value="BPC" {{ old('banco')=="BPC" ? 'selected' : '' }}>BPC</option>
                                                <option value="BIC" {{ old('banco')=="BIC" ? 'selected' : '' }}>BIC</option>
                                                <option value="BAI" {{ old('banco')=="BAI" ? 'selected' : '' }}>BAI</option>
                                                <option value="BAI" {{ old('banco')=="BAI" ? 'selected' : '' }}>BCA</option>
                                                <option value="ATLANTICO" {{ old('banco')=="ATLANTICO" ? 'selected' : '' }}>
                                                    ATLANTICO</option>
                                                <option value="CAIXA ANGOLA" {{ old('banco')=="CAIXA ANGOLA" ? 'selected' : ''
                                                            }}>CAIXA ANGOLA</option>
                                                <option value="OUTROS" {{ old('banco')=="OUTROS" ? 'selected' : '' }}>OUTROS
                                                </option>
                                            </select>
                                            @error('banco')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
        
                                        <div class="form-group col-md-3 col-12">
                                            <label for="sobre_nome">Número de Transição (Opcional)</label>
                                            <input type="text" value="{{ old('numero_transicao') }}" name="numero_transicao" class="form-control numero_transicao @error('numero_transicao') is-invalid @enderror" placeholder="Número da seríe ou ordem Bancaria">
                                            @error('numero_transicao')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
        
                                    </div>
                                </div>
                                <div class="card-footer"></div>
                            </div>
                        @endif
                    @endif
                    
                    @if ($escola->categoria == "Privado" && $escola->modulo != "Basico")
                        <div class="card">
                            <div class="card-header bg-light">
                                <p>Actualizar Documentos</p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3 mb-3 col-12">
                                        <label for="doc_bilheite">Bilhete de Identidade (Opcional)</label>
                                        <input type="file" name="doc_bilheite" accept=".pdf" id="doc_bilheite" class="form-control doc_bilheite">
                                        @error('doc_bilheite')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                        <input type="hidden" name="doc_bilheite_guardado" accept=".pdf" value="{{ $arquivo->bilheite ?? '' }}" id="doc_bilheite_guardado" class="form-control doc_bilheite_guardado">
                                    </div>
    
                                    <div class="form-group col-md-3 mb-3 col-12">
                                        <label for="doc_certificado">Certificado (Opcional)</label>
                                        <input type="file" name="doc_certificado" accept=".pdf" id="doc_certificado" class="form-control doc_certificado">
                                        @error('doc_certificado')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                        <input type="hidden" name="doc_certificado_guardado" accept=".pdf" value="{{ $arquivo->certificado ?? '' }}" id="doc_certificado_guardado" class="form-control doc_certificado_guardado">
                                    </div>
    
                                    <div class="form-group col-md-3 mb-3 col-12">
                                        <label for="doc_atestedao_medico">Atestado Médico (Opcional)</label>
                                        <input type="file" name="doc_atestedao_medico" accept=".pdf" id="doc_atestedao_medico" class="form-control doc_atestedao_medico">
                                        @error('doc_atestedao_medico')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                        <input type="hidden" name="doc_atestedao_medico_guardado" value="{{ $arquivo->atestado ?? '' }}" accept=".pdf" id="doc_atestedao_medico_guardado" class="form-control doc_atestedao_medico_guardado">
                                    </div>
    
                                    <div class="form-group col-md-3 mb-3 col-12">
                                        <label for="doc_outros">Outros Documentos (Opcional)</label>
                                        <input type="file" name="doc_outros" accept=".pdf" class="form-control doc_outros" id="doc_outros">
                                        @error('doc_outros')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                        <input type="hidden" name="doc_outros_guardado" accept=".pdf" value="{{ $arquivo->outros ?? '' }}" class="form-control doc_outros_guardado" id="doc_outros_guardado">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary concluir_cadastro_estudante">Concluir</button>
                            </div>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header bg-light">
                            <p>Dados Pessoais</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3 col-12">
                                    <label for="nome_turmas">Nome</label>
                                    <input type="text" class="form-control nome" placeholder="Nome" value="{{ $matricula->estudante->nome ?? '' }}" disabled>
                                    @error('nome')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="sobre_nome">Sobrenome</label>
                                    <input type="text" class="form-control sobre_nome" placeholder="Sobrenome" value="{{ $matricula->estudante->sobre_nome ?? '' }}" disabled>
                                    @error('sobre_nome')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="pai">Pai</label>
                                    <input type="text" class="form-control pai" placeholder="Nome Completo do Pai" value="{{ $matricula->estudante->pai ?? '' }}" disabled>
                                    @error('pai')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="mae">Mãe</label>
                                    <input type="text" class="form-control mae" placeholder="Nome Completo da Mãe" value="{{ $matricula->estudante->mae ?? '' }}" disabled>
                                    @error('mae')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="nascimento">Data Nascimento</label>
                                    <input type="date" class="form-control nascimento" value="{{ $matricula->estudante->nascimento ?? '' }}" disabled>
                                    @error('nascimento')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="genero">Genero</label>
                                    <select id="genero" class="form-control genero select2" disabled>
                                        <option value="">Selecione Genero</option>
                                        <option value="Masculino" selected>{{ $matricula->estudante->genero ?? '' }}
                                        </option>
                                    </select>
                                    <span class="text-danger error-text genero_error"></span>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="estado_civil">Estado Cívil</label>
                                    <select id="estado_civil" class="form-control estado_civil select2" disabled>
                                        <option value="">Selecione Status</option>
                                        <option value="" selected>{{ $matricula->estudante->estado_civil ?? '' }}
                                        </option>
                                    </select>
                                    <span class="text-danger error-text estado_civil_error"></span>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="" class="form-label">País</label>
                                    <select id="pais_id" class="form-control select2 pais_id" style="width: 100%" disabled>
                                        <option value="">Selecione o País</option>
                                        <option value="{{ $matricula->estudante->pais_id }}" selected>{{
                                            $matricula->estudante->pais->name ?? '' }}</option>
                                    </select>
                                    @error('pais_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="" class="form-label">Provincia</label>
                                    <select id="provincia_id" class="form-control select2 provincia_id" style="width: 100%" disabled>
                                        <option value="">Selecione a província</option>
                                        <option value="" selected>{{ $matricula->estudante->provincia->nome ?? '' }}
                                        </option>
                                    </select>
                                    @error('provincia_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="" class="form-label">Municípios</label>
                                    <select id="municipio_id" class="form-control select2 municipio_id" style="width: 100%" disabled>
                                        <option value="" selected>{{ $matricula->estudante->municipio->nome ?? '' }}
                                        </option>
                                    </select>
                                    @error('municipio_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="" class="form-label">Distrito</label>
                                    <select id="distrito_id" class="form-control select2 distrito_id" style="width: 100%" disabled>
                                        <option value="" selected>{{ $matricula->estudante->distrito->nome ?? '' }}
                                        </option>
                                    </select>
                                    @error('distrito_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="nascimento">Natural de: </label>
                                    <input type="text" class="form-control naturalidade" value="{{ $matricula->estudante->naturalidade ?? '' }}" disabled placeholder="Informe a naturalidade">
                                    <span class="text-danger error-text naturalidade_error"></span>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="bilheite">B.I/CÉDULA</label>
                                    <input type="text" class="form-control bilheite" value="{{ $matricula->estudante->bilheite ?? '' }}" placeholder="Nº B.I" disabled>
                                    @error('bilheite')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="dificiencia">Deficiência</label>
                                    <select id="dificiencia" class="form-control dificiencia" disabled>
                                        <option value="">Selecione</option>
                                        <option value="Nenhuma" {{ $matricula->estudante->dificiencia == 'Nenhuma' ?
                                            'selected' : '' }}>Nenhuma</option>
                                        <option value="Auditiva" {{ $matricula->estudante->dificiencia == 'Auditiva' ?
                                            'selected' : '' }}>Auditiva</option>
                                        <option value="Visual" {{ $matricula->estudante->dificiencia == 'Visual' ?
                                            'selected' : '' }}>Visual</option>
                                        <option value="Motora" {{ $matricula->estudante->dificiencia == 'Motora' ?
                                            'selected' : '' }}>Motora</option>
                                        <option value="Outras" {{ $matricula->estudante->dificiencia == 'Outras' ?
                                            'selected' : '' }}>Outras</option>
                                    </select>
                                    <span class="text-danger error-text dificiencia_error"></span>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="telefone">Telefone estudante</label>
                                    <input type="text" class="form-control telefone" value="{{ $matricula->estudante->telefone_estudante ?? '' }}" placeholder="Terminal do Estudante" disabled>
                                    @error('telefone')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="telefone_pai">Telefone do Pai</label>
                                    <input type="text" class="form-control telefone_pai" value="{{ $matricula->estudante->telefone_pai ?? '' }}" placeholder="Terminal do Pai" disabled>
                                    @error('telefone_pai')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-1 col-12">
                                    <label for="telefone_mae">Telefone da Mãe</label>
                                    <input type="text" class="form-control telefone_mae" value="{{ $matricula->estudante->telefone_mae ?? '' }}" placeholder="Terminal do Mãe" disabled>
                                    @error('telefone_mae')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label for="endereco" class="form-label">Endereço da Morada</label>
                                    <textarea class="form-control endereco" placeholder="descrever endereço" id="endereco" rows="3" disabled>{{ $matricula->estudante->endereco ?? '' }}</textarea>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-light">
                            <p>Dados Academicos</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3 col-12">
                                    <label for="at_classes_id">Classe Anterior</label>
                                    <select id="at_classes_id" class="form-control at_classes_id" disabled>
                                        <option value="">Selecione</option>
                                        @if ($classes)
                                        @foreach ($classes as $classe)
                                        <option value="{{ $classe->classe->id ?? '' }}" {{ $matricula->at_classes_id ==
                                            $classe->classe->id ? 'selected' : '' }}>{{ $classe->classe->classes ?? ''
                                            }}</option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Curso cadastrado</option>
                                        @endif
                                    </select>

                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="classes_id">Classe</label>
                                    <select id="classes_id" class="form-control classes_id" disabled>
                                        <option value="">Selecione</option>
                                        @if ($classes)
                                        @foreach ($classes as $classe)
                                        <option value="{{ $classe->classe->id ?? '' }}" {{ $matricula->classes_id ==
                                            $classe->classe->id ? 'selected' : '' }}>{{ $classe->classe->classes ?? ''
                                            }}</option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Curso cadastrado</option>
                                        @endif
                                    </select>

                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="cursos_id">Curso</label>
                                    <select id="cursos_id" class="form-control cursos_id" disabled>
                                        <option value="">Selecione</option>
                                        @if ($cursos)
                                        @foreach ($cursos as $curso)
                                        <option value="{{ $curso->curso->id ?? '' }}" {{ $matricula->cursos_id ==
                                            $curso->curso->id ? 'selected' : '' }}>{{ $curso->curso->curso ?? '' }}
                                        </option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Curso cadastrado</option>
                                        @endif
                                    </select>

                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="turnos_id">Turno</label>
                                    <select id="turnos_id" class="form-control turnos_id" disabled>
                                        <option value="">Selecione</option>
                                        @if ($turnos)
                                        @foreach ($turnos as $turno)
                                        <option value="{{ $turno->turno->id ?? '' }}" {{ $matricula->turnos_id ==
                                            $turno->turno->id ? 'selected' : '' }}>{{ $turno->turno->turno ?? '' }}
                                        </option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Turno cadastrado</option>
                                        @endif
                                    </select>

                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="tipo_matricula">Situação</label>
                                    <select id="tipo_matricula" class="form-control tipo_matricula" disabled>
                                        <option value="">Selecione</option>
                                        <option value="matricula" selected>{{ $matricula->tipo ?? '' }}</option>
                                    </select>
                                    <span class="text-danger error-text tipo_error"></span>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="situacao_estudante">Situação Estudante</label>
                                    <select id="situacao_estudante" class="form-control situacao_estudante" disabled>
                                        <option value="" selected>{{ $matricula->status ?? '' }}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="condicao_estudante">Condição Estudante</label>
                                    <select id="condicao_estudante" class="form-control condicao_estudante" disabled>
                                        <option value="" selected>{{ $matricula->condicao ?? '' }}</option>
                                    </select>
                                    <span class="text-danger error-text condicao_estudante_error"></span>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="ano_lectivos_id">Ano Lectivo</label>
                                    <select id="ano_lectivos_id" class="form-control ano_lectivos_id" disabled>
                                        <option value="">Selecione</option>
                                        @if ($anolectivos)
                                        @foreach ($anolectivos as $anolectivo)
                                        <option selected>{{ $matricula->ano_lectivo->ano }}</option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Ano Lectivo cadastrado</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </div>

                    <input type="hidden" name="documento_estudante" value="{{ $matricula->documento }}">
                    <input type="hidden" name="id_matricula" value="{{ $matricula->id }}">
                    <input type="hidden" name="id_estudante" value="{{ $estudante->id }}">

                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')

<script>
    var turmasServico;

    $("#ano_lectivos_id").change(() => {
        let id = $("#ano_lectivos_id").val();
        $.get(`/get-dados-ano-lectivo/${id}`, function(data) {

            $('#at_classes_id').html("");
            $('#classes_id').html("");
            $('#cursos_id').html("");
            $('#turnos_id').html("");

            $('#at_classes_id').append('<option value="">Selecione uma classe</option>');
            for (let index = 0; index < data.classes.length; index++) {
                $('#at_classes_id').append('<option value="' + data.classes[index].classe.id + '">' + data.classes[index].classe.classes + '</option>');
            }

            $('#classes_id').append('<option value="">Selecione uma classe</option>');
            for (let index = 0; index < data.classes.length; index++) {
                $('#classes_id').append('<option value="' + data.classes[index].classe.id + '">' + data.classes[index].classe.classes + '</option>');
            }

            $('#cursos_id').append('<option value="">Selecione um curso</option>');
            for (let index = 0; index < data.cursos.length; index++) {
                $('#cursos_id').append('<option value="' + data.cursos[index].curso.id + '">' + data.cursos[index].curso.curso + '</option>');
            }

            $('#turnos_id').append('<option value="">Selecione um turno</option>');
            for (let index = 0; index < data.turnos.length; index++) {
                $('#turnos_id').append('<option value="' + data.turnos[index].turno.id + '">' + data.turnos[index].turno.turno + '</option>');
            }
        })
    })

    $("#classes_id").change(() => {
        let id = $("#classes_id").val();
        let id_ano_lectivo = $("#ano_lectivos_id").val();
        let id_cursos = $("#cursos_id").val();
        $.get('../../carregar-servicos-turmas?ano_lectivos_id=' + id_ano_lectivo + '&classes_id=' + id + '&cursos_id=' + id_cursos, function(data) {
            $('#servicos_id').html("");
            $('#servicos_id').append('<option value="">Selecione um Serviço</option>');
            for (let index = 0; index < data.servicos.length; index++) {
                $('#servicos_id').append('<option value="' + data.servicos[index].id + '">' + data.servicos[index].servico + '</option>');
            }
            turmasServico = data.turma.id;
        })
    })

    $("#cursos_id").change(() => {
        let id = $("#cursos_id").val();
        let id_ano_lectivo = $("#ano_lectivos_id").val();
        let id_classes = $("#classes_id").val();
        $.get('../../carregar-servicos-turmas?ano_lectivos_id=' + id_ano_lectivo + '&classes_id=' + id_classes + '&cursos_id=' + id, function(data) {
            $('#servicos_id').html("");
            $('#servicos_id').append('<option value="">Selecione um Serviço</option>');
            for (let index = 0; index < data.servicos.length; index++) {
                $('#servicos_id').append('<option value="' + data.servicos[index].id + '">' + data.servicos[index].servico + '</option>');
            }
            turmasServico = data.turma.id;
        })
    })

    $("#servicos_id").change(() => {
        let id = $("#servicos_id").val();
        let id_ano_lectivo = $("#ano_lectivos_id").val();
        $.get(`../../carregar-valor-servicos-turmas/${id}/${turmasServico}/${id_ano_lectivo}`, function(data) {
            $('.valor').val("");
            $('.valor').val(data.servico.preco);
        })
    })

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

    $("#provincia_id").change(() => {
        let id = $("#provincia_id").val();
        $.get('../carregar-municipios/' + id, function(data) {
            $("#municipio_id").html("")
            $("#municipio_id").html(data)
        })
    })

    $("#municipio_id").change(() => {
        let id = $("#municipio_id").val();
        $.get('../carregar-distritos/' + id, function(data) {
            $("#distrito_id").html("")
            $("#distrito_id").html(data)
        })
    })


    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.location.href = response.redirect;
                    // window.location.reload();
                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n *`; // Exibe os erros
                        });
                        showMessage('Erro de Validação!', messages, 'error');
                    } else {
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                }
            , });
        });
    });

</script>
@endsection
