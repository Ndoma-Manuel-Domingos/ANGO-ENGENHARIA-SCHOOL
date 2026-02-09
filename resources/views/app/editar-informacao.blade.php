@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Configurar informações da Escola</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.informacoes-escola') }}">Informações</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div><!-- /.col -->
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <form action="{{ route('web.informacoes-escola-update', $escola->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-md-12 col-12">
                          <div class="card">
                            <div class="card-header">
                              <h6>Dados do Director</h6>
                            </div>
                            <div class="card-body">
                              <div class="row">
                                <div class="form-group mb-3 col-md-6 col-12">
                                  <label for="nome_director" class="form-label">Nome do Director <span class="text-danger">*</span></label>
                                  <input type="text" value="{{ old('director') ?? $director->nome }}" name="director" id="director" class="form-control director"
                                    placeholder="Nome do Director">
                                  @error('director')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                                
                                <input type="hidden" name="director_id" value="{{ $director->id }}">
                
                                <div class="form-group mb-3 col-md-3 col-12">
                                  <label for="genero" class="form-label">Genero <span class="text-danger">*</span></label>
                                  <select name="genero" id="genero" class="select2 form-control genero">
                                    <option value="Masculino" {{ "Masculino" == $director->genero ? 'selected' : '' }}>Masculino</option>
                                    <option value="Femenino" {{ "Femenino" == $director->genero ? 'selected' : '' }}>Femenino</option>
                                  </select>
                                  @error('genero')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-3 col-12">
                                  <label for="estado_civil" class="form-label">Estado Cívil <span class="text-danger">*</span></label>
                                  <select name="estado_civil" id="estado_civil" class="select2 form-control estado_civil">
                                    <option value="">Selecione</option>
                                    <option value="Casado" {{ "Casado" == $director->estado_civil ?  'selected' : '' }}>Casado(a)</option>
                                    <option value="Solteiro" {{ "Solteiro" == $director->estado_civil ? 'selected' : '' }}>Solteiro(a)</option>
                                    <option value="Divorciado" {{ "Divorciado" == $director->estado_civil ? 'selected' : '' }}>Divorciado(a)</option>
                                    <option value="Viuvo" {{ "Viuvo" == $director->estado_civil ? 'selected' : '' }}>Viúvo(a)</option>
                                  </select>
                                  @error('estado_civil')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-3 col-12">
                                  <label for="bilhete" class="form-label">B.I <span class="text-danger">*</span></label>
                                  <input type="text" name="bilheite" id="bilheite" value=" {{ old('bilheite') ?? $director->bilheite }}" class="form-control bilheite"
                                    placeholder="Curso do Bilhete">
                                  @error('bilheite')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-3 col-12">
                                  <label for="curso" class="form-label">Curso <span class="text-danger">*</span></label>
                                  <input type="text" name="curso" id="curso" value=" {{ old('curso') ?? $director->curso }}"  class="form-control curso" placeholder="Curso do Director">
                                  @error('curso')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-6 col-12">
                                  <label for="especialidade" class="form-label">Especialidade <span class="text-danger">*</span></label>
                                  <input type="text" name="especialidade" value=" {{ old('especialidade') ?? $director->especialidade }}" id="especialidade" class="form-control especialidade"
                                    placeholder="Curso do especialidade">
                                  @error('especialidade')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-12 col-12">
                                  <label for="descricao" class="form-label">Descrição</label>
                                  <textarea name="descricao" id="" cols="30" rows="3" class="form-control descricao" placeholder="Descrição do director">{{ old('descricao') ?? $director->descricao }}</textarea>
                                  @error('descricao')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-md-12 col-12">
                          <div class="card">
                            @if(session()->has('message'))
                            <div class="alert alert-success">
                              {{ session()->get('message') }}
                            </div>
                            @endif
                            <div class="card-header">
                                <h6>Dados da Escola</h6>
                            </div>
                            <div class="card-body">
                              <div class="row">
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="nome_turmas" class="form-label">Nome da Escola <span class="text-danger">*</span></label>
                                  <input type="text" name="nome" value="{{ old('nome') ?? $escola->nome }}" id="nome" class="form-control nome" placeholder="Nome da Escola">
                                  @error('nome')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="documento" class="form-label">NIF <span class="text-danger">*</span></label>
                                  <input type="text" name="documento" value="{{ old('documento') ?? $escola->documento  }}" id="documento" placeholder="Informe o NIF"
                                    class="form-control documento">
                                  @error('documento')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="numero_escola" class="form-label">Número da Escola <span class="text-danger">*</span></label>
                                  <input type="text" name="numero_escola" value="{{ old('numero_escola') ?? $escola->numero_escola  }}" id="numero_escola" placeholder="Número da Escola" class="form-control numero_escola">
                                  @error('numero_escola')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="" class="form-label">Tipos Ensinos <span class="text-danger">*</span></label>
                                  <select name="ensino_id" id="ensino_id" class="form-control ensino_id" style="width: 100%">
                                    <option value="">Selecione</option>
                                    @foreach ($ensinos as $item)
                                    <option value="{{ $item->id }}"  {{ $escola->ensino_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                  </select>
                                  @error('ensino_id')
                                  <span class="text-danger"> {{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="tipo_regime_id" class="form-label"> <span class="text-danger">*</span>Tipo de Regime do IVA</label>
                                  <select class="form-control" name="tipo_regime_id" id="tipo_regime_id">
                                    <option value="regime_exclusao"  {{ $escola->tipo_regime_id == 'regime_exclusao' ? 'selected' : '' }}>REGIME DE EXCLUSÃO</option>
                                    <option value="regime_geral" {{ $escola->tipo_regime_id == 'regime_geral' ? 'selected' : '' }}>REGIME GERAL</option>
                                    <option value="regime_simplificado" {{ $escola->tipo_regime_id == 'regime_simplificado' ? 'selected' : '' }}>REGIME SIMPLIFICADO</option>
                                  </select>
                                  @error('tipo_regime_id')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="sector" class="form-label">Sector <span class="text-danger">*</span></label>
                                  <select name="sector" id="sector" class="form-control sector select2">
                                    <option value="Privado" {{ $escola->categoria == 'Privado' ? 'selected' : '' }}>Privado</option>
                                    <option value="Publico" {{ $escola->categoria == 'Publico' ? 'selected' : '' }}>Publico</option>
                                    <option value="Publico-Privado" {{ $escola->categoria == 'Publico-Privado' ? 'selected' : '' }}>Público Privado</option>
                                  </select>
                                  @error('sector')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="" class="form-label">País <span class="text-danger">*</span></label>
                                  <select name="pais_id" id="pais_id" class="select2 form-control pais_id" style="width: 100%">
                                    <option value="">Selecione o País</option>
                                    @foreach ($paises as $item)
                                    <option value="{{ $item->id }}" {{ $escola->pais_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                  </select>
                                  @error('pais_id')
                                  <span class="text-danger"> {{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="" class="form-label">Provincia <span class="text-danger">*</span></label>
                                  <select name="provincia_id" id="provincia_id" class="select2 form-control provincia_id"
                                    style="width: 100%">
                                    <option value="">Selecione</option>
                                    @foreach ($provincias as $item)
                                    <option value="{{ $item->id }}" {{ $escola->provincia_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                  </select>
                                  @error('provincia_id')
                                  <span class="text-danger"> {{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="" class="form-label">Municípios <span class="text-danger">*</span></label>
                                  <select name="municipio_id" id="municipio_id" class="select2 form-control municipio_id"
                                    style="width: 100%">
                                    <option value="">Selecione</option>
                                    @foreach ($municipios as $item)
                                    <option value="{{ $item->id }}" {{ $escola->municipio_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                  </select>
                                  @error('municipio_id')
                                  <span class="text-danger"> {{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="" class="form-label">Distritos <span class="text-danger">*</span></label>
                                  <select name="distrito_id" id="distrito_id" class="select2 form-control distrito_id"
                                    style="width: 100%">
                                    <option value="">Selecione</option>
                                    @foreach ($distritos as $item)
                                    <option value="{{ $item->id }}" {{ $escola->distrito_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                  </select>
                                  @error('distrito_id')
                                  <span class="text-danger"> {{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="site" class="form-label">Site <span class="text-danger">*</span></label>
                                  <input type="text" name="site" value="{{ old('site') ?? $escola->site }}" id="site" class="form-control site" placeholder="Informe o site da escola Ex: wwww.escola.com">
                                  @error('site')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="sigla" class="form-label">Sigla <span class="text-danger">*</span></label>
                                  <input type="text" name="sigla" value="{{ old('sigla') ?? $escola->sigla }}" id="sigla" class="form-control sigla" placeholder="Sigla da escola">
                                  @error('sigla')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                                  <input type="text" name="email" value="{{ old('email') ?? $escola->email }}" id="email" class="form-control email" placeholder="E-mail">
                                  @error('email')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="telefone" class="form-label">Telefone <span class="text-danger">*</span></label>
                                  <input type="text" name="telefone" value="{{ old('telefone') ?? $escola->telefone1 }}" id="telefone" class="form-control telefone"
                                    placeholder="Nº Telefone">
                                  @error('telefone')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="mb-3 col-md-2 col-12">
                                  <label for="endereco" class="form-label">Endereço da Morada</label>
                                  <textarea name="endereco" id="endereco" class="form-control endereco" placeholder="descrever endereço" id="endereco" rows="1">{{ old('endereco') ?? $escola->endereco }}</textarea>
                                  @error('endereco')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="whatsapp" class="form-label">Whatsapp (opcional)</label>
                                  <input type="text" name="whatsapp" value="{{ old('whatsapp') ?? $escola->whatsapp }}" id="whatsapp" class="form-control whatsapp"
                                    placeholder="Nº do Whatsapp">
                                  @error('whatsapp')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="facebook" class="form-label">Facebook (opcional)</label>
                                  <input type="text" name="facebook" value="{{ old('facebook') ?? $escola->facebook }}" id="facebook" class="form-control facebook"
                                    placeholder="Conta do facebook">
                                  @error('facebook')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-2 col-md-2 col-12">
                                  <label for="instagram" class="form-label">Instagram (opcional)</label>
                                  <input type="text" name="instagram" value="{{ old('instagram') ?? $escola->instagram }}" id="instagram" class="form-control instagram"
                                    placeholder="Conta do instagram">
                                  @error('instagram')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                              </div>
                            </div>
                            <div class="card-footer">
                            </div>
                          </div>
                          <!-- /.card -->
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-md-12">
                          <div class="card">
                            <div class="card-header">
                              <h6>Dados do Infranstrutura</h6>
                            </div>
                            <div class="card-body">
                              <div class="row">
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="internet" class="form-label">Internet <span class="text-danger">*</span></label>
                                  <select name="internet" id="internet" class="select2 form-control internet">
                                    <option value="Sim" {{ $escola->internet == 'Sim' ? 'selected' : '' }}>Sim</option>
                                    <option value="Não" {{ $escola->internet == 'Nao' ? 'selected' : '' }}>Não</option>
                                  </select>
                                  @error('internet')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="cantina" class="form-label">Cantina <span class="text-danger">*</span></label>
                                  <select name="cantina" id="cantina" class="select2 form-control cantina">
                                    <option value="Sim" {{ $escola->cantina == 'Sim' ? 'selected' : '' }}>Sim</option>
                                    <option value="Não" {{ $escola->cantina == 'Nao' ? 'selected' : '' }}>Não</option>
                                  </select>
                                  @error('cantina')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="electricidade" class="form-label">Electricidade <span class="text-danger">*</span></label>
                                  <select name="electricidade" id="electricidade" class="select2 form-control electricidade">
                                    <option value="Sim" {{ $escola->electricidade == 'Sim' ? 'selected' : '' }}>Sim</option>
                                    <option value="Não" {{ $escola->electricidade == 'Nao' ? 'selected' : '' }}>Não</option>
                                  </select>
                                  @error('electricidade')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="casas_banho" class="form-label">Casas de Banhos <span class="text-danger">*</span></label>
                                  <select name="casas_banho" id="casas_banho" class="select2 form-control casas_banho">
                                    <option value="Sim" {{ $escola->casas_banho == 'Sim' ? 'selected' : '' }}>Sim</option>
                                    <option value="Não" {{ $escola->casas_banho == 'Nao' ? 'selected' : '' }}>Não</option>
                                  </select>
                                  @error('casas_banho')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="zip" class="form-label">Zip <span class="text-danger">*</span></label>
                                  <select name="zip" id="zip" class="select2 form-control zip">
                                    <option value="Sim" {{ $escola->zip == 'Sim' ? 'selected' : '' }}>Sim</option>
                                    <option value="Não" {{ $escola->zip == 'Nao' ? 'selected' : '' }}>Não</option>
                                  </select>
                                  @error('zip')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="transporte" class="form-label">Transportes <span class="text-danger">*</span></label>
                                  <select name="transporte" id="transporte" class="select2 form-control transporte">
                                    <option value="Sim" {{ $escola->transporte == 'Sim' ? 'selected' : '' }}>Sim</option>
                                    <option value="Não" {{ $escola->transporte == 'Nao' ? 'selected' : '' }}>Não</option>
                                  </select>
                                  @error('transporte')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="agua" class="form-label">Água Potável <span class="text-danger">*</span></label>
                                  <select name="agua" id="agua" class="select2 form-control agua">
                                    <option value="Sim" {{ $escola->agua == 'Sim' ? 'selected' : '' }}>Sim</option>
                                    <option value="Não" {{ $escola->agua == 'Nao' ? 'selected' : '' }}>Não</option>
                                  </select>
                                  @error('agua')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="biblioteca" class="form-label">Biblioteca <span class="text-danger">*</span></label>
                                  <select name="biblioteca" id="biblioteca" class="select2 form-control biblioteca">
                                    <option value="Sim" {{ $escola->biblioteca == 'Sim' ? 'selected' : '' }}>Sim</option>
                                    <option value="Não" {{ $escola->biblioteca == 'Nao' ? 'selected' : '' }}>Não</option>
                                  </select>
                                  @error('biblioteca')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="campo_desportivo" class="form-label">Campo Desportivos <span class="text-danger">*</span></label>
                                  <select name="campo_desportivo" id="campo_desportivo" class="select2 form-control campo_desportivo">
                                    <option value="Sim" {{ $escola->campo_desportivo == 'Sim' ? 'selected' : '' }}>Sim</option>
                                    <option value="Não" {{ $escola->campo_desportivo == 'Nao' ? 'selected' : '' }}>Não</option>
                                  </select>
                                  @error('campo_desportivo')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="computadores" class="form-label">Computadores <span class="text-danger">*</span></label>
                                  <select name="computadores" id="computadores" class="select2 form-control computadores">
                                    <option value="Sim" {{ $escola->computadores == 'Sim' ? 'selected' : '' }}>Sim</option>
                                    <option value="Não" {{ $escola->computadores == 'Nao' ? 'selected' : '' }}>Não</option>
                                  </select>
                                  @error('computadores')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="farmacia" class="form-label">Farmácia/Enfermagem <span class="text-danger">*</span></label>
                                  <select name="farmacia" id="farmacia" class="select2 form-control farmacia">
                                    <option value="Sim" {{ $escola->farmacia == 'Sim' ? 'selected' : '' }}>Sim</option>
                                    <option value="Não" {{ $escola->farmacia == 'Nao' ? 'selected' : '' }}>Não</option>
                                  </select>
                                  @error('farmacia')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="laboratorio" class="form-label">Laboratórios <span class="text-danger">*</span></label>
                                  <select name="laboratorio" id="laboratorio" class="select2 form-control laboratorio">
                                    <option value="Sim" {{ $escola->laboratorio == 'Sim' ? 'selected' : '' }}>Sim</option>
                                    <option value="Não" {{ $escola->laboratorio == 'Nao' ? 'selected' : '' }}>Não</option>
                                  </select>
                                  @error('laboratorio')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                              </div>
                            </div>
                            <div class="card-footer">
                            </div>
                          </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                          <div class="card">
                            <div class="card-header">
                              <h6>Imagens e Impressão</h6>
                            </div>
                            <div class="card-body">
                              <div class="row">
                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="logotipo" class="form-label">Logotipo <span class="text-danger">*</span></label>
                                    <input type="file" name="logotipo" value="{{ old('logotipo') ?? $escola->logotipo }}" id="logotipo" class="form-control logotipo">
                                    @error('logotipo')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="logotipo2" class="form-label">Imagem Principal</label>
                                    <input type="file" name="logotipo2" value="{{ old('logotipo2') ?? $escola->logotipo2 }}" id="logotipo2" class="form-control logotipo2">
                                    @error('logotipo2')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="logotipo_assinatura_director" class="form-label">Imagen Assinatura</label>
                                    <input type="file" name="logotipo_assinatura_director" value="{{ old('logotipo_assinatura_director') ?? $escola->logotipo_assinatura_director }}" id="logotipo_assinatura_director" class="form-control logotipo_assinatura_director">
                                    @error('logotipo_assinatura_director')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="logotipo_documentos" class="form-label">Imagem do Fundo para Documentos <span class="text-danger">*</span></label>
                                  <input type="file" name="logotipo_documentos" value="{{ old('logotipo_documentos') ?? $escola->logotipo_documentos }}" id="logotipo_documentos" class="form-control logotipo_documentos">
                                  @error('logotipo_documentos')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="impressora" class="form-label">Impressão Principal <span class="text-danger">*</span></label>
                                  <select name="impressora" id="impressora" class="select2 form-control impressora">
                                    <option value="Normal" {{ $escola->impressora == 'Normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="Ticket" {{ $escola->impressora == 'Ticket' ? 'selected' : '' }}>Ticket</option>
                                  </select>
                                  @error('impressora')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                                
                                <div class="form-group mb-3 col-md-2 col-12">
                                  <label for="desconto_percentagem" class="form-label">Descontos <span class="text-danger">*</span></label>
                                  <select name="desconto_percentagem" id="desconto_percentagem" class="select2 form-control desconto_percentagem">
                                    <option value="Y" {{ $escola->desconto_percentagem == 'Y' ? 'selected' : '' }}>Em Percentagem</option>
                                    <option value="N" {{ $escola->desconto_percentagem == 'N' ? 'selected' : '' }}>Somente Valor</option>
                                  </select>
                                  @error('desconto_percentagem')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                                </div>
                
                              </div>
                            </div>
                            <div class="card-footer">
                            </div>
                          </div>
                        </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header">
                            <h6>Intervalo dos dias para se efectuar o pagamento de mensalidades. Taxa de Multa para atraso de mensalidades.</h6>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="form-group mb-3 col-md-3 col-12">
                                  <label for="intervalo_pagamento_inicio" class="form-label">Dia Inicial para o pagamento <span class="text-danger">*</span></label>
                                  <input type="number" name="intervalo_pagamento_inicio" placeholder="Dia inicial Ex: 1" value="{{ old('intervalo_pagamento_inicio') ?? $escola->intervalo_pagamento_inicio }}" id="intervalo_pagamento_inicio" class="form-control intervalo_pagamento_inicio">
                                  @error('intervalo_pagamento_inicio')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                              </div>
                              
                              <div class="form-group mb-3 col-md-3 col-12">
                                  <label for="intervalo_pagamento_final" class="form-label">Dia Final para o pagamento <span class="text-danger">*</span></label>
                                  <input type="number" name="intervalo_pagamento_final" placeholder="Dia final Ex: 15" value="{{ old('intervalo_pagamento_final') ?? $escola->intervalo_pagamento_final }}" id="intervalo_pagamento_final" class="form-control intervalo_pagamento_final">
                                  @error('intervalo_pagamento_final')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                              </div>
                              
                              <div class="form-group mb-3 col-md-3 col-12">
                                <label for="taxa_multa1_dia" class="form-label">Dia de Atraso para aplica 1º Taxa <span class="text-danger">*</span></label>
                                <input type="number" name="taxa_multa1_dia" placeholder="Dias de Atraso para primeira taxa" value="{{ old('taxa_multa1_dia') ?? $escola->taxa_multa1_dia }}" id="taxa_multa1_dia" class="form-control taxa_multa1_dia">
                                @error('taxa_multa1_dia')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                              </div>
                              
                              <div class="form-group mb-3 col-md-3 col-12">
                                <label for="taxa_multa1" class="form-label">Valor 1º Taxa (%) <span class="text-danger">*</span></label>
                                <input type="number" name="taxa_multa1" placeholder="Dia final Ex: 15" value="{{ old('taxa_multa1') ?? $escola->taxa_multa1 }}" id="taxa_multa1" class="form-control taxa_multa1">
                                @error('taxa_multa1')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                              </div>
                              
                              <div class="form-group mb-3 col-md-3 col-12">
                                <label for="taxa_multa2_dia" class="form-label">Dia de Atraso para aplica 2º Taxa <span class="text-danger">*</span></label>
                                <input type="number" name="taxa_multa2_dia" placeholder="Dias de Atraso para segunda taxa" value="{{ old('taxa_multa2_dia') ?? $escola->taxa_multa2_dia }}" id="taxa_multa2_dia" class="form-control taxa_multa2_dia">
                                @error('taxa_multa2_dia')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                              </div>
                            
                              <div class="form-group mb-3 col-md-3 col-12">
                                <label for="taxa_multa2" class="form-label">Valor 2º Taxa (%) <span class="text-danger">*</span></label>
                                <input type="number" name="taxa_multa2" placeholder="Dia final Ex: 15" value="{{ old('taxa_multa2') ?? $escola->taxa_multa2 }}" id="taxa_multa2" class="form-control taxa_multa2">
                                @error('taxa_multa2')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                              </div>
                              
                              <div class="form-group mb-3 col-md-3 col-12">
                                <label for="taxa_multa3_dia" class="form-label">Dia de Atraso para aplica 3º Taxa <span class="text-danger">*</span></label>
                                <input type="number" name="taxa_multa3_dia" placeholder="Dias de Atraso para terceira taxa" value="{{ old('taxa_multa3_dia') ?? $escola->taxa_multa3_dia }}" id="taxa_multa3_dia" class="form-control taxa_multa3_dia">
                                @error('taxa_multa3_dia')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                              </div>
                          
                              <div class="form-group mb-3 col-md-3 col-12">
                                <label for="taxa_multa3" class="form-label">Valor 3º Taxa (%) <span class="text-danger">*</span></label>
                                <input type="number" name="taxa_multa3" placeholder="Dia final Ex: 15" value="{{ old('taxa_multa3') ?? $escola->taxa_multa3 }}" id="taxa_multa3" class="form-control taxa_multa3">
                                @error('taxa_multa3')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                              </div>
              
                            </div>
                          </div>
                          <div class="card-footer">
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header">
                            <h6>Coordenadas bancárias</h6>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="form-group mb-3 col-md-4 col-12">
                                  <label for="banco" class="form-label">Banco <span class="text-danger">*</span></label>
                                  <input type="text" name="banco" placeholder="EX: BANCO DE INVESTIMENTO ANGOLANO" value="{{ old('banco') ?? $escola->banco }}" id="banco" class="form-control banco">
                                  @error('banco')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                              </div>
                              
                              <div class="form-group mb-3 col-md-4 col-12">
                                  <label for="conta" class="form-label">Nº Conta</label>
                                  <input type="text" name="conta" placeholder="EX: 0000 0000 0000 0000 0" value="{{ old('conta') ?? $escola->conta }}" id="conta" class="form-control conta">
                                  @error('conta')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                              </div>
                              
                              <div class="form-group mb-3 col-md-4 col-12">
                                  <label for="iban" class="form-label">Nº IBAN:</label>
                                  <input type="text" name="iban" placeholder="EX: AO06 0000 0000 0000 0000 0000 0" value="{{ old('iban') ?? $escola->iban }}" id="iban" class="form-control iban">
                                  @error('iban')
                                  <span class="text-danger error-text">{{ $message }}</span>
                                  @enderror
                              </div>
              
                            </div>
                          </div>
                          <div class="card-footer">
                            @if (Auth::user()->can('update: escola'))
                            <button type="submit" class="btn btn-success">Actulizar</button>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

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

  </script>
@endsection