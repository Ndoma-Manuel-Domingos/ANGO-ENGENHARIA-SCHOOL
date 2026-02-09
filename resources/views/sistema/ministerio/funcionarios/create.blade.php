@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Cadastro de Funcianário</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item">Voltar</li>
          <li class="breadcrumb-item active">Funcionários</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
  <div class="container-fluid">
    <form action="{{ route('web.funcionarios-ministerio-store') }}" method="post" enctype="multipart/form-data" id="formulario_cadastro_professor">
      <div class="row">
        <div class="col-md-12 col-12">
          <div class="card">
            @if(session()->has('message'))
            <div class="alert alert-success">
              {{ session()->get('message') }}
            </div>
            @endif
            <div class="card-header">
              <h6>Dados Pessoais</h6>
            </div>
            <div class="card-body">
              @csrf
              <div class="row">
                <div class="form-group mb-3 col-md-6 col-12">
                  <label for="nome_turmas" class="form-label">Nome</label>
                  <input type="text" name="nome" id="nome" value="{{ old('nome') }}" class="form-control nome" placeholder="Nome">
                  @error('nome')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group mb-3 col-md-6 col-12">
                  <label for="sobre_nome" class="form-label">Sobrenome</label>
                  <input type="text" name="sobre_nome" value="{{ old('sobre_nome') }}" id="sobre_nome" class="form-control sobre_nome"
                    placeholder="Sobrenome">
                  @error('sobre_nome')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>
                
                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="pai" class="form-label">Nome Completo do Pai</label>
                  <input type="text" name="pai" value="{{ old('pai') }}" id="pai" class="form-control pai" placeholder="pai">
                  @error('pai')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="mae" class="form-label">Nome Completo da Mãe</label>
                  <input type="text" name="mae" value="{{ old('mae') }}" id="mae" class="form-control mae"
                    placeholder="Nome Completo da Mãe">
                  @error('mae')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="bilheite" class="form-label">Bilhete de Identidade</label>
                  <input type="text" name="bilheite" value="{{ old('bilheite') }}" id="bilheite" class="form-control bilheite" placeholder="Nº B.I">
                  @error('bilheite')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>
                
                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="emissiao_bilheite" class="form-label">Data Emissão Bilhete</label>
                  <input type="date" name="emissiao_bilheite" value="{{ old('emissiao_bilheite') }}" id="emissiao_bilheite" class="form-control emissiao_bilheite" placeholder="Data Emissão">
                  @error('emissiao_bilheite')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="nascimento" class="form-label">Data Nascimento</label>
                  <input type="date" name="nascimento" value="{{ old('nascimento') }}" id="nascimento" class="form-control nascimento">
                  @error('nascimento')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="genero" class="form-label">Genero</label>
                  <select name="genero" id="genero" class="form-control genero select2">
                    <option value="">Selecione Genero</option>
                    <option value="Masculino" {{ old('nascimento') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="Femenino" {{ old('nascimento') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                  </select>
                  @error('genero')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="estado_civil" class="form-label">Estado Cívil</label>
                  <select name="estado_civil" id="estado_civil" class="form-control estado_civil select2">
                    <option value="">Selecione Status</option>
                    <option value="Casado" {{ old('estado_civil') == 'Casado' ? 'selected' : '' }}>Casado</option>
                    <option value="Solteiro" {{ old('estado_civil') == 'Solteiro' ? 'selected' : '' }}>Solteiro</option>
                  </select>
                  @error('estado_civil')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="" class="form-label">País</label>
                  <select name="pais_id" id="pais_id" class="form-control pais_id select2" style="width: 100%">
                    <option value="">Selecione o País</option>
                    @foreach ($paises as $item)
                    <option value="{{ $item->id }}" {{ old('pais_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                  </select>
                  @error('pais_id')
                  <span class="text-danger"> {{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="" class="form-label">Provincia</label>
                  <select name="provincia_id" id="provincia_id" class="form-control provincia_id select2" style="width: 100%">
                    <option value="">Selecione</option>
                    @foreach ($provincias as $item)
                    <option value="{{ $item->id }}" {{ old('provincia_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                    @endforeach
                  </select>
                  @error('provincia_id')
                  <span class="text-danger"> {{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="" class="form-label">Municípios</label>
                  <select name="municipio_id" id="municipio_id" class="form-control municipio_id select2" style="width: 100%">
                    <option value="">Selecione</option>
                    @foreach ($municipios as $item)
                    <option value="{{ $item->id }}" {{ old('municipio_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                    @endforeach
                  </select>
                  @error('municipio_id')
                  <span class="text-danger"> {{ $message }}</span>
                  @enderror
                </div>
                
                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="" class="form-label">Distrito</label>
                  <select name="distrito_id" id="distrito_id" class="form-control distrito_id select2" style="width: 100%">
                    <option value="">Selecione</option>
                    @foreach ($distritos as $item)
                    <option value="{{ $item->id }}" {{ old('distrito_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                    @endforeach
                  </select>
                  @error('distrito_id')
                  <span class="text-danger"> {{ $message }}</span>
                  @enderror
                </div>

                <div class="mb-3 col-md-3 col-12">
                  <label for="endereco" class="form-label">Endereço da Morada</label>
                  <textarea name="endereco" id="endereco" class="form-control endereco" placeholder="descrever endereço" id="endereco" rows="1">{{ old('endereco') }}</textarea>
                  @error('endereco')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>

              </div>
            </div>
            <div class="card-footer"></div>
          </div>
          <!-- /.card -->
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-12 col-12">
          <div class="card">
            <div class="card-header">
              <h6>Dados Academicos</h6>
            </div>
            <div class="card-body">
              <div class="row">
             
                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="categoria_id" class="form-label">Categorias</label>
                  <select name="categoria_id" id="categoria_id" class="form-control categoria_id select2">
                    <option value="">Selecione</option>
                    @foreach ($categorias as $item)
                    <option value="{{ $item->id }}" {{ old('categoria_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                    @endforeach
                  </select>
                  @error('categoria_id')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>
                
                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="universidade_id" class="form-label">Universidades</label>
                  <select name="universidade_id" id="universidade_id" class="form-control universidade_id select2">
                    <option value="">Selecione</option>
                    @foreach ($universidades as $item)
                    <option value="{{ $item->id }}" {{ old('universidade_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                    @endforeach
                  </select>
                  @error('universidade_id')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>
                
                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="especialidade_id" class="form-label">Especialidades</label>
                  <select name="especialidade_id" id="especialidade_id" class="form-control especialidade_id select2">
                    <option value="">Selecione</option>
                    @foreach ($especialidades as $item)
                    <option value="{{ $item->id }}" {{ old('especialidade_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                    @endforeach
                  </select>
                  @error('especialidade_id')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="escolaridade_id" class="form-label">Nível Academicos</label>
                  <select name="escolaridade_id" id="escolaridade" class="form-control escolaridade_id select2">
                    <option value="">Selecione</option>
                    @foreach ($escolaridade as $item)
                    <option value="{{ $item->id }}" {{ old('escolaridade_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                    @endforeach
                  </select>
                  @error('escolaridade_id')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="formacao_academica_id" class="form-label">Formação Academicas</label>
                  <select name="formacao_academica_id" id="formacao_academica_id" class="form-control formacao_academica_id  select2">
                    <option value="">Selecione</option>
                    @foreach ($formacao_academicos as $item)
                    <option value="{{ $item->id }}" {{ old('formacao_academica_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                    @endforeach
                  </select>
                  @error('formacao_academica_id')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>
                
                <div class="form-group mb-3 col-md-3 col-12">
                    <label for="departamento_id" class="form-label">Departamento</label>
                    <select name="departamento_id" id="departamento_id" class="form-control departamento_id select2">
                      <option value="">Selecione</option>
                      @foreach ($departamentos as $item)
                      <option value="{{ $item->id }}" {{ old('departamento_id') == $item->id ? 'selected' : '' }}>{{ $item->departamento }}</option>
                      @endforeach
                    </select>
                    @error('departamento_id')
                    <span class="text-danger error-text">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="cargo_id" class="form-label">Cargo</label>
                  <select name="cargo_id" id="cargo_id" class="form-control cargo_id select2">
                      <option value="">Selecione</option>
                      @foreach ($cargos as $item)
                      <option value="{{ $item->id }}" {{ old('cargo_id') == $item->id ? 'selected' : '' }}>{{ $item->cargo }}</option>
                      @endforeach
                  </select>
                  @error('cargo_id')
                    <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>
                
                
                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="ano_trabalho" class="form-label">Tempo de Trabalho</label>
                  <input type="text" name="ano_trabalho" value="{{ old('ano_trabalho') }}" class="form-control" placeholder="Tempo de Trabalho na Educação">
                  @error('ano_trabalho')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div> 
                
              </div>
            </div>
            <div class="card-footer"></div>
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-12 col-12">
          <div class="card">
            <div class="card-header">
              <h6>Redes Sociais/Correio Electronico/Contactos</h6>
            </div>
            <div class="card-body">
              <div class="row">
                
                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="telefone" class="form-label">Telefone</label>
                  <input type="text" name="telefone" id="telefone" value="{{ old('telefone') }}" class="form-control telefone" placeholder="Terminal do Funcionário">
                  @error('telefone')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>
                
                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="email" class="form-label">E-mail</label>
                  <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control email" placeholder="Correiro Electronico">
                  @error('email')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>
                
                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="facebook" class="form-label">Facebook</label>
                  <input type="text" name="facebook" id="facebook" value="{{ old('facebook') }}" class="form-control facebook" placeholder="Facebook">
                  @error('facebook')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>
                
                <div class="form-group mb-3 col-md-3 col-12">
                  <label for="instagram" class="form-label">Instagram</label>
                  <input type="text" name="instagram" id="instagram" value="{{ old('instagram') }}" class="form-control instagram" placeholder="Instagram">
                  @error('instagram')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>
                
                <div class="form-group mb-3 col-md-6 col-12">
                  <label for="whatsapp" class="form-label">Whatsapp</label>
                  <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" class="form-control whatsapp" placeholder="Whatsapp">
                  @error('whatsapp')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>
                
                <div class="form-group mb-3 col-md-6 col-12">
                  <label for="outras_redes" class="form-label">Outras Redes</label>
                  <input type="text" name="outras_redes" id="outras_redes" value="{{ old('outras_redes') }}" class="form-control outras_redes" placeholder="Outra Rede">
                  @error('outras_redes')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>


              </div>
            </div>
            <div class="card-footer"></div>
          </div>
          <!-- /.card -->
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-12 col-12">
          <div class="card">
            <div class="card-header">
              <h6>Documentos</h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="form-group my-5 col-md-3 col-12">
                  <label for="doc_bilheite" class="form-label">Bilhete de Identidade</label>
                  <input type="file" name="doc_bilheite" value="{{ old('doc_bilheite') }}" accept=".pdf" id="doc_bilheite" class="form-control doc_bilheite">
                  @error('doc_bilheite')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group my-5 col-md-3 col-12">
                  <label for="doc_certificado" class="form-label">CV</label>
                  <input type="file" name="doc_certificado" value="{{ old('doc_certificado') }}" accept=".pdf" id="doc_certificado" class="form-control doc_certificado">
                  @error('doc_certificado')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group my-5 col-md-3 col-12">
                  <label for="doc_atestedao_medico" class="form-label">Atestado Médico</label>
                  <input type="file" name="doc_atestedao_medico" value="{{ old('doc_atestedao_medico') }}" accept=".pdf" id="doc_atestedao_medico" class="form-control doc_atestedao_medico">
                  @error('doc_atestedao_medico')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>

                <div class="form-group my-5 col-md-3 col-12">
                  <label for="doc_outros" class="form-label">Outros Documentos</label>
                  <input type="file" name="doc_outros" value="{{ old('doc_outros') }}" accept=".pdf" class="form-control doc_outros" id="doc_outros">
                  @error('doc_outros')
                  <span class="text-danger error-text">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>
             <!-- /.card-body -->
            <div class="card-footer">
              <button form="formulario_cadastro_professor" type="submit" class="btn btn-primary">Enviar</button>
            </div>
        </div>
      </div>
    </form>
  </div>
  <!-- /.container-fluid -->
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

  </script>
@endsection