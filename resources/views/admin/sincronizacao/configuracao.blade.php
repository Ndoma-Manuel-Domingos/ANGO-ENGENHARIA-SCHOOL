@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Sincronização de informações</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Sincronização</li>
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
                    <form action="{{ route('web.sincronizacao-configuracao-post') }}" method="post">
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                
                                <div class="form-group col-md-3">
                                    <label for="ano_lectivo_de">Ano Lectivo DE</label>
                                    <select name="ano_lectivo_de" class="form-control select2 editar_ano_lectivo_de" id="ano_lectivo_de">
                                        <option value="">Selecionar</option>
                                        @foreach ($anos as $item)
                                        <option value="{{ $item->id }}">{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                    @error('ano_lectivo_de')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="ano_lectivo_para">Ano Lectivo PARA</label>
                                    <select name="ano_lectivo_para" class="form-control select2 editar_ano_lectivo_para" id="ano_lectivo_para">
                                        <option value="">Selecionar</option>
                                        @foreach ($anos as $item)
                                        <option value="{{ $item->id }}">{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                    @error('ano_lectivo_para')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label for="sincronizar">O que sincronizar?</label>
                                    <select name="sincronizar" class="form-control select2 editar_sincronizar" id="sincronizar">
                                        <option value="">Selecionar</option>
                                        <option value="classes">Classes</option>
                                        <option value="cursos">Cursos</option>
                                        <option value="salas">Salas</option>
                                        <option value="disciplinas">Disciplinas</option>
                                        <option value="turmas">Turmas</option>
                                        <option value="turnos">Turnos</option>
                                    </select>
                                    @error('sincronizar')
                                    <span class="text-danger">{{ $message }}</span>
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