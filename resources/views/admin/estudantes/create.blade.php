@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Matricular Estudante</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.estudantes-matricula') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Matriculas</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form action="{{ route('web.estudantes-matricula-store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12 col-md-7">
                    <div class="card">
                        <div class="card-header bg-light">
                            <p>Passo 1: Dados Pessoais</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                
                                <div class="form-group col-md-3 col-12">
                                    <label for="bilheite">B.I/CÉDULA/PASSPORTE <span class="text-danger">*</span></label>
                                    <input type="text" name="bilheite" id="bilheite" value="{{ old('bilheite') }}" class="form-control bilheite @error('bilheite') is-invalid @enderror" placeholder="Nº B.I">
                                    @error('bilheite')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                            
                                <div class="form-group col-md-3 col-12">
                                    <label for="nome">Nome <span class="text-danger">*</span></label>
                                    <input type="text" id="nome" name="nome" value="{{ old('nome') }}" class="form-control nome  @error('nome') is-invalid @enderror" placeholder="Nome" value="{{ old('nome') }}">
                                    @error('nome')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="sobre_nome">Sobrenome <span class="text-danger">*</span></label>
                                    <input type="text" id="sobre_nome" name="sobre_nome" value="{{ old('sobre_nome') }}" class="form-control sobre_nome @error('sobre_nome') is-invalid @enderror" placeholder="Sobrenome" value="{{ old('sobre_nome') }}">
                                    @error('sobre_nome')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="nascimento">Data Nascimento <span class="text-danger">*</span></label>
                                    <input type="date" id="nascimento" name="nascimento" value="{{ old('nascimento') }}" class="form-control nascimento @error('nascimento') is-invalid @enderror">
                                    @error('nascimento')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="genero">Genero <span class="text-danger">*</span></label>
                                    <select name="genero" id="genero" class="form-control genero @error('genero') is-invalid @enderror">
                                        <option value="Masculino" {{ old('genero') == "Masculino" ? 'selected' : '' }}>Masculino</option>
                                        <option value="Femenino" {{ old('genero') == "Femenino" ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                    <span class="text-danger error-text genero_error"></span>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="estado_civil">Estado Cívil <span class="text-danger">*</span></label>
                                    <select name="estado_civil" id="estado_civil" class="form-control estado_civil @error('estado_civil') is-invalid @enderror">
                                        <option value="Solteiro" {{ old('estado_civil') == "Solteiro" ? 'selected' : '' }}>SOLTEIRO(A)</option>
                                        <option value="Casado" {{ old('estado_civil') == "Casado" ? 'selected' : '' }}>CASADO(A)</option>
                                        <option value="Viuvo" {{ old('estado_civil') == "Viuvo" ? 'selected' : '' }}>VIUVO(A)</option>
                                        <option value="Divorciado" {{ old('estado_civil') == "Divorciado" ? 'selected' : '' }}>DIVORCIDO(A)</option>
                                    </select>
                                    <span class="text-danger error-text estado_civil_error"></span>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="pais_id">País <span class="text-danger">*</span></label>
                                    <select name="pais_id" id="pais_id" class="form-control pais_id @error('pais_id') is-invalid @enderror">
                                        <option value="">Selecione o País</option>
                                        @foreach ($paises as $item)
                                        <option value="{{ $item->id }}" {{ old('pais_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('pais_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="provincia_id">Provincia <span class="text-danger">*</span></label>
                                    <select name="provincia_id" id="provincia_id" class="form-control select2 provincia_id @error('provincia_id') is-invalid @enderror">
                                        <option value="">Selecione o País</option>
                                        @foreach ($provincias as $item)
                                        <option value="{{ $item->id }}" {{ old('provincia_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('provincia_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="municipio_id">Município <span class="text-danger">*</span></label>
                                    <select name="municipio_id" id="municipio_id" class="form-control select2 municipio_id @error('municipio_id') is-invalid @enderror" style="width: 100%">
                                        <option value="">Selecione o País</option>
                                        @foreach ($municipios as $item)
                                        <option value="{{ $item->id }}" {{ old('municipio_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('municipio_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-2 col-md-3">
                                    <label for="distrito_id" class="form-label">Distrito <span class="text-danger">*</span></label>
                                    <select name="distrito_id" id="distrito_id" class="form-control select2 distrito_id  @error('distrito_id') is-invalid @enderror" style="width: 100%">
                                        <option value="">Selecione o Distrito</option>
                                        @foreach ($distritos as $item)
                                        <option value="{{ $item->id }}" {{ old('distrito_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('distrito_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                    <span class="text-danger error_distrito_id"></span>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="data_emissao">Data Emissão do B.I</label>
                                    <input type="date" id="data_emissao" name="data_emissao" value="{{ old('data_emissao') }}" class="form-control data_emissao @error('data_emissao') is-invalid @enderror">
                                    @error('data_emissao')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="dificiencia">Deficiência</label>
                                    <select name="dificiencia" id="dificiencia" class="form-control dificiencia @error('dificiencia') is-invalid @enderror">
                                        <option value="">Selecione</option>
                                        <option value="Nenhuma" {{ old('dificiencia') == "Nenhuma" ? 'selected' : 'selected' }}>Nenhuma</option>
                                        <option value="Auditiva" {{ old('dificiencia') == "Auditiva" ? 'selected' : '' }}>Auditiva</option>
                                        <option value="Visual" {{ old('dificiencia') == "Visual" ? 'selected' : '' }}>Visual</option>
                                        <option value="Motora" {{ old('dificiencia') == "Motora" ? 'selected' : '' }}>Motora</option>
                                        <option value="Outras" {{ old('dificiencia') == "Outras" ? 'selected' : '' }}>Outras</option>
                                    </select>
                                    <span class="text-danger error-text dificiencia_error"></span>
                                </div>

                                <div class="form-group col-md-6 col-12">
                                    <label for="telefone">Telefone estudante</label>
                                    <input type="text" id="telefone" name="telefone" value="{{ old('telefone') ?? '000-000-000' }}" class="form-control telefone @error('telefone') is-invalid @enderror" placeholder="Terminal do Estudante">
                                    @error('telefone')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6 col-12">
                                    <label for="endereco" class="form-label">Endereço da Morada</label>
                                    <input name="endereco" id="endereco" class="form-control endereco @error('endereco') is-invalid @enderror" value="{{ old('endereco') }}" placeholder="descrever endereço" id="endereco" rows="3" />
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-md-5">
                    <div class="card">
                        <div class="card-header bg-light">
                            <p>Passo 2: Dados do Encarregado (Responsável Financeiro)</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                            
                                <div class="form-group col-md-12 col-12">
                                    <label for="numero_telefonico_encarregado">Numero Telefonico</label>
                                    <input type="text" id="numero_telefonico_encarregado" name="numero_telefonico_encarregado" value="{{ old('numero_telefonico_encarregado') }}" class="form-control numero_telefonico_encarregado @error('numero_telefonico_encarregado') is-invalid @enderror" placeholder="Digite o numero do encarregado">
                                    @error('numero_telefonico_encarregado')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-6 col-12">
                                    <label for="nome_encarregado">Nome do Pai</label>
                                    <input type="text" id="nome_encarregado" name="nome_encarregado" value="{{ old('nome_encarregado') }}" class="form-control nome_encarregado @error('nome_encarregado') is-invalid @enderror" placeholder="Nome completo do encarregado" value="{{ old('nome_encarregado') }}">
                                    @error('nome_encarregado')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-12">
                                    <label for="nome_da_mae_encarregado">Nome da Mãe</label>
                                    <input type="text" id="nome_da_mae_encarregado" name="nome_da_mae_encarregado" value="{{ old('nome_da_mae_encarregado') }}" class="form-control nome_da_mae_encarregado  @error('nome_da_mae_encarregado') is-invalid @enderror" placeholder="Nome Completo da Mãe" value="{{ old('nome_da_mae_encarregado') }}">
                                    @error('nome_da_mae_encarregado')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="genero_encarregado">Gênero</label>
                                    <select name="genero_encarregado" id="genero_encarregado" class="form-control select2">
                                        <option value="Masculino">Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                    </select>
                                    @error('genero_encarregado')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="estado_civil_encarregado">Estado Civil</label>
                                    <select name="estado_civil_encarregado" id="estado_civil_encarregado" class="form-control select2">
                                        <option value="Solteiro" {{ old('estado_civil_encarregado') == "Solteiro" ? 'selected' : '' }}>SOLTEIRO(A)</option>
                                        <option value="Casado" {{ old('estado_civil_encarregado') == "Casado" ? 'selected' : '' }}>CASADO(A)</option>
                                        <option value="Viuvo" {{ old('estado_civil_encarregado') == "Viuvo" ? 'selected' : '' }}>VIUVO(A)</option>
                                        <option value="Divorciado" {{ old('estado_civil_encarregado') == "Divorciado" ? 'selected' : '' }}>DIVORCIDO(A)</option>
                                    </select>
                                    @error('estado_civil_encarregado')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-6 col-12">
                                    <label for="profissao_encarregado">Profissão</label>
                                    <input type="text" id="profissao_encarregado" name="profissao_encarregado" value="" class="form-control profissao_encarregado @error('profissao_encarregado') is-invalid @enderror" placeholder="Terminal do Pai">
                                    @error('profissao_encarregado')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-6 col-12">
                                    <label for="grau_parantesco">Grau Parentesco</label>
                                    <input type="text" id="grau_parantesco" name="grau_parantesco" value="" class="form-control grau_parantesco @error('grau_parantesco') is-invalid @enderror" placeholder="Terminal do Mãe">
                                    @error('grau_parantesco')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <p>Passo 3: Dados Academicos</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3 col-12">
                                    <label for="ano_lectivos_id">Ano Lectivo <span class="text-danger">*</span></label>
                                    <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control ano_lectivos_id select2 @error('ano_lectivos_id') is-invalid @enderror">
                                        @if ($anolectivos)
                                        @foreach ($anolectivos as $anolectivo)
                                        <option value="{{ $anolectivo->id }}" {{ $anolectivo->status == 'activo' ? 'selected' : '' }}>{{ $anolectivo->ano }}</option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Ano Lectivo cadastrado</option>
                                        @endif
                                    </select>
                                    @error('ano_lectivos_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                            
                                <div class="form-group col-md-3 col-12">
                                    <label for="at_classes_id">Classe Anterior <span class="text-danger">*</span></label>
                                    <select name="at_classes_id" id="at_classes_id" class="form-control at_classes_id select2 @error('at_classes_id') is-invalid @enderror">
                                        <option value="">Selecione a Classe</option>
                                        @if ($classes)
                                        @foreach ($classes as $classe)
                                        <option value="{{ $classe->classe->id }}" {{ old('at_classes_id') == $classe->classe->id ? 'selected' : '' }}>{{ $classe->classe->classes }}
                                        </option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhuma Classe cadastrada</option>
                                        @endif
                                    </select>
                                    @error('at_classes_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="classes_id">Classe <span class="text-danger">*</span></label>
                                    <select name="classes_id" id="classes_id" class="form-control classes_id select2 @error('classes_id') is-invalid @enderror">
                                        <option value="">Selecione a Classe</option>
                                        @if ($classes)
                                        @foreach ($classes as $classe)
                                        <option value="{{ $classe->classe->id }}" {{ old('classes_id') == $classe->classe->id ? 'selected' : '' }}>{{ $classe->classe->classes }}
                                        </option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhuma Classe cadastrada</option>
                                        @endif
                                    </select>
                                    @error('classes_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="cursos_id">Curso <span class="text-danger">*</span></label>
                                    <select name="cursos_id" id="cursos_id" class="form-control cursos_id select2 @error('cursos_id') is-invalid @enderror">
                                        <option value="">Selecione o curso</option>
                                        @if ($cursos)
                                        @foreach ($cursos as $curso)
                                        <option value="{{ $curso->curso->id }}" {{ old('cursos_id') == $curso->curso->id ? 'selected' : '' }}>{{ $curso->curso->curso }}</option>
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
                                    <select name="turnos_id" id="turnos_id" class="form-control turnos_id select2 @error('turnos_id') is-invalid @enderror">
                                        <option value="">Selecione o turno</option>
                                        @if ($turnos)
                                        @foreach ($turnos as $turno)
                                        <option value="{{ $turno->turno->id }}" {{ old('turnos_id') == $turno->turno->id ? 'selected' : '' }}>{{ $turno->turno->turno }}</option>
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
                                    <select name="tipo_matricula" id="tipo_matricula" class="form-control tipo_matricula select2 @error('tipo_matricula') is-invalid @enderror">
                                        <option value="matricula" {{ old('tipo_matricula') == "matricula" ? 'selected' : '' }}>Matricula</option>
                                    </select>
                                    @error('tipo_matricula')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="situacao_estudante">Tipo de Estudante <span class="text-danger">*</span></label>
                                    <select name="situacao_estudante" id="situacao_estudante" class="form-control situacao_estudante select2  @error('situacao_estudante') is-invalid @enderror">
                                        <option value="Novo" {{ old('situacao_estudante') == "Novo" ? 'selected' : '' }}>Novo</option>
                                        <option value="Transferido" {{ old('situacao_estudante') == "Transferido" ? 'selected' : '' }}>Transferido</option>
                                        <option value="Destistente" {{ old('situacao_estudante') == "Destistente" ? 'selected' : '' }}>Destistente</option>
                                        <option value="Antigo" {{ old('situacao_estudante') == "Antigo" ? 'selected' : '' }}>Antigo</option>
                                        <option value="Repitente" {{ old('situacao_estudante') == "Repitente" ? 'selected' : '' }}>Repitente</option>
                                    </select>
                                    @error('situacao_estudante')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="condicao_estudante">Condição Estudante <span class="text-danger">*</span></label>
                                    <select name="condicao_estudante" id="condicao_estudante" class="form-control condicao_estudante select2 @error('condicao_estudante') is-invalid @enderror">
                                        <option value="Isento" {{ old('condicao_estudante') == "Isento" ? 'selected' : '' }}>Isento</option>
                                        <option value="Paga" {{ old('condicao_estudante') == "Paga" ? 'selected' : '' }} selected>Sujeito a mensalidade</option>
                                    </select>
                                    @error('condicao_estudante')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                                <div class="form-group col-md-6 col-12">
                                    <label for="cursos_primeira_opcao_id">Curso 2º Opção <span class="text-secondary">(Opcional)</span></label>
                                    <select name="cursos_primeira_opcao_id" id="cursos_primeira_opcao_id" class="form-control cursos_primeira_opcao_id select2 @error('cursos_primeira_opcao_id') is-invalid @enderror">
                                        <option value="">Selecione o curso</option>
                                        @if ($cursos)
                                        @foreach ($cursos as $curso)
                                        <option value="{{ $curso->curso->id }}" {{ old('cursos_primeira_opcao_id') == $curso->curso->id ? 'selected' : '' }}>{{ $curso->curso->curso }}</option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Curso cadastrado</option>
                                        @endif
                                    </select>
                                    @error('cursos_primeira_opcao_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="cursos_segunda_opcao_id">Curso 3º Opção <span class="text-secondary">(Opcional)</span></label>
                                    <select name="cursos_segunda_opcao_id" id="cursos_segunda_opcao_id" class="form-control cursos_segunda_opcao_id select2 @error('cursos_segunda_opcao_id') is-invalid @enderror">
                                        <option value="">Selecione o curso</option>
                                        @if ($cursos)
                                        @foreach ($cursos as $curso)
                                        <option value="{{ $curso->curso->id }}" {{ old('cursos_segunda_opcao_id') == $curso->curso->id ? 'selected' : '' }}>{{ $curso->curso->curso }}</option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Curso cadastrado</option>
                                        @endif
                                    </select>
                                    @error('cursos_segunda_opcao_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                @endif


                                @if ($escola->processo_admissao_estudante == 'Normal')
                                <div class="form-group col-md-3 col-12">
                                    <label for="media_final_curso_medio">Média do Final curso(Médio) <span class="text-danger">*</span></label>
                                    <input name="media_final_curso_medio" value="{{ old('media_final_curso_medio') ?? '10' }}" id="media_final_curso_medio" type="text" class="form-control media_final_curso_medio @error('media_final_curso_medio') is-invalid @enderror">

                                    @error('media_final_curso_medio')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                    
                <div class="col-12 col-md-12">
                    @if ($escola->processo_pagamento_servico == "Secretaria")
                        @if ($escola->categoria == "Privado" && $escola->modulo != "Basico")
                        <div class="card">
                            <div class="card-header bg-light">
                                <p>Passo 4: Dados dos Pagamentos <span class="float-right fs-3 text-danger">Troco <strong id="valor_troco_apresenta">0</strong></span></p>
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
    
                                    <input type="hidden" name="valor" class="valor valor_total_a_pagar" value="{{ old('valor') ?? '' }}">
    
    
                                    <div class="form-group col-12  col-md-3">
                                        <label for="valor_entregue">Digite o Valor Entregue <span class="text-danger">*</span></label>
                                        <input type="text" value="{{ old('valor_entregue') }}" name="valor_entregue" class="form-control valor_entregue" id="valor_entregue" placeholder="Valor Entregue pelo Estudante">
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
                                            <option value="FR" {{ old('documento') == "FR" ? 'selected' : 'selected' }}>Factura Recibo</option>
                                            <option value="PP" {{ old('documento') == "PP" ? 'selected' : '' }}>Factura Pró-forma</option>
                                            <option value="FT" {{ old('documento') == "FT" ? 'selected' : '' }}>Factura</option>
                                        </select>
                                        @error('documento')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-3 col-12">
                                        <label for="tipo_pagamento">Forma de Pagamento <span class="text-danger">*</span></label>
                                        <select name="tipo_pagamento" id="tipo_pagamento" class="form-control tipo_pagamento select2 @error('tipo_pagamento') is-invalid @enderror">
                                            @foreach ($formas_pagamento as $item)
                                            <option value="{{ $item->sigla_tipo_pagamento }}" {{ old('tipo_pagamento') == $item->sigla_tipo_pagamento ? 'selected' : '' }}>{{ $item->descricao }}</option>
                                            @endforeach
                                        </select>
                                        @error('tipo_pagamento')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-3 col-12">
                                        <label for="banco_id">Banco (Opcional)</label>
                                        <select name="banco_id" id="banco_id" class="form-control banco_id select2 @error('banco_id') is-invalid @enderror">
                                            <option value="">Selecione</option>
                                            @foreach ($bancos as $item)
                                            <option value="{{ $item->id }}" {{ old('banco_id') == $item->banco_id ? 'selected' : '' }}>{{ $item->conta }} - {{ $item->banco }}</option>
                                            @endforeach
                                            {{-- <option value="Nenhum" {{ old('banco') == "Nenhum" ? 'selected' : '' }}>Nenhum</option>
                                            <option value="BFA" {{ old('banco') == "BFA" ? 'selected' : '' }}>BFA</option>
                                            <option value="BPC" {{ old('banco') == "BPC" ? 'selected' : '' }}>BPC</option>
                                            <option value="BIC" {{ old('banco') == "BIC" ? 'selected' : '' }}>BIC</option>
                                            <option value="BAI" {{ old('banco') == "BAI" ? 'selected' : '' }}>BAI</option>
                                            <option value="BAI" {{ old('banco') == "BAI" ? 'selected' : '' }}>BCA</option>
                                            <option value="ATLANTICO" {{ old('banco') == "ATLANTICO" ? 'selected' : '' }}>ATLANTICO</option>
                                            <option value="CAIXA ANGOLA" {{ old('banco') == "CAIXA ANGOLA" ? 'selected' : '' }}>CAIXA ANGOLA</option>
                                            <option value="OUTROS" {{ old('banco') == "OUTROS" ? 'selected' : '' }}>OUTROS</option> --}}
                                        </select>
                                        @error('banco_id')
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
                        </div>
                        @endif
                    @endif
                    
                    @if ($escola->categoria == "Privado" && $escola->modulo != "Basico")
                        <div class="card">
                            <div class="card-header bg-light">
                                <p>Passo 5: Rede Sociais</p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group mb-2 col-md-3 col-12">
                                        <label for="whatsapp" class="form-label">Whatsapp (Opcional)</label>
                                        <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" class="form-control whatsapp @error('whatsapp') is-invalid @enderror" placeholder="Whatsapp do Estudante">
                                        @error('whatsapp')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group mb-2 col-md-3 col-12">
                                        <label for="instagram" class="form-label">Instagram (Opcional)</label>
                                        <input type="text" name="instagram" id="instagram" value="{{ old('instagram') }}" class="form-control instagram @error('instagram') is-invalid @enderror" placeholder="Instagram do Estudante">
                                        @error('instagram')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group mb-2 col-md-3 col-12">
                                        <label for="facebook" class="form-label">facebook (Opcional)</label>
                                        <input type="text" name="facebook" id="facebook" value="{{ old('facebook') }}" class="form-control facebook @error('facebook') is-invalid @enderror" placeholder="facebook do Estudante">
                                        @error('facebook')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group mb-2 col-md-3 col-12">
                                        <label for="email" class="form-label">E-mail (Opcional)</label>
                                        <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control email @error('email') is-invalid @enderror" placeholder="E-mail do Estudante">
                                        @error('email')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                
                    @if ($escola->categoria == "Privado" && $escola->modulo != "Basico")
                        <div class="card">
                            <div class="card-header bg-light">
                                <p>Passo 6: Documentos</p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-3 mb-3 col-12">
                                        <label for="doc_bilheite">Bilhete de Identidade (Opcional)</label>
                                        <input type="file" name="doc_bilheite" accept=".pdf" id="doc_bilheite" class="form-control doc_bilheite @error('doc_bilheite') is-invalid @enderror">
                                        @error('doc_bilheite')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-3 mb-3 col-12">
                                        <label for="doc_certificado">Certificado (Opcional)</label>
                                        <input type="file" name="doc_certificado" accept=".pdf" id="doc_certificado" class="form-control doc_certificado @error('doc_certificado') is-invalid @enderror">
                                        @error('doc_certificado')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-3 mb-3 col-12">
                                        <label for="doc_atestedao_medico">Atestado Médico (Opcional)</label>
                                        <input type="file" name="doc_atestedao_medico" accept=".pdf" id="doc_atestedao_medico" class="form-control doc_atestedao_medico  @error('doc_atestedao_medico') is-invalid @enderror">
                                        @error('doc_atestedao_medico')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-3 mb-3 col-12">
                                        <label for="doc_outros">Outros Documentos (Opcional)</label>
                                        <input type="file" name="doc_outros" accept=".pdf" class="form-control doc_outros @error('doc_outros') is-invalid @enderror" id="doc_outros">
                                        @error('doc_outros')
                                        <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    @endif
                    
                    <div class="card">
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary concluir_cadastro_estudante">Concluir</button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
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
        $.get('carregar-servicos-turmas?ano_lectivos_id='+id_ano_lectivo+'&classes_id=' + id + '&cursos_id=' + id_cursos, function(data) {
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
        $.get('carregar-servicos-turmas?ano_lectivos_id='+id_ano_lectivo+'&classes_id=' + id_classes + '&cursos_id=' + id, function(data) {
            $('#servicos_id').html("");
            $('#servicos_id').append('<option value="">Selecione um Serviço</option>');
            for (let index = 0; index < data.servicos.length; index++) {
                $('#servicos_id').append('<option value="' + data.servicos[index].id + '">' + data.servicos[index].servico + '</option>');
            }
            turmasServico = data.turma.id;
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

    $("#servicos_id").change(() => {
        let id = $("#servicos_id").val();
        let id_ano_lectivo = $("#ano_lectivos_id").val();
        
        $.get(`carregar-valor-servicos-turmas/${id}/${turmasServico}/${id_ano_lectivo}`, function(data) {
            $('.valor').val("");
            $('.valor').val(data.servico.preco);
        })
    })

    $("#pais_id").change(() => {
        let id = $("#pais_id").val();

        if (id != 6) {
            $.get('../carregar-privincia-municipios-distrito-estrageiros/' + id, function(data) {

                $("#provincia_id").html("")
                $("#provincia_id").html(data['provincias'])

                $("#municipio_id").html("")
                $("#municipio_id").html(data['municipios'])

                $("#distrito_id").html("")
                $("#distrito_id").html(data['distritos'])
            })
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
    
    document.getElementById('bilheite').addEventListener('input', async function () {
        const bilhete = this.value;
    
        if (bilhete.length >= 3) {
            try {
                const response = await fetch(`/buscar-por-bilhete-estudante?bilhete=${bilhete}`);
                const data = await response.json();
    
                if (data) {
                    document.getElementById('nome').value = data.nome || '';
                    document.getElementById('sobre_nome').value = data.sobre_nome || '';
                    document.getElementById('nascimento').value = data.nascimento || '';
                    document.getElementById('genero').value = data.genero || '';
                    document.getElementById('estado_civil').value = data.estado_civil || '';
                    document.getElementById('pais_id').value = data.pais_id || '';
                    $('#provincia_id').val(data.provincia_id || '').trigger('change');
                    document.getElementById('data_emissao').value = data.data_emissao || '';
                    document.getElementById('dificiencia').value = data.dificiencia || '';
                    $('#municipio_id').val(data.municipio_id || '').trigger('change');
                    document.getElementById('telefone').value = data.telefone_estudante || '';
                    document.getElementById('endereco').value = data.endereco || '';
                    $('#distrito_id').val(data.distrito_id || '').trigger('change');
                    
                }
            } catch (error) {
                console.error('Erro ao buscar telefone:', error);
            }
        }else {
            document.getElementById('nome').value = '';
            document.getElementById('sobre_nome').value = '';
            document.getElementById('nascimento').value = '';
            document.getElementById('genero').value = '';
            document.getElementById('estado_civil').value = '';
            document.getElementById('pais_id').value = '';
            document.getElementById('data_emissao').value = '';
            document.getElementById('dificiencia').value = '';
            document.getElementById('telefone').value = '';
            document.getElementById('endereco').value = '';
            document.getElementById('provincia_id').value = '';
            document.getElementById('municipio_id').value = '';
            document.getElementById('distrito_id').value = '';
        }
    });
    
    document.getElementById('numero_telefonico_encarregado').addEventListener('input', async function () {
        const telefone = this.value;
    
        if (telefone.length >= 3) {
            try {
                const response = await fetch(`/buscar-por-telefone-encarregado?telefone=${telefone}`);
                const data = await response.json();
    
                if (data) {
                    document.getElementById('nome_encarregado').value = data.nome_completo || '';
                    document.getElementById('telefone_encarregado').value = data.telefone || '';
                    document.getElementById('genero_encarregado').value = data.genero || '';
                    document.getElementById('estado_civil_encarregado').value = data.estado_civil || '';
                    document.getElementById('profissao_encarregado').value = data.profissao || '';
                    document.getElementById('grau_parantesco').value = 'Pai';
                }
            } catch (error) {
                console.error('Erro ao buscar telefone:', error);
            }
        }
    });

    $(document).ready(function() {
        $('form').on('submit', function(e) {
            e.preventDefault(); // Impede o envio tradicional do formulário

            let form = $(this);
            let formData = form.serialize(); // Serializa os dados do formulário

            $.ajax({
                url: form.attr('action'), // URL do endpoint no backend
                method: form.attr('method'), // Método HTTP definido no formulário
                data: formData, // Dados do formulário
                beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    
                    showMessage('Sucesso!', 'Dados salvos com sucesso!', 'success');
                    
                    window.location.href = response.redirect;

                }
                , error: function(xhr) {
                    // Feche o alerta de carregamento
                    Swal.close();
                    
                    console.log(xhr)
                    
                    
                    // Trata erros e exibe mensagens para o usuário
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = '';
                        $.each(errors, function(key, value) {
                            messages += `${value}\n`; // Exibe os erros
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
