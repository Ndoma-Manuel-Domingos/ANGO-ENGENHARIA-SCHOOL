@extends('layouts.admin')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-8">
              <h1 class="m-0 text-dark">Editar Direcção Provincial</h1>
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
                    <form action="{{ route('direccoes-provincias.update', $data->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-body">
    
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="" class="form-label">Nome da diraçção provincial</label>
                                        <input type="text" name="nome" id="" value="{{ $data->nome }}" placeholder="Escola" class="form-control">
                                        @error('nome')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-4">
                                        <label for="" class="form-label">Nome do Director</label>
                                        <input type="text" name="director" id="" value="{{ $data->director }}" placeholder="Informe o nome do director"
                                            class="form-control">
                                        @error('director')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="sigla">Sigla</label>
                                        <input type="text" name="sigla" id="sigla" value="{{ $data->sigla }}"
                                            placeholder="Informe as Siglas EX: TEC.ONE" class="form-control" value="">
                                        @error('sigla')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
    
                                    <div class="form-group col-md-2">
                                        <label for="decreto">Decreto de Criação</label>
                                        <input type="text" class="form-control decreto" name="decreto"  value="{{ $data->decreto }}"
                                            placeholder="Informe o Decreto de Criação"  value="">
                                        @error('decreto')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
    
                                    <div class="form-group col-md-2">
                                        <label for="" class="form-label">Documento</label>
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
                                        <label for="" class="form-label">Países</label>
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
                                        <label for="" class="form-label">Provincia</label>
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
                                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                        <label for="" class="form-label">Municípios</label>
                                        <select name="municipio_id" id="municipio_id" class="form-control municipio_id" style="width: 100%">
                                          <option value="">Selecione</option>
                                          @foreach ($municipios as $item)
                                          <option value="{{ $item->id }}" {{ $item->id == $data->municipio_id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                          @endforeach
                                        </select>
                                        @error('municipio_id')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group mb-3 col-md-2 col-12">
                                      <label for="" class="form-label">Distritos</label>
                                      <select name="distrito_id" id="distrito_id" class="form-control distrito_id" style="width: 100%">
                                        <option value="">Selecione</option>
                                        @foreach ($distritos as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $data->distrito_id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                      </select>
                                      @error('distrito_id')
                                      <span class="text-danger"> {{ $message }}</span>
                                      @enderror
                                    </div>
    
    
                                    <div class="form-group col-md-2">
                                        <label for="telefone1">Número Telefonico 1ª</label>
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
