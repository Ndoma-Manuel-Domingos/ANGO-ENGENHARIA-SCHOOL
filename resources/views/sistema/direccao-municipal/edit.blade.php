@extends('layouts.provinciais')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-8">
              <h1 class="m-0 text-dark">Editar Direcção Municipal  <span class="text-dark">{{ $data->nome }}</span> </h1>
            </div><!-- /.col -->
            <div class="col-sm-4">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('direccoes-municipais.index') }}">Voltar em direcções</a></li>
                <li class="breadcrumb-item active">Editar</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 mb-3">
                    <form action="{{ route('direccoes-municipais.update', Crypt::encrypt($data->id)) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        
                        <div class="card">
                            <div class="card-header">
                                <h5>Dados do Director</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group mb-3 col-md-6 col-12">
                                      <label for="nome_director" class="form-label">Nome do Director <span class="text-danger">*</span></label>
                                      <input type="text" name="director" id="director" class="form-control director" value="{{ $director->nome }}" placeholder="Nome do Director">
                                      @error('director')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-3 col-12">
                                      <label for="genero" class="form-label">Genero <span class="text-danger">*</span></label>
                                      <select name="genero" id="genero" class="select2 form-control genero">
                                        <option value="Masculino" {{ $director->genero ==  "Masculino" ? 'selected' : ''}}>Masculino</option>
                                        <option value="Femenino" {{ $director->genero ==  "Femenino" ? 'selected' : ''}}>Femenino</option>
                                      </select>
                                      @error('genero')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-3 col-12">
                                      <label for="estado_civil" class="form-label">Estado Cívil <span class="text-danger">*</span></label>
                                      <select name="estado_civil" id="estado_civil" class="select2 form-control estado_civil">
                                        <option value="">Selecione</option>
                                        <option value="Casado" {{ $director->estado_civil ==  "Casado" ? 'selected' : ''}}>Casado(a)</option>
                                        <option value="Solteiro" {{ $director->estado_civil ==  "Solteiro" ? 'selected' : ''}}>Solteiro(a)</option>
                                        <option value="Divorciado" {{ $director->estado_civil ==  "Divorciado" ? 'selected' : ''}}>Divorciado(a)</option>
                                        <option value="Viuvo" {{ $director->estado_civil ==  "Viuvo" ? 'selected' : ''}}>Viúvo(a)</option>
                                      </select>
                                      @error('estado_civil')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-3 col-12">
                                      <label for="bilheite" class="form-label">B.I <span class="text-danger">*</span></label>
                                      <input type="text" name="bilheite" value="{{ $director->bilheite ?? '' }}" id="bilheite" class="form-control bilheite"
                                        placeholder="Curso do Bilheite">
                                      @error('bilheite')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-3 col-12">
                                      <label for="curso" class="form-label">Curso <span class="text-danger">*</span></label>
                                      <input type="text" name="curso" value="{{ $director->curso ?? '' }}" id="curso" class="form-control curso" placeholder="Curso do Director">
                                      @error('curso')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-6 col-12">
                                      <label for="especialidade"  class="form-label">Especialidade <span class="text-danger">*</span></label>
                                      <input type="text" name="especialidade" value="{{ $director->especialidade ?? '' }}" id="especialidade" class="form-control especialidade"
                                        placeholder="Curso do especialidade">
                                      @error('especialidade')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-12 col-12">
                                      <label for="descricao" class="form-label">Descrição <span class="text-danger">*</span></label>
                                      <textarea name="descricao" id="" cols="30" rows="3" class="form-control descricao"
                                        placeholder="Descrição do director"> {{ $director->descricao ?? '' }}</textarea>
                                      @error('descricao')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                                    
                                    <input type="hidden" name="director_id" value="{{ $director->id }}">
                    
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="card">
                            <div class="card-body">
    
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="" class="form-label">Nome da diraçção Municipal <span class="text-danger">*</span></label>
                                        <input type="text" name="nome" id="" value="{{ $data->nome }}" placeholder="Escola" class="form-control">
                                        @error('nome')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-4">
                                        <label for="sigla">Sigla <span class="text-danger">*</span></label>
                                        <input type="text" name="sigla" id="sigla" value="{{ $data->sigla }}"
                                            placeholder="Informe as Siglas EX: TEC.ONE" class="form-control" value="">
                                        @error('sigla')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
    
                                    <div class="form-group col-md-4">
                                        <label for="decreto">Decreto de Criação <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control decreto" name="decreto"  value="{{ $data->decreto }}"
                                            placeholder="Informe o Decreto de Criação"  value="">
                                        @error('decreto')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="" class="form-label">Documento <span class="text-danger">*</span></label>
                                        <input type="text" name="documento" id="" value="{{ $data->documento }}" placeholder="Documento" class="form-control">
                                        @error('documento')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="" class="form-label">Site ou E-mail</label>
                                        <input type="text" name="site" id="" placeholder="Site" value="{{ $data->site }}" class="form-control">
                                        @error('site')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="" class="form-label">Países <span class="text-danger">*</span></label>
                                        <select name="pais_id" id="pais_id" class="form-control select2 pais_id">
                                            <option value="">Selecione o País</option>
                                            @foreach ($paises as $item)
                                                <option value="{{ $item->id }}" {{ $item->id == $data->pais_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('pais_id')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="" class="form-label">Provincia <span class="text-danger">*</span></label>
                                        <select name="provincia_id" id="provincia_id" class="form-control select2 provincia_id">
                                            <option value="">Selecione o País</option>
                                            @foreach ($provincias as $item)
                                                <option value="{{ $item->id }}" {{ $item->id == $data->provincia_id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('provincia_id')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group col-md-2">
                                        <label for="" class="form-label">Municipio <span class="text-danger">*</span></label>
                                        <select name="municipio_id" id="municipio_id" class="form-control select2 municipio_id">
                                            <option value="">Selecione o País</option>
                                            @foreach ($municipios as $item)
                                                <option value="{{ $item->id }}" {{ $item->id == $data->municipio_id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('municipio_id')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="" class="form-label">Distrito <span class="text-danger">*</span></label>
                                        <select name="distrito_id" id="distrito_id" class="form-control select2 distrito_id">
                                            <option value="">Selecione o País</option>
                                            @foreach ($distritos as $item)
                                                <option value="{{ $item->id }}" {{ $item->id == $data->distrito_id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('distrito_id')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="telefone1">Número Telefonico 1ª <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control telefone1" value="{{ $data->telefone1 }}" name="telefone1" placeholder="Informe Numero telefonico 1">
                                        @error('telefone1')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="telefone2">Número Telefonico 2ª</label>
                                        <input type="text" class="form-control telefone2" value="{{ $data->telefone2 }}" name="telefone2" placeholder="Informe Numero telefonico 2">
                                        @error('telefone2')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-4">
                                        <label for="" class="form-label">Logotipo</label>
                                        <input type="file" class="form-control logotipo"  name="logotipo" placeholder="Informe o logotipo">
                                        <input type="hidden" class="form-control logotipo" value="{{ $data->logotipo }}"  name="logotipo_guardado" placeholder="Informe o logotipo">
                                        @error('logotipo')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    
                                    <div class="form-group col-md-4">
                                        <label for="" class="form-label">Logotipo para Assinaturas</label>
                                        <input type="file" class="form-control logotipo" name="logotipo_assinatura_director" placeholder="Informe o logotipo">
                                        <input type="hidden" class="form-control logotipo" value="{{ $data->logotipo_assinatura_director }}" name="logotipo_assinatura_director_guardado" placeholder="Informe o logotipo">
                                        @error('logotipo_assinatura_director')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                              
    
                                    <div class="form-group col-md-12">
                                        <label for="" class="form-label">Endereço Completo</label>
                                        <textarea class="form-control" name="endereco" placeholder="Descrever o Endereço completo da sobre a escola" id="" cols="30" rows="3">{{ $data->endereco }}</textarea>
                                        @error('endereco')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                  
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h5>Dados do Infranstrutura</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="internet" class="form-label">Internet</label>
                                      <select name="internet" id="internet" class="select2 form-control internet">
                                        <option value="Sim" {{ $data->internet == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Nao" {{ $data->internet == 'Nao' ? 'selected' : '' }}>Não</option>
                                      </select>
                                      @error('internet')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="cantina" class="form-label">Cantina</label>
                                      <select name="cantina" id="cantina" class="select2 form-control cantina">
                                        <option value="Sim" {{ $data->cantina == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Nao"  {{ $data->cantina == 'Nao' ? 'selected' : '' }}>Não</option>
                                      </select>
                                      @error('cantina')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="electricidade" class="form-label">Electricidade</label>
                                      <select name="electricidade" id="electricidade" class="select2 form-control electricidade">
                                        <option value="Sim" {{ $data->electricidade == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Nao"  {{ $data->electricidade == 'Nao' ? 'selected' : '' }}>Não</option>
                                      </select>
                                      @error('electricidade')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="casas_banho" class="form-label">Casas de Banhos</label>
                                      <select name="casas_banho" id="casas_banho" class="select2 form-control casas_banho">
                                        <option value="Sim" {{ $data->casas_banho == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Nao"  {{ $data->casas_banho == 'Nao' ? 'selected' : '' }}>Não</option>
                                      </select>
                                      @error('casas_banho')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="zip" class="form-label">Zip</label>
                                      <select name="zip" id="zip" class="select2 form-control zip">
                                        <option value="Sim" {{ $data->zip == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Nao"  {{ $data->zip == 'Nao' ? 'selected' : '' }}>Não</option>
                                      </select>
                                      @error('zip')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="transporte" class="form-label">Transportes</label>
                                      <select name="transporte" id="transporte" class="select2 form-control transporte">
                                        <option value="Sim" {{ $data->transporte == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Nao"  {{ $data->transporte == 'Nao' ? 'selected' : '' }}>Não</option>
                                      </select>
                                      @error('transporte')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="agua" class="form-label">Água Potável</label>
                                      <select name="agua" id="agua" class="select2 form-control agua">
                                        <option value="Sim" {{ $data->agua == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Nao"  {{ $data->agua == 'Nao' ? 'selected' : '' }}>Não</option>
                                      </select>
                                      @error('agua')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="biblioteca" class="form-label">Biblioteca</label>
                                      <select name="biblioteca" id="biblioteca" class="select2 form-control biblioteca">
                                        <option value="Sim" {{ $data->biblioteca == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Nao"  {{ $data->biblioteca == 'Nao' ? 'selected' : '' }}>Não</option>
                                      </select>
                                      @error('biblioteca')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="campo_desportivo" class="form-label">Campo Desportivos</label>
                                      <select name="campo_desportivo" id="campo_desportivo" class="select2 form-control campo_desportivo">
                                        <option value="Sim" {{ $data->campo_desportivo == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Nao"  {{ $data->campo_desportivo == 'Nao' ? 'selected' : '' }}>Não</option>
                                      </select>
                                      @error('campo_desportivo')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="computadores" class="form-label">Computadores</label>
                                      <select name="computadores" id="computadores" class="select2 form-control computadores">
                                        <option value="Sim" {{ $data->computadores == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Nao"  {{ $data->computadores == 'Nao' ? 'selected' : '' }}>Não</option>
                                      </select>
                                      @error('computadores')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="farmacia" class="form-label">Farmácia/Enfermagem</label>
                                      <select name="farmacia" id="farmacia" class="select2 form-control farmacia">
                                        <option value="Sim" {{ $data->farmacia == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Nao"  {{ $data->farmacia == 'Nao' ? 'selected' : '' }}>Não</option>
                                      </select>
                                      @error('farmacia')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="laboratorio" class="form-label">Laboratórios</label>
                                      <select name="laboratorio" id="laboratorio" class="select2 form-control laboratorio">
                                        <option value="Sim" {{ $data->laboratorio == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Nao"  {{ $data->laboratorio == 'Nao' ? 'selected' : '' }}>Não</option>
                                      </select>
                                      @error('laboratorio')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Confirmar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
          
        </div>
      </div>
      
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
