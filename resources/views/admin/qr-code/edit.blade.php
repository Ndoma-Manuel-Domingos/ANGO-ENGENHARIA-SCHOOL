@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editar Comunicado</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('comunicados.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Comunicado</li>
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
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Preencha todos os campos para inscrever estudante.</h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card card-primary card-outline">
                    <form action="{{ route('comunicados.update', $comunicado->id) }}" method="post" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <div class="card-header">
                            <h3 class="card-title">Editar Comunicado</h3>
                        </div>
    
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="to_escola" class="form-label">Para</label>
                                        <select name="to_escola" id="to_escola" class="form-control @error('to_escola') is-invalid @enderror">
                                            <option value="Todos" {{ $comunicado->to_escola == "Todos" ? 'selected': '' }}>Todos</option>
                                            <option value="Professores" {{ $comunicado->to_escola == "Professores" ? 'selected': '' }}>Professores</option>
                                            <option value="Funcionários" {{ $comunicado->to_escola == "Funcionários" ? 'selected': '' }}>Funcionários</option>
                                            <option value="Estudantes" {{ $comunicado->to_escola == "Estudantes" ? 'selected': '' }}>Estudantes</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="tipo_acesso_comunicado" class="form-label">Tipo de Acesso Comunicado</label>
                                        <select name="tipo_acesso_comunicado" id="tipo_acesso_comunicado" class="form-control @error('tipo_acesso_comunicado') is-invalid @enderror">
                                            <option value="">Selecione</option>
                                            <option value="Internos" {{ $comunicado->tipo_acesso_comunicado == "Internos" ? 'selected': '' }}>Internos</option>
                                            <option value="Externos" {{ $comunicado->tipo_acesso_comunicado == "Externos" ? 'selected': '' }}>Externos</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="tipo_comunicado" class="form-label">Tipo Comunicado</label>
                                        <select name="tipo_comunicado" id="tipo_comunicado" class="form-control @error('tipo_comunicado') is-invalid @enderror">
                                            <option value="">Selecione</option>
                                            <option value="comunicado" {{ $comunicado->tipo_comunicado == "comunicado" ? 'selected': '' }}>Comunicado</option>
                                            <option value="noticia" {{ $comunicado->tipo_comunicado == "noticia" ? 'selected': '' }}>Notícia</option>
                                        </select>
                                    </div>
                                </div>
                               
                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="" class="form-label">Titulo</label>
                                        <input class="form-control @error('titulo') is-invalid @enderror" value="{{ $comunicado->titulo ?? old('titulo') }}" name="titulo" placeholder="Titulo do Comunicado:">
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="descricao" class="form-label">Descrição</label>
                                        <textarea name="descricao" rows="6" id="descricao" class="form-control @error('descricao') is-invalid @enderror">{{ $comunicado->descricao ?? old('descricao') }}</textarea>
                                </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <div class="btn btn-default btn-file">
                                            <i class="fas fa-paperclip"></i> Anexo
                                            <input type="file" name="anexo">
                                        </div>
                                        <p class="help-block">Max. 32MB</p>
                                    </div>
                                    <input type="hidden" name="anexo_aguardado" value="{{ $comunicado->documento }}">
                                </div>
                            </div>
                        </div>
    
                        <div class="card-footer">
                            <div class="float-right">
                                <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Salvar</button>
                            </div>
                            <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> Cancelar</button>
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
    $(document).ready(function() {
        $('#summernote').summernote();
    });
</script>
@endsection