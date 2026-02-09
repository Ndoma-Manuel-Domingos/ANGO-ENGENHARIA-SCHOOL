@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cadastra Nova Escola</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.escolas-afilhares.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Cadastrar</li>
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
                <div class="card">
                    <form action="{{ route('web.escolas-afilhares.store') }}" method="post">
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                <div class="form-group col-md-6">
                                    <label for="nome">Nome <span class="text-danger">*</span></label>
                                    <input type="text" name="nome" class="form-control" id="nome" placeholder="Nome da Instituição">
                                    @error('nome')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="director">Director <span class="text-danger">*</span></label>
                                    <input type="text" name="director" class="form-control" id="director" placeholder="Nome Completo do Pai Director">
                                    @error('director')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                
                                <div class="form-group col-md-3">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control editar_status" id="status">
                                        <option value="desactivo">Desactivo</option>
                                        <option value="activo" selected>Activo</option>
                                    </select>
                                    @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
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

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="sector" class="form-label">Sector</label>
                                    <select name="sector" id="sector" class="form-control sector select2">
                                        <option value="Publico" {{ old('sector') == 'Publico' ? : '' }}>Publico</option>
                                        <option value="Publico-Privado" {{ old('sector') == 'Publico-Privado' ? : '' }}>Público Privado</option>
                                        <option value="Privado" {{ old('sector') == 'Privado' ? : '' }} selected>Privado</option>
                                    </select>
                                    @error('sector')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="" class="form-label">País</label>
                                    <select name="pais_id" id="pais_id" class="select2 form-control pais_id" style="width: 100%">
                                        @foreach ($paises as $item)
                                        <option value="{{ $item->id }}" {{ old('pais_id') == $item->id ? : '' }}>{{ $item->name }}</option>
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
                                        <option value="{{ $item->id }}" {{ old('provincia_id') == $item->id ? : '' }}>{{ $item->nome }}</option>
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
                                        <option value="{{ $item->id }}" {{ old('municipio_id') == $item->id ? : '' }}>{{ $item->nome }}</option>
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
                                        <option value="{{ $item->id }}" {{ old('distrito_id') == $item->id ? : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('distrito_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>



                            </div>
                        </div>
                        <div class="card-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@section('scripts')

<script>

    var turmasServico;
    
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