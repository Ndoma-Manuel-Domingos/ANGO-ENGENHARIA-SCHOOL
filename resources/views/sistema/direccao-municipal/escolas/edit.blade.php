@extends('layouts.municipal')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editar de Escolas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('listagem-escola-municipal') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Escola</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('web.edit-escola-municipal-update', $escola->id) }}" method="post" enctype="multipart/form-data" id="formulario_cadastro_professor">
            @csrf
            @method('put')
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Dados do Director</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group mb-3 col-md-6 col-12">
                                    <label for="nome_director" class="form-label">Nome do Director</label>
                                    <input type="text" value="{{ old('director') ?? $director->nome }}" name="director" id="director" class="form-control director" placeholder="Nome do Director">
                                    @error('director')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <input type="hidden" name="director_id" value="{{ $director->id }}">

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="genero" class="form-label">Genero</label>
                                    <select name="genero" id="genero" class="select2 form-control genero">
                                        <option value="Masculino" {{ "Masculino" == $director->genero ? 'selected' : '' }}>Masculino</option>
                                        <option value="Femenino" {{ "Femenino" == $director->genero ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                    @error('genero')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="estado_civil" class="form-label">Estado Cívil</label>
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
                                    <label for="bilheite" class="form-label">B.I</label>
                                    <input type="text" name="bilheite" id="bilheite" value=" {{ old('bilheite') ?? $director->bilheite }}" class="form-control bilheite" placeholder="Curso do Bilheite">
                                    @error('bilheite')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="curso" class="form-label">Curso</label>
                                    <input type="text" name="curso" id="curso" value=" {{ old('curso') ?? $director->curso }}" class="form-control curso" placeholder="Curso do Director">
                                    @error('curso')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-6 col-12">
                                    <label for="especialidade" class="form-label">Especialidade</label>
                                    <input type="text" name="especialidade" value=" {{ old('especialidade') ?? $director->especialidade }}" id="especialidade" class="form-control especialidade" placeholder="Curso do especialidade">
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
                        <div class="card-footer"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="card">
                         <div class="card-header">
                            <h5>Dados da Escola</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="nome_turmas" class="form-label">Nome da Escola</label>
                                    <input type="text" name="nome" value="{{ old('nome') ?? $escola->nome }}" id="nome" class="form-control nome" placeholder="Nome da Escola">
                                    @error('nome')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="documento" class="form-label">NIF</label>
                                    <input type="text" name="documento" value="{{ old('documento') ?? $escola->documento  }}" id="documento" placeholder="Informe o NIF" class="form-control documento">
                                    @error('documento')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-1 col-12">
                                    <label for="numero_escola" class="form-label">Número da Escola</label>
                                    <input type="text" name="numero_escola" value="{{ old('numero_escola') ?? $escola->numero_escola  }}" id="numero_escola" placeholder="Número da Escola" class="form-control numero_escola">
                                    @error('numero_escola')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="" class="form-label">Tipos Ensinos</label>
                                    <select name="ensino_id" id="ensino_id" class="form-control ensino_id" style="width: 100%">
                                        <option value="">Selecione</option>
                                        @foreach ($ensinos as $item)
                                        <option value="{{ $item->id }}" {{ $escola->ensino_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('ensino_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="sector" class="form-label">Sector</label>
                                    <select name="sector" id="sector" class="form-control sector select2">
                                        <option value="Publico" {{ $escola->categoria == 'Publico' ? 'selected' : '' }}>Publico</option>
                                        <option value="Publico-Privado" {{ $escola->categoria == 'Publico-Privado' ? 'selected' : '' }}>Público Privado</option>
                                        <option value="Privado" {{ $escola->categoria == 'Privado' ? 'selected' : '' }}>Privado</option>
                                    </select>
                                    @error('sector')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="modulo_id" class="form-label">Módulos</label>
                                    <select name="modulo_id" id="modulo_id" class="form-control modulo_id select2">
                                        <option value="Basico" {{ $escola->modulo == 'Basico' ? 'selected' : '' }}>Básico</option>
                                        <option value="Intermedio" {{ $escola->modulo == 'Intermedio' ? 'selected' : '' }}>Intermédio</option>
                                        <option value="Avancado" {{ $escola->modulo == 'Avancado' ? 'selected' : '' }}>Avançado</option>
                                    </select>
                                    @error('modulo_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="" class="form-label">País</label>
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

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="" class="form-label">Provincia</label>
                                    <select name="provincia_id" id="provincia_id" class="select2 form-control provincia_id" style="width: 100%">
                                        <option value="">Selecione</option>
                                        @foreach ($provincias as $item)
                                        <option value="{{ $item->id }}" {{ $escola->provincia_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('provincia_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="" class="form-label">Municípios</label>
                                    <select name="municipio_id" id="municipio_id" class="select2 form-control municipio_id" style="width: 100%">
                                        <option value="">Selecione</option>
                                        @foreach ($municipios as $item)
                                        <option value="{{ $item->id }}" {{ $escola->municipio_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('municipio_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="" class="form-label">Distritos</label>
                                    <select name="distrito_id" id="distrito_id" class="select2 form-control distrito_id" style="width: 100%">
                                        <option value="">Selecione</option>
                                        @foreach ($distritos as $item)
                                        <option value="{{ $item->id }}" {{ $escola->distrito_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('distrito_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="site" class="form-label">Site</label>
                                    <input type="text" name="site" value="{{ old('site') ?? $escola->site }}" id="site" class="form-control site" placeholder="Informe o site da escola Ex: wwww.escola.com">
                                    @error('site')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="sigla" class="form-label">Sigla</label>
                                    <input type="text" name="sigla" value="{{ old('sigla') ?? $escola->sigla }}" id="sigla" class="form-control sigla" placeholder="Sigla da escola">
                                    @error('sigla')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="text" name="email" value="{{ old('email') ?? $escola->email }}" id="email" class="form-control email" placeholder="E-mail">
                                    @error('email')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" name="telefone" value="{{ old('telefone') ?? $escola->telefone1 }}" id="telefone" class="form-control telefone" placeholder="Nº Telefone">
                                    @error('telefone')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-3 col-12">
                                    <label for="endereco" class="form-label">Endereço da Morada</label>
                                    <textarea name="endereco" id="endereco" class="form-control endereco" placeholder="descrever endereço" id="endereco" rows="1">{{ old('endereco') ?? $escola->endereco }}</textarea>
                                    @error('endereco')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="whatsapp" class="form-label">Whatsapp (opcional)</label>
                                    <input type="text" name="whatsapp" value="{{ old('whatsapp') ?? $escola->whatsapp }}" id="whatsapp" class="form-control whatsapp" placeholder="Nº do Whatsapp">
                                    @error('whatsapp')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="facebook" class="form-label">Facebook (opcional)</label>
                                    <input type="text" name="facebook" value="{{ old('facebook') ?? $escola->facebook }}" id="facebook" class="form-control facebook" placeholder="Conta do facebook">
                                    @error('facebook')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="instagram" class="form-label">Instagram (opcional)</label>
                                    <input type="text" name="instagram" value="{{ old('instagram') ?? $escola->instagram }}" id="instagram" class="form-control instagram" placeholder="Conta do instagram">
                                    @error('instagram')
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

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Dados do Infranstrutura</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="internet" class="form-label">Internet</label>
                                    <select name="internet" id="internet" class="select2 form-control internet">
                                        <option value="Sim" {{ $escola->internet == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ $escola->internet == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('internet')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="cantina" class="form-label">Cantina</label>
                                    <select name="cantina" id="cantina" class="select2 form-control cantina">
                                        <option value="Sim" {{ $escola->cantina == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ $escola->cantina == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('cantina')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="electricidade" class="form-label">Electricidade</label>
                                    <select name="electricidade" id="electricidade" class="select2 form-control electricidade">
                                        <option value="Sim" {{ $escola->electricidade == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ $escola->electricidade == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('electricidade')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="casas_banho" class="form-label">Casas de Banhos</label>
                                    <select name="casas_banho" id="casas_banho" class="select2 form-control casas_banho">
                                        <option value="Sim" {{ $escola->casas_banho == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ $escola->casas_banho == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('casas_banho')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="zip" class="form-label">Zip</label>
                                    <select name="zip" id="zip" class="select2 form-control zip">
                                        <option value="Sim" {{ $escola->zip == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ $escola->zip == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('zip')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="transporte" class="form-label">Transportes</label>
                                    <select name="transporte" id="transporte" class="select2 form-control transporte">
                                        <option value="Sim" {{ $escola->transporte == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ $escola->transporte == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('transporte')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="agua" class="form-label">Água Potável</label>
                                    <select name="agua" id="agua" class="select2 form-control agua">
                                        <option value="Sim" {{ $escola->agua == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ $escola->agua == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('agua')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="biblioteca" class="form-label">Biblioteca</label>
                                    <select name="biblioteca" id="biblioteca" class="select2 form-control biblioteca">
                                        <option value="Sim" {{ $escola->biblioteca == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ $escola->biblioteca == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('biblioteca')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="campo_desportivo" class="form-label">Campo Desportivos</label>
                                    <select name="campo_desportivo" id="campo_desportivo" class="select2 form-control campo_desportivo">
                                        <option value="Sim" {{ $escola->campo_desportivo == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ $escola->campo_desportivo == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('campo_desportivo')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="computadores" class="form-label">Computadores</label>
                                    <select name="computadores" id="computadores" class="select2 form-control computadores">
                                        <option value="Sim" {{ $escola->computadores == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ $escola->computadores == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('computadores')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="farmacia" class="form-label">Farmácia/Enfermagem</label>
                                    <select name="farmacia" id="farmacia" class="select2 form-control farmacia">
                                        <option value="Sim" {{ $escola->farmacia == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ $escola->farmacia == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('farmacia')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="laboratorio" class="form-label">Laboratórios</label>
                                    <select name="laboratorio" id="laboratorio" class="select2 form-control laboratorio">
                                        <option value="Sim" {{ $escola->laboratorio == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ $escola->laboratorio == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('laboratorio')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Actulizar</button>
                        </div>
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
    $("#provincia_id").change(function() {
        carregarDados({
            origem: "#provincia_id"
            , destino: "#municipio_id"
            , rota: rotas.carregarMunicipios
            , mensagemSucesso: "Municípios carregados"
        });
    });

    $("#municipio_id").change(function() {
        carregarDados({
            origem: "#municipio_id"
            , destino: "#distrito_id"
            , rota: rotas.carregarDistritos
            , mensagemSucesso: "Distritos carregados"
        });
    });

</script>
@endsection
