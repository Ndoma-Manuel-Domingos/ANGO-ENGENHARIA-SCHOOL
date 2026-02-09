@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Comunicado para Encarregados</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('comunicados.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">comunicados</li>
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
                <div class="card card-primary card-outline">
                    <form action="{{ route('comunicados.comunicadar-encarregados-store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header">
                            <h3 class="card-title">Novo Comunicado</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                               
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Titulo</label>
                                        <input class="form-control @error('titulo') is-invalid @enderror" name="titulo" placeholder="Titulo do Comunicado:">
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label for="tipo_comunicado" class="form-label">Tipo Comunicado</label>
                                        <select name="tipo_comunicado" id="tipo_comunicado" class="form-control @error('tipo_comunicado') is-invalid @enderror">
                                            <option value="">Selecione</option>
                                            <option value="comunicado">Comunicado</option>
                                            <option value="noticia">Notícia</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label for="encarregado_id" class="form-label">Encarregados</label>
                                        <select name="encarregado_id[]" id="encarregado_id" multiple class="form-control select2 @error('encarregado_id') is-invalid @enderror">
                                            <option value="">Selecione</option>
                                                @foreach ($encarregados as $item)
                                                <option value="{{ $item->id }}">{{ $item->nome_completo }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="descricao" class="form-label">Descrição</label>
                                        <textarea name="descricao" rows="6" id="descricao" class="form-control @error('descricao') is-invalid @enderror"></textarea>
                                    </div>
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

</script>
@endsection
