@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editar Encarregado : <span class="text-dark">{{ $encarregado->nome_completo }}</span></h1>
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
                <form action="{{ route('encarregados.update', $encarregado->id) }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="nome">Nome</label>
                                    <input type="text" name="nome" class="form-control" id="nome" value="{{ $encarregado->nome ?? old('nome') }}" placeholder="Nome do Encarregado">
                                    @error('nome')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label for="sobre_nome">Sobre</label>
                                    <input type="text" name="sobre_nome" class="form-control" value="{{ $encarregado->sobre_nome ?? old('sobre_nome') }}" id="sobre_nome" placeholder="Sobrenome do Encarregado">
                                    @error('sobre_nome')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label for="numero_bilhete">Número Bilhete</label>
                                    <input type="text" name="numero_bilhete" class="form-control" id="numero_bilhete" value="{{ $encarregado->numero_bilhete ?? old('numero_bilhete') }}" placeholder="Número bilhete do encarregado">
                                    @error('numero_bilhete')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-3">
                                    <label for="data_nascimento">Data de Nascimento</label>
                                    <input type="date" name="data_nascimento" value="{{ $encarregado->data_nascimento ?? old('data_nascimento') }}" class="form-control editar_data_nascimento">
                                     @error('data_nascimento')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-3">
                                    <label for="genero">Gênero</label>
                                    <select name="genero" id="genero" class="form-control select2">
                                        <option value="">Selecione Genero</option>
                                        <option value="Masculino" {{ $encarregado->genero == "Masculino" ? 'selected' : '' }}>Masculino</option>
                                        <option value="Femenino" {{ $encarregado->genero == "Femenino" ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                    @error('genero')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-3">
                                    <label for="estado_civil">Estado Civil</label>
                                    <select name="estado_civil" id="estado_civil" class="form-control select2">
                                        <option value="">Selecione Estado</option>
                                        <option value="Solteiro" {{ $encarregado->estado_civil == "Solteiro" ? 'selected' : '' }}>SOLTEIRO(A)</option>
                                        <option value="Casado" {{ $encarregado->estado_civil == "Casado" ? 'selected' : '' }}>CASADO(A)</option>
                                        <option value="Viuvo" {{ $encarregado->estado_civil == "Viuvo" ? 'selected' : '' }}>VIUVO(A)</option>
                                        <option value="Divorciado" {{ $encarregado->estado_civil == "Divorciado" ? 'selected' : '' }}>DIVORCIDO(A)</option>
                                    </select>
                                    @error('estado_civil')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-3">
                                    <label for="profissao">Profissão</label>
                                    <input type="text" name="profissao" value="{{ $encarregado->profissao ?? old('profissao')  }}" class="form-control" placeholder="Profissão do Encarregado">
                                    @error('profissao')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-3">
                                    <label for="telefone">Telefone</label>
                                    <input type="text" name="telefone" value="{{ $encarregado->telefone ?? old('telefone')  }}" class="form-control" placeholder="Número Telefonico do Encarregado">
                                    @error('telefone')
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
