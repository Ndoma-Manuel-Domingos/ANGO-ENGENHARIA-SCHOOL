@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editar Escola</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.escolas-afilhares.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Editar</li>
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
                    <form action="{{ route('web.escolas-afilhares.update', $escolas->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                @method('put')
                                <div class="form-group col-md-6">
                                    <label for="nome">Nome <span class="text-danger">*</span></label>
                                    <input type="text" name="nome" class="form-control" value="{{ $escolas->nome }}" id="nome" placeholder="Nome da Instituição">
                                    @error('nome')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="director">Director <span class="text-danger">*</span></label>
                                    <input type="text" name="director" class="form-control" value="{{ $escolas->director }}" id="director" placeholder="Nome Completo do Pai Director">
                                    @error('director')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-3">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control editar_status" id="status">
                                        <option value="desactivo" {{ $escolas->status == 'desactivo' ? 'selected' : '' }}>Desactivo</option>
                                        <option value="activo" {{ $escolas->status == 'activo' ? 'selected' : '' }}>Activo</option>
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
                                        <option value="{{ $item->id }}" {{ $escolas->ensino_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('ensino_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="sector" class="form-label">Sector</label>
                                    <select name="sector" id="sector" class="form-control sector select2">
                                        <option value="Publico" {{ $escolas->sector == 'Publico' ? : '' }}>Publico</option>
                                        <option value="Publico-Privado" {{ $escolas->sector == 'Publico-Privado' ? 'selected' : '' }}>Público Privado</option>
                                        <option value="Privado" {{ $escolas->sector == 'Privado' ? : '' }} selected>Privado</option>
                                    </select>
                                    @error('sector')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="" class="form-label">País</label>
                                    <select name="pais_id" id="pais_id" class="select2 form-control pais_id" style="width: 100%">
                                        @foreach ($paises as $item)
                                        <option value="{{ $item->id }}" {{ $escolas->pais_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
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
                                        <option value="{{ $item->id }}" {{ $escolas->provincia_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
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
                                        <option value="{{ $item->id }}" {{ $escolas->municipio_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
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
                                        <option value="{{ $item->id }}" {{ $escolas->distrito_id == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('distrito_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Actualizar Dados</button>
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
