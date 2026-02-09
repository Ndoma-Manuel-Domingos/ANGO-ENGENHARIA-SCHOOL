@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Cadastrar Encarregado</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('encarregados.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Encarregados</li>
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
            <div class="col-2 col-md-12">
                <form action="{{ route('encarregados.store') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="nome">Nome</label>
                                    <input type="text" name="nome" class="form-control" id="nome" value="{{ old('nome') }}" placeholder="Nome do Encarregado">
                                    @error('nome')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label for="sobre_nome">Sobre</label>
                                    <input type="text" name="sobre_nome" class="form-control" value="{{ old('sobre_nome') }}" id="sobre_nome" placeholder="Sobrenome do Encarregado">
                                    @error('sobre_nome')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label for="numero_bilhete">Número Bilhete</label>
                                    <input type="text" name="numero_bilhete" class="form-control" id="numero_bilhete" value="{{ old('numero_bilhete') }}" placeholder="Número bilhete do encarregado">
                                    @error('numero_bilhete')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-3">
                                    <label for="data_nascimento">Data de Nascimento</label>
                                    <input type="date" name="data_nascimento" value="{{ old('data_nascimento') }}" class="form-control editar_data_nascimento">
                                     @error('data_nascimento')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-3">
                                    <label for="genero">Gênero</label>
                                    <select name="genero" id="genero" class="form-control select2">
                                        <option value="">Selecione Genero</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                    </select>
                                    @error('genero')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-3">
                                    <label for="estado_civil">Estado Civil</label>
                                    <select name="estado_civil" id="estado_civil" class="form-control select2">
                                        <option value="">Selecione Estado</option>
                                        <option value="Solteiro" {{ old('estado_civil') == "Solteiro" ? 'selected' : '' }}>SOLTEIRO(A)</option>
                                        <option value="Casado" {{ old('estado_civil') == "Casado" ? 'selected' : '' }}>CASADO(A)</option>
                                        <option value="Viuvo" {{ old('estado_civil') == "Viuvo" ? 'selected' : '' }}>VIUVO(A)</option>
                                        <option value="Divorciado" {{ old('estado_civil') == "Divorciado" ? 'selected' : '' }}>DIVORCIDO(A)</option>
                                    </select>
                                    @error('estado_civil')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-3">
                                    <label for="profissao">Profissão</label>
                                    <input type="text" name="profissao" class="form-control editar_profissao" placeholder="Profissão do Encarregado">
                                    @error('profissao')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-3">
                                    <label for="telefone">Telefone</label>
                                    <input type="text" name="telefone" class="form-control editar_telefone" placeholder="Número Telefonico do Encarregado">
                                    @error('profissao')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@section('scripts')
<script>
</script>
@endsection
