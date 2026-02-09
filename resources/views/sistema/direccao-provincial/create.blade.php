@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-8">
              <h1 class="m-0 text-dark">Cadastrar Direcção Provincial</h1>
            </div><!-- /.col -->
            <div class="col-sm-4">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('direccoes-provincias.index') }}">Direcções</a></li>
                <li class="breadcrumb-item active">geral</li>
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
                    <form action="{{ route('direccoes-provincias.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                            
                        <div class="card">
                            <div class="card-header">
                                <h5>Dados do Director</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group mb-3 col-md-6 col-12">
                                      <label for="nome_director" class="form-label">Nome do Director</label>
                                      <input type="text" name="director" id="director" class="form-control director"
                                        placeholder="Nome do Director">
                                      @error('director')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-3 col-12">
                                      <label for="genero" class="form-label">Genero</label>
                                      <select name="genero" id="genero" class="select2 form-control genero">
                                        <option value="Masculino">Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                      </select>
                                      @error('genero')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-3 col-12">
                                      <label for="estado_civil" class="form-label">Estado Cívil</label>
                                      <select name="estado_civil" id="estado_civil" class="select2 form-control estado_civil">
                                        <option value="">Selecione</option>
                                        <option value="Casado">Casado(a)</option>
                                        <option value="Solteiro">Solteiro(a)</option>
                                        <option value="Divorciado">Divorciado(a)</option>
                                        <option value="Viuvo">Viúvo(a)</option>
                                      </select>
                                      @error('estado_civil')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-3 col-12">
                                      <label for="bilheite" class="form-label">B.I</label>
                                      <input type="text" name="bilheite" id="bilheite" class="form-control bilheite"
                                        placeholder="Curso do Bilheite">
                                      @error('bilheite')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-3 col-12">
                                      <label for="curso" class="form-label">Curso</label>
                                      <input type="text" name="curso" id="curso" class="form-control curso" placeholder="Curso do Director">
                                      @error('curso')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-6 col-12">
                                      <label for="especialidade" class="form-label">Especialidade</label>
                                      <input type="text" name="especialidade" id="especialidade" class="form-control especialidade"
                                        placeholder="Curso do especialidade">
                                      @error('especialidade')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-12 col-12">
                                      <label for="descricao" class="form-label">Descrição</label>
                                      <textarea name="descricao" id="" cols="30" rows="3" class="form-control descricao"
                                        placeholder="Descrição do director"></textarea>
                                      @error('descricao')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                  </div>
                            </div>
                        </div>
                        
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="" class="form-label">Nome da diraçção provincial</label>
                                        <input type="text" name="nome" id="" placeholder="Direcção" class="form-control">
                                        @error('nome')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                
                                    <div class="form-group col-md-4">
                                        <label for="sigla">Sigla</label>
                                        <input type="text" name="sigla" id="sigla"
                                            placeholder="Informe as Siglas EX: TEC.ONE" class="form-control" value="">
                                        @error('sigla')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
    
                                    <div class="form-group col-md-4">
                                        <label for="decreto">Decreto de Criação</label>
                                        <input type="text" class="form-control decreto" name="decreto"
                                            placeholder="Informe o Decreto de Criação"  value="">
                                        @error('decreto')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="" class="form-label">Documento</label>
                                        <input type="text" name="documento" id="" placeholder="Documento" class="form-control">
                                        @error('documento')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="" class="form-label">Site ou E-mail</label>
                                        <input type="text" name="site" id="" placeholder="Site" class="form-control">
                                        @error('site')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="" class="form-label">Países</label>
                                        <select name="pais_id" id="pais_id" class="form-control select2 pais_id">
                                            <option value="">Selecione o País</option>
                                            @foreach ($paises as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('pais_id')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="" class="form-label">Provincia</label>
                                        <select name="provincia_id" id="provincia_id" class="form-control select2 provincia_id">
                                            <option value="">Selecione o País</option>
                                            @foreach ($provincias as $item)
                                                <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                            @endforeach
                                        </select>
                                        @error('provincia_id')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                        <label for="" class="form-label">Municípios</label>
                                        <select name="municipio_id" id="municipio_id" class="form-control municipio_id" style="width: 100%">
                                          <option value="">Selecione</option>
                                          @foreach ($municipios as $item)
                                          <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                          @endforeach
                                        </select>
                                        @error('municipio_id')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="" class="form-label">Distritos</label>
                                      <select name="distrito_id" id="distrito_id" class="form-control distrito_id select2" style="width: 100%">
                                        <option value="">Selecione</option>
                                        @foreach ($distritos as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                        @endforeach
                                      </select>
                                      @error('distrito_id')
                                      <span class="text-danger"> {{ $message }}</span>
                                      @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="telefone1">Número Telefonico 1ª</label>
                                        <input type="text" class="form-control telefone1" name="telefone1" placeholder="Informe Numero telefonico 1">
                                        @error('telefone1')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="telefone2">Número Telefonico 2ª</label>
                                        <input type="text" class="form-control telefone2" name="telefone2" placeholder="Informe Numero telefonico 2">
                                        @error('telefone2')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-4">
                                        <label for="" class="form-label">Logotipo</label>
                                        <input type="file" class="form-control logotipo"  name="logotipo" placeholder="Informe o logotipo">
                                        @error('logotipo')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    
                                    <div class="form-group col-md-4">
                                        <label for="" class="form-label">Logotipo para Assinaturas</label>
                                        <input type="file" class="form-control logotipo" name="logotipo_assinatura_director" placeholder="Informe o logotipo">
                                        @error('logotipo_assinatura_director')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                              
    
                                    <div class="form-group col-md-12">
                                        <label for="" class="form-label">Endereço Completo</label>
                                        <textarea class="form-control" name="endereco" placeholder="Descrever o Endereço completo da sobre a escola" id="" cols="30" rows="3"></textarea>
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
                                        <option value="Sim">Sim</option>
                                        <option value="Não" selected>Não</option>
                                      </select>
                                      @error('internet')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="cantina" class="form-label">Cantina</label>
                                      <select name="cantina" id="cantina" class="select2 form-control cantina">
                                        <option value="Sim">Sim</option>
                                        <option value="Não" selected>Não</option>
                                      </select>
                                      @error('cantina')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="electricidade" class="form-label">Electricidade</label>
                                      <select name="electricidade" id="electricidade" class="select2 form-control electricidade">
                                        <option value="Sim">Sim</option>
                                        <option value="Não" selected>Não</option>
                                      </select>
                                      @error('electricidade')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="casas_banho" class="form-label">Casas de Banhos</label>
                                      <select name="casas_banho" id="casas_banho" class="select2 form-control casas_banho">
                                        <option value="Sim">Sim</option>
                                        <option value="Não" selected>Não</option>
                                      </select>
                                      @error('casas_banho')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="zip" class="form-label">Zip</label>
                                      <select name="zip" id="zip" class="select2 form-control zip">
                                        <option value="Sim">Sim</option>
                                        <option value="Não" selected>Não</option>
                                      </select>
                                      @error('zip')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="transporte" class="form-label">Transportes</label>
                                      <select name="transporte" id="transporte" class="select2 form-control transporte">
                                        <option value="Sim">Sim</option>
                                        <option value="Não" selected>Não</option>
                                      </select>
                                      @error('transporte')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="agua" class="form-label">Água Potável</label>
                                      <select name="agua" id="agua" class="select2 form-control agua">
                                        <option value="Sim">Sim</option>
                                        <option value="Não" selected>Não</option>
                                      </select>
                                      @error('agua')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="biblioteca" class="form-label">Biblioteca</label>
                                      <select name="biblioteca" id="biblioteca" class="select2 form-control biblioteca">
                                        <option value="Sim">Sim</option>
                                        <option value="Não" selected>Não</option>
                                      </select>
                                      @error('biblioteca')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="campo_desportivo" class="form-label">Campo Desportivos</label>
                                      <select name="campo_desportivo" id="campo_desportivo" class="select2 form-control campo_desportivo">
                                        <option value="Sim">Sim</option>
                                        <option value="Não" selected>Não</option>
                                      </select>
                                      @error('campo_desportivo')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="computadores" class="form-label">Computadores</label>
                                      <select name="computadores" id="computadores" class="select2 form-control computadores">
                                        <option value="Sim">Sim</option>
                                        <option value="Não" selected>Não</option>
                                      </select>
                                      @error('computadores')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="farmacia" class="form-label">Farmácia/Enfermagem</label>
                                      <select name="farmacia" id="farmacia" class="select2 form-control farmacia">
                                        <option value="Sim">Sim</option>
                                        <option value="Não" selected>Não</option>
                                      </select>
                                      @error('farmacia')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                      @enderror
                                    </div>
                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="laboratorio" class="form-label">Laboratórios</label>
                                      <select name="laboratorio" id="laboratorio" class="select2 form-control laboratorio">
                                        <option value="Sim">Sim</option>
                                        <option value="Não" selected>Não</option>
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
    
    $("#departamento_id").change(function () {
      carregarDados({
        origem: "#departamento_id",
        destino: "#cargo_id",
        rota: rotas.carregarCargos,
        mensagemSucesso: "Cargos carregados"
      });
    });
    
    $("#instituicao_id").change(function () {
      carregarDados({
        origem: "#instituicao_id",
        destino: "#instituicoes_destino",
        rota: rotas.carregarDestinoFuncionario,
        mensagemSucesso: "Cargos Destino Funcionário"
      });
    });
    
  </script>
@endsection

