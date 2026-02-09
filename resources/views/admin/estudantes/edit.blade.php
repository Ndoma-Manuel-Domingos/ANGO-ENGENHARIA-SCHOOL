@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editar Estudante</h1>
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
        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('web.estudantes-matricula-update', $matricula->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-header bg-light">
                            <p>Dados Pessoais</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3 col-12">
                                    <label for="nome_turmas">Nome</label>
                                    <input type="text" name="nome" class="form-control nome @error('nome') is-invalid @enderror" placeholder="Nome" value="{{ $estudante->nome ?? old('nome') }}" >
                                    @error('nome')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                    
                                <div class="form-group col-md-3 col-12">
                                    <label for="sobre_nome">Sobrenome</label>
                                    <input type="text" name="sobre_nome" class="form-control sobre_nome @error('sobre_nome') is-invalid @enderror" placeholder="Sobrenome" value="{{ $estudante->sobre_nome ?? old('sobre_nome') }}">
                                    @error('sobre_nome')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                    
                                <div class="form-group col-md-3 col-12">
                                    <label for="pai">Pai ( nome completo )</label>
                                    <input type="text" name="pai" class="form-control pai @error('pai') is-invalid @enderror" placeholder="Nome Completo do Pai" value="{{ $estudante->pai ?? old('pai') }}">
                                    @error('pai')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3 col-12">
                                    <label for="mae">Mãe ( nome completo )</label>
                                    <input type="text" name="mae" class="form-control mae @error('mae') is-invalid @enderror" placeholder="Nome Completo da Mãe"  value="{{ $estudante->mae ?? old('mae') }}">
                                    @error('mae')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                    
                                <div class="form-group col-md-3 col-12">
                                    <label for="nascimento">Data Nascimento</label>
                                    <input type="date" name="nascimento" class="form-control nascimento @error('nascimento') is-invalid @enderror" value="{{ $estudante->nascimento ?? old('nascimento') }}">
                                    @error('nascimento')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                    
                                <div class="form-group col-md-3 col-12">
                                    <label for="genero">Genero</label>
                                    <select name="genero" id="genero" class="form-control genero @error('genero') is-invalid @enderror">
                                        <option value="">Selecione Genero</option>
                                        <option value="Masculino" {{ $estudante->genero == "Masculino" ? 'selected' : '' }}>Masculino</option>
                                        <option value="Femenino" {{ $estudante->genero == "Femenino" ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                    @error('genero')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                    
                                <div class="form-group col-md-3 col-12">
                                    <label for="estado_civil">Estado Cívil</label>
                                    <select name="estado_civil" id="estado_civil" class="form-control estado_civil @error('estado_civil') is-invalid @enderror">
                                        <option value="">Selecione Status</option>
                                        <option value="Casado" {{ $estudante->estado_civil == "Casado" ? 'selected' : '' }}>Casado</option>
                                        <option value="Solteiro" {{ $estudante->estado_civil == "Solteiro" ? 'selected' : '' }}>Solteiro</option>
                                    </select>
                                    @error('estado_civil')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                    
                                <div class="form-group col-md-3">
                                    <label for="pais_id" class="form-label">País</label>
                                    <select name="pais_id" id="pais_id" class="form-control select2 pais_id @error('pais_id') is-invalid @enderror" style="width: 100%">
                                        <option value="">Selecione o País</option>
                                        @foreach ($paises as $item)
                                        <option value="{{ $item->id }}" {{ $estudante->pais_id == $item->id ? 'selected' : '' }} >{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('pais_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                    
                                <div class="form-group col-md-3">
                                    <label for="" class="form-label">Provincia</label>
                                    <select name="provincia_id" id="provincia_id" class="form-control select2 provincia_id @error('provincia_id') is-invalid @enderror"
                                        style="width: 100%">
                                        <option value="">Selecione a província</option>
                                        @foreach ($provincias as $item)
                                        <option value="{{ $item->id }}" {{ $estudante->provincia_id == $item->id ? 'selected' : '' }} >{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('provincia_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                    
                                <div class="form-group col-md-3 col-12">
                                    <label for="" class="form-label">Municípios</label>
                                    <select name="municipio_id" id="municipio_id" class="form-control select2 municipio_id @error('municipio_id') is-invalid @enderror" style="width: 100%">
                                        @foreach ($municipios as $item)
                                        <option value="{{ $item->id }}" {{ $estudante->municipio_id == $item->id ? 'selected' : '' }} >{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('municipio_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                    
                                <div class="form-group mb-2 col-md-3">
                                    <label for="" class="form-label">Distrito</label>
                                    <select name="distrito_id" id="distrito_id" class="form-control select2 distrito_id @error('distrito_id') is-invalid @enderror" style="width: 100%">
                                        <option value="">Selecione o Distrito</option>
                                        @foreach ($distritos as $item)
                                            <option value="{{ $item->id }}" {{ old('distrito_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('distrito_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                    <span class="text-danger error_distrito_id" ></span>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="bilheite">B.I/CÉDULA</label>
                                    <input type="text" name="bilheite" class="form-control bilheite @error('bilheite') is-invalid @enderror"  value="{{ $estudante->bilheite }}" placeholder="Nº B.I">
                                    @error('bilheite')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3 col-12">
                                    <label for="data_emissao">Data Emissão do B.I</label>
                                    <input type="date" name="data_emissao" value="{{ $estudante->data_emissao }}" class="form-control data_emissao @error('data_emissao') is-invalid @enderror">
                                    @error('data_emissao')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                    
                                <div class="form-group col-md-3 col-12">
                                    <label for="dificiencia">Deficiência</label>
                                    <select name="dificiencia" id="dificiencia" class="form-control dificiencia @error('dificiencia') is-invalid @enderror">
                                        <option value="">Selecione</option>
                                        <option value="Nenhuma" {{ $estudante->dificiencia == 'Nenhuma' ? 'selected' : '' }}>Nenhuma</option>
                                        <option value="Auditiva" {{ $estudante->dificiencia == 'Auditiva' ? 'selected' : '' }}>Auditiva</option>
                                        <option value="Visual" {{ $estudante->dificiencia == 'Visual' ? 'selected' : '' }}>Visual</option>
                                        <option value="Motora" {{ $estudante->dificiencia == 'Motora' ? 'selected' : '' }}>Motora</option>
                                        <option value="Outras" {{ $estudante->dificiencia == 'Outras' ? 'selected' : '' }}>Outras</option>
                                    </select>
                                    @error('dificiencia')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
        
                                <div class="form-group col-md-2 col-12">
                                    <label for="telefone">Telefone estudante</label>
                                    <input type="text" name="telefone" class="form-control telefone @error('telefone') is-invalid @enderror" value="{{ $estudante->telefone_estudante }}" placeholder="Terminal do Estudante">
                                    @error('telefone')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-2 col-12">
                                    <label for="telefone_pai">Telefone do Pai</label>
                                    <input type="text" name="telefone_pai" class="form-control telefone_pai @error('telefone_pai') is-invalid @enderror" value="{{ $estudante->telefone_pai }}" placeholder="Terminal do Pai">
                                    @error('telefone_pai')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-2 col-12">
                                    <label for="telefone_mae">Telefone da Mãe</label>
                                    <input type="text" name="telefone_mae" class="form-control telefone_mae @error('telefone_mae') is-invalid @enderror" value="{{ $estudante->telefone_mae }}" placeholder="Terminal do Mãe">
                                    @error('telefone_mae')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                    
                                <div class="mb-3 col-md-12">
                                    <label for="endereco" class="form-label">Endereço da Morada</label>
                                    <textarea class="form-control endereco @error('endereco') is-invalid @enderror" placeholder="descrever endereço" id="endereco" rows="3">{{ $estudante->endereco }}</textarea>
                                </div>
                    
                            </div>
                        </div>
                    </div>
            
                    <div class="card">
                        <div class="card-header bg-light">
                            <p>Dados Academicos</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3 col-12">
                                  <label for="at_classes_id">Classe Anterior</label>
                                    <select name="at_classes_id" id="at_classes_id" class="form-control at_classes_id @error('at_classes_id') is-invalid @enderror">
                                        <option value="">Selecione</option>
                                        @if ($classes)
                                            @foreach ($classes as $classe)
                                                <option value="{{ $classe->classe->id }}" {{ $matricula->at_classes_id == $classe->classe->id ? 'selected' : '' }}>{{ $classe->classe->classes }}</option>
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
                                  <label for="classes_id">Classe</label>
                                  <select name="classes_id" id="classes_id" class="form-control classes_id @error('classes_id') is-invalid @enderror">
                                    <option value="">Selecione</option>
                                    @if ($classes)
                                      @foreach ($classes as $classe)
                                        <option value="{{ $classe->classe->id }}" {{ $matricula->classes_id == $classe->classe->id ? 'selected' : '' }}>{{ $classe->classe->classes }}</option>
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
                                  <label for="cursos_id">Curso</label>
                                  <select name="cursos_id" id="cursos_id" class="form-control cursos_id @error('cursos_id') is-invalid @enderror">
                                    <option value="">Selecione</option>
                                    @if ($cursos)
                                      @foreach ($cursos as $curso)
                                        <option value="{{ $curso->curso->id }}"  {{ $matricula->cursos_id == $curso->curso->id ? 'selected' : '' }}>{{ $curso->curso->curso }}</option>
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
                                    <label for="turnos_id">Turno</label>
                                    <select name="turnos_id" id="turnos_id" class="form-control turnos_id @error('turnos_id') is-invalid @enderror">
                                        <option value="">Selecione</option>
                                        @if ($turnos)
                                            @foreach ($turnos as $turno)
                                                <option value="{{ $turno->turno->id }}"  {{ $matricula->turnos_id == $turno->turno->id ? 'selected' : '' }}>{{ $turno->turno->turno }}</option>
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
                                  <label for="tipo_matricula">Situação</label>
                                  <select name="tipo_matricula" id="tipo_matricula" class="form-control tipo_matricula @error('tipo_matricula') is-invalid @enderror">
                                    <option value="">Selecione</option>
                                    <option value="matricula" {{ $matricula->tipo == 'matricula' ? 'selected' : '' }}>Matricula</option>
                                    <option value="confirmacao" {{ $matricula->tipo == 'confirmacao' ? 'selected' : '' }}>Confirmação</option>
                                  </select>
                                    @error('tipo_matricula')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
            
                                <div class="form-group col-md-3 col-12">
                                    <label for="situacao_estudante">Situação Estudante</label>
                                    <select name="situacao_estudante" id="situacao_estudante" class="form-control situacao_estudante @error('situacao_estudante') is-invalid @enderror">
                                        <option value="">Selecione</option>
                                        <option value="Novo" {{ $matricula->status == 'Novo' ? 'selected' : '' }}>Novo</option>
                                        <option value="Transferido" {{ $matricula->status == 'Transferido' ? 'selected' : '' }}>Transferido</option>
                                        <option value="Destistente" {{ $matricula->status == 'Destistente' ? 'selected' : '' }}>Destistente</option>
                                        <option value="Antigo" {{ $matricula->status == 'Antigo' ? 'selected' : '' }}>Antigo</option>
                                        <option value="Repitente" {{ $matricula->status == 'Repitente' ? 'selected' : '' }}>Repitente</option>
                                    </select>
                                    @error('situacao_estudante')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
            
                                <div class="form-group col-md-3 col-12">
                                  <label for="condicao_estudante">Condição Estudante</label>
                                  <select name="condicao_estudante" id="condicao_estudante" class="form-control condicao_estudante @error('condicao_estudante') is-invalid @enderror" disabled>
                                    <option value="">Condição do estudante Propinas</option>
                                    <option value="Isento" {{ $matricula->condicao == 'Isento' ? 'selected' : '' }}>Isento</option>
                                    <option value="Paga" {{ $matricula->condicao == 'Paga' ? 'selected' : '' }}>Sujeito a mensalidade</option>
                                  </select>
                                    @error('condicao_estudante')
                                        <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
            
                                <div class="form-group col-md-3 col-12">
                                  <label for="ano_lectivos_id">Ano Lectivo</label>
                                    <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control ano_lectivos_id @error('ano_lectivos_id') is-invalid @enderror">
                                        <option value="">Selecione</option>
                                        @if ($anolectivos)
                                        @foreach ($anolectivos as $anolectivo)
                                            <option value="{{ $anolectivo->id }}" {{ $matricula->ano_lectivos_id == $anolectivo->id ? 'selected' : '' }}>{{ $anolectivo->ano }}</option>
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
                                    <label for="cursos_primeira_opcao_id">Curso 2º opção</label>
                                    <select name="cursos_primeira_opcao_id" id="cursos_primeira_opcao_id" class="form-control cursos_primeira_opcao_id @error('cursos_primeira_opcao_id') is-invalid @enderror">
                                      <option value="">Selecione</option>
                                      @if ($cursos)
                                        @foreach ($cursos as $curso)
                                          <option value="{{ $curso->curso->id }}"  {{ $matricula->cursos_primeira_opcao_id == $curso->curso->id ? 'selected' : '' }}>{{ $curso->curso->curso }}</option>
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
                                    <label for="cursos_segunda_opcao_id">Curso 3º opção</label>
                                    <select name="cursos_segunda_opcao_id" id="cursos_segunda_opcao_id" class="form-control cursos_segunda_opcao_id @error('cursos_segunda_opcao_id') is-invalid @enderror">
                                      <option value="">Selecione</option>
                                      @if ($cursos)
                                        @foreach ($cursos as $curso)
                                          <option value="{{ $curso->curso->id }}"  {{ $matricula->cursos_segunda_opcao_id == $curso->curso->id ? 'selected' : '' }}>{{ $curso->curso->curso }}</option>
                                        @endforeach
                                      @else
                                        <option value="">Sem Nenhum Curso cadastrado</option>
                                      @endif
                                    </select>
                                    @error('cursos_segunda_opcao_id')
                                          <span class="text-danger error-text nome_error">{{ $message }}</span>
                                      @enderror
                                </div>
                                
                                <div class="form-group col-md-6 col-12">
                                    <label for="media_final_curso_medio">Média do Final curso(Médio)  <span class="text-danger">*</span></label>
                                    <input name="media_final_curso_medio" value="{{ old('media_final_curso_medio') ?? $matricula->media }}" id="media_final_curso_medio" type="text" class="form-control media_final_curso_medio @error('media_final_curso_medio') is-invalid @enderror">
                                    
                                    @error('media_final_curso_medio')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                            </div>
                        </div>
            
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-light">
                            <p>Rede Sociais</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                              <div class="form-group mb-2 col-md-3 col-12">
                                <label for="whatsapp"  class="form-label">Whatsapp</label>
                                <input type="text" name="whatsapp" id="whatsapp" value="{{ $estudante->whatsapp ?? old('whatsapp') }}" class="form-control whatsapp @error('whatsapp') is-invalid @enderror" placeholder="Whatsapp do Estudante">
                                @error('whatsapp')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                              </div>
                              
                              <div class="form-group mb-2 col-md-3 col-12">
                                <label for="instagram"  class="form-label">Instagram</label>
                                <input type="text" name="instagram" id="instagram" value="{{ $estudante->instagram ??  old('instagram') }}" class="form-control instagram @error('instagram') is-invalid @enderror" placeholder="Instagram do Estudante">
                                @error('instagram')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                              </div>
                              
                              <div class="form-group mb-2 col-md-3 col-12">
                                <label for="facebook"  class="form-label">facebook</label>
                                <input type="text" name="facebook" id="facebook" value="{{  $estudante->facebook ?? old('facebook') }}" class="form-control facebook @error('facebook') is-invalid @enderror" placeholder="facebook do Estudante">
                                @error('facebook')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                              </div>
                              
                              <div class="form-group mb-2 col-md-3 col-12">
                                <label for="email"  class="form-label">E-mail</label>
                                <input type="text" name="email" id="email" value="{{  $estudante->email ?? old('email') }}" class="form-control email @error('email') is-invalid @enderror" placeholder="E-mail do Estudante">
                                @error('email')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                              </div>
                            </div>
                          </div>
                    </div>

        
                    <div class="card">
                        <div class="card-header bg-light">
                            <p>Documentos</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3 mb-3 col-12">
                                  <label for="doc_bilheite">Bilhete de Identidade</label>
                                  <input type="file" name="doc_bilheite" accept=".pdf" id="doc_bilheite" class="form-control doc_bilheite @error('doc_bilheite') is-invalid @enderror">
                                  @error('doc_bilheite')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                    <input type="hidden" name="doc_bilheite_guardado" accept=".pdf" value="{{ $bilheite }}" id="doc_bilheite_guardado" class="form-control doc_bilheite_guardado @error('doc_bilheite_guardado') is-invalid @enderror">
                                </div>
        
                                <div class="form-group col-md-3 mb-3 col-12">
                                  <label for="doc_certificado">Certificado</label>
                                  <input type="file" name="doc_certificado" accept=".pdf" id="doc_certificado" class="form-control doc_certificado @error('doc_certificado') is-invalid @enderror">
                                  @error('doc_certificado')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                  <input type="hidden" name="doc_certificado_guardado" accept=".pdf" value="{{ $certificado }}" id="doc_certificado_guardado" class="form-control doc_certificado_guardado @error('doc_certificado_guardado') is-invalid @enderror">
                                </div>
                    
                                <div class="form-group col-md-3 mb-3 col-12">
                                  <label for="doc_atestedao_medico">Atestado Médico</label>
                                  <input type="file" name="doc_atestedao_medico" accept=".pdf" id="doc_atestedao_medico" class="form-control doc_atestedao_medico @error('doc_atestedao_medico') is-invalid @enderror">
                                  @error('doc_atestedao_medico')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                  <input type="hidden" name="doc_atestedao_medico_guardado" value="{{ $atestado }}" accept=".pdf" id="doc_atestedao_medico_guardado" class="form-control doc_atestedao_medico_guardado @error('doc_atestedao_medico_guardado') is-invalid @enderror">
                                </div>
        
                                <div class="form-group col-md-3 mb-3 col-12">
                                  <label for="doc_outros">Outros Documentos</label>
                                  <input type="file" name="doc_outros" accept=".pdf" class="form-control doc_outros" id="doc_outros" >
                                  @error('doc_outros')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                  <input type="hidden" name="doc_outros_guardado" accept=".pdf" value="{{ $outros }}" class="form-control doc_outros_guardado" id="doc_outros_guardado" >
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary concluir_cadastro_estudante">Concluir</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection

@section('scripts')

 <script>

    // Eventos
    $("#provincia_id").change(function () {
      carregarDados({
        origem: "#provincia_id",
        destino: "#municipio_id",
        rota: rotas.carregarMunicipios,
        mensagemSucesso: "Municípios carregados"
      });
    });
    
    $("#municipio_id").change(function () {
      carregarDados({
        origem: "#municipio_id",
        destino: "#distrito_id",
        rota: rotas.carregarDistritos,
        mensagemSucesso: "Distritos carregados"
      });
    });

    
    $("#pais_id").change(()=>{
      let id = $("#pais_id").val();
      
      if(id != 6) {
        $.get('../../carregar-privincia-municipios-distrito-estrageiros/'+id, function(data){
   
            $("#provincia_id").html("")
            $("#provincia_id").html(data['provincias'])
            
            $("#municipio_id").html("")
            $("#municipio_id").html(data['municipios'])
            
            $("#distrito_id").html("")
            $("#distrito_id").html(data['distritos'])
        })
      }
    })

 </script>

@endsection
