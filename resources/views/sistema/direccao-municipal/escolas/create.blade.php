@extends('layouts.municipal')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cadastro de Escolas/Universidades</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('listagem-escola-municipal') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Escolas</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
    <div class="container-fluid">
        <form action="{{ route('criar-escola-municipal-store') }}" method="post" enctype="multipart/form-data" id="formulario_cadastro_professor">

            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Dados do Director/Reitor</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group mb-3 col-md-6 col-12">
                                    <label for="nome_director" class="form-label">Nome do Director</label>
                                    <input type="text" value="{{ old('director') }}" name="director" id="director" class="form-control director" placeholder="Nome do Director">
                                    @error('director')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="genero" class="form-label">Genero</label>
                                    <select name="genero" id="genero" class="select2 form-control genero">
                                        <option value="Masculino" {{ old('genero') == 'Masculino' ? : '' }}>Masculino</option>
                                        <option value="Femenino" {{ old('genero') == 'Femenino' ? : '' }}>Femenino</option>
                                    </select>
                                    @error('genero')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="estado_civil" class="form-label">Estado Cívil</label>
                                    <select name="estado_civil" id="estado_civil" class="select2 form-control estado_civil">
                                        <option value="Solteiro" {{ old('estado_civil') == 'Solteiro' ? : '' }}>Solteiro(a)</option>
                                        <option value="Casado" {{ old('estado_civil') == 'Casado' ? : '' }}>Casado(a)</option>
                                        <option value="Divorciado" {{ old('estado_civil') == 'Divorciado' ? : '' }}>Divorciado(a)</option>
                                        <option value="Viuvo" {{ old('estado_civil') == 'Viuvo' ? : '' }}>Viúvo(a)</option>
                                    </select>
                                    @error('estado_civil')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="bilheite" class="form-label">B.I</label>
                                    <input type="text" name="bilheite" id="bilheite" value="00000000LA000" class="form-control bilheite" placeholder="00000000LA000">
                                    @error('bilheite')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="curso" class="form-label">Curso</label>
                                    <input type="text" name="curso" id="curso" value="Desconhecido" class="form-control curso" placeholder="Desconhecido">
                                    @error('curso')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-6 col-12">
                                    <label for="especialidade" class="form-label">Especialidade</label>
                                    <input type="text" name="especialidade" value="Desconhecida" id="especialidade" class="form-control especialidade" placeholder="Desconhecido">
                                    @error('especialidade')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-12 col-12">
                                    <label for="descricao" class="form-label">Descrição</label>
                                    <textarea name="descricao" id="descricao" cols="30" rows="3" class="form-control descricao" placeholder="Desconhecido">Desconhecido</textarea>
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
                            @csrf
                            <div class="row">

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="nome_turmas" class="form-label">Nome da Instituição</label>
                                    <input type="text" name="nome" value="{{ old('nome') }}" id="nome" class="form-control nome" placeholder="Nome da Escola">
                                    @error('nome')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="documento" class="form-label">NIF</label>
                                    <input type="text" name="documento" value="{{ old('documento') }}" id="documento" placeholder="Informe o NIF" class="form-control documento">
                                    @error('documento')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-1 col-12">
                                    <label for="numero_escola" class="form-label">Nº da Instituição</label>
                                    <input type="text" name="numero_escola" value="{{ old('numero_escola') }}" id="numero_escola" placeholder="Número da Escola" class="form-control numero_escola">
                                    @error('numero_escola')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="" class="form-label">Tipos Ensinos</label>
                                    <select name="ensino_id" id="ensino_id" class="form-control ensino_id" style="width: 100%">
                                        <option value="">Selecione</option>
                                        @foreach ($ensinos as $item)
                                        <option value="{{ $item->id }}" {{ old('ensino_id') == $item->id ? : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('ensino_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="sector" class="form-label">Sector</label>
                                    <select name="sector" id="sector" class="form-control sector select2">
                                        <option value="Publico" {{ old('sector') == 'Publico' ? : '' }}>Publico</option>
                                        <option value="Publico-Privado" {{ old('sector') == 'Publico-Privado' ? : '' }}>Público Privado</option>
                                        <option value="Privado" {{ old('sector') == 'Privado' ? : '' }}>Privado</option>
                                    </select>
                                    @error('sector')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                
                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="modulo_id" class="form-label">Módulos</label>
                                    <select name="modulo_id" id="modulo_id" class="form-control modulo_id select2">
                                        <option value="Basico">Básico</option>
                                        <option value="Intermedio">Intermédio</option>
                                        <option value="Avancado">Avançado</option>
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
                                        <option value="{{ $item->id }}" {{ old('pais_id') == $item->id ? : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('pais_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="provincia_id" class="form-label">Provincia</label>
                                    <select name="provincia_id" id="provincia_id" class="select2 form-control provincia_id" style="width: 100%">
                                        <option value="">Selecione</option>
                                        @foreach ($provincias as $item)
                                        <option value="{{ $item->id }}" {{ old('provincia_id') == $item->id ? : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('provincia_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="municipio_id" class="form-label">Municípios</label>
                                    <select name="municipio_id" id="municipio_id" class="select2 form-control municipio_id" style="width: 100%">
                                        <option value="">Selecione</option>
                                        @foreach ($municipios as $item)
                                        <option value="{{ $item->id }}" {{ old('municipio_id') == $item->id ? : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('municipio_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="distrito_id" class="form-label">Distritos</label>
                                    <select name="distrito_id" id="distrito_id" class="select2 form-control distrito_id" style="width: 100%">
                                        <option value="">Selecione</option>
                                        @foreach ($distritos as $item)
                                        <option value="{{ $item->id }}" {{ old('distrito_id') == $item->id ? : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('distrito_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="site" class="form-label">Site</label>
                                    <input type="text" name="site" value="{{ old('site') }}" id="site" class="form-control site" placeholder="Informe o site da escola Ex: wwww.escola.com">
                                    @error('site')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="sigla" class="form-label">Sigla</label>
                                    <input type="text" name="sigla" value="{{ old('sigla') }}" id="sigla" class="form-control sigla" placeholder="Sigla da escola">
                                    @error('sigla')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="text" name="email" value="{{ old('email') }}" id="email" class="form-control email" placeholder="E-mail">
                                    @error('email')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" name="telefone" value="{{ old('telefone') }}" id="telefone" class="form-control telefone" placeholder="Nº Telefone">
                                    @error('telefone')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-3 col-12">
                                    <label for="endereco" class="form-label">Endereço da Morada</label>
                                    <textarea name="endereco" id="endereco" class="form-control endereco" placeholder="descrever endereço" id="endereco" rows="1">{{ old('endereco') }}</textarea>
                                    @error('endereco')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="whatsapp" class="form-label">Whatsapp (opcional)</label>
                                    <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" id="whatsapp" class="form-control whatsapp" placeholder="Nº do Whatsapp">
                                    @error('whatsapp')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="facebook" class="form-label">Facebook (opcional)</label>
                                    <input type="text" name="facebook" value="{{ old('facebook') }}" id="facebook" class="form-control facebook" placeholder="Conta do facebook">
                                    @error('facebook')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="instagram" class="form-label">Instagram (opcional)</label>
                                    <input type="text" name="instagram" value="{{ old('instagram') }}" id="instagram" class="form-control instagram" placeholder="Conta do instagram">
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
                                        <option value="Sim" {{ old('internet') == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ old('internet') == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('internet')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="cantina" class="form-label">Cantina</label>
                                    <select name="cantina" id="cantina" class="select2 form-control cantina">
                                        <option value="Sim" {{ old('cantina') == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ old('cantina') == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('cantina')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="electricidade" class="form-label">Electricidade</label>
                                    <select name="electricidade" id="electricidade" class="select2 form-control electricidade">
                                        <option value="Sim" {{ old('electricidade') == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ old('electricidade') == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('electricidade')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="casas_banho" class="form-label">Casas de Banhos</label>
                                    <select name="casas_banho" id="casas_banho" class="select2 form-control casas_banho">
                                        <option value="Sim" {{ old('casas_banho') == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ old('casas_banho') == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('casas_banho')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="zip" class="form-label">Zip</label>
                                    <select name="zip" id="zip" class="select2 form-control zip">
                                        <option value="Sim" {{ old('zip') == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ old('zip') == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('zip')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="transporte" class="form-label">Transportes</label>
                                    <select name="transporte" id="transporte" class="select2 form-control transporte">
                                        <option value="Sim" {{ old('transporte') == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ old('transporte') == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('transporte')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="agua" class="form-label">Água Potável</label>
                                    <select name="agua" id="agua" class="select2 form-control agua">
                                        <option value="Sim" {{ old('agua') == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ old('agua') == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('agua')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="biblioteca" class="form-label">Biblioteca</label>
                                    <select name="biblioteca" id="biblioteca" class="select2 form-control biblioteca">
                                        <option value="Sim" {{ old('biblioteca') == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ old('biblioteca') == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('biblioteca')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="campo_desportivo" class="form-label">Campo Desportivos</label>
                                    <select name="campo_desportivo" id="campo_desportivo" class="select2 form-control campo_desportivo">
                                        <option value="Sim" {{ old('campo_desportivo') == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ old('campo_desportivo') == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('campo_desportivo')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="computadores" class="form-label">Computadores</label>
                                    <select name="computadores" id="computadores" class="select2 form-control computadores">
                                        <option value="Sim" {{ old('computadores') == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ old('computadores') == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('computadores')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="farmacia" class="form-label">Farmácia/Enfermagem</label>
                                    <select name="farmacia" id="farmacia" class="select2 form-control farmacia">
                                        <option value="Sim" {{ old('farmacia') == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ old('farmacia') == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('farmacia')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-2 col-12">
                                    <label for="laboratorio" class="form-label">Laboratórios</label>
                                    <select name="laboratorio" id="laboratorio" class="select2 form-control laboratorio">
                                        <option value="Sim" {{ old('laboratorio') == 'Sim' ? 'selected' : '' }}>Sim</option>
                                        <option value="Não" {{ old('laboratorio') == 'Não' ? 'selected' : '' }} selected>Não</option>
                                    </select>
                                    @error('laboratorio')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
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
        carregarDados({ origem: "#provincia_id", destino: "#municipio_id" , rota: rotas.carregarMunicipios , mensagemSucesso: "Municípios carregados"
        });
    });

    $("#municipio_id").change(function() {
        carregarDados({ origem: "#municipio_id" , destino: "#distrito_id" , rota: rotas.carregarDistritos , mensagemSucesso: "Distritos carregados"
        });
    });

</script>
@endsection
